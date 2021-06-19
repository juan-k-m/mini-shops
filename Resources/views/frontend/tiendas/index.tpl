{extends file="parent:frontend/listing/index.tpl"}
{* Main content *}
{block name='frontend_index_content'}
<div class="content--wrapper-inner">
{foreach $shops as $shop}
<div class="wrapper-shops">
  <a href="{$shop.url}">

  {*TODO create absolute path*}
  <div class="shop" style="background:url('http://sw5.jc/custom/plugins/Vendedor/Resources/frontend/images/{$shop.image}') no-repeat center;">


    <div class="logo-shop">
      <img src="http://sw5.jc/{$shop.image}">
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
