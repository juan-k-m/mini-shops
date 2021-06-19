{extends file="frontend/account/index.tpl"}

{* Breadcrumb *}
{block name="frontend_index_start"}
{$smarty.block.parent}
{s name="productsTitleNuevo" assign="productsTitleNuevo" force}Nuevo producto{/s}
{$sBreadcrumb[] = ["name" => $productsTitleNuevo, "link" => {url action="nuevoproducto"}]}
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

  <div>
    <h1 class="page--title">{s name="newProduct"}Producto Nuevo{/s}</h1>
  </div>
  <div class="forms--container panel has--border is--rounded">
    <form class="panel register--form" method="post" action="{url controller='Vendedor' action='saveProduct'}" enctype="multipart/form-data">
      <div class="panel">
        <div class="columns">
          <div class="field-container">
            <input type="text" name="newArticle[product_name]" placeholder="{s name="newArticleName"}*Nombre del artículo{/s}" required class="address--field is--required">
          </div>
          <div class="field-container">
            <input type="text" name="newArticle[product_number]" placeholder="{s name="newArticleNumber"}*Número del artículo{/s}" required class="address--field is--required">
          </div>
          <div class="field-container">
            <textarea name="newArticle[product_description]" placeholder="{s name="newArticleDescription"}*Descripción del producto{/s}"></textarea>
          </div>
          <div class="field-container">
            <input type="number" step="0.01" min="0" name="newArticle[product_price]" placeholder="{s name="newArticlePrice"}*Precio del artículo{/s}" required class="address--field is--required">
          </div>
        </div>
        <div class="columns">
          <div class="field-container">
            <input type="number" min="0" name="newArticle[product_storage]" placeholder="{s name="newArticleStprage"}*Número de artículos disponibles{/s}" required class="address--field is--required">
          </div>
          <div class="field-container">
            <input type="file"class="custom-file-input" name="image" required class="address--field is--required">
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="columns">
          <div class="field-container">
            <label for='active'>Activar</label>
            <input type="radio" id="active" name="newArticle[product_active]" value="1" required>
            <label for='notactive'>Desactivar</label>
            <input type="radio" id="notactive" name="newArticle[product_active]" value="0" required>
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="columns">
          <div class="field-container">
            <h4>Producto puede ser ordenado aunque no haya en existencia:</h4>
            <label for='active'>Disponible</label>
            <input type="radio" id="active" name="newArticle[product_available]" value="1" required>
            <label for='notactive'>No disponible</label>
            <input type="radio" id="notactive" name="newArticle[product_available]" value="0" required>
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="columns">
          <div class="field-container">
            <input type="submit" value="Crear producto" class="button is--primary">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

{/block}
