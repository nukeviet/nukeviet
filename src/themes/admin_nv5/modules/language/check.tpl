<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.css">
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>
<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form action="{$NV_BASE_ADMINURL}index.php" method="get">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="typelang">{$LANG->get('nv_lang_data')}:</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="typelang" name="typelang">
                        <option value="">--</option>
                        {foreach from=$LANGUAGE_ARRAY key=lkey item=lvalue}
                        {if in_array($lkey, $ARRAY_LANG_EXIT)}
                        <option value="{$lkey}"{if $lkey eq $TYPELANG} selected="selected"{/if}>{$lvalue.name}</option>
                        {/if}
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="sourcelang">{$LANG->get('nv_lang_data_source')}:</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="sourcelang" name="sourcelang">
                        <option value="">--</option>
                        {foreach from=$LANGUAGE_ARRAY_SOURCE item=lkey}
                        {if in_array($lkey, $ARRAY_LANG_EXIT)}
                        <option value="{$lkey}"{if $lkey eq $SOURCELANG} selected="selected"{/if}>{$LANGUAGE_ARRAY[$lkey].name}</option>
                        {/if}
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="idfile">{$LANG->get('nv_lang_area')}:</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="idfile" name="idfile">
                        <option value="0">{$LANG->get('nv_lang_checkallarea')}</option>
                        {foreach from=$LANGUAGE_AREA item=row}
                        <option value="{$row.key}"{if $row.key eq $IDFILE} selected="selected"{/if}>{$row.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="check_type">{$LANG->get('nv_check_type')}:</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="check_type" name="check_type">
                        {foreach from=$LANGUAGE_CHECK_TYPE key=ckey item=cval}
                        <option value="{$ckey}"{if $ckey eq $CHECK_TYPE} selected="selected"{/if}>{$cval}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="hidden" name="submit" value="1">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('nv_admin_submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#idfile").select2({
        containerCssClass: "select2-sm"
    });
});
</script>
{if $IS_SUBMIT}
{if empty($ARRAY_LANG_DATA)}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('nv_lang_check_no_data')}</div>
</div>
{else}
<form action="{$NV_BASE_ADMINURL}index.php" method="post">
    <input type="hidden" name ="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
    <input type="hidden" name ="{$NV_OP_VARIABLE}" value="{$OP}">
    <input type="hidden" name ="submit" value="1">
    <input type="hidden" name ="typelang" value="{$TYPELANG}">
    <input type="hidden" name ="sourcelang" value="{$SOURCELANG}">
    <input type="hidden" name ="check_type" value="{$CHECK_TYPE}">
    <input type="hidden" name ="idfile" value="{$IDFILE}">
    <input type="hidden" name ="savedata" value="{$NV_CHECK_SESSION}">
    <div class="card card-table">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="text-center">{$LANG->get('nv_lang_nb')}</th>
                            <th style="width: 40%;" class="text-right">{$LANG->get('nv_lang_key')}</th>
                            <th style="width: 55%;">{$LANG->get('nv_lang_value')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {assign var="stt" value=1}
                        {foreach from=$ARRAY_LANG_DATA key=idfile_i item=array_lang_file}
                        <tr>
                            <td colspan="3"><i class="far fa-file-alt"></i> <strong>{$ARRAY_FILES[$idfile_i]}</strong></td>
                        </tr>
                        {foreach from=$array_lang_file key=id_i item=row_i}
                        <tr>
                            <td class="text-center">{$stt++}</td>
                            <td class="text-right">{$row_i.lang_key}</td>
                            <td class="text-left">
                                <textarea rows="1" name="pozlang[{$id_i}]" class="form-control">{$row_i.datalang|htmlspecialchars}</textarea>
                                <div class="form-text text-muted">{$row_i.sourcelang|htmlspecialchars}</div>
                            </td>
                        </tr>
                        {/foreach}
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <input type="submit" value="{$LANG->get('nv_admin_edit_save')}" class="btn btn-primary">
        </div>
    </div>
</form>
{/if}
{/if}
