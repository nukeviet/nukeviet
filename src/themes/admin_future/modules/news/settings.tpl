<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.css">
<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate autocomplete="off">
    <input type="hidden" name="savesetting" value="1">
    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
    
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
        <div class="card-header fs-5 fw-medium">
            <i class="fa fa-file-text-o me-1"></i>{$LANG->getModule('setting_view')}
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="element_indexfile" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_indexfile')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_indexfile" name="indexfile">
                        {foreach from=$INDEXFILE key=key item=title}
                        <option value="{$key}"{if $key eq $DATA.indexfile} selected{/if}>{$title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_mobile_indexfile" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_mobile_indexfile')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_mobile_indexfile" name="mobile_indexfile">
                        {foreach from=$MOBILE_INDEXFILE key=key item=title}
                        <option value="{$key}"{if $key eq $DATA.mobile_indexfile} selected{/if}>{$title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_homewidth" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_homesite')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="row g-2">
                        <div class="col-auto">
                            <input class="form-control text-end" style="width:100px" type="number" value="{$DATA.homewidth}" name="homewidth" id="element_homewidth" min="0">
                        </div>
                        <div class="col-auto d-flex align-items-center">×</div>
                        <div class="col-auto">
                            <input class="form-control text-end" style="width:100px" type="number" value="{$DATA.homeheight}" name="homeheight" min="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_blockwidth" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_thumbblock')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="row g-2">
                        <div class="col-auto">
                            <input class="form-control text-end" style="width:100px" type="number" value="{$DATA.blockwidth}" name="blockwidth" id="element_blockwidth" min="0">
                        </div>
                        <div class="col-auto d-flex align-items-center">×</div>
                        <div class="col-auto">
                            <input class="form-control text-end" style="width:100px" type="number" value="{$DATA.blockheight}" name="blockheight" min="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_imagefull" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_imagefull')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control text-end" style="width:100px" type="number" value="{$DATA.imagefull}" name="imagefull" id="element_imagefull" min="0">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_per_page" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_per_page')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" style="width:100px" id="element_per_page" name="per_page">
                        {for $i=5 to 100}
                        <option value="{$i}"{if $i eq $DATA.per_page} selected{/if}>{$i}</option>
                        {/for}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_st_links" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_st_links')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" style="width:100px" id="element_st_links" name="st_links">
                        {for $i=0 to 50}
                        <option value="{$i}"{if $i eq $DATA.st_links} selected{/if}>{$i}</option>
                        {/for}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_showtooltip" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('showtooltip')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="showtooltip" value="1"{if $DATA.showtooltip} checked{/if} id="element_showtooltip">
                                <label class="form-check-label" for="element_showtooltip">{$LANG->getModule('showtooltip_position')}</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <select name="tooltip_position" class="form-select" style="width:150px">
                                {foreach from=$TOOLTIP_POSITION key=key item=title}
                                <option value="{$key}"{if $key eq $DATA.tooltip_position} selected{/if}>{$title}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="form-label mb-0">{$LANG->getModule('showtooltip_length')}</label>
                        </div>
                        <div class="col-auto">
                            <input type="number" name="tooltip_length" class="form-control text-end" value="{$DATA.tooltip_length}" style="width:100px" min="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="showhometext" value="1"{if $DATA.showhometext} checked{/if} id="element_showhometext">
                        <label class="form-check-label" for="element_showhometext">{$LANG->getModule('showhometext')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="htmlhometext" value="1"{if $DATA.htmlhometext} checked{/if} id="element_htmlhometext">
                        <label class="form-check-label" for="element_htmlhometext">{$LANG->getModule('htmlhometext')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('socialbutton')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    {foreach from=$SOCIALBUTTONS item=sb}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="socialbutton[]" value="{$sb.key}"{if $sb.checked} checked{/if}{if $sb.disabled} disabled{/if} id="element_sb_{$sb.key}">
                        <label class="form-check-label" for="element_sb_{$sb.key}">{$sb.title}{$sb.note}</label>
                    </div>
                    {/foreach}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="allowed_rating" value="1"{if $DATA.allowed_rating} checked{/if} id="element_allowed_rating">
                        <label class="form-check-label" for="element_allowed_rating">{$LANG->getModule('content_allowed_rating')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_allowed_rating_point" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('allowed_rating_point')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" style="width:150px" id="element_allowed_rating_point" name="allowed_rating_point">
                        {foreach from=$RATING_POINT key=key item=title}
                        <option value="{$key}"{if $key eq $DATA.allowed_rating_point} selected{/if}>{$title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="report_active" value="1"{if $DATA.report_active} checked{/if} id="element_report_active">
                        <label class="form-check-label" for="element_report_active">{$LANG->getModule('report_active')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_report_limit" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('report_limit')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group" style="width:200px">
                        <input type="number" class="form-control text-end" value="{$DATA.report_limit}" name="report_limit" id="element_report_limit" maxlength="4" min="1">
                        <span class="input-group-text">{$LANG->getGlobal('minute')}</span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_show_no_image" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('show_no_image')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input class="form-control" type="text" name="show_no_image" id="element_show_no_image" value="{$SHOW_NO_IMAGE}" autocomplete="off">
                        <button type="button" data-toggle="selectfile" data-target="element_show_no_image" data-path="{$PATH}" data-currentpath="{$CURRENTPATH}" data-type="image" class="btn btn-secondary" aria-label="{$LANG->getGlobal('browse_image')}"><i class="fa fa-folder-open-o"></i></button>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_config_source" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('config_source')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_config_source" name="config_source">
                        {foreach from=$CONFIG_SOURCE key=key item=title}
                        <option value="{$key}"{if $key eq $DATA.config_source} selected{/if}>{$title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="hide_author" value="1"{if $DATA.hide_author} checked{/if} id="element_hide_author">
                        <label class="form-check-label" for="element_hide_author">{$LANG->getModule('hide_author')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="hide_inauthor" value="1"{if $DATA.hide_inauthor} checked{/if} id="element_hide_inauthor">
                        <label class="form-check-label" for="element_hide_inauthor">{$LANG->getModule('hide_inauthor')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_copyright')}</div>
                <div class="col-sm-8 col-lg-8 col-xxl-7">
                    {$COPYRIGHTHTML}
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
        <div class="card-header fs-5 fw-medium">
            <i class="fa fa-file-text-o me-1"></i>{$LANG->getModule('setting_post')}
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="element_facebookappid" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('facebookAppID')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control" style="max-width:300px" name="facebookappid" value="{$DATA.facebookappid}" type="text" id="element_facebookappid">
                    <div class="form-text">{$LANG->getModule('facebookAppIDNote')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_structure_upload" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('structure_image_upload')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select select2" id="element_structure_upload" name="structure_upload">
                        {foreach from=$STRUCTURE_UPLOAD key=key item=title}
                        <option value="{$key}"{if $key eq $DATA.structure_upload} selected{/if}>{$title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_imgposition" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('imgpositiondefault')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_imgposition" name="imgposition">
                        {foreach from=$IMGPOSITION key=key item=title}
                        <option value="{$key}"{if $key eq $DATA.imgposition} selected{/if}>{$title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="alias_lower" value="1"{if $DATA.alias_lower} checked{/if} id="element_alias_lower">
                        <label class="form-check-label" for="element_alias_lower">{$LANG->getModule('setting_alias_lower')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="tags_alias" value="1"{if $DATA.tags_alias} checked{/if} id="element_tags_alias">
                        <label class="form-check-label" for="element_tags_alias">{$LANG->getModule('tags_alias')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="auto_tags" value="1"{if $DATA.auto_tags} checked{/if} id="element_auto_tags">
                        <label class="form-check-label" for="element_auto_tags">{$LANG->getModule('setting_auto_tags')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="tags_remind" value="1"{if $DATA.tags_remind} checked{/if} id="element_tags_remind">
                        <label class="form-check-label" for="element_tags_remind">{$LANG->getModule('setting_tags_remind')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="keywords_tag" value="1"{if $DATA.keywords_tag} checked{/if} id="element_keywords_tag">
                        <label class="form-check-label" for="element_keywords_tag">{$LANG->getModule('setting_keywords_tag')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="copy_news" value="1"{if $DATA.copy_news} checked{/if} id="element_copy_news">
                        <label class="form-check-label" for="element_copy_news">{$LANG->getModule('setting_copy_news')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_order_articles" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('order_articles')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_order_articles" name="order_articles">
                        {foreach from=$ORDER_ARTICLES key=key item=title}
                        <option value="{$key}"{if $key eq $DATA.order_articles} selected{/if}>{$title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="auto_save" value="1"{if $DATA.auto_save} checked{/if} id="element_auto_save">
                        <label class="form-check-label" for="element_auto_save">{$LANG->getModule('setting_auto_save')}</label>
                    </div>
                    <div class="form-text">{$LANG->getModule('setting_auto_save_help')}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
        <div class="card-header fs-5 fw-medium">
            <i class="fa fa-file-text-o me-1"></i>{$LANG->getModule('setting_elasticseach')}
        </div>
        <div class="card-body pt-4">
            <div class="alert alert-info">
                <div>{$LANG->getModule('use_setup_elasticseach')}: <a href="http://wiki.nukeviet.vn/web_server:install-and-configure-elasticsearch-on-centos-7" target="_blank">http://wiki.nukeviet.vn/web_server:install-and-configure-elasticsearch-on-centos-7</a></div>
                <div>{$LANG->getModule('use_dev_elasticseach')}: <a href="http://wiki.nukeviet.vn/web_server:use-elasticsearch-in-nukeviet" target="_blank">http://wiki.nukeviet.vn/web_server:use-elasticsearch-in-nukeviet</a></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="elas_use" value="1"{if $DATA.elas_use} checked{/if} id="element_elas_use">
                        <label class="form-check-label" for="element_elas_use">{$LANG->getModule('setting_elas_use')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_elas_host" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_elas_host')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control" type="text" value="{$DATA.elas_host}" name="elas_host" id="element_elas_host">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_elas_port" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_elas_port')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control text-end" style="width:150px" type="number" value="{$DATA.elas_port}" name="elas_port" id="element_elas_port" min="0">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_elas_index" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_elas_index')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control" type="text" value="{$DATA.elas_index}" name="elas_index" id="element_elas_index">
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
        <div class="card-header fs-5 fw-medium">
            <i class="fa fa-file-text-o me-1"></i>{$LANG->getModule('setting_insart_caption')}
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="instant_articles_active" value="1"{if $DATA.instant_articles_active} checked{/if} id="element_instant_articles_active">
                        <label class="form-check-label" for="element_instant_articles_active">{$LANG->getModule('setting_active_instant_articles')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="instant_articles_auto" value="1"{if $DATA.instant_articles_auto} checked{/if} id="element_instant_articles_auto">
                        <label class="form-check-label" for="element_instant_articles_auto">{$LANG->getModule('setting_instant_articles_auto')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_instant_articles_template" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_instant_articles_template')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" value="{$DATA.instant_articles_template}" name="instant_articles_template" class="form-control" id="element_instant_articles_template">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="instant_articles_httpauth" value="1"{if $DATA.instant_articles_httpauth} checked{/if} id="element_instant_articles_httpauth">
                        <label class="form-check-label" for="element_instant_articles_httpauth">{$LANG->getModule('setting_instant_articles_httpauth')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_instant_articles_username" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_instant_articles_username')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" value="{$DATA.instant_articles_username}" name="instant_articles_username" class="form-control" id="element_instant_articles_username" autocomplete="username">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_instant_articles_password" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_instant_articles_password')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="password" value="{$INSTANT_ARTICLES_PASSWORD}" name="instant_articles_password" class="form-control" id="element_instant_articles_password" autocomplete="new-password">
                        <button type="button" class="btn btn-secondary showhidepass" data-target="#element_instant_articles_password" data-bs-toggle="tooltip" title="{$LANG->getModule('show_hide_pass')}" aria-label="{$LANG->getModule('show_hide_pass')}"><i class="fa fa-key"></i></button>
                        <button type="button" class="btn btn-secondary genrandpass" data-target="#element_instant_articles_password" data-bs-toggle="tooltip" title="{$LANG->getModule('gen_rand_pass')}" aria-label="{$LANG->getModule('gen_rand_pass')}"><i class="fa fa-refresh"></i></button>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_instant_articles_livetime" class="col-sm-4 col-form-label text-sm-end">
                    {$LANG->getModule('setting_instant_articles_livetime')}
                    <a href="javascript:void(0);" data-bs-toggle="tooltip" title="{$LANG->getModule('setting_instant_articles_livetime1')}" aria-label="{$LANG->getModule('setting_instant_articles_livetime1')}"><i class="fa fa-info-circle"></i></a>
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="number" value="{$DATA.instant_articles_livetime}" name="instant_articles_livetime" class="form-control text-end" style="width:150px" id="element_instant_articles_livetime" min="0">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_instant_articles_gettime" class="col-sm-4 col-form-label text-sm-end">
                    {$LANG->getModule('setting_instant_articles_gettime')}
                    <a href="javascript:void(0);" data-bs-toggle="tooltip" title="{$LANG->getModule('setting_instant_articles_gettime1')}" aria-label="{$LANG->getModule('setting_instant_articles_gettime1')}"><i class="fa fa-info-circle"></i></a>
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="number" value="{$DATA.instant_articles_gettime}" name="instant_articles_gettime" class="form-control text-end" style="width:150px" id="element_instant_articles_gettime" min="0">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_instant_articles_url" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_instant_articles_defaulturl')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control mb-2" value="{$INSTANT_ARTICLES_URL_DEFAULT}" data-toggle="selectall" id="element_instant_articles_url" readonly aria-label="{$LANG->getModule('setting_instant_articles_defaulturl')}">
                    <div class="form-text">{$LANG->getModule('setting_instant_articles_defaulturl1')}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
        <div class="card-header fs-5 fw-medium">
            <i class="fa fa-file-text-o me-1"></i>{$LANG->getModule('setting_sys')}
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="identify_cat_change" value="1"{if $DATA.identify_cat_change} checked{/if} id="element_identify_cat_change">
                        <label class="form-check-label" for="element_identify_cat_change">{$LANG->getModule('setting_identify_cat_change')}</label>
                    </div>
                    <div class="form-text">{$LANG->getModule('setting_identify_cat_change_help')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="active_history" value="1"{if $DATA.active_history} checked{/if} id="element_active_history">
                        <label class="form-check-label" for="element_active_history">{$LANG->getModule('setting_active_history')}</label>
                    </div>
                    <div class="form-text">{$LANG->getModule('setting_active_history_help')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_schema_type" class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('setting_schema_type')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" name="schema_type" id="element_schema_type">
                        {foreach from=$SCHEMA_TYPES key=key item=title}
                        <option value="{$key}"{if $key eq $DATA.schema_type} selected{/if}>{$title}</option>
                        {/foreach}
                    </select>
                    <div class="form-text">{$LANG->getModule('setting_schema_type_help')}</div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary" name="submit_savesetting">{$LANG->getGlobal('save')}</button>
        </div>
    </div>
</form>

{if $SHOW_ADMIN_CONFIG_POST}
<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <input type="hidden" name="savepost" value="1">
    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
    
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
        <div class="card-header fs-5 fw-medium">
            <i class="fa fa-file-text-o me-1"></i>{$LANG->getModule('group_content')}
        </div>
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap">{$LANG->getGlobal('mod_groups')}</th>
                            <th class="text-nowrap text-center">{$LANG->getModule('group_addcontent')}</th>
                            <th class="text-nowrap text-center">{$LANG->getModule('group_postcontent')}</th>
                            <th class="text-nowrap text-center">{$LANG->getModule('group_editcontent')}</th>
                            <th class="text-nowrap text-center">{$LANG->getModule('group_delcontent')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$GROUPS_POST item=row}
                        <tr>
                            <td><strong>{$row.group_title}</strong>
                                <input type="hidden" value="{$row.group_id}" name="array_group_id[]">
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input" type="checkbox" value="1" name="array_addcontent[{$row.group_id}]"{if $row.addcontent} checked{/if} id="element_add_{$row.group_id}">
                                    <label class="form-check-label" for="element_add_{$row.group_id}"></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input" type="checkbox" value="1" name="array_postcontent[{$row.group_id}]"{if $row.postcontent} checked{/if} id="element_post_{$row.group_id}">
                                    <label class="form-check-label" for="element_post_{$row.group_id}"></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input" type="checkbox" value="1" name="array_editcontent[{$row.group_id}]"{if $row.editcontent} checked{/if}{if $row.is_guest} disabled{/if} id="element_edit_{$row.group_id}">
                                    <label class="form-check-label" for="element_edit_{$row.group_id}"></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input" type="checkbox" value="1" name="array_delcontent[{$row.group_id}]"{if $row.delcontent} checked{/if}{if $row.is_guest} disabled{/if} id="element_del_{$row.group_id}">
                                    <label class="form-check-label" for="element_del_{$row.group_id}"></label>
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
        <div class="card-header fs-5 fw-medium">
            <i class="fa fa-file-text-o me-1"></i>{$LANG->getModule('setting_frontend_post')}
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="frontend_edit_alias" value="1"{if $DATA.frontend_edit_alias} checked{/if} id="element_frontend_edit_alias">
                        <label class="form-check-label" for="element_frontend_edit_alias">{$LANG->getModule('frontend_edit_alias')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="frontend_edit_layout" value="1"{if $DATA.frontend_edit_layout} checked{/if} id="element_frontend_edit_layout">
                        <label class="form-check-label" for="element_frontend_edit_layout">{$LANG->getModule('frontend_edit_layout')}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
        <div class="card-header fs-5 fw-medium">
            <i class="fa fa-file-text-o me-1"></i>{$LANG->getModule('report')}
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label text-sm-end">{$LANG->getModule('report_group')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    {foreach from=$REPORT_GROUPS item=option}
                    <div class="form-check">
                        <input class="form-check-input" name="report_group[]" type="checkbox" value="{$option.value}"{if $option.checked} checked{/if} id="element_report_group_{$option.value}">
                        <label class="form-check-label" for="element_report_group_{$option.value}">{$option.title}</label>
                    </div>
                    {/foreach}
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary" name="submit_savepost">{$LANG->getGlobal('save')}</button>
        </div>
    </div>
</form>
{/if}
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
