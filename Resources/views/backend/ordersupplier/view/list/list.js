
Ext.define('Shopware.apps.Ordersupplier.view.list.List', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.ordersupplier-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.Ordersupplier.view.detail.Window'
        };
    }
});
