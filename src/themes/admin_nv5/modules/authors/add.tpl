<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off" id="addadmin">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="add_userid">{$LANG->get('add_user')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="add_userid" name="userid" value="{$USERID}">
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">
                            <button class="btn btn-secondary btn-input-sm" type="button" onclick="open_browse_us();"><i class="icon icon-left fas fa-mouse-pointer"></i> {$LANG->get('add_select')}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="adm_position">{$LANG->get('position')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="adm_position" name="position" value="{$POSITION}">
                    <span class="form-text text-muted">{$LANG->get('position_info')}</span>
                </div>
            </div>
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
            {if not empty($EDITORS)}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="admin_editor">{$LANG->get('editor')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="admin_editor" name="editor">
                        <option value="">{$LANG->get('not_use')}</option>
                        {foreach from=$EDITORS key=key item=editor}
                        <option value="{$editor}"{if $editor eq $EDITOR} selected="selected"{/if}>{$editor}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}
            {if not empty($FILE_ALLOWED_EXT)}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('allow_files_type')}</label>
                <div class="col-12 col-sm-10 col-lg-8 mt-1">
                    <div class="row">
                        {foreach from=$FILE_ALLOWED_EXT item=filetype}
                        <div class="col-12 col-sm-6">
                            <label class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="allow_files_type[]" value="{$filetype}"{if in_array($filetype, $ALLOW_FILES_TYPE)} checked="checked"{/if}><span class="custom-control-label">{$filetype}</span>
                            </label>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            {/if}
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline my-0">
                        <input class="custom-control-input" type="checkbox" name="allow_modify_files" value="1"{if $ALLOW_MODIFY_FILES} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('allow_modify_files')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline my-0">
                        <input class="custom-control-input" type="checkbox" name="allow_create_subdirectories" value="1"{if $ALLOW_CREATE_SUBDIRECTORIES} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('allow_create_subdirectories')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline my-0">
                        <input class="custom-control-input" type="checkbox" name="allow_modify_subdirectories" value="1"{if $ALLOW_MODIFY_SUBDIRECTORIES} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('allow_modify_subdirectories')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row pt-1 pb-1 mt-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('lev')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    {if $SHOW_LEV2}
                    <label class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="lev" value="2" onclick="nv_show_hidden('modslist', 0);"{if $LEV eq 2} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('level2')}</span>
                    </label>
                    {/if}
                    <label class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="lev" value="3" onclick="nv_show_hidden('modslist', 1);"{if $LEV eq 3} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('level3')}</span>
                    </label>
                    <div id="modslist"{if $LEV neq 3} style="display: none;"{/if}>
                        <p>
                            <strong>{$LANG->get('if_level3_selected')}:</strong> <br>
                            <a id="checkall" href="javascript:void(0);"><i class="fas fa-check-square"></i> {$LANG->get('checkall')}</a>
                            <a id="uncheckall" href="javascript:void(0);" class="ml-4"><i class="far fa-square"></i> {$LANG->get('uncheckall')}</a>
                        </p>
                        <div class="row">
                            {foreach from=$MODS key=mod item=value}
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
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input name="save" type="hidden" value="1">
                    <button class="btn btn-space btn-primary" type="submit" name="go_add">{$LANG->get('nv_admin_add')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
function open_browse_us() {
    nv_open_browse('{NV_BASE_ADMINURL}index.php?' + nv_name_variable + '=users&' + nv_fc_variable + '=getuserid&area=add_userid&return=username&filtersql={$FILTERSQL}', 'NVImg', 850, 500, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');
}

$(document).ready(function() {
    $("form#addadmin").submit(function() {
        a = $(this).serialize();
        var b = $(this).attr("action");
        $("[type=submit]").attr("disabled", "disabled");
        $.ajax({
            type : "POST",
            url : b,
            data : a,
            success : function(c) {
                if (c == "OK") {
                    window.location = '{$RESULT_URL}';
                } else {
                    $.gritter.add({
                        title: "{$LANG->get('error')}",
                        text: c,
                        class_name: "color danger"
                    });
                }
                $("[type=submit]").removeAttr("disabled")
            }
        });
        return !1;
    });
});
</script>
