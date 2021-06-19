{extends file="parent:frontend/account/sidebar.tpl"}
{block name="frontend_account_menu_container"}
{if $jIsSupplier}
{block name="frontend_account_menu_link_profile"}
<ul class="sidebar--navigation navigation--list is--level0 show--active-items">
  <li class="navigation--entry{if {controllerAction|lower} == 'productos'} is--active{/if}">
    <a href="/Vendedor/datos-contacto"
    title="{s name="AccountLinkProducts"}Productos{/s}"
    class="navigation--link{if {controllerName|lower} == 'vendedor' && {controllerAction} == 'datoscontacto'} is--active{/if}" rel="nofollow">
    {s name="AccountLinkInfo"}Datos de contacto{/s}
  </a>
  </li>
<li class="navigation--entry{if {controllerAction|lower} == 'productos'} is--active{/if}">
  <a href="{url module='frontend' controller='Vendedor' action=productos}"
  title="{s name="AccountLinkProducts"}Productos{/s}"
  class="navigation--link{if {controllerName|lower} == 'vendedor' && {controllerAction} == 'productos'} is--active{/if}" rel="nofollow">
  {s name="AccountLinkProducts"}Productos{/s}
</a>
</li>

<li class="navigation--entry{if {controllerAction|lower} == 'productos'} is--active{/if}">
  <a href="/Vendedor/nuevo-producto"
  title="{s name="AccountLinkNewProducts"}Nuevo Producto{/s}"
  class="navigation--link{if {controllerName|lower} == 'vendedor' && {controllerAction} == 'nuevoproducto'} is--active{/if}" rel="nofollow">
  {s name="AccountLinkNewProducts"}Nuevo Producto{/s}
</a>
</li>

<li class="navigation--entry{if {controllerAction|lower} == 'mi-tienda'} is--active{/if}">
  <a href="/Vendedor/mi-tienda"
  title="{s name="AccountLinkNewMyShop"}Mi Tienda{/s}"
  class="navigation--link{if {controllerName|lower} == 'vendedor' && {controllerAction} == 'mitienda'} is--active{/if}" rel="nofollow">
  {s name="AccountLinkNewMyShop" }Mi Tienda{/s}
</a>
</li>

<li class="navigation--entry{if {controllerAction|lower} == 'pedidos'} is--active{/if}">
  <a href="/Vendedor/pedidos"
  title="{s name="AccountLinkOrders"}Pedidos{/s}"
  class="navigation--link{if {controllerName|lower} == 'vendedor' && {controllerAction} == 'pedidos'} is--active{/if}" rel="nofollow">
  {s name="AccountLinkOrders" }Pedidos{/s}
</a>
</li>
{* Logout action *}
{block name="frontend_account_menu_link_logout"}

        {block name="frontend_account_menu_link_logout_standard"}
            <li class="navigation--entry">
                {block name="frontend_account_menu_link_logout_standard_link"}
                    <a href="{url module='frontend' controller='account' action='logout'}" title="{s name='AccountLinkLogout2'}{/s}" class="navigation--link link--logout" rel="nofollow">
                        {block name="frontend_account_menu_link_logout_standard_link_icon"}
                            <i class="icon--logout"></i>
                        {/block}

                        {block name="frontend_account_menu_link_logout_standard_link_text"}
                            <span class="navigation--logout logout--label">{s name="AccountLinkLogout2"}{/s}</span>
                        {/block}
                    </a>
                {/block}
            </li>
        {/block}
</ul>


{/block}
{/block}
{else}
{$smarty.block.parent}
{/if}
{/block}
