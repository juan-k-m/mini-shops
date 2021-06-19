{extends file="parent:frontend/detail/content.tpl"}
{* "Buy now" box container *}
{block name="frontend_detail_index_buy_box_container"}
{$smarty.block.parent}
<a href="{url controller='listing' action='manufacturer' sSupplier=$sArticle.supplierID}"
target="{$information.target}"
class="content--link link--supplier"
title="{$snippetDetailDescriptionLinkInformation|escape}">

<i class="icon--arrow-right"></i>
{s name="DetailDescriptionLinkInformation" force}Visitar tienda y ver más artículos del vendedor{/s}
<span class="supplierNameDetail">{$sArticle.supplierName}</span>
</a>

{/block}
