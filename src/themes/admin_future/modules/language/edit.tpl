<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<div class="card mb-3">
    <div class="card-body">
        {$LANG->getModule('nv_lang_note_edit')} <code>{$smarty.const.ALLOWED_HTML_LANG}</code>
    </div>
</div>
<form method="post" id="lang-edit-form" action="{$FORM_ACTION}" novalidate>
    <input type="hidden" name="savedata" value="{$smarty.const.NV_CHECK_SESSION}">
    <input type="hidden" name="pozauthor[langtype]" data-key="langtype" value="{$TRANSLATOR.langtype}">
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
        <div class="card-header fs-5 fw-medium">
            {$LANG->getModule('nv_lang_module')}: {$EDIT_MODULE}, {$LANG->getModule('nv_lang_area')}: {$MODULE_AREA}
        </div>
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table table-striped align-middle table-sticky mb-1">
                    <colgroup>
                        <col style="width: 1%;"/>
                        <col style="width: 30%;"/>
                        <col/>
                        <col style="width: 1%;"/>
                        <col style="width: 1%;"/>
                    </colgroup>
                    <thead class="text-muted">
                        <tr>
                            <th class="text-center text-nowrap">{$LANG->getModule('nv_lang_nb')}</th>
                            <th class="text-nowrap">{$LANG->getModule('nv_lang_key')}</th>
                            <th class="text-nowrap" colspan="3">{$LANG->getModule('nv_lang_value')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$TRANSLATOR key=key item=value}
                        {if $key neq 'langtype'}
                        <tr>
                            <td>&nbsp;</td>
                            <td>{$key}</td>
                            <td colspan="3">
                                <input type="text" value="{$value|strencode}" data-key="{$key}" name="pozauthor[{$key}]" data-sanitize-ignore="true" class="form-control">
                            </td>
                        </tr>
                        {/if}
                        {/foreach}
                    </tbody>
                    <tbody class="counter" id="sortable">
                        {foreach from=$ARRAY item=row}
                        <tr class="item">
                            <td class="text-center text-nowrap">
                                <input type="hidden" name="langid[]" value="{$row.id}">
                                <i class="fa-solid fa-sort"></i>
                            </td>
                            <td>
                                <input type="text" value="{$row.lang_key}" name="langkey[]" class="form-control alphanumeric" maxlength="50" data-duplicate-error="{$LANG->getModule('key_is_duplicate')}" data-empty-error="{$LANG->getModule('field_is_required')}">
                                <span class="invalid-feedback"></span>
                            </td>
                            <td>
                                <input type="text" value="{$row.value}" name="langvalue[]" data-sanitize-ignore="true" class="form-control">
                            </td>
                            <td class="text-nowrap text-end">
                                <input type="hidden" name="isdel[]" value="0">
                                <div class="form-check delitem">
                                    <input class="form-check-input del-item" type="checkbox" id="isdel{$row.id}">
                                    <label class="form-check-label" for="isdel{$row.id}">{$LANG->getGlobal('delete')}</label>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm del-new d-none"><i class="fa-solid fa-xmark text-danger"></i></button>
                            </td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-secondary btn-sm add-new"><i class="fa-solid fa-plus"></i></button>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <div class="hstack gap-2">
                                    {if $ALLOWED_WRITE}
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="write" value="1" id="element_write">
                                        <label class="form-check-label" for="element_write">{$LANG->getModule('nv_admin_write')}</label>
                                    </div>
                                    {/if}
                                    <button type="submit" class="btn btn-primary">{$LANG->getModule('nv_admin_edit_save')}</button>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</form>
