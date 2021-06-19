{extends file="parent:frontend/index/sidebar.tpl"}
{* Actual include of the categories *}
{* Sidebar category tree *}
{block name="frontend_index_sidebar"}

{if !$isSupplier}
{$smarty.block.parent}
{/if}
{/block}
