{extends file="parent:frontend/index/main-navigation.tpl"}

{block name='frontend_index_navigation_categories_top_after'}
{$smarty.block.parent}
<li class="navigation--entry{if {controllerName|lower} == 'tiendas' && {controllerAction|lower} == 'index'} is--active{/if}" role="menuitem">
        <a class="navigation--link is--first{if {controllerName|lower} == 'tiendas' && {controllerAction|lower} == 'index'} is--active{/if}" href="/Tiendas" title='Ver todas las tiendas' aria-label="{s name='IndexLinkHome' namespace="frontend/index/categories_top"}{/s}" itemprop="url">
Tiendas
        </a>
</li>
{if !$userIsLogged}
<li class="navigation--entry{if {controllerName|lower} == 'vendedor' && {controllerAction|lower} == 'registro'} is--active{/if}" role="menuitem">
        <a class="navigation--link is--first{if {controllerName|lower} == 'vendedor' && {controllerAction|lower} == 'registro'} is--active{/if}" href="/Vendedor/registro" title='Registrarse para abrir una tienda' aria-label="{s name='IndexLinkHome' namespace="frontend/index/categories_top"}{/s}" itemprop="url">
Registro
        </a>
</li>
{/if}
{/block}
