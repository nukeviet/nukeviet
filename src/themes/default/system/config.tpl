<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/codemirror/css.bundle.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/pickr/pickr.min.js"></script>
{if not empty($ERROR)}
<div class="alert alert-danger" role="alert">{join($ERROR, '<br />')}</div>
{elseif not empty($WARNING)}
<div class="alert alert-warning" role="alert">
    <div class="mb-2 fw-medium fs-5">{$LANG->getModule('tconf_warning')}:</div>
    {join($WARNING, '<br />')}
</div>
{elseif not empty($CLEAN_TAB)}
<div class="alert alert-info" role="alert">{$LANG->getModule('tconf_cleaned')}</div>
{elseif not empty($SUCCESS)}
<div class="alert alert-success" role="alert">{$LANG->getModule('tconf_success')}</div>
{/if}
<form method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card">
        <div class="card-header card-header-tabs">
            <ul class="nav nav-tabs nav-justified" id="tab-tconf">
                <li class="nav-item">
                    <a class="nav-link text-truncate{$TAB eq 'color' ? ' active' : ''}" data-bs-toggle="tab" id="link-color" data-tab="color" data-bs-target="#tab-color" aria-current="{$TAB eq 'color' ? 'true' : 'false'}" role="tab" aria-controls="tab-color" aria-selected="{$TAB eq 'color' ? 'true' : 'false'}" href="#" data-location="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;tab=color">{$LANG->getModule('tconf_color_mode')}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-truncate{$TAB eq 'variables' ? ' active' : ''}" data-bs-toggle="tab" id="link-variables" data-tab="variables" data-bs-target="#tab-variables" aria-current="{$TAB eq 'variables' ? 'true' : 'false'}" role="tab" aria-controls="tab-variables" aria-selected="{$TAB eq 'variables' ? 'true' : 'false'}" href="#" data-location="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;tab=variables">{$LANG->getModule('tconf_customize_variables')}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-truncate{$TAB eq 'css' ? ' active' : ''}" data-bs-toggle="tab" id="link-css" data-tab="css" data-bs-target="#tab-css" aria-current="{$TAB eq 'css' ? 'true' : 'false'}" role="tab" aria-controls="tab-css" aria-selected="{$TAB eq 'css' ? 'true' : 'false'}" href="#" data-location="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;tab=css">{$LANG->getModule('tconf_css')}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-truncate{$TAB eq 'gfonts' ? ' active' : ''}" data-bs-toggle="tab" id="link-gfonts" data-tab="gfonts" data-bs-target="#tab-gfonts" aria-current="{$TAB eq 'gfonts' ? 'true' : 'false'}" role="tab" aria-controls="tab-gfonts" aria-selected="{$TAB eq 'gfonts' ? 'true' : 'false'}" href="#" data-location="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;tab=gfonts">{$LANG->getModule('tconf_gfonts')}</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade{$TAB eq 'color' ? ' show active' : ''}" id="tab-color" role="tabpanel" aria-labelledby="link-color" tabindex="0">
                    <div class="form-contents">
                        <div class="mb-3">
                            <div class="hstack gap-2">
                                <div>
                                    <input type="radio" class="btn-check" name="color_mode" value="light" id="color_mode_light" autocomplete="off"{if not isset($CONFIG.color, $CONFIG.color.mode) or $CONFIG.color.mode eq 'light'} checked{/if}>
                                    <label class="btn btn-outline-primary" for="color_mode_light"><i class="fa-solid fa-sun"></i> {$LANG->getModule('tconf_cm_light')}</label>
                                </div>
                                <div>
                                    <input type="radio" class="btn-check" name="color_mode" value="dark" id="color_mode_dark" autocomplete="off"{if isset($CONFIG.color, $CONFIG.color.mode) and $CONFIG.color.mode eq 'dark'} checked{/if}>
                                    <label class="btn btn-outline-dark" for="color_mode_dark"><i class="fa-solid fa-moon"></i> {$LANG->getModule('tconf_cm_dark')}</label>
                                </div>
                                <div>
                                    <input type="radio" class="btn-check" name="color_mode" value="auto" id="color_mode_auto" autocomplete="off"{if isset($CONFIG.color, $CONFIG.color.mode) and $CONFIG.color.mode eq 'auto'} checked{/if}>
                                    <label class="btn btn-outline-success" for="color_mode_auto"><i class="fa-solid fa-wand-magic-sparkles"></i> {$LANG->getModule('tconf_cm_auto')}</label>
                                </div>
                            </div>
                        </div>
                        <div class="fs-5 fw-medium mb-2">{$LANG->getModule('tconf_light_theme')}</div>
                        <div class="row g-3">
                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 col-xxl-1">
                                <input type="radio" class="btn-check" name="light_theme" value="light" id="light_theme_default" autocomplete="off" checked>
                                <label class="btn d-grid btn-outline-primary" for="light_theme_default">
                                    <img src="{$smarty.const.NV_BASE_SITEURL}themes/{$TEMPLATE}/default.jpg" class="img-fluid mb-2 mx-auto" alt="{$TEMPLATE}">
                                    <span>{$LANG->getModule('tconf_default')}</span>
                                </label>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade{$TAB eq 'variables' ? ' show active' : ''}" id="tab-variables" role="tabpanel" aria-labelledby="link-variables" tabindex="0">
                    <div class="form-contents vstack gap-3">
                        {foreach from=$VARIABLES key=confcat item=vals}
                        <div class="config-sections">
                            <div class="fw-medium fs-5 mb-1"><i class="fa-solid fa-palette"></i> {$LANG->getModule("tconf_var_`$confcat`")}</div>
                            <div class="row g-2">
                                {foreach from=$vals key=ckey item=cvonf}
                                {if isset($CONFIG.variables, $CONFIG.variables[$confcat], $CONFIG.variables[$confcat][$ckey])}
                                {assign var="vconf_value" value=$CONFIG.variables[$confcat][$ckey] nocache}
                                {else}
                                {assign var="vconf_value" value="" nocache}
                                {/if}
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-3 col-xxl-2">
                                    <label class="form-label fw-medium" for="{$confcat}_{$ckey}">{$LANG->getModule("tconf_vari_`$ckey`")}:</label>
                                    {if $cvonf.type eq 'color'}
                                    {* Chọn màu sắc *}
                                    <div class="input-group">
                                        <input type="text" name="{$confcat}_{$ckey}" id="{$confcat}_{$ckey}" value="{$vconf_value}" maxlength="7" class="form-control" data-toggle="colpick" autocomplete="off">
                                        <div class="input-group-text" id="{$confcat}_{$ckey}_preview"{if not empty($vconf_value)} style="background-color: {$vconf_value}; border-color: {$vconf_value};"{/if}>&nbsp; &nbsp;</div>
                                    </div>
                                    {elseif $cvonf.type eq 'text'}
                                    {* Nhập text *}
                                    <input type="text" name="{$confcat}_{$ckey}" id="{$confcat}_{$ckey}" value="{$vconf_value}" class="form-control" maxlength="250">
                                    {elseif $cvonf.type eq 'number'}
                                    {* Nhập số *}
                                    <input type="number" step=".1" name="{$confcat}_{$ckey}" id="{$confcat}_{$ckey}" value="{$vconf_value}" class="form-control" maxlength="250">
                                    {elseif $cvonf.type eq 'size'}
                                    {* Nhập kích thước *}
                                    {assign var="unit" value=$vconf_value|substr:-3 nocache}
                                    <div class="input-group">
                                        <input type="text" name="{$confcat}_{$ckey}" id="{$confcat}_{$ckey}" value="{if $unit eq 'rem'}{$vconf_value|substr:0:($vconf_value|strlen - 3)}{elseif $vconf_value|strlen gt 2}{$vconf_value|substr:0:($vconf_value|strlen - 2)}{/if}" class="form-control">
                                        <select class="form-select fw-75 flex-grow-0 flex-shrink-0" name="unit_{$confcat}_{$ckey}" aria-label="{$LANG->getModule('tconf_unit')} {$LANG->getModule("tconf_vari_`$ckey`")}">
                                            <option value="rem"{if $unit eq 'rem'} selected{/if}>rem</option>
                                            <option value="px"{if $vconf_value|substr:-2 eq 'px'} selected{/if}>px</option>
                                        </select>
                                    </div>
                                    {elseif $cvonf.type eq 'font_weight'}
                                    {* Độ đậm của chữ *}
                                    <select class="form-select" name="{$confcat}_{$ckey}" id="{$confcat}_{$ckey}">
                                        <option value="0">{$LANG->getModule('tconf_ignore')}</option>
                                        {for $w=100 to 900 step 100}
                                        <option value="{$w}"{if $vconf_value eq $w} selected{/if}>{$LANG->getModule("tconf_fw_`$w`")}</option>
                                        {/for}
                                    </select>
                                    {elseif $cvonf.type eq 'border_style'}
                                    {* Đường viền *}
                                    <select class="form-select" name="{$confcat}_{$ckey}" id="{$confcat}_{$ckey}">
                                        <option value="">{$LANG->getModule('tconf_ignore')}</option>
                                        {foreach from=$BORDER_STYLES item=value}
                                        <option value="{$value}"{if $vconf_value eq $value} selected{/if}>{$LANG->getModule("tconf_border_style_`$value`")}</option>
                                        {/foreach}
                                    </select>
                                    {elseif $cvonf.type eq 'text_decoration'}
                                    {* Trang trí chữ *}
                                    <select class="form-select" name="{$confcat}_{$ckey}" id="{$confcat}_{$ckey}">
                                        <option value="">{$LANG->getModule('tconf_ignore')}</option>
                                        {foreach from=$TEXT_DECORATIONS item=value}
                                        <option value="{$value}"{if $vconf_value eq $value} selected{/if}>{$LANG->getModule("tconf_deco_`$value`")}</option>
                                        {/foreach}
                                    </select>
                                    {/if}
                                </div>
                                {/foreach}
                            </div>
                        </div>
                        {/foreach}
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="clean_variables" value="1" id="clean_variables">
                            <label class="form-check-label" for="clean_variables">
                                {$LANG->getModule('tconf_clean')}
                            </label>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
                    </div>
                </div>
                <div class="tab-pane fade{$TAB eq 'css' ? ' show active' : ''}" id="tab-css" role="tabpanel" aria-labelledby="link-css" tabindex="0">
                    <div class="form-contents">
                        <div data-toggle="tconf-css"></div>
                        <textarea name="css" class="d-none" data-toggle="tconf-css-textarea">{$CONFIG.css ?? ''}</textarea>
                        <div class="d-flex justify-content-center mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="clean_css" value="1" id="clean_css">
                                <label class="form-check-label" for="clean_css">
                                    {$LANG->getModule('tconf_clean')}
                                </label>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade{$TAB eq 'gfonts' ? ' show active' : ''}" id="tab-gfonts" role="tabpanel" aria-labelledby="link-gfonts" tabindex="0">
                    <div class="form-contents">
                        <p class="mb-2">{$LANG->getModule('tconf_gfonts_note')}</p>
                        <div class="row mb-3">
                            <div class="col-lg-6 col-xl-4 col-xxl-3">
                                <label class="form-label fw-medium" for="gfonts_family">{$LANG->getModule('tconf_font_family')}:</label>
                                <input type="text" class="form-control" name="gfonts_family" id="gfonts_family" value="{if isset($CONFIG.gfont, $CONFIG.gfont.family)}{$CONFIG.gfont.family}{/if}">
                            </div>
                        </div>
                        <p class="mb-2">{$LANG->getModule('tconf_gfonts_stylechoose')}:</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item ps-0">
                                <div class="hstack gap-2">
                                    <div class="fw-150">&nbsp;</div>
                                    <div class="fw-medium fw-75 text-center">{$LANG->getModule('tconf_font_normal')}</div>
                                    <div class="fw-medium fw-75 text-center">{$LANG->getModule('tconf_font_italic')}</div>
                                </div>
                            </li>
                            {for $w=100 to 900 step 100}
                            <li class="list-group-item ps-0">
                                <div class="hstack gap-2">
                                    <div class="fw-150 fw-medium">{$LANG->getModule("tconf_fw_`$w`")}</div>
                                    <div class="fw-75 text-center d-flex justify-content-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" name="gfonts_n{$w}" value="1" aria-label="{$LANG->getModule('tconf_font_normal')}"{if isset($CONFIG.gfont, $CONFIG.gfont.styles, $CONFIG.gfont.styles[$w]) and not empty($CONFIG.gfont.styles[$w].n)} checked{/if}>
                                        </div>
                                    </div>
                                    <div class="fw-75 text-center d-flex justify-content-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" name="gfonts_i{$w}" value="1" aria-label="{$LANG->getModule('tconf_font_normal')}"{if isset($CONFIG.gfont, $CONFIG.gfont.styles, $CONFIG.gfont.styles[$w]) and not empty($CONFIG.gfont.styles[$w].i)} checked{/if}>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            {/for}
                        </ul>
                        <div class="d-flex justify-content-center mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="clean_gfonts" value="1" id="clean_gfonts">
                                <label class="form-check-label" for="clean_gfonts">
                                    {$LANG->getModule('tconf_clean')}
                                </label>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="tab" value="{$TAB}">
    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
</form>
