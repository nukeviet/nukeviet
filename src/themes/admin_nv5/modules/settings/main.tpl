{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <input type="hidden" name="submit" value="1">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-body">
            {if sizeof($GLOBAL_CONFIG['my_domains']) gt 1}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="ftp_user_name">{$LANG->get('site_domain')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="ftp_user_name" name="ftp_user_name">
                        {foreach from=$GLOBAL_CONFIG['my_domains'] item=row}
                        <option value="{$row}"{if $row eq $GLOBAL_CONFIG['site_domain']} selected="selected"{/if}>{$row}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_name">{$LANG->get('sitename')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="site_name" name="site_name" value="{$DATA['sitename']}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_description">{$LANG->get('description')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="site_description" name="site_description" value="{$DATA['description']}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_keywords">{$LANG->get('site_keywords')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="site_keywords" name="site_keywords" value="{$DATA['site_keywords']}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_logo">{$LANG->get('site_logo')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="site_logo" name="site_logo" value="{$DATA['site_logo']}">
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">
                            <button class="btn btn-secondary btn-input-sm" type="button" data-toggle="browsefile" data-path="{$NV_UPLOADS_DIR}" data-cpath="{$NV_UPLOADS_DIR}" data-area="site_logo" data-type="image"><i class="icon icon-left far fa-folder-open"></i> {$LANG->get('browse_image')}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_banner">{$LANG->get('site_banner')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="site_banner" name="site_banner" value="{$DATA['site_banner']}">
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">
                            <button class="btn btn-secondary btn-input-sm" type="button" data-toggle="browsefile" data-path="{$NV_UPLOADS_DIR}" data-cpath="{$NV_UPLOADS_DIR}" data-area="site_banner" data-type="image"><i class="icon icon-left far fa-folder-open"></i> {$LANG->get('browse_image')}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_favicon">{$LANG->get('site_favicon')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="site_favicon" name="site_favicon" value="{$DATA['site_favicon']}">
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">
                            <button class="btn btn-secondary btn-input-sm" type="button" data-toggle="browsefile" data-path="{$NV_UPLOADS_DIR}" data-cpath="{$NV_UPLOADS_DIR}" data-area="site_favicon" data-type="image"><i class="icon icon-left far fa-folder-open"></i> {$LANG->get('browse_image')}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_home_module">{$LANG->get('default_module')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="select2" id="site_home_module" name="site_home_module">
                        {foreach from=$HOME_MODULES item=row}
                        <option value="{$row['title']}"{if $row['title'] eq $GLOBAL_CONFIG['site_home_module']} selected="selected"{/if}>{$row['custom_title']}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('allow_theme_type')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    {foreach from=$THEME_TYPE item=row}
                    <label class="custom-control custom-checkbox custom-control-inline">
                        <input class="custom-control-input" type="checkbox" name="theme_type[]" value="{$row}"{if in_array($row, $GLOBAL_CONFIG['array_theme_type'])} checked="checked"{/if}><span class="custom-control-label">{$LANG->get("theme_type_`$row`")}</span>
                    </label>
                    {/foreach}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_theme">{$LANG->get('theme')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="site_theme" name="site_theme">
                        {foreach from=$SITE_THEME item=row}
                        <option value="{$row}"{if $row eq $GLOBAL_CONFIG['site_theme']} selected="selected"{/if}>{$row}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {if not empty($MOBILE_THEME) and in_array('m', $GLOBAL_CONFIG['array_theme_type'])}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="mobile_theme">{$LANG->get('mobile_theme')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="mobile_theme" name="mobile_theme">
                        <option value="">{$LANG->get('theme')}</option>
                        {foreach from=$MOBILE_THEME item=row}
                        <option value="{$row}"{if $row eq $GLOBAL_CONFIG['mobile_theme']} selected="selected"{/if}>{$row}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline">
                        <input class="custom-control-input" type="checkbox" name="switch_mobi_des" value="1"{if $GLOBAL_CONFIG['switch_mobi_des']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('allow_switch_mobi_des')}</span>
                    </label>
                </div>
            </div>
            {/if}
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header">{$LANG->get('disable_content')}</div>
        <div class="card-body">
            {$DISABLE_SITE_CONTENT}
            <div class="mt-3 text-center">
                <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
            </div>
        </div>
    </div>
</form>

<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.css">

<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>
<script>
$(document).ready(function() {
    $(".select2").select2({
        width: "100%",
        containerCssClass: "select2-sm"
    });
});
</script>
