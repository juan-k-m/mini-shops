{extends file='frontend/register/index.tpl'}

{* Register Login *}
{block name='frontend_register_index_login'}
{/block}

{block name="frontend_account_menu_link_profile"}
{/block}

{* Maincategories navigation top *}
{block name='frontend_index_navigation_categories_top'}
    <nav class="navigation-main">
        <div class="container" data-menu-scroller="true" data-listSelector=".navigation--list.container" data-viewPortSelector=".navigation--list-wrapper">
            {block name="frontend_index_navigation_categories_top_include"}
                {include file='frontend/index/main-navigation.tpl'}
            {/block}
        </div>
    </nav>
    {$smarty.block.parent}
{/block}
{* Include the top bar navigation *}
{block name='frontend_index_top_bar_container'}
    {include file="frontend/index/topbar-navigation.tpl"}
{/block}

  {block name='frontend_register_index_back_to_shop_button'}
  {/block}

{* Register advantages *}
{block name='frontend_register_index_advantages'}
{/block}

{block name='frontend_register_index_form_personal_fieldset'}
{include file="frontend/vendedor/personal_fieldset.tpl" form_data=$register.personal error_flags=$errors.personal}
{/block}

{* Sidebar left *}
{block name='frontend_index_content_left'}
{/block}
{block name='frontend_register_index_registration'}
<div class="register--content panel content block has--border is--rounded{if $errors.occurred} is--collapsed{/if}" id="registration" data-register="true">

  {block name='frontend_register_index_dealer_register'}
  {* Included for compatibility reasons *}
  {/block}

  {block name='frontend_register_index_cgroup_header'}
  {if $register.personal.sValidation}
  {* Include information related to registration for other customergroups then guest, this block get overridden by b2b essentials plugin *}
  <div class="panel register--supplier">
    {block name='frontend_register_index_cgroup_header_title'}
    <h2 class="panel--title is--underline">{$sShopname|escapeHtml} {s name='RegisterHeadlineSupplier' namespace='frontend/register/index'}{/s}</h2>
    {/block}

    {block name='frontend_register_index_cgroup_header_body'}
    <div class="panel--body is--wide">
      <p class="is--bold">{s name='RegisterInfoSupplier3' namespace='frontend/register/index'}{/s}</p>

      <h3 class="is--bold">{s name='RegisterInfoSupplier4' namespace='frontend/register/index'}{/s}</h3>
      <p>{s name='RegisterInfoSupplier5' namespace='frontend/register/index'}{/s}</p>

      <h3 class="is--bold">{s name='RegisterInfoSupplier6' namespace='frontend/register/index'}{/s}</h3>
      <p>{s name='RegisterInfoSupplier7' namespace='frontend/register/index'}{/s}</p>
    </div>
    {/block}
  </div>
  {/if}
  {/block}

  {block name='frontend_register_index_form'}
  <form method="post" action="{url action=processForm controller=Vendedor}" class="panel register--form" id="register--form">

    {* Invalid hash while option verification process *}
    {block name='frontend_register_index_form_optin_invalid_hash'}
    {if $smarty.get.optinhashinvalid && ({config name=optinregister} || {config name=optinaccountless})}
    {s name="RegisterInfoInvalidHash" assign="snippetRegisterInfoInvalidHash"}{/s}
    {include file="frontend/_includes/messages.tpl" type="error" content=$snippetRegisterInfoInvalidHash}
    {/if}
    {/block}

    {block name='frontend_register_index_form_captcha_fieldset'}
    {include file="frontend/register/error_message.tpl" error_messages=$errors.captcha}
    {/block}

    {block name='frontend_register_index_form_personal_fieldset'}
    {include file="frontend/register/error_message.tpl" error_messages=$errors.personal}
    {include file="frontend/register/personal_fieldset.tpl" form_data=$register.personal error_flags=$errors.personal}
    {/block}

    {block name='frontend_register_index_form_billing_fieldset'}
    {include file="frontend/register/error_message.tpl" error_messages=$errors.billing}
    {include file="frontend/vendedor/billing_fieldset.tpl" form_data=$register.billing error_flags=$errors.billing country_list=$countryList}
    {/block}

    {block name='frontend_register_index_form_shipping_fieldset'}
    {/block}

    {* @deprecated Block will be excluded in 5.7 *}
    {* It has been replaced by "frontend_register_index_form_privacy" below *}
    {if !$update}
    {block name='frontend_register_index_input_privacy'}{/block}
    {/if}

    {block name='frontend_register_index_form_required'}
    {* Required fields hint *}
    <div class="register--required-info required_fields">
      {s name='RegisterPersonalRequiredText' namespace='frontend/register/personal_fieldset'}{/s}
    </div>
    {/block}

    {* Captcha *}
    {block name='frontend_register_index_form_captcha'}
    {$captchaName = {config name=registerCaptcha}}
    {$captchaHasError = $errors.captcha}
    {include file="widgets/captcha/custom_captcha.tpl" captchaName=$captchaName captchaHasError=$captchaHasError}
    {/block}

    {* Data protection information *}
    {if !$update}
    {block name="frontend_register_index_form_privacy"}
    {if {config name=ACTDPRTEXT} || {config name=ACTDPRCHECK}}
    {block name="frontend_register_index_form_privacy_title"}
    <h2 class="panel--title is--underline">
      {s name="PrivacyTitle" namespace="frontend/index/privacy"}{/s}
    </h2>
    {/block}
    <div class="panel--body is--wide">
      {block name="frontend_register_index_form_privacy_content"}
      <div class="register--password-description">
        {if {config name=ACTDPRCHECK}}
        {* Privacy checkbox *}
        {block name="frontend_register_index_form_privacy_content_checkbox"}
        <input name="register[personal][dpacheckbox]" type="checkbox" id="dpacheckbox"{if $form_data.dpacheckbox} checked="checked"{/if} required="required" aria-required="true" value="1" class="is--required" />
        <label for="dpacheckbox">
          {s name="PrivacyText" namespace="frontend/index/privacy"}{/s}
        </label>
        {/block}
        {else}
        {block name="frontend_register_index_form_privacy_content_text"}
        {s name="PrivacyText" namespace="frontend/index/privacy"}{/s}
        {/block}
        {/if}
      </div>
      {/block}
    </div>
    {/if}
    {/block}
    {/if}

    {block name='frontend_register_index_form_submit'}
    {* Submit button *}
    <div class="register--action">
      <button type="submit" class="register--submit btn is--primary is--large is--icon-right" name="Submit" data-preloader-button="true">{s name="RegisterIndexNewActionSubmit"}{/s} <i class="icon--arrow-right"></i></button>
    </div>
    {/block}
  </form>
  {/block}
