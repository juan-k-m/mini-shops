{extends file="frontend/account/index.tpl"}

{* Breadcrumb *}
{block name="frontend_index_start"}
{$smarty.block.parent}
{s name="myShop" assign="snippetInfo"}Datos de contacto{/s}
{$sBreadcrumb[] = ["name" => $snippetInfo, "link" => {url action="datos-contacto"}]}
{/block}

{* Account Main Content *}
{block name="frontend_index_content"}
    <div class="content account--content">

        {* Welcome text *}
        {block name="frontend_account_orders_welcome"}
            <div class="account--welcome panel">
                {block name="frontend_account_orders_welcome_headline"}
                    <h1 class="panel--title">{s name="infoData" force}Información de contacto{/s}</h1>
                {/block}

                {block name="frontend_account_orders_welcome_content"}
                    <div class="panel--body is--wide">
                        <p>{s name=infoDataText force}Estos son los datos que aparecerán en la tienda para que tue clientes te puedan encontrar.{/s}</p>
                    </div>

                    <div class="forms--container panel has--border is--rounded">
                      <form class="panel register--form" method="post" action="{url controller='Vendedor' action='saveInfo'}">
                        <div class="panel">
                          <div class="columns">
                            <div class="field-container">
                              <h5>Correo electrónico</h5>
                              <input type="email" name="infoShop[email]" placeholder="{s name="editInfoEmail"}*Correo electrónico{/s}" required class="address--field is--required" value="{$shop.email}">
                            </div>
                          </div>
                        </div>
                        <div class="panel">
                          <div class="columns">
                            <div class="field-container">
                              <h5>Teléfono de contacto</h5>
                              <input type="tel" name="infoShop[phone]" placeholder="{s name="editInfoPhone"}*Teléfono{/s}" required class="address--field is--required" value="{$shop.phone}">
                            </div>
                          </div>
                        </div>
                        <div class="panel">
                          <div class="columns">
                            <div class="field-container">
                              {assign var="address" value=","|explode:$shop.address}
                              <h5>Dirección de la tienda o del contacto</h5>
                              <input type="text" name="infoShop[address-streetNr]" placeholder="{s name="editInfoAddress1"}*Calle y número{/s}" required class="address--field is--required" value="{$address[0]}">
                              <input type="text" name="infoShop[address-zipCode]" placeholder="{s name="editInfoAddress2"}*Código postal{/s}" required class="address--field is--required" value="{$address[1]}">
                              <input type="text" name="infoShop[address-city]" placeholder="{s name="editInfoAddress3"}*Ciudad{/s}" required class="address--field is--required" value="{$address[2]}">
                              <p>En el área de 'Mi tienda' puedes elegir que aparezca o no la dirección.</p>
                            </div>
                          </div>
                        </div>
                        <div class="panel">
                          <div class="columns">
                            <div class="field-container">
                              <input type="submit" class="btn is--primary" value="Guardar cambios">
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>

                {/block}
            </div>
        {/block}

  </div>
{/block}
