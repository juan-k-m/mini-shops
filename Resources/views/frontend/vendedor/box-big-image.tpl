{extends file="frontend/vendedor/box-basic.tpl"}

{block name='frontend_listing_box_article_description'}
{/block}


{* Product box badges - highlight, newcomer, ESD product and discount *}
{block name='frontend_listing_box_article_badges'}
{/block}

{* Product actions - Compare product, more information *}
{block name='frontend_listing_box_article_actions'}
{/block}

{* Customer rating for the product *}
{block name='frontend_listing_box_article_rating'}
{/block}

{block name='frontend_listing_box_article_picture'}
<!-- {foreach $sArticle.images as $image}
    {block name='frontend_detail_images_image_slider_item'}
        <div class="image--box image-slider--item">

            {block name='frontend_detail_images_image_element'}

                {$alt = $sArticle.articleName|escape}

                {if $image.description}
                    {$alt = $image.description|escape}
                {/if}

                {$imageMediaClasses = 'image--media'}

                {if $image.extension == 'svg'}
                    {$imageMediaClasses = $imageMediaClasses|cat:' image--svg'}
                {/if}

                <span class="image--element"
                      data-img-large="{$image.thumbnails[2].source}"
                      data-img-small="{$image.thumbnails[0].source}"
                      data-img-original="{$image.source}"
                      data-alt="{$alt}">

                    {block name='frontend_detail_images_image_media'}
                        <span class="{$imageMediaClasses}">
                            {if isset($image.thumbnails)}
                                {block name='frontend_detail_images_picture_element'}
                                    <img srcset="{$image.thumbnails[1].sourceSet}" alt="{$alt}" itemprop="image" />
                                {/block}
                            {else}
                                {block name='frontend_detail_images_fallback'}
                                    <img src="{link file='frontend/_public/src/img/no-picture.jpg'}" alt="{$alt}" itemprop="image" />
                                {/block}
                            {/if}
                        </span>
                    {/block}
                </span>
            {/block}
        </div>
    {/block}
{/foreach} -->
<div class="product--wrapper-edit">
  <p>{$sArticle.images.media.thumbnail}</p>
    <p
       title="{$sArticle.name|escape}"
       class="product--image">
        {block name='frontend_listing_box_article_image_element'}
            <span class="image--element">
            {block name='frontend_listing_box_article_image_media'}
                <span class="image--media">

                    {$desc = $sArticle.articleName|escape}

                    {if isset($sArticle.images[0].thumbnail)}

                        {if $sArticle.image.description}
                            {$desc = $sArticle.image.description|escape}
                        {/if}

                        {block name='frontend_listing_box_article_image_picture_element'}
                            <img srcset="{$sArticle.images[0].thumbnail}"
                                 alt="{$desc}"
                                 title="{$desc|truncate:160}" />
                        {/block}
                    {else}
                        <img src="{link file='frontend/_public/src/img/no-picture.jpg'}"
                             alt="{$desc}"
                             title="{$desc|truncate:160}" />
                    {/if}
                </span>
            {/block}
        </span>
        {/block}
    </p>
    <div class="buttons--action-edit-delete">

    <a class="btn is--primary" href="/Vendedor/editar-producto?id={$sArticle.id}">Editar producto</a>
    <a class="btn is--secondary" href="/Vendedor/borrar-producto?id={$sArticle.id}">Borrar producto</a>
    <a href="{$sArticle.linkDetails}"
       class="product--title"
       title="{$sArticle.name|escapeHtml}" target="_blank">
        Ver producto en la tienda
    </a>

    </div>


  </div>
  <div class="product-name">
    <p
       class="product--title"
       title="{$sArticle.articleName|escapeHtml}">
        {$sArticle.name|truncate:50|escapeHtml} <i class="icon--arrow-down"></i>
    </p>
  </div>
  <div class="info--hidden">
    {if $sArticle.active eq true}
    {assign var="msgActive" value="Producto visible"}
    {else}
    {assign var="msgActive" value="Producto no visible"}
    {/if}
    {if $sArticle.lastStock eq false}
    {assign var="msgAvailable" value="Producto se puede agregar al pedido si no hay en existencia"}
    {else}
    {assign var="msgAvailable" value="Producto no se puede agregar al pedido si no hay en existencia."}
    {/if}
    <p>Productos disponibles: <span class="in--stock {if $sArticle.mainDetail.inStock <= 0 }red--color{/if}">{$sArticle.mainDetail.inStock}</span></p>
    <p>Productos Visible en la tienda: <span class="product-active {if $sArticle.active eq false}red--color{else}green--color{/if}">{$msgActive}</span></p>
    <p>Productos Disponible en la tienda: <span class="product-available {if $sArticle.lastStock eq true}red--color{else}green--color{/if}">{$msgAvailable}</span></p>
    <p>N.º artículo: {$sArticle.mainDetail.number}</p>
    <div class="product--description">
        {$sArticle.description|strip_tags|truncate:240}
    </div>
    <span class="price--default">
        {$sArticle.mainPrices[0].price|currency}
    </span>
  </div>
{/block}
