<form id="news-setting-form" method="post" class="ajax-submit" novalidate action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('setting_view')}</h5>
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="indexfile" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_indexfile')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="indexfile" name="indexfile">
                        {foreach from=$INDEXFILE_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="mobile_indexfile" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_mobile_indexfile')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="mobile_indexfile" name="mobile_indexfile">
                        {foreach from=$MOBILE_INDEXFILE_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 text-sm-end">
                    <div class="form-label mb-0">{$LANG->getModule('setting_homesite')}</div>
                </div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input class="form-control" type="text" name="homewidth" value="{$ITEM.homewidth}" inputmode="numeric" autocomplete="off">
                        <span class="input-group-text">x</span>
                        <input class="form-control" type="text" name="homeheight" value="{$ITEM.homeheight}" inputmode="numeric" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 text-sm-end">
                    <div class="form-label mb-0">{$LANG->getModule('setting_thumbblock')}</div>
                </div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input class="form-control" type="text" name="blockwidth" value="{$ITEM.blockwidth}" inputmode="numeric" autocomplete="off">
                        <span class="input-group-text">x</span>
                        <input class="form-control" type="text" name="blockheight" value="{$ITEM.blockheight}" inputmode="numeric" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="imagefull" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_imagefull')}</label>
                <div class="col-sm-8 col-lg-3 col-xxl-2">
                    <input class="form-control" type="text" id="imagefull" name="imagefull" value="{$ITEM.imagefull}" inputmode="numeric" autocomplete="off">
                </div>
            </div>
            <div class="row mb-3">
                <label for="per_page" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_per_page')}</label>
                <div class="col-sm-8 col-lg-3 col-xxl-2">
                    <select class="form-select" id="per_page" name="per_page">
                        {foreach from=$PER_PAGE_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="st_links" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_st_links')}</label>
                <div class="col-sm-8 col-lg-3 col-xxl-2">
                    <select class="form-select" id="st_links" name="st_links">
                        {foreach from=$ST_LINK_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-7 col-xxl-6">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="showtooltip" name="showtooltip" value="1"{if $ITEM.showtooltip} checked{/if}>
                        <label class="form-check-label" for="showtooltip">{$LANG->getModule('showtooltip')}</label>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tooltip_position" class="form-label">{$LANG->getModule('showtooltip_position')}</label>
                            <select name="tooltip_position" id="tooltip_position" class="form-select">
                                {foreach from=$TOOLTIP_OPTIONS item=option}
                                <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tooltip_length" class="form-label">{$LANG->getModule('showtooltip_length')}</label>
                            <input type="text" id="tooltip_length" name="tooltip_length" class="form-control" value="{$ITEM.tooltip_length}" inputmode="numeric" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="showhometext" name="showhometext" value="1"{if $ITEM.showhometext} checked{/if}>
                        <label class="form-check-label" for="showhometext">{$LANG->getModule('showhometext')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="htmlhometext" name="htmlhometext" value="1"{if $ITEM.htmlhometext} checked{/if}>
                        <label class="form-check-label" for="htmlhometext">{$LANG->getModule('htmlhometext')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 text-sm-end">
                    <div class="form-label mb-0">{$LANG->getModule('socialbutton')}</div>
                </div>
                <div class="col-sm-8 col-lg-7 col-xxl-6">
                    {foreach from=$SOCIALBUTTON_OPTIONS item=row}
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="socialbutton[]" value="{$row.key}" id="socialbutton_{$row.key}"{if $row.checked} checked{/if}{if $row.disabled} disabled{/if}>
                        <label class="form-check-label" for="socialbutton_{$row.key}">{$row.title}</label>
                        {if $row.key eq 'zalo' and $row.disabled}
                        <div class="form-text">
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=zalo&amp;{$smarty.const.NV_OP_VARIABLE}=settings">{$LANG->getModule('socialbutton_zalo_note')}</a>
                        </div>
                        {/if}
                    </div>
                    {/foreach}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="allowed_rating" name="allowed_rating" value="1"{if $ITEM.allowed_rating} checked{/if}>
                        <label class="form-check-label" for="allowed_rating">{$LANG->getModule('content_allowed_rating')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="allowed_rating_point" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('allowed_rating_point')}</label>
                <div class="col-sm-8 col-lg-3 col-xxl-2">
                    <select class="form-select" id="allowed_rating_point" name="allowed_rating_point">
                        {foreach from=$RATING_POINT_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="report_active" name="report_active" value="1"{if $ITEM.report_active} checked{/if}>
                        <label class="form-check-label" for="report_active">{$LANG->getModule('report_active')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-0">
                <label for="report_limit" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('report_limit')}</label>
                <div class="col-sm-8 col-lg-3 col-xxl-2">
                    <div class="input-group">
                        <input type="text" class="form-control" id="report_limit" name="report_limit" value="{$ITEM.report_limit}" maxlength="4" inputmode="numeric" autocomplete="off">
                        <span class="input-group-text">{$LANG->getGlobal('min')}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('setting_post')}</h5>
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="facebookappid" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('facebookAppID')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control" id="facebookappid" name="facebookappid" value="{$ITEM.facebookappid}" type="text" autocomplete="off">
                    <div class="form-text">{$LANG->getModule('facebookAppIDNote')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="show_no_image" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('show_no_image')}</label>
                <div class="col-sm-8 col-lg-7 col-xxl-6">
                    <div class="input-group">
                        <input class="form-control" type="text" name="show_no_image" id="show_no_image" value="{$SHOW_NO_IMAGE}" autocomplete="off">
                        <button type="button" class="btn btn-secondary" aria-label="{$LANG->getGlobal('browse_image')}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getGlobal('browse_image')}" data-toggle="selectfile" data-target="show_no_image" data-path="{$UPLOAD_PATH}" data-currentpath="{$UPLOAD_CURRENT}" data-type="image"><i class="fa-solid fa-folder-open"></i></button>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="structure_upload" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('structure_image_upload')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" name="structure_upload" id="structure_upload">
                        {foreach from=$STRUCTURE_UPLOAD_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="imgposition" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('imgpositiondefault')}</label>
                <div class="col-sm-8 col-lg-4 col-xxl-3">
                    <select class="form-select" name="imgposition" id="imgposition">
                        {foreach from=$IMGPOSITION_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="config_source" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('config_source')}</label>
                <div class="col-sm-8 col-lg-4 col-xxl-3">
                    <select class="form-select" name="config_source" id="config_source">
                        {foreach from=$CONFIG_SOURCE_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="hide_author" name="hide_author" value="1"{if $ITEM.hide_author} checked{/if}>
                        <label class="form-check-label" for="hide_author">{$LANG->getModule('hide_author')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="hide_inauthor" name="hide_inauthor" value="1"{if $ITEM.hide_inauthor} checked{/if}>
                        <label class="form-check-label" for="hide_inauthor">{$LANG->getModule('hide_inauthor')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 text-sm-end">
                    <div class="form-label mb-0">{$LANG->getModule('setting_copyright')}</div>
                </div>
                <div class="col-sm-8 col-lg-7 col-xxl-6">
                    {$COPYRIGHTHTML nofilter}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="alias_lower" name="alias_lower" value="1"{if $ITEM.alias_lower} checked{/if}>
                        <label class="form-check-label" for="alias_lower">{$LANG->getModule('setting_alias_lower')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="tags_alias" name="tags_alias" value="1"{if $ITEM.tags_alias} checked{/if}>
                        <label class="form-check-label" for="tags_alias">{$LANG->getModule('tags_alias')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="auto_tags" name="auto_tags" value="1"{if $ITEM.auto_tags} checked{/if}>
                        <label class="form-check-label" for="auto_tags">{$LANG->getModule('setting_auto_tags')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="tags_remind" name="tags_remind" value="1"{if $ITEM.tags_remind} checked{/if}>
                        <label class="form-check-label" for="tags_remind">{$LANG->getModule('setting_tags_remind')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="keywords_tag" name="keywords_tag" value="1"{if $ITEM.keywords_tag} checked{/if}>
                        <label class="form-check-label" for="keywords_tag">{$LANG->getModule('setting_keywords_tag')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="copy_news" name="copy_news" value="1"{if $ITEM.copy_news} checked{/if}>
                        <label class="form-check-label" for="copy_news">{$LANG->getModule('setting_copy_news')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="order_articles" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('order_articles')}</label>
                <div class="col-sm-8 col-lg-4 col-xxl-3">
                    <select class="form-select" name="order_articles" id="order_articles">
                        {foreach from=$ORDER_ARTICLES_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-sm-8 offset-sm-4 col-lg-7 col-xxl-6">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="auto_save" name="auto_save" value="1"{if $ITEM.auto_save} checked{/if}>
                        <label class="form-check-label" for="auto_save">{$LANG->getModule('setting_auto_save')}</label>
                    </div>
                    <div class="form-text">{$LANG->getModule('setting_auto_save_help')}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('setting_elasticseach')}</h5>
        </div>
        <div class="card-body pt-4">
            <div class="alert alert-info" role="alert">
                <div>{$LANG->getModule('use_setup_elasticseach')}: <a href="http://wiki.nukeviet.vn/web_server:install-and-configure-elasticsearch-on-centos-7" target="_blank" rel="noopener noreferrer">http://wiki.nukeviet.vn/web_server:install-and-configure-elasticsearch-on-centos-7</a></div>
                <div>{$LANG->getModule('use_dev_elasticseach')}: <a href="http://wiki.nukeviet.vn/web_server:use-elasticsearch-in-nukeviet" target="_blank" rel="noopener noreferrer">http://wiki.nukeviet.vn/web_server:use-elasticsearch-in-nukeviet</a></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="elas_use" name="elas_use" value="1"{if $ITEM.elas_use} checked{/if}>
                        <label class="form-check-label" for="elas_use">{$LANG->getModule('setting_elas_use')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="elas_host" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_elas_host')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control" type="text" id="elas_host" value="{$ITEM.elas_host}" name="elas_host" autocomplete="off">
                </div>
            </div>
            <div class="row mb-3">
                <label for="elas_port" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_elas_port')}</label>
                <div class="col-sm-8 col-lg-3 col-xxl-2">
                    <input class="form-control" type="text" id="elas_port" value="{$ITEM.elas_port}" name="elas_port" inputmode="numeric" autocomplete="off">
                </div>
            </div>
            <div class="row mb-0">
                <label for="elas_index" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_elas_index')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control" type="text" id="elas_index" value="{$ITEM.elas_index}" name="elas_index" autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('setting_sys')}</h5>
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-7 col-xxl-6">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="identify_cat_change" name="identify_cat_change" value="1"{if $ITEM.identify_cat_change} checked{/if}>
                        <label class="form-check-label" for="identify_cat_change">{$LANG->getModule('setting_identify_cat_change')}</label>
                    </div>
                    <div class="form-text">{$LANG->getModule('setting_identify_cat_change_help')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-7 col-xxl-6">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="active_history" name="active_history" value="1"{if $ITEM.active_history} checked{/if}>
                        <label class="form-check-label" for="active_history">{$LANG->getModule('setting_active_history')}</label>
                    </div>
                    <div class="form-text">{$LANG->getModule('setting_active_history_help')}</div>
                </div>
            </div>
            <div class="row mb-0">
                <label for="schema_type" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_schema_type')}</label>
                <div class="col-sm-8 col-lg-7 col-xxl-6">
                    <select class="form-select mb-2" name="schema_type" id="schema_type">
                        {foreach from=$SCHEMA_TYPE_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                    <div class="form-text">{$LANG->getModule('setting_schema_type_help')}</div>
                </div>
            </div>
        </div>
        <div class="card-footer border-top text-center">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <input type="hidden" name="savesetting" value="1">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}</button>
        </div>
    </div>
</form>

{if $CAN_CONFIG_POST}
<form id="news-post-setting-form" method="post" class="ajax-submit" novalidate action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('group_content')}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead class="text-muted">
                        <tr>
                            <th class="text-nowrap" style="width: 40%;">{$LANG->getGlobal('in_groups')}</th>
                            <th class="text-center text-nowrap" style="width: 15%;">{$LANG->getModule('group_addcontent')}</th>
                            <th class="text-center text-nowrap" style="width: 15%;">{$LANG->getModule('group_postcontent')}</th>
                            <th class="text-center text-nowrap" style="width: 15%;">{$LANG->getModule('group_editcontent')}</th>
                            <th class="text-center text-nowrap" style="width: 15%;">{$LANG->getModule('group_delcontent')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$POST_CONFIG_ROWS item=row}
                        <tr>
                            <td>
                                <strong>{$row.group_title}</strong>
                                <input type="hidden" name="array_group_id[]" value="{$row.group_id}">
                            </td>
                            <td class="text-center"><input class="form-check-input" type="checkbox" name="array_addcontent[{$row.group_id}]" value="1" aria-label="{$LANG->getModule('group_addcontent')}"{if $row.addcontent} checked{/if}></td>
                            <td class="text-center"><input class="form-check-input" type="checkbox" name="array_postcontent[{$row.group_id}]" value="1" aria-label="{$LANG->getModule('group_postcontent')}"{if $row.postcontent} checked{/if}></td>
                            <td class="text-center"><input class="form-check-input" type="checkbox" name="array_editcontent[{$row.group_id}]" value="1" aria-label="{$LANG->getModule('group_editcontent')}"{if $row.editcontent} checked{/if}{if $row.disable_editcontent} disabled{/if}></td>
                            <td class="text-center"><input class="form-check-input" type="checkbox" name="array_delcontent[{$row.group_id}]" value="1" aria-label="{$LANG->getModule('group_delcontent')}"{if $row.delcontent} checked{/if}{if $row.disable_delcontent} disabled{/if}></td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('setting_frontend_post')}</h5>
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="frontend_edit_alias" name="frontend_edit_alias" value="1"{if $ITEM.frontend_edit_alias} checked{/if}>
                        <label class="form-check-label" for="frontend_edit_alias">{$LANG->getModule('frontend_edit_alias')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-sm-8 offset-sm-4 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="frontend_edit_layout" name="frontend_edit_layout" value="1"{if $ITEM.frontend_edit_layout} checked{/if}>
                        <label class="form-check-label" for="frontend_edit_layout">{$LANG->getModule('frontend_edit_layout')}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('report')}</h5>
        </div>
        <div class="card-body pt-4">
            <div class="row mb-0">
                <div class="col-sm-4 text-sm-end">
                    <div class="form-label mb-0">{$LANG->getModule('report_group')}</div>
                </div>
                <div class="col-sm-8 col-lg-7 col-xxl-6">
                    {foreach from=$REPORT_GROUP_OPTIONS item=option}
                    <div class="form-check mb-2">
                        <input class="form-check-input" name="report_group[]" type="checkbox" value="{$option.value}" id="report_group_{$option.value}"{if $option.checked} checked{/if}>
                        <label class="form-check-label" for="report_group_{$option.value}">{$option.title}</label>
                    </div>
                    {/foreach}
                </div>
            </div>
        </div>
        <div class="card-footer border-top text-center">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <input type="hidden" name="savepost" value="1">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}</button>
        </div>
    </div>
</form>
{/if}
