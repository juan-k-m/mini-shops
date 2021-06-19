{namespace name="frontend/vendedor/order_item"}
<div id="order{$pedido.numberRanges}" class="order--details panel--table">

  {block name="frontend_account_order_item_detail_table"}

  {block name="frontend_account_order_item_detail_id"}
  <input type="hidden" name="sAddAccessories" value="{$ordernumber|escape}" />
  {/block}

  {block name="frontend_account_order_item_detail_table_head"}
  <div class="orders--table-header panel--tr is--secondary">

    {block name="frontend_account_order_item_detail_table_head_name"}
    <div class="panel--th column--name">{s name="OrderItemColumnName" force}Producto{/s}</div>
    {/block}

    {block name="frontend_account_order_item_detail_table_head_quantity"}
    <div class="panel--th column--quantity is--align-center">{s name="OrderItemColumnQuantity"}{/s}</div>
    {/block}

    {block name="frontend_account_order_item_detail_table_head_price"}
    <div class="panel--th column--price is--align-right">{s name="OrderItemColumnPrice"}{/s}</div>
    {/block}

    {block name="frontend_account_order_item_detail_table_head_total"}
    <div class="panel--th column--total is--align-right">{s name="OrderItemColumnTotal" force}Total{/s}</div>
    {/block}
  </div>
  {/block}

  {block name="frontend_account_order_item_detail_table_rows"}

  {foreach $pedido.articles as $article}

  {block name="frontend_account_order_item_detail_table_row"}
  <div class="panel--tr">

    {block name="frontend_account_order_item_info"}
    <div class="panel--td order--info column--name">

      {* Name *}
      {block name="frontend_account_order_item_name"}
      <p class="order--name is--strong">
        {$article.name}
      </p>
      {/block}

      {* Unit price *}
      {block name='frontend_account_order_item_unitprice'}
      {if $article.price}
      <div class="order--price-unit">
        {block name='frontend_account_order_item_purchaseunit'}
        <p>{s name="OrderItemInfoContent" force}Precio individual comprado{/s}:   {$article.price|currency}  {$article.sUnit.description}</p>
        {/block}

        {block name="frontend_account_order_item_referenceunit"}
        {if $article.purchaseunit != $article.referenceunit}
        <p>
          {if $article.referenceunit}
          <span class="order--base-price">{s name="OrderItemInfoBaseprice"}{/s}:</span>
          {$article.referenceunit} {$article.sUnit.description} = {$article.referenceprice|currency}
          {s name="Star" namespace="frontend/listing/box_article"}{/s}
          {/if}
        </p>
        {/if}
        {/block}
      </div>
      {/if}
      {/block}

      {* Current price *}
      {block name='frontend_account_order_item_currentprice'}
      {if $article.currentPrice}
      <div class="order--current-price">
        {block name="frontend_account_order_item_currentprice_label"}
        <span>{s name="OrderItemInfoCurrentPrice" force}Precio por unidad{/s}:</span>
        {/block}

        {block name="frontend_account_order_item_currentprice_value"}
        <span>
          {$article.currentPrice|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
        </span>
        {/block}

        {block name="frontend_account_order_item_pseudo_price"}
        {if $article.currentHas_pseudoprice}
        <span class="price--pseudo">
          {block name="frontend_account_order_item_pseudo_price_before"}
          {s name="priceDiscountLabel" namespace="frontend/detail/data"}{/s}
          {/block}

          {block name="frontend_account_order_item_pseudo_price_inner"}
          <span class="order--pseudo-price is--italic is--soft is--line-through">
            {block name="frontend_account_order_item_pseudo_price_inner_value"}
            {$article.currentPseudoprice|currency}
            {/block}
            {s name="Star" namespace="frontend/listing/box_article"}{/s}
          </span>
          {/block}

          {block name="frontend_account_order_item_pseudo_price_after"}
          {s name="priceDiscountInfo" namespace="frontend/detail/data"}{/s}
          {/block}
        </span>
        {/if}
        {/block}
      </div>
      {/if}
      {/block}

      {* image *}
      <div class="article-image">
        <img src="{$article.image}">
      </div>

      {* If ESD-Article *}
      {block name='frontend_account_order_item_downloadlink'}
      {if $article.esdarticle && $offerPosition.cleared|in_array:$sDownloadAvailablePaymentStatus}
      <div class="order--download is--strong">
        <a href="{$article.esdLink}" class="btn is--small">
          {s name="OrderItemInfoInstantDownload"}{/s}
        </a>
      </div>
      {/if}
      {/block}
    </div>
    {/block}

    {* Order item quantity *}
    {block name='frontend_account_order_item_quantity'}
    <div class="panel--td order--quantity column--quantity">

      {block name='frontend_account_order_item_quantity_label'}
      <div class="column--label">{s name="OrderItemColumnQuantity" force}Número{/s}</div>
      {/block}

      {block name='frontend_account_order_item_quantity_value'}
      <div class="column--value">{$article.quantity}</div>
      {/block}
    </div>
    {/block}

    {* Order item price *}
    {block name='frontend_account_order_item_price'}
    <div class="panel--td order--price column--price">

      {block name='frontend_account_order_item_price_label'}
      <div class="column--label">{s name="OrderItemColumnPrice" force}Precio por unidad{/s}</div>
      {/block}

      {block name='frontend_account_order_item_price_value'}
      <div class="column--value">
        {if $article.price}
        {$article.price|currency}
        {/if}
      </div>
      {/block}
    </div>
    {/block}

    {* Order item total amount *}
    {block name='frontend_account_order_item_amount'}
    <div class="panel--td order--amount column--total">

      {block name='frontend_account_order_item_amount_label'}
      <div class="column--label">{s name="OrderItemColumnTotal"}{/s}</div>
      {/block}

      {block name='frontend_account_order_item_amount_value'}
      <div class="column--value">
        {if $article.price}
        {assign var='articlesTotal' value=$article.price * $article.quantity}
  {$articlesTotal|currency}
        {/if}
      </div>
      {/block}
    </div>
    {/block}
  </div>
  {/block}
  {/foreach}
  {/block}

  <div class="panel--tr is--odd">
    {block name="frontend_account_order_item_detail_info_wrapper"}
    <div class="column--info-wrapper">
      {block name="frontend_account_order_item_detail_info_labels"}
      <div class="panel--td column--info-labels">
        {* Order date label *}
        {block name="frontend_account_order_item_label_date"}
        <p class="is--strong">{s name="OrderItemColumnDate" force}Datos del cliente:{/s}</p>

        {/block}

        {* Order number label *}
        {block name="frontend_account_order_item_label_ordernumber"}
        <p class="is--strong">{s name="OrderItemColumnId" force}{/s}</p>
        {/block}

        {* Shipping method label  *}
        {block name="frontend_account_order_item_label_dispatch"}
        {if $offerPosition.dispatch}
        <p class="is--strong">{s name="OrderItemColumnDispatch"}{/s}</p>
        {/if}
        {/block}

        {* Package tracking code label *}
        {block name="frontend_account_order_item_label_trackingcode"}
        {if $offerPosition.trackingcode}
        <p class="is--strong">{s name="OrderItemColumnTracking"}{/s}</p>
        {/if}
        {/block}
      </div>
      {/block}

      {block name="frontend_account_order_item_detail_info_data"}
      <div class="panel--td column--info-data">
        {* Order date *}
        {block name='frontend_account_order_item_date'}
        <p>{$offerPosition.datum|date}</p>
        {/block}

        {* Order number *}
        {block name='frontend_account_order_item_ordernumber'}
        <p>Nombre: {$pedido.user.salutation}. {$pedido.user.firstname} {$pedido.user.lastname}</p>
        <p>Teléfono: {$pedido.address.phone}</p>
        <p>Correo electrónico: {$pedido.user.email}</p>
        {/block}

        {* Shipping method *}
        {block name='frontend_account_order_item_dispatch'}
        {if $offerPosition.dispatch}
        <p>{$offerPosition.dispatch.name}</p>
        {/if}
        {/block}

        {* Package tracking code *}
        {block name='frontend_account_order_item_trackingcode'}
        {if $offerPosition.trackingcode}
        <p>
          {if $offerPosition.dispatch.status_link}
          <a href="{$offerPosition.dispatch.status_link}"
          title="{$offerPosition.dispatch.name}"
          onclick="return !window.open(this.href, 'popup', 'width=500,height=600,left=20,top=20');"
          target="_blank">{$offerPosition.trackingcode}</a>
          {else}
          {$offerPosition.trackingcode}
          {/if}
        </p>
        {/if}
        {/block}
      </div>
      {/block}
    </div>
    {/block}

    {block name="frontend_account_order_item_detail_summary_labels"}
    <div class="panel--td column--summary-labels">

      {* Shipping costs label *}
      {block name="frontend_account_order_item_detail_shipping_costs_label"}
      <p class="is--strong">{s name="OrderItemShippingcosts"}{/s}</p>
      {/block}

      {block name="frontend_account_order_item_detail_shipping_costs"}

      <p class="is--strong">{s name="OrderItemTotal" force}Total{/s}</p>

      {/block}
    </div>
    {/block}

    {block name="frontend_account_order_item_detail_summary_data"}
    <div class="panel--td column--summary-data">

      {* Shopping costs *}
      {block name="frontend_account_order_item_shippingamount"}
      <p class="is--strong">
        {if $offerPosition.currency_position == "32"}
        {$offerPosition.currency_html} {$offerPosition.invoice_shipping}
        {else}
        {$offerPosition.invoice_shipping} {$offerPosition.currency_html}
        {/if}
      </p>
      {/block}

      {block name="frontend_acccount_order_item_amount"}
      <p class="is--bold">
        {$pedido.amount|currency}
      </p>
      {/block}
    </div>
    {/block}
  </div>

  {* User comment *}
  {block name="frontend_account_order_item_user_comment"}
  {if $offerPosition.customercomment}
  <div class="order--user-comments panel">
    {block name="frontend_account_order_item_user_comment_title"}
    <div class="panel--title">{s name="OrderItemCustomerComment"}{/s}</div>
    {/block}

    {block name="frontend_account_order_item_user_comment_content"}
    <div class="panel--body is--wide">
      <blockquote>{$offerPosition.customercomment}</blockquote>
    </div>
    {/block}
  </div>
  {/if}
  {/block}

  {* Shop comment *}
  {block name="frontend_account_order_item_shop_comment"}
  {if $offerPosition.comment}
  <div class="order--shop-comments panel">
    {block name="frontend_account_order_item_shop_comment_title"}
    <div class="panel--title">{s name="OrderItemComment"}{/s}</div>
    {/block}

    {block name="frontend_account_order_item_shop_comment_content"}
    <div class="panel--body is--wide">
      <blockquote>{$offerPosition.comment}</blockquote>
    </div>
    {/block}
  </div>
  {/if}
  {/block}

  {* Repeat order *}
  {block name="frontend_account_order_item_repeat_order"}
  {if $offerPosition.activeBuyButton}
  <div class="order--repeat panel--tr">
    <form method="post" action="{url controller='checkout' action='add_accessories'}">
      {foreach $offerPosition.details as $article}{if $article.modus == 0}
      <input name="sAddAccessories[]" type="hidden" value="{$article.articleordernumber|escape}" />
      <input name="sAddAccessoriesQuantity[]" type="hidden" value="{$article.quantity|escape}" />
      {/if}{/foreach}

      {* Repeat order button *}
      {block name="frontend_account_order_item_repeat_button"}
      <input type="submit" class="btn is--primary is--small" value="{s name='OrderLinkRepeat'}{/s}" />
      {/block}
    </form>
  </div>
  {/if}
  {/block}

  {/block}
</div>
