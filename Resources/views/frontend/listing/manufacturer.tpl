{extends file="parent:frontend/listing/manufacturer.tpl"}
{block name="frontend_listing_listing_content"}
{$smarty.block.parent}
{/block}

{* Actual listing *}
{block name="frontend_listing_list_inline"}
    {foreach $sArticles as $sArticle}
        {include file="frontend/listing/product-box/box-minimal.tpl"}
    {/foreach}
{/block}
