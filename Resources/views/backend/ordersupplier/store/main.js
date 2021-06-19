
Ext.define('Shopware.apps.Ordersupplier.store.Main', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'Ordersupplier'
        };
    },
    model: 'Shopware.apps.Ordersupplier.model.Main'
});
