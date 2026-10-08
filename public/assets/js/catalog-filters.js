window.catalogSidebar = () => ({
    collapsed: false,
    init() {
        try {
            this.collapsed = localStorage.getItem('catalog-sidebar-collapsed') === 'true';
        } catch {}
    },
    toggle() {
        this.collapsed = !this.collapsed;
        try {
            localStorage.setItem('catalog-sidebar-collapsed', String(this.collapsed));
        } catch {}
    }
});

window.catalogPriceFilter = wire => ({
    minimum: wire.entangle('minPrice'),
    maximum: wire.entangle('maxPrice'),
    get floor() { return Number(this.$refs.priceBounds.dataset.minimum); },
    get ceiling() { return Number(this.$refs.priceBounds.dataset.maximum); },
    get lower() {
        const value = this.minimum === '' ? this.floor : Number(this.minimum);
        return Number.isFinite(value) ? Math.max(this.floor, Math.min(this.ceiling, value)) : this.floor;
    },
    get upper() {
        const value = this.maximum === '' ? this.ceiling : Number(this.maximum);
        return Number.isFinite(value) ? Math.max(this.floor, Math.min(this.ceiling, value)) : this.ceiling;
    },
    get selectionStyle() {
        const span = this.ceiling - this.floor;
        const start = (Math.min(this.lower, this.upper) - this.floor) / span * 100;
        const end = (Math.max(this.lower, this.upper) - this.floor) / span * 100;
        return { left: start + '%', right: (100 - end) + '%' };
    },
    setMinimum(value) {
        this.minimum = String(Math.min(this.upper, Math.max(this.floor, Number(value) || 0)));
    },
    setMaximum(value) {
        this.maximum = String(Math.max(this.lower, Math.min(this.ceiling, Number(value) || 0)));
    },
    apply() { return this.$nextTick(() => wire.$commit()); }
});