</div>
<aside>
  <div class="sidebox">
    <h5>Tienda online gratuita en mercadovirtual.com</h5>
    <p>Al registrarse en mercadovirtual.com
      <b>usted acepta las siguientes condiciones de uso del sitio:</b><br>
      No pondrá textos descriptivos perjudiciales. Usted acepta
      las <a href="/reglas-de-procesamiento">reglas de procesamiento</a> y las <a href="/condiciones-de-uso">condiciones de uso</a> de este portal.
    </p>
    <div class="collapse-box"> <div class="collapse-title">
      <a class="btn is--primary" href="javascript:void(0)">Mehr Informationen <i class="icon--plus3 is--bold"></i></a>
    </div>
    <div class="collapse-content" style="display: none;">
      <h5 class="is--bold">
        Regístrate y abre tu tienda en línea gratuita ahora.</h5>
          <p>También revisamos que todos los datos de los
            vendedores sean de procedendia legítima.
            Si detectamos cuentas falsas o estafadores,
            lo reportaremos inmediatamente y bloquearemos
            la cuenta igualmente. mercadovirtual.com quiere ayudarle lo más rápido posible,
            por lo que usted puede comenzar enseguida
            después de su registro a administrar su tienda.
            <br><br>
            <h5>Manténgase justo.</h5><br>
            Calcule precios justos y ofrezca artículos que pueda entregar a los
            clientes en un tiempo razonable.<br>
          </p>
            <h5>Documentos de ayuda para descargar</h5>
          <p> Usando los dos botones debajo de este texto
            le ofrecemos dos documentos más para descargar,
            que puede usar para colocarlos
            visiblemente en su tienda así como distribuirlos.
            Contienen un código de escaneo para sus clientes puedan
            sepan que sus productos pueden ser encontrados en el portal mercadovirtual.com
            ser encontrados en mercadovirtual.com.
          </p>
          <a class="btn is--primary is--large" href="/media/pdf/d4/cc/30/Aushang-kiezware-de.pdf" target="_blank">Letrero A4 vertical</a><br><br>
          <a href="/media/pdf/1e/d1/36/Aushang-kiezware-de-quer.pdf" target="_blank" class="btn is--primary is--large">Letrero A4 horizontal</a><br><p></p>
        </div>
      </div>
    </div>



  </aside>
  {/block}
