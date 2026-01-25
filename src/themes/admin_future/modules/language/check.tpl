{if empty($LANG_EMPTY)}
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body pt-4">
        <form method="get" action="{$smarty.const.NV_BASE_ADMINURL}index.php" id="form-checklang">
            <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
            <div class="row mb-3">
                <label for="element_typelang" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('language_to_check')}</label>
                <div class="col-sm-8 col-lg-5 col-xxl-4">
                    <select class="form-select" id="element_typelang" name="typelang">
                        <option value=""></option>
                        {foreach from=$LANGUAGE_ARRAY key=key item=value}
                        {if in_array($key, $LANG_EXIT, true)}
                        <option value="{$key}"{if $key eq $TYPELANG} selected{/if}{if $key eq $SOURCELANG} disabled{/if}>{$value.name}</option>
                        {/if}
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_sourcelang" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('nv_lang_data_source')}</label>
                <div class="col-sm-8 col-lg-5 col-xxl-4">
                    <select class="form-select" id="element_sourcelang" name="sourcelang">
                        {foreach from=$LANGUAGE_ARRAY_SOURCE item=key}
                        {if in_array($key, $LANG_EXIT, true)}
                        <option value="{$key}"{if $key eq $SOURCELANG} selected{/if}>{$LANGUAGE_ARRAY[$key].name}</option>
                        {/if}
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_idfile" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('nv_lang_area')}</label>
                <div class="col-sm-8 col-lg-5 col-xxl-4">
                    <select class="form-select select2" id="element_idfile" name="idfile">
                        <option value="0">{$LANG->getModule('nv_lang_checkallarea')}</option>
                        {foreach from=$ARRAY_FILES key=key item=value}
                        <option value="{$key}"{if $key eq $IDFILE} selected{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_check_type" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('nv_check_type')}</label>
                <div class="col-sm-8 col-lg-5 col-xxl-4">
                    <select class="form-select" id="element_check_type" name="check_type">
                        {for $ctype=0 to 2}
                        <option value="{$ctype}"{if $ctype eq $CHECK_TYPE} selected{/if}>{$LANG->getModule("nv_check_type_`$ctype`")}</option>
                        {/for}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <input type="hidden" name="save" value="1">
                    <button type="submit" class="btn btn-primary"{if empty($TYPELANG)} disabled{/if}>{$LANG->getModule('nv_admin_submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
{if $IS_SUBMIT}
{if not empty($ARRAY_LANG_DATA)}
<form method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="typelang" value="{$TYPELANG}">
    <input type="hidden" name="sourcelang" value="{$SOURCELANG}">
    <input type="hidden" name="check_type" value="{$CHECK_TYPE}">
    <input type="hidden" name="idfile" value="{$IDFILE}">
    <input type="hidden" name="savedata" value="{$smarty.const.NV_CHECK_SESSION}">
    <div class="row mt-4 g-2">
        {foreach from=$ARRAY_LANG_DATA key=idfile_i item=file_data}
        <div class="col-{if count($ARRAY_LANG_DATA) eq 1}12{else}xxl-6{/if}">
            <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
                <div class="card-header fs-5 fw-medium text-primary">{$ARRAY_FILES[$idfile_i]}</div>
                <div class="card-body">
                    <div class="table-responsive-lg table-card">
                        <table class="table table-striped align-middle mb-0{if count($ARRAY_LANG_DATA) eq 1} table-sticky{/if}">
                            <thead class="text-muted">
                                <tr>
                                    <th class="text-nowrap fw-50">{$LANG->getModule('nv_lang_nb')}</th>
                                    <th class="text-nowrap fw-200">{$LANG->getModule('nv_lang_key')}</th>
                                    <th class="text-nowrap">{$LANG->getModule('nv_lang_value')}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {assign var="stt" value=0 nocache}
                                {foreach from=$file_data key=id item=row}
                                {assign var="stt" value=($stt + 1) nocache}
                                <tr>
                                    <td>{$stt}</td>
                                    <td class="text-break">{$row.lang_key}</td>
                                    <td>
                                        <p class="mb-1"><code>{$row.sourcelang}</code></p>
                                        <textarea rows="1" name="pozlang[{$id}]" class="form-control" data-sanitize-ignore="true">{$row.datalang}</textarea>
                                    </td>
                                </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {/foreach}
    </div>
    <div class="card mt-2">
        <div class="card-body text-center p-3">
            <button type="submit" class="btn btn-primary">{$LANG->getModule('nv_admin_edit_save')}</button>
        </div>
    </div>
</form>
{else}
<div class="alert alert-info mt-4" role="alert">{$LANG->getModule('nv_lang_check_no_data')}</div>
{/if}
{/if}
{else}
<div class="alert alert-info" role="alert">{$LANG_EMPTY}</div>
{/if}
