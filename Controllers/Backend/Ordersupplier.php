<?php
use Vendedor\Models\Supplier;
/**
 * Backend controllers extending from Shopware_Controllers_Backend_Application do support the new backend components
 */
class Shopware_Controllers_Backend_Ordersupplier extends Shopware_Controllers_Backend_Application
{
    protected $model = Supplier::class;
    protected $alias = 'mysupplier';
}
