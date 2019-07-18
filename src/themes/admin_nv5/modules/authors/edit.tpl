{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{else}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('nv_admin_edit_info', $DATA.username)}</div>
</div>
{/if}
<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$DATA.action}" autocomplete="off">
            {if isset($DATA.position)}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="adm_position">{$LANG->get('position')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="adm_position" name="position" value="{$DATA.position}">
                    <span class="form-text text-muted">{$LANG->get('position_info')}</span>
                </div>
            </div>
            {/if}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="admin_theme">{$LANG->get('themeadmin')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="admin_theme" name="admin_theme">
                        <option value="">{$LANG->get('theme_default')}</option>
                        {foreach from=$ADMINTHEMES key=key item=theme}
                        <option value="{$theme}"{if $theme eq $ADMIN_THEME} selected="selected"{/if}>{$theme}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {if isset($DATA.editor)}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="admin_editor">{$LANG->get('editor')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="admin_editor" name="editor">
                        <option value="">{$LANG->get('not_use')}</option>
                        {foreach from=$DATA.editor.0 key=key item=editor}
                        <option value="{$editor}"{if $editor eq $DATA.editor.1} selected="selected"{/if}>{$editor}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="main_module">{$LANG->get('main_module')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="main_module" name="main_module">
                        {foreach from=$ARRAY_MODULE key=key item=module}
                        <option value="{$module.module}"{if $module.module eq $MAIN_MODULE} selected="selected"{/if}>{$module.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {if isset($DATA.allow_files_type)}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('allow_files_type')}</label>
                <div class="col-12 col-sm-10 col-lg-8 mt-1">
                    <div class="row">
                        {foreach from=$DATA.allow_files_type.0 item=filetype}
                        <div class="col-12 col-sm-6">
                            <label class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="allow_files_type[]" value="{$filetype}"{if in_array($filetype, $DATA.allow_files_type.1)} checked="checked"{/if}><span class="custom-control-label">{$filetype}</span>
                            </label>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            {/if}
            {if isset($DATA.allow_modify_files)}
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline my-0">
                        <input class="custom-control-input" type="checkbox" name="allow_modify_files" value="1"{if $DATA.allow_modify_files} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('allow_modify_files')}</span>
                    </label>
                </div>
            </div>
            {/if}
            {if isset($DATA.allow_create_subdirectories)}
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline my-0">
                        <input class="custom-control-input" type="checkbox" name="allow_create_subdirectories" value="1"{if $DATA.allow_create_subdirectories} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('allow_create_subdirectories')}</span>
                    </label>
                </div>
            </div>
            {/if}
            {if isset($DATA.allow_modify_subdirectories)}
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline my-0">
                        <input class="custom-control-input" type="checkbox" name="allow_modify_subdirectories" value="1"{if $DATA.allow_modify_subdirectories} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('allow_modify_subdirectories')}</span>
                    </label>
                </div>
            </div>
            {/if}
            {if isset($DATA.lev)}
            <div class="form-group row pt-1 pb-1 mt-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('lev')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    {if isset($DATA.lev.1)}
                    <label class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="lev" value="2" onclick="nv_show_hidden('modslist', 0);"{if $DATA.lev.1 eq 2} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('level2')}</span>
                    </label>
                    <label class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="lev" value="3" onclick="nv_show_hidden('modslist', 1);"{if $DATA.lev.1 eq 3} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('level3')}</span>
                    </label>
                    {/if}
                    <div id="modslist"{if $DATA.lev.1 neq 3} style="display: none;"{/if}>
                        <p>
                            <strong>{$LANG->get('if_level3_selected')}:</strong> <br>
                            <a id="checkall" href="javascript:void(0);"><i class="fas fa-check-square"></i> {$LANG->get('checkall')}</a>
                            <a id="uncheckall" href="javascript:void(0);" class="ml-4"><i class="far fa-square"></i> {$LANG->get('uncheckall')}</a>
                        </p>
                        <div class="row">
                            {foreach from=$DATA.lev.0 key=mod item=value}
                            <div class="col-12 col-sm-6">
                                <label class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="modules[]" value="{$mod}"{if not empty($value.checked)} checked="checked"{/if}><span class="custom-control-label">{$value.custom_title}</span>
                                </label>
                            </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
            {/if}
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input name="save" type="hidden" value="1">
                    <button class="btn btn-space btn-primary" type="submit" name="go_edit">{$LANG->get('save')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
