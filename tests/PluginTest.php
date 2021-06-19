<?php

namespace Vendedor\Tests;

use Vendedor\Vendedor as Plugin;
use Shopware\Components\Test\Plugin\TestCase;

class PluginTest extends TestCase
{
    protected static $ensureLoadedPlugins = [
        'Vendedor' => []
    ];

    public function testCanCreateInstance()
    {
        /** @var Plugin $plugin */
        $plugin = Shopware()->Container()->get('kernel')->getPlugins()['Vendedor'];

        $this->assertInstanceOf(Plugin::class, $plugin);
    }
}
