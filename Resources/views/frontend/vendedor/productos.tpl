{extends file="frontend/account/index.tpl"}

{* Breadcrumb *}
{block name="frontend_index_start"}
{$smarty.block.parent}
{s name="productsTitle" assign="snippetProductsTitle"}Productos{/s}
{$sBreadcrumb[] = ["name" => $snippetProductsTitle, "link" => {url action="productos"}]}
{/block}

{* Step box *}
{block name='frontend_index_content_main'}
{if $stepShopArticles}
{include file="frontend/vendedor/steps.tpl" sStepActive="address"}
{/if}
{$smarty.block.parent}
{/block}


{* Account Main Content *}
{block name="frontend_index_content"}

{if $articleUpdated}
<div class="alert is--success is--rounded">
    <div class="alert--icon">
        <i class="icon--element icon--check"></i>
    </div>
    <div class="alert--content">El producto: <strong>{$articleUpdated}</strong> fue actualizado.</div>
</div>
{/if}
{*verify if there are products in DB*}
{if $stepShopArticles}
{block name="frontend_account_orders_info_empty"}
<div class="account--no-orders-info">
  {s name="noProducts" assign="noProducts" force}Todavía no has creado productos.{/s}
  {include file="frontend/_includes/messages.tpl" type="warning" content=$noProducts}
</div>
<div class="createProduct">
<a href="/Vendedor/nuevo-producto" class="btn is--primary j-new-product">Crear nuevoproducto</a>
</div>
{/block}
{else}
{foreach $sArticles as $sArticle}
{include file="frontend/vendedor/box-big-image.tpl"}
{/foreach}
{/if}
{/block}
