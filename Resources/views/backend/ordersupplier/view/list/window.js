//
Ext.define('Shopware.apps.Ordersupplier.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.supplier-list-window',
    height: 450,
    title : '{s name=window_title}Supplier listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.Ordersupplier.view.list.List',
            listingStore: 'Shopware.apps.Ordersupplier.store.Main'
        };
    }
});
