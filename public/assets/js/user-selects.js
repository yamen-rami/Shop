'use strict';

function constrainSelectDropdown(select) {
    const instance = select.data('select2');
    if (!instance?.isOpen()) return;
    const dropdown = instance.dropdown.$dropdown;
    const results = instance.results.$results;
    const anchor = instance.$container[0].getBoundingClientRect();
    const margin = 8;
    const width = Math.min(anchor.width, document.documentElement.clientWidth - margin * 2);
    dropdown.css('width', width);
    instance.dropdown.$dropdownContainer.css({ width, maxWidth: width });
    const overhead = dropdown.outerHeight() - results.outerHeight();
    const below = Math.max(0, window.innerHeight - anchor.bottom - margin);
    const above = Math.max(0, anchor.top - margin);
    const desiredHeight = Math.min(results[0].scrollHeight, 200) + overhead;
    const openAbove = below < desiredHeight && above > below;
    results.css('max-height', Math.max(0, Math.min(200, (openAbove ? above : below) - overhead)));
    const height = dropdown.outerHeight();
    const left = Math.max(margin, Math.min(anchor.left, document.documentElement.clientWidth - width - margin));
    const top = Math.max(margin, Math.min(openAbove ? anchor.top - height : anchor.bottom, window.innerHeight - height - margin));
    instance.dropdown.$dropdownContainer.offset({ left: left + window.scrollX, top: top + window.scrollY });
    dropdown.toggleClass('select2-dropdown--above', openAbove).toggleClass('select2-dropdown--below', !openAbove);
    instance.$container.toggleClass('select2-container--above', openAbove).toggleClass('select2-container--below', !openAbove);
}

function registerSelectDropdownBounds(select) {
    let observer;
    let frame;
    const schedule = () => {
        cancelAnimationFrame(frame);
        frame = requestAnimationFrame(() => constrainSelectDropdown(select));
    };
    select.on('select2:open.userSelects', () => {
        const instance = select.data('select2');
        observer = new MutationObserver(schedule);
        observer.observe(instance.results.$results[0], { childList: true, subtree: true });
        window.addEventListener('resize', schedule);
        window.addEventListener('scroll', schedule, true);
        schedule();
    });
    select.on('select2:close.userSelects', () => {
        observer?.disconnect();
        cancelAnimationFrame(frame);
        window.removeEventListener('resize', schedule);
        window.removeEventListener('scroll', schedule, true);
    });
}

function selectLivewireComponent(element) {
    const root = element.closest('[wire\\:id]');
    return root && window.Livewire ? window.Livewire.find(root.getAttribute('wire:id')) : null;
}

function notifyRemoteSelection(element, select) {
    const selected = select.select2('data').map(item => ({
        ...item,
        price: item.price ?? item.element?.dataset.price
    }));
    element.dispatchEvent(new CustomEvent('remote-select-changed', { bubbles: true, detail: { selected } }));
}

function initializeUserSelects() {
    if (!window.jQuery || !window.jQuery.fn.select2) return;
    window.jQuery('[data-user-select2]').each(function () {
        const element = this;
        const select = window.jQuery(element);
        if (select.hasClass('select2-hidden-accessible')) return;
        const options = {
            width: '100%',
            placeholder: element.dataset.placeholder || 'Search…',
            allowClear: true
        };
        if (element.dataset.select2Url) {
            options.ajax = {
                url: element.dataset.select2Url,
                dataType: 'json',
                delay: 300,
                data: params => ({ q: params.term || '', page: params.page || 1 }),
                processResults: response => response
            };
        }
        select.select2(options);
        registerSelectDropdownBounds(select);
        select.on('change.userSelects', function (event) {
            if (event.originalEvent) return;
            const component = selectLivewireComponent(element);
            if (component && element.dataset.livewireField) {
                component.$set(element.dataset.livewireField, select.val() ?? '');
            }
            element.dispatchEvent(new Event('change', { bubbles: true }));
            notifyRemoteSelection(element, select);
        });
        const selectedOptions = Array.from(element.selectedOptions).filter(option => option.value !== '');
        for (let offset = 0; element.dataset.select2Url && offset < selectedOptions.length; offset += 20) {
            const batch = selectedOptions.slice(offset, offset + 20);
            window.jQuery.getJSON(element.dataset.select2Url, { ids: batch.map(option => option.value) })
                .done(response => {
                    for (const item of response.results) {
                        const option = batch.find(option => String(option.value) === String(item.id));
                        if (!option || !option.isConnected) continue;
                        option.textContent = item.text;
                        if (item.price !== undefined) option.dataset.price = item.price;
                        window.jQuery(option).data('data', { ...item, element: option });
                    }
                    select.trigger('change.select2');
                    notifyRemoteSelection(element, select);
                });
        }
    });
}

function syncRemoteFilters() {
    document.querySelectorAll('[data-livewire-field]').forEach(element => {
        const component = selectLivewireComponent(element);
        if (!component) return;
        const value = component.$get(element.dataset.livewireField) ?? '';
        const select = window.jQuery(element);
        if (String(select.val() ?? '') !== String(value)) {
            select.val(value).trigger('change.select2');
        }
    });
}

function registerSelectLivewireHooks() {
    if (!window.Livewire || window.remoteSelectHooksRegistered) return;
    window.remoteSelectHooksRegistered = true;
    window.Livewire.hook('morphed', () => {
        initializeUserSelects();
        syncRemoteFilters();
    });
    window.Livewire.hook('commit', ({ succeed }) => {
        succeed(() => queueMicrotask(syncRemoteFilters));
    });
}

if (window.jQuery) window.jQuery(initializeUserSelects);
document.addEventListener('livewire:navigated', initializeUserSelects);
document.addEventListener('livewire:init', registerSelectLivewireHooks);
registerSelectLivewireHooks();
