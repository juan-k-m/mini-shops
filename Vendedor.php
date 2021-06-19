<?php

namespace Vendedor;

use Vendedor\Services\InstallHelper;
use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\ORM\Tools\SchemaTool;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
/**
* Shopware-Plugin Vendedor.
*/
class Vendedor extends Plugin
{
  /**
  * Adds the widget to the database and creates the database schema.
  *
  * @param Plugin\Context\InstallContext $installContext
  */
  public function install(Plugin\Context\InstallContext $installContext)
  {
    try {

      $helper = $this->gelHelper();
      $helper->createNumberRanges();
      $helper->createDocument();
      $this->createAttributes();
      $this->createSchema();

    } catch (\Exception $error) {
    }

    parent::install($installContext);

  }

  /**
  * Remove widget and remove database schema.
  *
  * @param Plugin\Context\UninstallContext $uninstallContext
  */
  public function uninstall(Plugin\Context\UninstallContext $uninstallContext)
  {
    parent::uninstall($uninstallContext);
    if (!$uninstallContext->keepUserData()) {
      $this->destroyAttributes();

    }
      $this->removeSchema();
  }

  public function deactivate(DeactivateContext $deactivateContext)
  {
      // on plugin deactivation clear the cache
      $deactivateContext->scheduleClearCache(DeactivateContext::CACHE_LIST_ALL);
  }

  /**
  * @param ContainerBuilder $container
  */
  public function build(ContainerBuilder $container)
  {
    $container->setParameter('vendedor.plugin_dir', $this->getPath());
    parent::build($container);
  }

  /**
  * creates database tables on base of doctrine models
  */
  private function createSchema()
  {
    $tool = new SchemaTool($this->container->get('models'));
    $classes = [
      $this->container->get('models')->getClassMetadata(\Vendedor\Models\Supplier::class)
    ];
    $tool->createSchema($classes);
  }

  private function removeSchema()
  {
    $tool = new SchemaTool($this->container->get('models'));
    $classes = [
      $this->container->get('models')->getClassMetadata(\Vendedor\Models\Supplier::class)
    ];
    $tool->dropSchema($classes);
  }


