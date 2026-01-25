<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div role="alert" class="alert alert-info">{$LANG->getModule('nv_admin_edit_info', $USER.username)}</div>
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body pt-4">
        <form class="ajax-submit" method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;admin_id={$ADMIN_ID}" novalidate>
            {if $POSITION_ALLOWED}
            <div class="row mb-3">
                <label for="element_position" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('position')} <span class="text-danger">(*)</span></label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_position" name="position" value="{$POSITION}" maxlength="250">
                    <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                    <div class="form-text">{$LANG->getModule('position_info')}</div>
                </div>
            </div>
            {/if}
            <div class="row mb-3">
                <label for="element_admin_theme" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('themeadmin')}</label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_admin_theme" name="admin_theme">
                        <option value="">{$LANG->getModule('theme_default')}</option>
                        {foreach from=$ADMINTHEMES item=theme}
                        <option value="{$theme}"{if $theme eq $ADMIN_THEME} selected{/if}>{$theme}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {if not empty($EDITORS)}
            <div class="row mb-3">
                <label for="element_editor" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('editor')}</label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_editor" name="editor">
                        <option value="">{$LANG->getModule('not_use')}</option>
                        {foreach from=$EDITORS item=editor}
                        <option value="{$editor}"{if $editor eq $ADMIN_EDITOR} selected{/if}>{$editor}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}
            <div class="row mb-3">
                <label for="element_main_module" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('main_module')}</label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_main_module" name="main_module">
                        {foreach from=$ARRAY_MODULE item=module}
                        <option value="{$module.module}"{if $module.module eq $ADMIN_MAIN_MODULE} selected{/if}>{$module.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {if $smarty.const.NV_IS_SPADMIN and not empty($GCONFIG.file_allowed_ext)}
            <div class="row mb-3">
                <div class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('allow_files_type')}</div>
                <div class="col-12 col-lg-8">
                    <div class="row mt-1">
                        {foreach from=$GCONFIG.file_allowed_ext item=ext}
                        <div class="col-6 col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="allow_files_type[]" value="{$ext}"{if in_array($ext, $ALLOW_FILES_TYPE, true)} checked{/if} id="file_allowed_ext_{$ext}">
                                <label class="form-check-label" for="file_allowed_ext_{$ext}">{$ext}</label>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            {/if}
            {if $smarty.const.NV_IS_SPADMIN}
            <div class="row mb-3">
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="allow_modify_files" value="1"{if $ALLOW_MODIFY_FILES} checked{/if} role="switch" id="element_allow_modify_files">
                        <label class="form-check-label" for="element_allow_modify_files">{$LANG->getModule('allow_modify_files')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="allow_create_subdirectories" value="1"{if $ALLOW_CREATE_SUBDIRECTORIES} checked{/if} role="switch" id="element_allow_create_subdirectories">
                        <label class="form-check-label" for="element_allow_create_subdirectories">{$LANG->getModule('allow_create_subdirectories')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="allow_modify_subdirectories" value="1"{if $ALLOW_MODIFY_SUBDIRECTORIES} checked{/if} role="switch" id="element_allow_modify_subdirectories">
                        <label class="form-check-label" for="element_allow_modify_subdirectories">{$LANG->getModule('allow_modify_subdirectories')}</label>
                    </div>
                </div>
            </div>
            {if $ADMIN_INFO.admin_id neq $ADMIN_ID}
            <div class="row mb-3">
                <div class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('lev')} <span class="text-danger">(*)</span></div>
                <div class="col-12 col-sm-9">
                    <div class="mt-2">
                        {if $ADMIN_INFO.level eq 1}
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" data-toggle="authorLev" name="lev" id="checkLev2" value="2"{if $LEV eq 2} checked{/if}>
                            <label class="form-check-label" for="checkLev2">{$LANG->getGlobal('level2')}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" data-toggle="authorLev" name="lev" id="checkLev3" value="3"{if $LEV eq 3} checked{/if}>
                            <label class="form-check-label" for="checkLev3">{$LANG->getGlobal('level3')}</label>
                        </div>
                        {/if}
                    </div>
                    <div id="modslist"{if $LEV eq 2} style="display: none;"{/if}>
                        <div class="mt-2">{$LANG->getModule('if_level3_selected')}</div>
                        {foreach from=$ALLMODS key=keylang item=mods}
                        <div class="mt-2 border border-primary p-3 rounded-2" data-toggle="checklist">
                            <div class="d-flex align-items-center flex-wrap mb-2">
                                <div class="fw-medium fs-5 my-1 me-3">{$LANGUAGE_ARRAY[$keylang].name}</div>
                                <button type="button" class="btn my-1 btn-sm btn-secondary me-2" data-toggle="checkall" data-check-value="true"><i class="fa-solid fa-square-check"></i> {$LANG->getModule('checkall')}</button>
                                <button type="button" class="btn my-1 btn-sm btn-default" data-toggle="checkall" data-check-value="false"><i class="fa-solid fa-square"></i> {$LANG->getModule('uncheckall')}</button>
                            </div>
                            <div class="row">
                                {foreach from=$mods key=mod item=modinfo}
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input data-toggle="checkitem" class="form-check-input" type="checkbox" id="modules_{$keylang}_{$mod}" name="modules[{$keylang}][]" value="{$mod}"{if !empty($MODULES[$keylang]) and in_array($mod, $MODULES[$keylang])} checked{/if}>
                                        <label class="form-check-label" for="modules_{$keylang}_{$mod}">{$mod} ({$modinfo.custom_title})</label>
                                    </div>
                                </div>
                                {/foreach}
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="lev_expired" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('lev_expired')}</label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group d-inline-flex w-auto">
                        <input type="text" class="form-control datepicker-post" id="lev_expired" name="lev_expired" value="{$LEV_EXPIRED}" placeholder="{$DATE_FORMAT}" autocomplete="off">
                        <button class="btn btn-secondary" type="button" data-toggle="focusDate"><i class="fa-solid fa-calendar"></i></button>
                        <button class="btn btn-secondary" type="button" data-toggle="clearDate"><i class="fa-solid fa-xmark text-danger"></i></button>
                    </div>
                    <div class="invalid-feedback">{$LANG->getModule('lev_expired_error')}</div>
                    <div class="form-text">{$LANG->getModule('lev_expired_note')}</div>
                </div>
            </div>
            {if $ADMIN_INFO.level eq 1}
            <div class="row mb-3" id="after_exp_action"{if $LEV eq 3 or empty($LEV_EXPIRED)} style="display: none;"{/if}>
                <div class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('after_exp_action')}</div>
                <div class="col-12 col-sm-9">
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" name="downgrade_to_modadmin" value="1" id="downgrade_to_modadmin"{if $DOWNGRADE_TO_MODADMIN} checked{/if}>
                        <label class="form-check-label" for="downgrade_to_modadmin">{$LANG->getModule('downgrade_to_modadmin')} ({$LANG->getModule('downgrade_to_modadmin_note')})</label>
                    </div>
                    <div id="modslist2"{if not $DOWNGRADE_TO_MODADMIN} style="display:none;"{/if}>
                        <div class="mt-2">{$LANG->getModule('if_level3_selected')}</div>
                        {foreach from=$ALLMODS key=keylang item=mods}
                        <div class="mt-2 border border-primary p-3 rounded-2" data-toggle="checklist">
                            <div class="d-flex align-items-center flex-wrap mb-2">
                                <div class="fw-medium fs-5 my-1 me-3">{$LANGUAGE_ARRAY[$keylang].name}</div>
                                <button type="button" class="btn my-1 btn-sm btn-secondary me-2" data-toggle="checkall" data-check-value="true"><i class="fa-solid fa-square-check"></i> {$LANG->getModule('checkall')}</button>
                                <button type="button" class="btn my-1 btn-sm btn-default" data-toggle="checkall" data-check-value="false"><i class="fa-solid fa-square"></i> {$LANG->getModule('uncheckall')}</button>
                            </div>
                            <div class="row">
                                {foreach from=$mods key=mod item=modinfo}
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input data-toggle="checkitem" class="form-check-input" type="checkbox" id="after_modules_{$keylang}_{$mod}"{if !empty($AFTER_MODULES[$keylang]) and in_array($mod, $AFTER_MODULES[$keylang])} checked{elseif $LEV eq 3 and !empty($MODULES[$keylang]) and in_array($mod, $MODULES[$keylang])} checked{/if} name="after_modules[{$keylang}][]" value="{$mod}">
                                        <label class="form-check-label" for="after_modules_{$keylang}_{$mod}">{$mod} ({$modinfo.custom_title})</label>
                                    </div>
                                </div>
                                {/foreach}
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            {/if}
            {/if}
            {/if}
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input name="save" id="save" type="hidden" value="1">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
