<script>
    // Shared collapsible-chart utilities (Alpine component + Chart.js redraw helpers).
    // Register any Chart instance with registerChart(id, chart) right after it is created
    // so it can be resized/redrawn when a collapsed card is re-expanded.

    window.__charts = window.__charts || {};

    window.registerChart = function (id, chart) {
        if (id && chart) window.__charts[id] = chart;
        return chart;
    };

    window.redrawChart = function (id) {
        const chart = window.__charts && window.__charts[id];
        if (chart && typeof chart.resize === 'function') {
            chart.resize();
        }
        return !!chart;
    };

    window.chartCollapsedKey = function (id) {
        return 'chart:collapse:' + id;
    };

    window.chartCard = function (id) {
        const key = window.chartCollapsedKey(id);
        let collapsed = false;
        try { collapsed = localStorage.getItem(key) === '1'; } catch (e) {}
        return {
            id: id,
            collapsed: collapsed,
            persist() {
                try { localStorage.setItem(key, this.collapsed ? '1' : '0'); } catch (e) {}
            },
            toggle() {
                this.collapsed = !this.collapsed;
                this.persist();
                if (!this.collapsed) {
                    // Let the container regain its size, then force a full redraw.
                    const id = this.id;
                    setTimeout(function () { window.redrawChart(id); }, 100);
                }
            },
        };
    };
</script>
