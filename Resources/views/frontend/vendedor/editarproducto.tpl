{extends file="frontend/account/index.tpl"}

{* Breadcrumb *}
{block name="frontend_index_start"}
{$smarty.block.parent}
{s name="editarProducto" assign="editarProducto" force}Editar producto{/s}
{$sBreadcrumb[] = ["name" => $editarProducto]}
{/block}

{* Account Main Content *}
{block name="frontend_index_content"}

<div class="content">
  <div>
    <h1 class="page--title">{s name="editProduct"}Editar Producto{/s}</h1>
  </div>
  <div class="forms--container panel is--rounded">
    <form class="panel register--form" method="post" action="{url controller='Vendedor' action='updateProduct'}" enctype="multipart/form-data">
      <div class="panel-border">
        <div class="columns">
          <div class="field-container">
            <h5 class="input-title">Nombre del producto.</h5>
            <input type="text" name="editArticle[product_name]" placeholder="{s name="editArticleName"}*Nombre del artículo{/s}" required class="address--field is--required" value="{$article.name}">
          </div>
          <div class="field-container">
            <h5 class="input-title">Número de producto.</h5>
            <input type="text" name="editArticle[product_number]" placeholder="{s name="editArticleNumber"}*Número del artículo{/s}" required class="address--field is--required" value="{$article.mainDetail.number}">
            <input type="hidden" name="editArticle[product_id]" value="{$article.id}">
          </div>
          <div class="field-container">
            <h5 class="input-title">Descripción del producto.</h5>
            <textarea name="editArticle[product_description]" placeholder="{s name="editArticleDescription"}*Descripción del producto{/s}" value="{$article.description}">{$article.description}</textarea>
          </div>
        </div>
        <div class="columns">
          <div class="field-container">
                <h5 class="input-title">Precio del producto.</h5>
            <input type="number" step="0.01" min="0" name="editArticle[product_price]" placeholder="{s name="editArticlePrice"}*Precio del artículo{/s}" required class="address--field is--required" value="{$article.mainPrices[0].price|string_format:"%.2f"}">
          </div>
          <div class="field-container">
                <h5 class="input-title">Productos que se tiene en existencia.</h5>
            <input type="number" name="editArticle[product_storage]" placeholder="{s name="editArticleStprage"}*Número de artículos disponibles{/s}" required class="address--field is--required" value="{$article.mainDetail.inStock}">
          </div>
        </div>
      </div>
      <div class="panel-border">
        <div class="field-container">
          <div class="title">
            <h5>{s name="editProductTitelImage" force}Imagenes del producto{/s}</h5>
          </div>
          <div class="image--wrapper-edit">
            {foreach $article.images as $img}
            <div class="image--wrapper">
              <div class="div-image"style="background: url({$img.thumbnail}) no-repeat center;">
              </div>
              <div>
                <p>Seleccione imagen para eliminar:</p>
                <input type="checkbox" name="deleteImage[]" value="{$img.id}">
              </div>
            </div>
            {/foreach}
          </div>

        </div>
      </div>
      <div class="panel-border">
        <div class="columns">
          <div class="field-container">
            <div class="image-change-div">
              <h5>Agregar imágenes</h5>
              <input type="file" class="custom-file-input" name="image[]" class="address--field" multiple>
            </div>
          </div>
        </div>
      </div>


      <div class="panel-border">
        <div class="columns">
          <div class="field-container">
            <h5>Mostrar producto en la tienda:</h5>
            <label for='active'>Activar</label>
            <input type="radio" id="active" name="editArticle[product_active]" value="1" {if $article.active eq true}checked{/if} required>
            <label for='notactive'>Desactivar</label>
            <input type="radio" id="notactive" name="editArticle[product_active]" value="0" {if $article.active eq false}checked{/if} required>
          </div>
        </div>
      </div>
      <div class="panel-border">
        <div class="columns">
          <div class="field-container">
            <h5>Producto puede ser ordenado aunque no haya en existencia:</h5>
            <label for='available'>Disponible</label>
            <input type="radio" id="available" name="editArticle[product_available]" value="0" {if $article.lastStock eq false}checked{/if} required>
            <label for='notavailable'>No disponible</label>
            <input type="radio" id="notavailable" name="editArticle[product_available]" value="1" {if $article.lastStock eq true}checked{/if} required>
          </div>
        </div>
      </div>
      <div class="panel-border">
        <div class="columns">
          <div class="field-container">
            <input type="hidden" name="editArticle[product_hidden]" value="{$article.hidden}">
            <input type="submit" value="Actualizar producto" class="button is--primary">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

{/block}
