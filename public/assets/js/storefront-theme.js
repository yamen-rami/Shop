'use strict';

(() => {
    const storageKey = 'storefront-theme';
    const root = document.documentElement;

    function applyTheme(theme) {
        const dark = theme === 'dark';
        root.dataset.storefrontTheme = dark ? 'dark' : 'light';
        document.getElementById('storefront-dark-styles').media = dark ? 'all' : 'not all';
        document.querySelectorAll('[data-storefront-theme-toggle]').forEach(button => {
            const label = dark ? button.dataset.lightLabel : button.dataset.darkLabel;
            button.setAttribute('aria-pressed', String(dark));
            button.setAttribute('aria-label', label);
            button.setAttribute('title', label);
        });
    }

    let savedTheme = 'light';
    try { savedTheme = localStorage.getItem(storageKey) || 'light'; } catch {}
    applyTheme(savedTheme);

    document.addEventListener('DOMContentLoaded', () => applyTheme(root.dataset.storefrontTheme));
    document.addEventListener('livewire:navigated', () => applyTheme(root.dataset.storefrontTheme));
    document.addEventListener('click', event => {
        if (!event.target.closest('[data-storefront-theme-toggle]')) return;
        const theme = root.dataset.storefrontTheme === 'dark' ? 'light' : 'dark';
        applyTheme(theme);
        try { localStorage.setItem(storageKey, theme); } catch {}
    });
    window.addEventListener('storage', event => {
        if (event.key === storageKey || event.key === null) applyTheme(event.newValue);
    });
})();
