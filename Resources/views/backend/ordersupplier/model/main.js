//
Ext.define('Shopware.apps.Ordersupplier.model.Main', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'Ordersupplier',
            detail: 'Shopware.apps.Ordersupplier.view.detail.Container'
        };
    },


    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'name', type: 'string', useNull: false },
        { name : 'userId', type: 'int', useNull: false },
        { name : 'supplierId', type: 'int', useNull: false },
        { name : 'orderParentId', type: 'int', useNull: false },
        { name : 'documentHash', type: 'string', useNull: false },
        { name : 'orderdata', type: 'string', useNull: false },
        { name : 'orderTime', type: 'date', dateFormat: 'd.m.Y', useNull: false },
    ]
});
