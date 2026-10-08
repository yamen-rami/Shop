// Uses an isolated SQLite catalog, separate from the application's database:
// php tests/CatalogFiltersPreview.php
// php -S 127.0.0.1:8017 -t public tests/CatalogFiltersPreview.php
// node tests/CatalogFiltersBrowser.cjs --screenshots
const { spawn } = require('node:child_process');
const { mkdtempSync, writeFileSync } = require('node:fs');
const { tmpdir } = require('node:os');
const { join } = require('node:path');
const assert = require('node:assert/strict');

const base = process.env.CATALOG_PREVIEW_URL || 'http://127.0.0.1:8017';
const sleep = ms => new Promise(resolve => setTimeout(resolve, ms));
const browser = spawn('C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe', [
    '--headless=new', '--disable-gpu', '--no-first-run', '--no-default-browser-check',
    '--remote-debugging-port=9239', '--user-data-dir=' + mkdtempSync(join(tmpdir(), 'catalog-filters-')),
    'about:blank'
], { windowsHide: true, stdio: 'ignore' });
let socket;
let sequence = 0;
const pending = new Map();
const errors = [];
async function command(method, params = {}) {
    const id = ++sequence;
    return new Promise((resolve, reject) => {
        const timeout = setTimeout(() => { pending.delete(id); reject(new Error('CDP timeout: ' + method)); }, 20000);
        pending.set(id, message => {
            clearTimeout(timeout);
            message.error ? reject(new Error(message.error.message)) : resolve(message.result);
        });
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
        const result = await evaluate('Boolean(' + expression + ')');
        if (result) return result;
        await sleep(250);
    }
    throw new Error('Browser condition timed out: ' + expression);
}
async function navigate(path) {
    await command('Page.navigate', { url: base + path });
    await until("document.readyState === 'complete' && window.Alpine && document.querySelector('.catalog-toggle') && document.querySelectorAll('[data-livewire-field].select2-hidden-accessible').length === 2");
}
async function columns() {
    return evaluate("(() => { const cards = Array.from(document.querySelectorAll('.catalog-results .ec-product-content')); const top = cards[0].getBoundingClientRect().top; return cards.filter(card => Math.abs(card.getBoundingClientRect().top - top) < 2).length; })()");
}
async function setPrice(selector, value) {
    await evaluate("(() => { const input = document.querySelector('" + selector + "'); input.value = '" + value + "'; input.dispatchEvent(new Event('input', { bubbles: true })); input.dispatchEvent(new Event('change', { bubbles: true })); })()");
}

(async () => {
    let targets;
    for (let attempt = 0; attempt < 40; attempt++) {
        try { targets = await (await fetch('http://127.0.0.1:9239/json/list')).json(); if (targets.length) break; } catch {}
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
    await command('Emulation.setDeviceMetricsOverride', { width: 1280, height: 1000, deviceScaleFactor: 1, mobile: false });
    await navigate('/products');
    await until("document.querySelectorAll('.catalog-results .ec-product-content').length === 12");
    assert.equal(await columns(), 3, 'Open sidebar has three desktop columns');
    await evaluate("document.querySelector('.catalog-toggle').click()");
    await until("document.querySelector('.catalog--collapsed') && document.querySelector('.catalog-sidebar').getClientRects().length === 0");
    assert.equal(await columns(), 4, 'Collapsed sidebar has four desktop columns');
    assert.equal(await evaluate("localStorage.getItem('catalog-sidebar-collapsed')"), 'true');
    await navigate('/products');
    await until("document.querySelector('.catalog--collapsed')");
    assert.equal(await columns(), 4, 'Collapsed preference survives reload');
    await evaluate("document.querySelector('.catalog-toggle').click()");
    await until("!document.querySelector('.catalog--collapsed')");
    assert.equal(await evaluate("localStorage.getItem('catalog-sidebar-collapsed')"), 'false');
    await navigate('/products');
    assert.equal(await columns(), 3, 'Expanded preference survives reload');
    console.log('PASS: three/four desktop columns and both saved sidebar states.');

    await setPrice('.catalog-range-max', 60);
    await until("document.querySelectorAll('.catalog-results .ec-product-content').length === 6");
    await setPrice('#catalog-min-price', 30);
    await until("document.querySelectorAll('.catalog-results .ec-product-content').length === 4");
    await evaluate("document.querySelector('.catalog-color[aria-label=Red]').click()");
    await until("document.querySelectorAll('.catalog-results .ec-product-content').length === 2 && document.querySelector('.catalog-color[aria-label=Red]').getAttribute('aria-pressed') === 'true'");
    assert.deepEqual(await evaluate("Array.from(document.querySelectorAll('.catalog-results .ec-pro-title')).map(el => el.textContent.trim())"), ['Filter fixture 4', 'Filter fixture 6']);
    assert.equal(await evaluate("document.querySelector('#catalog-min-price').value"), '30');
    assert.equal(await evaluate("document.querySelector('#catalog-max-price').value"), '60');
    await evaluate("document.querySelector('.catalog-toggle').click(); document.querySelector('#catalog-sort').value = 'price-desc'; document.querySelector('#catalog-sort').dispatchEvent(new Event('change', { bubbles: true }));");
    await until("document.querySelector('.catalog-results .ec-pro-title').textContent.includes('fixture 6')");
    assert.ok(await evaluate("!!document.querySelector('.catalog--collapsed') && document.querySelector('.catalog-sidebar').getClientRects().length === 0"), 'Livewire updates preserve collapsed state');
    await evaluate("document.querySelector('.catalog-toggle').click(); document.querySelector('.catalog-sidebar-heading .catalog-clear').click()");
    await until("document.querySelectorAll('.catalog-results .ec-product-content').length === 12 && document.querySelector('#catalog-min-price').value === '10' && document.querySelector('#catalog-max-price').value === '120'");
    assert.equal(await evaluate("document.querySelectorAll('.catalog-color.is-selected').length"), 0);
    console.log('PASS: slider, numeric prices, color matching, combined filters, sorting and reset.');

    // Drag the actual lower thumb to check pointer interaction with overlapping range inputs.
    await evaluate("document.querySelector('.catalog-price-slider').scrollIntoView({ block: 'center' })");
    await sleep(500);
    const drag = await evaluate("(() => { const r = document.querySelector('.catalog-range-min').getBoundingClientRect(); return { x: r.left + 9, y: r.top + r.height / 2, end: r.left + r.width * .3 }; })()");
    await command('Input.dispatchMouseEvent', { type: 'mouseMoved', x: drag.x, y: drag.y });
    await command('Input.dispatchMouseEvent', { type: 'mousePressed', x: drag.x, y: drag.y, button: 'left', clickCount: 1 });
    await command('Input.dispatchMouseEvent', { type: 'mouseMoved', x: drag.end, y: drag.y, button: 'left', buttons: 1 });
    await command('Input.dispatchMouseEvent', { type: 'mouseReleased', x: drag.end, y: drag.y, button: 'left', clickCount: 1 });
    await until("Number(document.querySelector('#catalog-min-price').value) > 20 && document.querySelectorAll('.catalog-results .ec-product-content').length < 12");
    await evaluate("document.querySelector('.catalog-sidebar-heading .catalog-clear').click()");
    await until("document.querySelectorAll('.catalog-results .ec-product-content').length === 12");
    console.log('PASS: mouse drag filters products when the price thumb is released.');

    for (const width of [768, 375]) {
        await command('Emulation.setDeviceMetricsOverride', { width, height: 1000, deviceScaleFactor: 1, mobile: false });
        await navigate('/products');
        assert.equal(await columns(), 2, 'Two responsive product columns at ' + width);
        assert.ok(await evaluate("document.documentElement.scrollWidth <= innerWidth + 1"), 'No horizontal overflow at ' + width);
        await evaluate("document.querySelector('.catalog-toggle').click()");
        await until("document.querySelector('.catalog-sidebar').getClientRects().length === 0");
        assert.equal(await columns(), 2, 'Responsive columns remain usable when collapsed');
        await evaluate("document.querySelector('.catalog-toggle').click()");
        console.log('PASS: ' + width + 'px responsive layout and sidebar toggle.');
    }
    await evaluate("document.documentElement.dataset.storefrontTheme = 'dark'");
    assert.equal(await evaluate("getComputedStyle(document.querySelector('.catalog-sidebar')).backgroundColor"), 'rgb(48, 47, 44)');
    await command('Emulation.setDeviceMetricsOverride', { width: 1280, height: 1200, deviceScaleFactor: 1, mobile: false });
    await evaluate("document.documentElement.dataset.storefrontTheme = 'light'; document.querySelector('.catalog-toolbar').scrollIntoView({ block: 'start' })");
    await sleep(300);
    if (process.argv.includes('--screenshots')) {
        const screenshot = await command('Page.captureScreenshot', { format: 'png' });
        writeFileSync(join(process.cwd(), 'storage/framework/testing/catalog-sidebar-desktop.png'), Buffer.from(screenshot.data, 'base64'));
    }
    assert.deepEqual(errors, [], 'No JavaScript errors');
    console.log('PASS: dark sidebar styling and no JavaScript errors.');
})().catch(async error => {
    console.error(error);
    try {
        console.error(await evaluate("({ url: location.href, count: document.querySelectorAll('.catalog-results .ec-product-content').length, minimum: document.querySelector('#catalog-min-price')?.value, maximum: document.querySelector('#catalog-max-price')?.value })"));
        console.error(errors);
    } catch {}
    if (process.argv.includes('--screenshots') && socket?.readyState === WebSocket.OPEN) {
        const screenshot = await command('Page.captureScreenshot', { format: 'png' });
        writeFileSync(join(process.cwd(), 'storage/framework/testing/catalog-sidebar-debug.png'), Buffer.from(screenshot.data, 'base64'));
    }
    process.exitCode = 1;
}).finally(() => { socket?.close(); browser.kill(); });
