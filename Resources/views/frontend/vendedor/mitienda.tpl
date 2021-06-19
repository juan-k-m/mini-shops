{extends file="frontend/account/index.tpl"}

{* Breadcrumb *}
{block name="frontend_index_start"}
{$smarty.block.parent}
{s name="myShop" assign="snippetMyShopTitle"}Mi Tienda{/s}
{$sBreadcrumb[] = ["name" => $snippetMyShopTitle, "link" => {url action="mi-tienda"}]}
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

<div class="content">

  <div class="alert is--error do-not-show is--rounded{if $j_error} error-show{/if}">
    <div class="alert--icon">
      <i class="icon--element icon--cross"></i>
    </div>
    <div class="alert--content" id="target_msg_div_error">{if $j_error}{$j_error}{/if}</div>
  </div>

  <div>
    <h1 class="page--title">{s name="myShop"}Mi Tienda{/s}</h1>
  </div>
  <div class="panel">
    <p><a href="{$supplier.shopLink}" target="_blank">{s  name="shopLinkP"}Visitar mi tienda{/s}</a></p>
  </div>
  <div class="forms--container panelis--rounded">
    <form class="panel register--form" method="post" action="{url controller='Vendedor' action='saveShopChanges'}" enctype="multipart/form-data">
      <div class="panel-border">
        <div class="columns">
          <div class="field-container">
              <h5 class="input-title">Nombre de la tienda.</h5>
            <input id="myShopNameInput" type="text" name="myShop[shop_name]" placeholder="{s name="myShopName"}*Nombre de la tienda{/s}" required class="address--field is--required" value="{$supplier.name}">
          </div>
          <div class="field-container">
              <h5 class="input-title">Descriptión de la tienda.</h5>
            <textarea name="myShop[shop_description]" placeholder="{s name="myShopDescription"}*Descripción de la tienda{/s}" value="{$supplier.description}" required>
              {$supplier.description}
            </textarea>
          </div>
          <div class="field-container">
              <h5 class="input-title">Dirección de página del vendedor.</h5>
            <input type="text" name="myShop[shop_website]" placeholder="{s name="myShopWebsite"}*Página web de tu empresa{/s}" required class="address--field is--required" value="{$supplier.website}">
          </div>
        </div>
      </div>
      <div class="panel-border">
        <div class="columns">
          <div class="field-container">

            {if $supplier}
            <div class="image-cover-container">
              <img src="{$supplier.coverImage}">
            </div>
            {/if}
            <p class="upload_p_trigger">{s name="shopCoverImageInput"}Imagen para la portada de la tienda{/s}</p>
            <input type="file" class="custom-file-input" name="image" class="address--field is--required has--border" id="cover_image">

          </div>
        </div>
      </div>
      <div class="panel-border">
        <div class="columns">
          <div class="field-container">
        {if $supplier}
        <div class="logo">
          <img src="{$supplier.logo}">
        </div>
        {/if}
        <p class="upload_p_trigger">{s name="shopCoverLogoInput"}Logo de tu empresa{/s}</p>
        <input type="file" class="custom-file-input" name="logo"  class="address--field is--required has--border" id="logo">
      </div>
    </div>
  </div>

  <div class="panel">
    <div class="columns">
      <div class="field-container">
        <input type="submit" value="Guardar Cambios" class="button is--primary">
      </div>
    </div>
  </form>
</div>
</div>
</div>

{/block}
