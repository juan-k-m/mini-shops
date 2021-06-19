{extends file="parent:frontend/index/index.tpl"}

{* Shop header *}
{block name='frontend_index_navigation'}
{$smarty.block.parent}
{if $isSupplier}
{if $supplierCoverImage}
{assign var='backgroundCoverImage' value=$supplierCoverImage}
<div class="supplier-cover-image" style="background:url({$backgroundCoverImage}); width:100%">&nbsp</div>
{else}
<div class="supplier-cover-image" style="background:url(''); width:100%">&nbsp</div>
{/if}
{/if}
{/block}
{* Main content *}
{block name='frontend_index_content_wrapper'}
{if $j_error}
<div class="alert is--error is--rounded">
    <div class="alert--icon">
        <i class="icon--element icon--cross"></i>
    </div>
    <div class="alert--content">Una disculpa!, no se pudo concretar el proceso.</div>
</div>
{/if}
{$smarty.block.parent}
{/block}
