const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const { runInNewContext } = require('node:vm');
const test = require('node:test');

function harness({ field = '', selectedCount = 0 } = {}) {
    const listeners = {}, hooks = {}, requests = [], changes = [], updates = [];
    let initialized = false, initializationCount = 0, settings, handler, currentValue = '';
    const element = {
        dataset: { select2Url: '/select-options/categories', livewireField: field },
        selectedOptions: Array.from({ length: selectedCount }, (_, id) => ({ value: String(id + 1), dataset: {}, isConnected: true })),
        closest: () => field ? { getAttribute: () => 'catalog-id' } : null,
        dispatchEvent(event) {
            changes.push(event);
            if (event.type === 'change') handler.call(this, { originalEvent: event });
        },
    };
    let selectedValue = '';
    const select = {
        hasClass: () => initialized,
        select2(value) {
            if (value === 'data') return element.selectedOptions.map(option => option.select2Data || { id: option.value, element: option });
            settings = value; initialized = true; initializationCount++; return this;
        },
        on(name, callback) { if (name === 'change.userSelects') handler = callback; return this; },
        val(...args) { if (!args.length) return selectedValue; selectedValue = args[0]; return this; },
        trigger: () => select,
    };
    const jquery = value => {
        if (typeof value === 'function') return value();
        if (value === element) return select;
        if (typeof value === 'object') return { data: (key, data) => { value.select2Data = data; } };
        return { each: callback => callback.call(element) };
    };
    jquery.fn = { select2: () => {} };
    jquery.getJSON = (url, data) => {
        requests.push({ url, data });
        return { done: callback => callback({ results: data.ids.map(id => ({ id, text: 'Category ' + id, price: 12.35 })) }) };
    };
    const component = {
        $set: (name, value) => { currentValue = value; updates.push({ name, value }); },
        $get: () => currentValue,
    };
    runInNewContext(readFileSync('public/assets/js/user-selects.js', 'utf8'), {
        window: { jQuery: jquery, Livewire: { find: () => component, hook: (name, callback) => { hooks[name] = callback; } } },
        document: { addEventListener: (name, callback) => { listeners[name] = callback; }, querySelectorAll: () => field ? [element] : [] },
        Event, CustomEvent, queueMicrotask: callback => callback(),
    });
    return {
        settings, element, select, requests, changes, updates, hooks, listeners,
        change: () => handler.call(element, {}),
        setServerValue: value => { currentValue = value; },
        initializationCount: () => initializationCount,
    };
}

test('remote Select2 sends search and page to the endpoint and accepts server pagination', () => {
    const h = harness();
    assert.equal(h.settings.ajax.url, '/select-options/categories');
    assert.equal(h.settings.ajax.delay, 300);
    const params = h.settings.ajax.data({ term: 'Coffee', page: 3 });
    assert.equal(params.q, 'Coffee');
    assert.equal(params.page, 3);
    const response = { results: [{ id: 1, text: 'Coffee' }], pagination: { more: true } };
    assert.equal(h.settings.ajax.processResults(response), response);
});

test('Select2 changes update Livewire and Alpine once and initialize only once', () => {
    const h = harness({ field: 'companyId' });
    h.select.val('4');
    h.change();
    assert.deepEqual(h.updates, [{ name: 'companyId', value: '4' }]);
    assert.equal(h.changes.filter(event => event.type === 'change').length, 1);
    assert.equal(h.changes.find(event => event.type === 'change').bubbles, true);
    assert.equal(h.changes.filter(event => event.type === 'remote-select-changed').length, 1);
    h.listeners['livewire:navigated']();
    h.hooks.morphed();
    assert.equal(h.initializationCount(), 1);
    h.setServerValue('');
    h.hooks.commit({ succeed: callback => callback() });
    assert.equal(h.select.val(), '');
    assert.equal(h.updates.length, 1);
});

test('selected labels and prices are restored in batches of at most twenty without losing selections', () => {
    const h = harness({ selectedCount: 45 });
    assert.deepEqual(h.requests.map(request => request.data.ids.length), [20, 20, 5]);
    assert.equal(h.element.selectedOptions.length, 45);
    assert.equal(h.element.selectedOptions[44].textContent, 'Category 45');
    assert.equal(h.element.selectedOptions[44].dataset.price, 12.35);
    const lastEvent = h.changes.filter(event => event.type === 'remote-select-changed').at(-1);
    assert.equal(lastEvent.detail.selected[44].price, 12.35);
});
