<div class="d-flex justify-content-center">
    <div class="rounded-4 border shadow-lg p-4">
        {if not empty($NV_HEADER)}
        {* Hiển thị logo tại login box *}
        <div class="text-center mb-3">
            <a title="{$GCONFIG.site_name}" href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}">
                <img class="img-fluid" src="{$smarty.const.NV_BASE_SITEURL}{$GCONFIG.site_logo}" alt="{$GCONFIG.site_name}">
            </a>
        </div>
        {else}
        {* Icon đăng nhập *}
        <div class="mb-2 d-flex justify-content-center">
            <div class="d-flex fw-40 fh-40 align-items-center rounded-circle justify-content-center bg-primary-subtle text-primary-emphasis">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </div>
        </div>
        {/if}
        <h1 class="h2 text-center mb-3">{$LANG->getModule('login')}</h1>
        {include file='login_form.tpl'}
        {if not empty($NAVS)}
        <hr class="mt-4">
        <div class="d-flex justify-content-center">
            <ul class="list-inline mb-0" data-area="other-form">
                {foreach from=$NAVS item=nav}
                <li class="list-inline-item">
                    <a href="{$nav.href}">
                        <i class="fa-solid fa-caret-right"></i>&nbsp;{$nav.title}
                    </a>
                </li>
                {/foreach}
            </ul>
        </div>
        {/if}
    </div>
</div>
