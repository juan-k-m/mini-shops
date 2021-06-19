{namespace name="frontend/vendedor/steps"}
{extends file='parent:frontend/register/steps.tpl'}

{* Step box *}

    <div class="steps--content panel--body center">
        {block name='frontend_register_steps'}
            <ul class="steps--list">

                {* First Step - Address *}
                {block name='frontend_register_steps_basket'}
                    <li class="steps--entry step--basket{if {controllerName|lower} == 'vendedor' && {controllerAction} == 'registro'} is--active{/if}">
                        <span class="icon">{s name="RegistroNumero1" force}1{/s}</span>
                        <span class="text"><span class="text--inner">{s name="RegistroTexto1" force}Resgístrate como vendedor{/s}</span></span>
                    </li>
                {/block}

                {* Spacer *}
                {block name='frontend_register_steps_spacer1'}
                    <li class="steps--entry steps--spacer">
                        <i class="icon--arrow-right"></i>
                    </li>
                {/block}

                {* Second Step - shop configuration *}
                {block name='frontend_register_steps_register'}
                    <li class="steps--entry step--register{if {controllerName|lower} == 'vendedor' && {controllerAction} == 'mitienda'} is--active{/if}">
                        <span class="icon">{s name="ConfiguraTienda"}2{/s}</span>
                        <span class="text"><span class="text--inner">{s name="ConfiguraTuTienda" force}Configura tu tienda{/s}</span></span>
                    </li>
                {/block}

                {* Spacer *}
                {block name='frontend_register_steps_spacer2'}
                    <li class="steps--entry steps--spacer">
                        <i class="icon--arrow-right"></i>
                    </li>
                {/block}

                {* Third Step - Confirmation *}
                {block name='frontend_register_steps_confirm'}
                    <li class="steps--entry step--confirm{if {controllerName|lower} == 'vendedor' && ({controllerAction} == 'nuevoproducto' || {controllerAction} == 'productos')} is--active{/if}">
                        <span class="icon">{s name="RegistroProductosNumero" force}3{/s}</span>
                        <span class="text"><span class="text--inner">{s name="AgregaProductos" force}Agrega productos a tu tienda y listo!{/s}</span></span>
                    </li>
                {/block}
            </ul>
        {/block}
    </div>
</div>
