const { spawn } = require('node:child_process');
const { mkdtempSync, writeFileSync } = require('node:fs');
const { tmpdir } = require('node:os');
const { join } = require('node:path');
const assert = require('node:assert/strict');

const sleep = ms => new Promise(resolve => setTimeout(resolve, ms));
const browser = spawn('C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe', [
    '--headless=new', '--disable-gpu', '--no-first-run', '--no-default-browser-check',
    '--remote-debugging-port=9237', '--user-data-dir=' + mkdtempSync(join(tmpdir(), 'remote-select-check-')),
    'about:blank'
], { windowsHide: true, stdio: 'ignore' });
let socket;
const errors = [], pending = new Map();
let sequence = 0;
async function command(method, params = {}) {
    const id = ++sequence;
    return new Promise((resolve, reject) => {
        const timeout = setTimeout(() => { pending.delete(id); reject(new Error('CDP timeout: ' + method)); }, 20000);
        pending.set(id, message => { clearTimeout(timeout); message.error ? reject(new Error(message.error.message)) : resolve(message.result); });
        socket.send(JSON.stringify({ id, method, params }));
    });
}
async function evaluate(expression) {
    const result = await command('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true });
    if (result.exceptionDetails) throw new Error(result.exceptionDetails.exception?.description || result.exceptionDetails.text);
    return result.result.value;
}
async function until(expression) {
    for (let attempt = 0; attempt < 80; attempt++) {
        const result = await evaluate(expression);
        if (result) return result;
        await sleep(250);
    }
    throw new Error('Browser condition timed out: ' + expression);
}
(async () => {
    let targets;
    for (let attempt = 0; attempt < 40; attempt++) {
        try { targets = await (await fetch('http://127.0.0.1:9237/json/list')).json(); if (targets.length) break; } catch {}
        await sleep(250);
    }
    assert.ok(targets?.length, 'Headless Edge started');
    socket = new WebSocket(targets.find(target => target.type === 'page').webSocketDebuggerUrl);
    await new Promise(resolve => socket.addEventListener('open', resolve, { once: true }));
    socket.addEventListener('message', event => {
        const message = JSON.parse(event.data);
        if (message.id && pending.has(message.id)) { pending.get(message.id)(message); pending.delete(message.id); }
        if (message.method === 'Runtime.exceptionThrown') errors.push(message.params.exceptionDetails.exception?.description || message.params.exceptionDetails.text);
    });
    await command('Runtime.enable');
    await command('Page.enable');
    for (const viewport of [{ width: 1280, height: 720 }, { width: 756, height: 488 }, { width: 375, height: 667 }]) {
        await command('Emulation.setDeviceMetricsOverride', { ...viewport, deviceScaleFactor: 1, mobile: false });
        await command('Page.navigate', { url: 'http://127.0.0.1:8007/products' });
        await until("window.jQuery?.fn.select2 && window.Livewire && document.querySelectorAll('[data-livewire-field].select2-hidden-accessible').length === 2");
        for (const field of ['companyId', 'categoryId']) {
            await evaluate("document.querySelector('[data-livewire-field=\"" + field + "\"]').nextElementSibling.scrollIntoView({ block: 'center' }); true");
            await evaluate("window.jQuery('[data-livewire-field=\"" + field + "\"]').select2('open'); true");
            const count = await until("document.querySelectorAll('.select2-results__option[aria-selected]').length");
            assert.ok(count <= 20, field + ' dropdown contains at most twenty results');
            const bounds = await evaluate("(() => { const rect = document.querySelector('.select2-dropdown').getBoundingClientRect(); return { page: document.documentElement.scrollWidth, viewport: innerWidth, left: rect.left, right: rect.right, top: rect.top, bottom: rect.bottom, height: innerHeight }; })()");
            assert.ok(bounds.page <= bounds.viewport + 1 && bounds.left >= 0 && bounds.right <= bounds.viewport + 1, 'Dropdown stays inside horizontal viewport');
            assert.ok(bounds.top >= 0 && bounds.bottom <= bounds.height + 1, 'Dropdown stays inside vertical viewport');
            const appearance = await evaluate("(() => { const el = document.querySelector('.select2-dropdown'); return { background: getComputedStyle(el).backgroundColor, zIndex: getComputedStyle(el.parentElement).zIndex }; })()");
            assert.equal(appearance.background, 'rgb(255, 255, 255)');
            assert.ok(Number(appearance.zIndex) >= 1080, 'Dropdown appears above product cards');
            await evaluate("window.jQuery('.select2-results__option[aria-selected]').first().trigger('mouseup'); true");
            await until("(() => { const el = document.querySelector('[data-livewire-field=\"" + field + "\"]'); const root = el.closest('[wire\\\\:id]'); return window.Livewire.find(root.getAttribute('wire:id')).$get('" + field + "') && el.value; })()");
        }
        await evaluate("document.querySelector('[wire\\\\:click=\"clearFilters\"]').click()");
        await until("Array.from(document.querySelectorAll('[data-livewire-field]')).every(el => !window.jQuery(el).val())");
        assert.deepEqual(errors, []);
        console.log('PASS: ' + viewport.width + 'px viewport, dropdown bounds/background/stacking, twenty-result cap, Livewire filters and clear.');
    }
    await evaluate("Array.from(document.querySelectorAll('[data-storefront-theme-toggle]')).find(button => button.getClientRects().length).click(); true");
    await until("document.documentElement.dataset.storefrontTheme === 'dark' && getComputedStyle(document.body).backgroundColor === 'rgb(38, 37, 34)'");
    assert.equal(await evaluate("localStorage.getItem('storefront-theme')"), 'dark');
    assert.ok(await evaluate("document.documentElement.scrollWidth <= innerWidth + 1"), 'Dark-mode mobile header stays within the viewport');
    assert.ok(await evaluate("Array.from(document.querySelectorAll('[data-storefront-theme-toggle]')).every(button => button.getAttribute('aria-pressed') === 'true')"));
    await command('Page.reload');
    await until("document.readyState === 'complete' && document.documentElement.dataset.storefrontTheme === 'dark' && document.querySelector('[data-storefront-theme-toggle]')?.getAttribute('aria-pressed') === 'true'");
    assert.equal(await evaluate("getComputedStyle(document.body).backgroundColor"), 'rgb(38, 37, 34)');
    await evaluate("window.jQuery('[data-livewire-field=\"categoryId\"]').select2('open'); true");
    await until("document.querySelector('.select2-dropdown') && getComputedStyle(document.querySelector('.select2-dropdown')).backgroundColor === 'rgb(48, 47, 44)'");
    await evaluate("window.jQuery('[data-livewire-field=\"categoryId\"]').select2('close'); true");
    if (process.argv.includes('--screenshots')) {
        await command('Emulation.setDeviceMetricsOverride', { width: 1280, height: 900, deviceScaleFactor: 1, mobile: false });
        await evaluate("scrollTo(0, 0)");
        await new Promise(resolve => setTimeout(resolve, 500));
        const screenshot = await command('Page.captureScreenshot');
        const path = join(tmpdir(), 'storefront-dark-products.png');
        writeFileSync(path, Buffer.from(screenshot.data, 'base64'));
        console.log('Screenshot: ' + path);
    }
    await command('Page.navigate', { url: 'http://127.0.0.1:8007/login' });
    await until("document.readyState === 'complete' && document.querySelector('.ec-login-container') && document.documentElement.dataset.storefrontTheme === 'dark'");
    assert.equal(await evaluate("getComputedStyle(document.querySelector('.ec-login-container')).backgroundColor"), 'rgb(48, 47, 44)');
    await evaluate("Array.from(document.querySelectorAll('[data-storefront-theme-toggle]')).find(button => button.getClientRects().length).click(); true");
    assert.equal(await evaluate("localStorage.getItem('storefront-theme')"), 'light');
    assert.equal(await evaluate("document.getElementById('storefront-dark-styles').media"), 'not all');
    await command('Page.navigate', { url: 'http://127.0.0.1:8007/products' });
    await until("document.readyState === 'complete' && document.querySelector('[data-storefront-theme-toggle]') && document.documentElement.dataset.storefrontTheme === 'light'");
    assert.equal(await evaluate("getComputedStyle(document.body).backgroundColor"), 'rgb(255, 255, 255)');
    assert.deepEqual(errors, []);
    console.log('PASS: storefront dark/light toggle, #262522 background, Select2 colors, accessible state, reload and cross-page localStorage persistence.');
    for (const path of ['/products', '/home', '/home/offers', '/login']) {
        await command('Page.navigate', { url: 'http://127.0.0.1:8007' + path });
        await until("document.readyState === 'complete' && document.querySelector('[data-storefront-theme-toggle]')");
        for (const theme of ['light', 'dark']) {
            await evaluate(`(() => { if (document.documentElement.dataset.storefrontTheme !== '${theme}') Array.from(document.querySelectorAll('[data-storefront-theme-toggle]')).find(button => button.getClientRects().length).click(); return true; })()`);
            const blue = await evaluate(`Array.from(document.querySelectorAll('body *')).filter(el => !el.closest('.phpdebugbar') && el.getClientRects().length).flatMap(el => {
                const css = getComputedStyle(el);
                return ['color', 'backgroundColor', 'borderTopColor'].filter(key => {
                    const rgb = css[key].match(/[\\d.]+/g)?.map(Number);
                    return rgb && rgb[2] > rgb[0] + 30 && rgb[2] > rgb[1] + 15 && (rgb.length < 4 || rgb[3] > 0);
                }).map(key => ({ tag: el.tagName, cls: el.className, parent: el.parentElement.className, key, value: css[key] }));
            }).slice(0, 20)`);
            assert.deepEqual(blue, [], path + ' uses warm accents in ' + theme + ' mode');
            const gradient = await evaluate("getComputedStyle(document.querySelector('.btn-primary')).backgroundImage");
            assert.ok(gradient.startsWith('linear-gradient('), path + ' uses the shared button gradient');
        }
        console.log('PASS: ' + path + ', warm charcoal gradients and no blue UI accents in light/dark mode.');
    }
    const adminFixture = `<!doctype html><html data-bs-theme="light" data-skin="default"><head>
        <link rel="stylesheet" href="/assets/vendor/css/core.css">
        <link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css">
        <link rel="stylesheet" href="/assets/css/user-selects.css">
        </head><body><div class="card p-6"><div class="select2-primary">
        <label class="form-label">Categories</label>
        <select id="admin-single" class="select2 form-select" data-user-select2><option value="">Choose</option><option value="1">Category one</option></select>
        <label class="form-label mt-4">Tags</label>
        <select id="admin-multiple" class="select2 form-select" data-user-select2 multiple><option value="1" selected>Tag one</option><option value="2">Tag two</option></select>
        </div></div><script src="/assets/vendor/libs/jquery/jquery.js"></script>
        <script src="/assets/vendor/libs/select2/select2.js"></script>
        <script src="/assets/js/user-selects.js"></script></body></html>`;
    await evaluate(`(() => { const frame = document.createElement('iframe'); frame.id = 'admin-theme-check'; frame.style.cssText = 'position:fixed;inset:0;width:100%;height:100%;z-index:20000;background:white'; frame.srcdoc = ${JSON.stringify(adminFixture)}; document.body.append(frame); return true; })()`);
    await until("document.querySelector('#admin-theme-check').contentDocument.querySelectorAll('.select2-hidden-accessible').length === 2");
    for (const theme of ['light', 'dark']) {
        for (const id of ['admin-single', 'admin-multiple']) {
            await evaluate(`(() => { const win = document.querySelector('#admin-theme-check').contentWindow; win.document.documentElement.dataset.bsTheme = '${theme}'; win.jQuery('#${id}').select2('open'); return true; })()`);
            await evaluate("new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve)))");
            const styles = await evaluate("(() => { const win = document.querySelector('#admin-theme-check').contentWindow; const el = win.document.querySelector('.select2-dropdown'); const css = win.getComputedStyle(el); const rect = el.getBoundingClientRect(); return { background: css.backgroundColor, card: win.getComputedStyle(win.document.querySelector('.card')).backgroundColor, border: css.borderTopWidth, left: rect.left, right: rect.right, top: rect.top, bottom: rect.bottom, width: win.innerWidth, height: win.innerHeight }; })()");
            assert.equal(styles.background, styles.card, 'Select2 uses Vuexy paper background in ' + theme);
            assert.equal(styles.border, '0px', 'Select2 uses Vuexy border styling');
            assert.ok(styles.left >= 0 && styles.right <= styles.width + 1 && styles.top >= 0 && styles.bottom <= styles.height + 1);
            await evaluate(`document.querySelector('#admin-theme-check').contentWindow.jQuery('#${id}').select2('close'); true`);
        }
        console.log('PASS: Vuexy ' + theme + ' theme, single/multiple Select2 styling and bounds.');
    }
})().catch(error => {
    console.error(error.message);
    if (errors.length) console.error(errors.join('\n'));
    process.exitCode = 1;
}).finally(async () => {
    if (socket?.readyState === 1) { try { await command('Browser.close'); } catch {} socket.close(); }
    browser.kill();
});
