{extends file="parent:frontend/listing/index.tpl"}
{* Main content *}
{block name='frontend_index_content'}
<div class="content--wrapper-inner">
{foreach $shops as $shop}
<div class="wrapper-shops">
  <a href="{$shop.url}">
  <div class="shop" style="background:url('http://mercadovirtual.com/custom/plugins/Vendedor/Resources/frontend/images/tianguis.png') no-repeat center;">


    <div class="logo-shop">
      <img src="http://mercadovirtual.com/{$shop.image}">
    </div>
    <div class="info-shop">
      <h4>{$shop.name}</h4>
    </div>
  </div>
</a>

</div>

{/foreach}
</div>
{/block}
