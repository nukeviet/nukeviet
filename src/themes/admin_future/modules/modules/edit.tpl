<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;mod={$DATA.title}" novalidate>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-9">
                    {$LANG->getGlobal('required')}
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_mod_name" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('module_name')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control-plaintext" id="element_mod_name" name="mod_name" value="{$DATA.title}" maxlength="55" readonly="readonly">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_custom_title" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('custom_title')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_custom_title" name="custom_title" value="{$DATA.custom_title}" maxlength="100">
                    <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_admin_title" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('admin_title')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_admin_title" name="admin_title" value="{$DATA.admin_title}" maxlength="100">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_theme" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('theme')}</label>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_theme" name="theme">
                        <option value="">{$LANG->getModule('theme_default')}</option>
                        {foreach from=$THEME_LIST item=theme}
                        <option value="{$theme}"{if $theme eq $DATA.theme} selected{/if}>{$theme}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_mobile" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('mobile')}</label>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_mobile" name="mobile">
                        {foreach from=$DTHEME_MOBILE item=theme}
                        <option value="{$theme.key}"{if $theme.key eq $DATA.mobile} selected{/if}>{$theme.title}</option>
                        {/foreach}
                        {foreach from=$THEME_MOBILE item=theme}
                        <option value="{$theme}"{if $theme eq $DATA.mobile} selected{/if}>{$theme}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_site_title" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('site_title')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_site_title" name="site_title" value="{$DATA.site_title}" maxlength="255">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_description" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('description')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_description" name="description" value="{$DATA.description}" maxlength="255">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_keywords" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('keywords')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_keywords" name="keywords" value="{$DATA.keywords}" maxlength="255" aria-describedby="element_keywords_helper">
                    <div id="element_keywords_helper" class="form-text">{$LANG->getModule('keywords_info')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="act" value="1"{if $DATA.act} checked{/if} role="switch" id="element_act">
                        <label class="form-check-label" for="element_act">{$LANG->getGlobal('activate')}</label>
                    </div>
                </div>
            </div>
            {if $FEATURE_RSS}
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="rss" value="1"{if $DATA.rss} checked{/if} role="switch" id="element_rss">
                        <label class="form-check-label" for="element_rss">{$LANG->getModule('activate_rss')}</label>
                    </div>
                </div>
            </div>
            {/if}
            {if $FEATURE_SITEMAP}
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="sitemap" value="1"{if $DATA.sitemap} checked{/if} role="switch" id="element_sitemap">
                        <label class="form-check-label" for="element_sitemap">{$LANG->getModule('activate_sitemap')}</label>
                    </div>
                </div>
            </div>
            {/if}
            {if $DATA.title neq $GCONFIG.site_home_module}
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end">{$LANG->getGlobal('groups_view')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    {foreach from=$GROUPS_LIST key=group_id item=group_name}
                    {if $group_id gt 2}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="groups_view[]" value="{$group_id}" id="element_groups_view_{$group_id}"{if in_array($group_id, $DATA.groups_view)} checked{/if}>
                        <label class="form-check-label" for="element_groups_view_{$group_id}">
                            {$group_name}
                        </label>
                    </div>
                    {/if}
                    {/foreach}
                    <div class="form-text">{$LANG->getModule('module_groups_view_note')}</div>
                </div>
            </div>
            {/if}
            <div class="row mb-3">
                <label for="element_icon" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('icon')}</label>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <select class="form-select select2-fontawesome" id="element_icon" name="icon" data-placeholder="{$LANG->getModule('icon_placeholder')}">
                        {if not empty($DATA.icon)}
                        <option value="{$DATA.icon}">{$ICON_PACKS[$DATA.icon] ?? $DATA.icon}</option>
                        {/if}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input name="module_theme" type="hidden" value="{$DATA.module_theme}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