  public function createAttributes()
  {

    $service = $this->container->get('shopware_attribute.crud_service');

    $service->update('s_user_attributes', 'j_supplier_id', 'integer', [
      'label' => 'Vendedor Id',
      'supportText' => 'Unterstützungstext',
      'helpText' => 'Hilfetext',

      //user has the opportunity to translate the attribute field for each shop
      'translatable' => true,

      //attribute will be displayed in the backend module
      'displayInBackend' => true,

      //in case of multi_selection or single_selection type, article entities can be selected,
      //'entity' => 'Shopware\Models\Article\Article',

      //numeric position for the backend view, sorted ascending
      'position' => 2,

      //user can modify the attribute in the free text field module
      'custom' => true,

    ]);

    $service->update('s_articles_attributes', 'j_image_id', 'integer', [


      //user has the opportunity to translate the attribute field for each shop
      'translatable' => true,

      //attribute will be displayed in the backend module
      'displayInBackend' => false,

      //in case of multi_selection or single_selection type, article entities can be selected,
      //'entity' => 'Shopware\Models\Article\Article',

      //numeric position for the backend view, sorted ascending
      'position' => 2,

      //user can modify the attribute in the free text field module
      'custom' => true,

    ]);

    $service->update('s_user_attributes', 'j_is_supplier', 'boolean', [
      'label' => 'Usuario es un vendedor',
      'supportText' => 'Unterstützungstext',
      'helpText' => 'Hilfetext',

      //user has the opportunity to translate the attribute field for each shop
      'translatable' => true,

      //attribute will be displayed in the backend module
      'displayInBackend' => true,

      //in case of multi_selection or single_selection type, article entities can be selected,
      //'entity' => 'Shopware\Models\Article\Article',

      //numeric position for the backend view, sorted ascending
      'position' => 2,

      //user can modify the attribute in the free text field module
      'custom' => true,

    ]);

    $service->update('s_articles_supplier_attributes', 'j_supplier_cover_image', 'text', [

      //user has the opportunity to translate the attribute field for each shop
      'translatable' => true,

      //attribute will be displayed in the backend module
      'displayInBackend' => false,

      //in case of multi_selection or single_selection type, article entities can be selected,
      //'entity' => 'Shopware\Models\Article\Article',

      //numeric position for the backend view, sorted ascending
      'position' => 2,

      //user can modify the attribute in the free text field module
      'custom' => true,

    ]);

    $service->update('s_articles_supplier_attributes', 'j_supplier_cover_image_id', 'integer', [

      //user has the opportunity to translate the attribute field for each shop
      'translatable' => true,

      //attribute will be displayed in the backend module
      'displayInBackend' => true,

      //in case of multi_selection or single_selection type, article entities can be selected,
      //'entity' => 'Shopware\Models\Article\Article',

      //numeric position for the backend view, sorted ascending
      'position' => 2,

      //user can modify the attribute in the free text field module
      'custom' => true,

    ]);

    $service->update('s_articles_supplier_attributes', 'j_supplier_email', 'text', [

      'label' => 'Correo electrónico:',
      //'supportText' => 'Unterstützungstext',
      //'helpText' => 'Hilfetext',

      //user has the opportunity to translate the attribute field for each shop
      'translatable' => true,

      //attribute will be displayed in the backend module
      'displayInBackend' => true,

      //in case of multi_selection or single_selection type, article entities can be selected,
      //'entity' => 'Shopware\Models\Article\Article',

      //numeric position for the backend view, sorted ascending
      'position' => 2,

      //user can modify the attribute in the free text field module
      'custom' => true,

    ]);
    $service->update('s_articles_supplier_attributes', 'j_supplier_phone', 'text', [

      'label' => 'Correo electrónico:',
      //'supportText' => 'Unterstützungstext',
      //'helpText' => 'Hilfetext',

      //user has the opportunity to translate the attribute field for each shop
      'translatable' => true,

      //attribute will be displayed in the backend module
      'displayInBackend' => true,

      //in case of multi_selection or single_selection type, article entities can be selected,
      //'entity' => 'Shopware\Models\Article\Article',

      //numeric position for the backend view, sorted ascending
      'position' => 2,

      //user can modify the attribute in the free text field module
      'custom' => true,

    ]);

    $service->update('s_articles_supplier_attributes', 'j_supplier_address', 'text', [

      'label' => 'Teléfono:',
    //  'supportText' => 'Unterstützungstext',
      // 'helpText' => 'Hilfetext',

      //user has the opportunity to translate the attribute field for each shop
      'translatable' => true,

      //attribute will be displayed in the backend module
      'displayInBackend' => true,

      //in case of multi_selection or single_selection type, article entities can be selected,
      //'entity' => 'Shopware\Models\Article\Article',

      //numeric position for the backend view, sorted ascending
      'position' => 3,

      //user can modify the attribute in the free text field module
      'custom' => true,

    ]);


    $metaDataCache = Shopware()->Models()->getConfiguration()->getMetadataCacheImpl();
    $metaDataCache->deleteAll();

    Shopware()->Models()->generateAttributeModels(['s_user_attributes']);
    Shopware()->Models()->generateAttributeModels(['s_articles_supplier_attributes']);
    Shopware()->Models()->generateAttributeModels(['s_articles_attributes']);


  }

  private function destroyAttributes()
  {

    $service = $this->container->get('shopware_attribute.crud_service');

    try {
      $service->delete('s_user_attributes', 'j_is_supplier');
      $service->delete('s_user_attributes', 'j_supplier_id');
      $service->delete('s_articles_supplier_attributes', 'j_supplier_cover_image');
      $service->delete('s_articles_supplier_attributes', 'j_supplier_cover_image_id');
      $service->delete('s_articles_supplier_attributes', 'j_supplier_email');
      $service->delete('s_articles_supplier_attributes', 'j_supplier_phone');
      $service->delete('s_articles_supplier_attributes', 'j_supplier_address');
      $service->delete('s_articles_attributes', 'j_image_id');
    } catch (\Exception $error) {

    }

    Shopware()->Models()->generateAttributeModels(['s_user_attributes']);
    Shopware()->Models()->generateAttributeModels(['s_articles_supplier_attributes']);
    Shopware()->Models()->generateAttributeModels(['s_articles_attributes']);
  }


  private function gelHelper()
  {

    return new InstallHelper;

  }

}
