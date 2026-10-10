<div class="d-flex justify-content-center">
    <div class="rounded-4 border shadow-lg p-4 w-100">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="d-flex fw-40 fh-40 flex-shrink-0 align-items-center rounded-circle justify-content-center bg-primary-subtle text-primary-emphasis">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h1 class="h3 mb-0">{$LANG->getModule('register')}</h1>
        </div>
        <hr class="mb-4">
        {include file='register_form.tpl'}
        {if not empty($NAVS)}
        <div class="d-flex justify-content-center mt-4">
            <ul class="list-inline mb-0">
                {foreach from=$NAVS item=nav}
                <li class="list-inline-item text-nowrap">
                    <a href="{$nav.href}"><i class="fa-solid fa-caret-right"></i> {$nav.title}</a>
                </li>
                {/foreach}
            </ul>
        </div>
        {/if}
    </div>
</div>
{if $DATEPICKER}
<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
{/if}
