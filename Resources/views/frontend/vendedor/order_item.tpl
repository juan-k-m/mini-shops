{block name="frontend_account_order_item_overview_row"}
    <div class="order--item panel--tr">

        {* Order date *}
        {block name="frontend_account_order_item_date"}
            <div class="order--date panel--td column--date">

                {block name="frontend_account_order_item_date_label"}
                    <div class="column--label">
                        {s name="OrderColumnDate" namespace="frontend/account/orders"}{/s}:
                    </div>
                {/block}

                {block name="frontend_account_order_item_date_value"}
                    <div class="column--value">
                        {$pedido.orderday|date_format:"%d/%m/%Y"} {$pedido.ordertime}

                    </div>
                {/block}
            </div>
        {/block}

        {* Order id *}
        {block name="frontend_account_order_item_number"}
            <div class="order--number panel--td column--id is--bold">

                {block name="frontend_account_order_item_number_label"}
                    <div class="column--label">
                        {s name="OrderColumnId" namespace="frontend/account/orders"}{/s}:
                    </div>
                {/block}

                {block name="frontend_account_order_item_number_value"}
                    <div class="column--value">
                        {$pedido.numberRanges}
                    </div>
                {/block}
            </div>
        {/block}

        {* Dispatch type *}
        {block name="frontend_account_order_item_dispatch"}
            <div class="order--dispatch panel--td column--dispatch">

                {block name="frontend_account_order_item_dispatch_label"}
                    <div class="column--label">
                        {s name="OrderColumnDispatch" namespace="frontend/vendedor/orders" force}Tipo de envío{/s}:
                    </div>
                {/block}

                {block name="frontend_account_order_item_dispatch_value"}
                    <div class="column--value">
                        {if $pedido.dispatch.name}
                            {$pedido.dispatch.name}
                        {else}
                            {s name="OrderInfoNoDispatch"}{/s}
                        {/if}
                    </div>
                {/block}
            </div>
        {/block}

        {* Order status *}
        {block name="frontend_account_order_item_status"}
            <div class="order--status panel--td column--status">

                {block name="frontend_account_order_item_status_label"}
                    <div class="column--label">
                        {s name="OrderColumnDocumentLink" namespace="frontend/vendedor/orders"}{/s}:
                    </div>
                {/block}

                {block name="frontend_account_order_item_status_value"}
                    <div class="column--value">
              <a href="/Vendedor/documento?hash={$pedido.documentHash}" target="_blank">Ver documento</a>
                    </div>
                {/block}
            </div>
        {/block}

        {* Order actions *}
        {block name="frontend_account_order_item_actions"}
            <div class="order--actions panel--td column--actions">
                {s name="OrderActionSlide" assign="snippetOrderActionSlide" force}Mostrar{/s}
                <a href="#order{$pedido.numberRanges}"
                   title="{$snippetOrderActionSlide|escape} {$pedido.numberRanges}"
                   class="btn is--small"
                   data-collapse-panel="true"
                   data-collapseTarget="#order{$pedido.numberRanges}">
                    {s name="OrderActionSlide"}{/s}
                </a>
            </div>
        {/block}
    </div>
{/block}

{* Order details *}
{block name="frontend_account_order_item_detail"}
    {include file="frontend/vendedor/order_item_details.tpl"}
{/block}
