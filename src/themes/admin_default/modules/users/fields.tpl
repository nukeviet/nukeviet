<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>

<div id="module_show_list"></div>

<form class="ajax-submit" method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" autocomplete="off" novalidate>
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <input type="hidden" name="system" value="{$DATAFORM.system}">
    <input type="hidden" name="fid" value="{$DATAFORM.fid}">
    <input type="hidden" name="fieldid" value="{$DATAFORM.field}">

    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
        <div class="card-header fs-5 fw-medium">{$CAPTIONFORM}</div>
        <div class="card-body">
            {if $DATAFORM.fid eq 0}
            <div class="row mb-3" id="row_field_id">
                <label for="field_id" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_id')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control" type="text" value="{$DATAFORM.field}" name="field" id="field_id" maxlength="50" required autocomplete="off"
                        pattern="[a-zA-Z][a-zA-Z0-9_]*" title="{$LANG->getModule('field_error_start')}">
                    <div class="form-text">{$LANG->getModule('field_id_note')}. {$LANG->getModule('field_error_start')}</div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            {/if}

            <div class="row mb-3" id="row_field_title">
                <label for="field_title" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_title')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control" type="text" value="{$DATAFORM.title}" name="title" id="field_title" required autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="field_description" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_description')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <textarea cols="60" rows="3" name="description" id="field_description" class="form-control" autocomplete="off">{$DATAFORM.description}</textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('for_admin')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="for_admin" value="1" id="for_admin"{if $DATAFORM.for_admin} checked{/if}>
                    </div>
                </div>
            </div>

            <div class="row mb-3{if $IS_HIDDEN} d-none{/if}" id="row_required">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('field_required')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="required" value="1" id="required"{if $DATAFORM.required} checked{/if}{if $IS_HIDDEN} disabled{/if}>
                    </div>
                    <div class="form-text">{$LANG->getModule('field_required_note')}</div>
                </div>
            </div>

            <div class="row mb-3{if $IS_HIDDEN} d-none{/if}" id="row_show_register">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('field_show_register')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="show_register" value="1" id="show_register"{if $DATAFORM.show_register} checked{/if}{if $IS_HIDDEN} disabled{/if}>
                    </div>
                </div>
            </div>

            <div class="row mb-3{if $IS_HIDDEN} d-none{/if}" id="row_user_editable">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('field_user_editable')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="user_editable" value="1" id="user_editable"{if $DATAFORM.user_editable} checked{/if}{if $IS_HIDDEN} disabled{/if}>
                    </div>
                </div>
            </div>

            <div class="row mb-3{if $IS_HIDDEN} d-none{/if}" id="row_show_profile">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('field_show_profile')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="show_profile" value="1" id="show_profile"{if $DATAFORM.show_profile} checked{/if}{if $IS_HIDDEN} disabled{/if}>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('field_type')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    {if $SHOW_FIELD_TYPE_SELECT}
                    <div class="mb-2">
                        {foreach from=$FIELD_TYPE_LIST item=ft}
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="field_type" value="{$ft.key}" id="f_{$ft.key}"{if $ft.checked} checked{/if}>
                            <label class="form-check-label" for="f_{$ft.key}">{$ft.value}</label>
                        </div>
                        {/foreach}
                    </div>
                    <div class="form-text">{$LANG->getModule('field_type_note')}</div>
                    {else}
                    {$FIELD_TYPE_TEXT}
                    {/if}
                </div>
            </div>

            <div class="row mb-3{if $DATAFORM.classdisabled} d-none{/if}" id="classfields">
                <label for="field_class" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_class')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control" type="text" value="{$DATAFORM.class}" name="class" id="field_class" maxlength="50" autocomplete="off">
                </div>
            </div>

            <div class="row mb-3{if $DATAFORM.editordisabled} d-none{/if}" id="editorfields">
                <label for="editor_width" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_size')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="row g-2">
                        <div class="col-auto">
                            <label for="editor_width" class="col-form-label">Width:</label>
                        </div>
                        <div class="col-auto">
                            <input class="form-control" style="width: 100px;" type="text" value="{$DATAFORM.editor_width ?? '100%'}" name="editor_width" id="editor_width" maxlength="5" autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <label for="editor_height" class="col-form-label">Height:</label>
                        </div>
                        <div class="col-auto">
                            <input class="form-control" style="width: 100px;" type="text" value="{$DATAFORM.editor_height ?? '100px'}" name="editor_height" id="editor_height" maxlength="5" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {* Textbox/Textarea/Editor Options *}
    <div class="card border-secondary border-3 border-bottom-0 border-start-0 border-end-0 mb-4{if not $DATAFORM.display_textfields} d-none{/if}" id="textfields">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('field_options_text')}</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('field_match_type')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    {foreach from=$MATCH_TYPE_LIST item=mt}
                    <div class="form-check" id="li_{$mt.key}">
                        <input class="form-check-input" type="radio" name="match_type" value="{$mt.key}" id="m_{$mt.key}"{if $mt.checked} checked{/if}>
                        <label class="form-check-label" for="m_{$mt.key}">{$mt.value}</label>
                        {if $mt.has_input}
                        <input class="form-control mt-1" type="text" value="{$mt.match_value}" name="match_{$mt.key}"{if not $mt.checked} disabled{/if} autocomplete="off">
                        {/if}
                        {if $mt.key eq 'callback'}
                        <div class="form-text">
                            {$LANG->getModule('field_match_type_callback_note')}.
                            <a href="#" data-toggle="modalShowByObj" data-obj="#li_{$mt.key}_view">{$LANG->getModule('field_match_type_callback_view')}</a>
                            <div class="d-none" id="li_{$mt.key}_view" title="{$LANG->getModule('field_match_type_callback_list')}">
                                {if empty($CALLBACK_FUNCTION_LIST)}
                                <div class="alert alert-warning mb-0" role="alert">{$LANG->getModule('field_match_type_callback_list_empty')}</div>
                                {else}
                                <div class="row">
                                    {foreach from=$CALLBACK_FUNCTION_LIST item=func}
                                    <div class="col-6 text-break">
                                        <code>{$func}</code>
                                    </div>
                                    {/foreach}
                                </div>
                                {/if}
                            </div>
                        </div>
                        {/if}
                    </div>
                    {/foreach}
                </div>
            </div>

            <div class="row mb-3">
                <label for="default_value" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_default_value')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control" maxlength="255" type="text" value="{$DATAFORM.default_value}" name="default_value" id="default_value" autocomplete="off">
                </div>
            </div>

            <div class="row mb-3" id="max_length">
                <label for="min_length" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_min_length')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="row g-2">
                        <div class="col-auto">
                            <input class="form-control" style="width: 100px;" type="text" value="{$DATAFORM.min_length}" name="min_length" id="min_length" autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <label for="max_length_input" class="col-form-label">{$LANG->getModule('field_max_length')}</label>
                        </div>
                        <div class="col-auto">
                            <input class="form-control" style="width: 100px;" type="text" value="{$DATAFORM.max_length}" name="max_length" id="max_length_input" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {* Number Options *}
    <div class="card border-secondary border-3 border-bottom-0 border-start-0 border-end-0 mb-4{if not $DATAFORM.display_numberfields} d-none{/if}" id="numberfields">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('field_options_number')}</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('field_number_type')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="number_type" value="1" id="number_type_1"{if ($DATAFORM.number_type ?? 1) eq 1} checked{/if}>
                        <label class="form-check-label" for="number_type_1">{$LANG->getModule('field_integer')}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="number_type" value="2" id="number_type_2"{if ($DATAFORM.number_type ?? 1) eq 2} checked{/if}>
                        <label class="form-check-label" for="number_type_2">{$LANG->getModule('field_real')}</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="default_value_number" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_default_value')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input class="form-control" maxlength="255" type="text" value="{$DATAFORM.default_value_number}" name="default_value_number" id="default_value_number" required autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="min_number_length" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_min_value')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="row g-2">
                        <div class="col-auto">
                            <input class="form-control" style="width: 120px;" type="text" value="{$DATAFORM.min_number ?? '0'}" name="min_number_length" id="min_number_length" maxlength="11" required autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <label for="max_number_length" class="col-form-label">{$LANG->getModule('field_max_value')}</label>
                        </div>
                        <div class="col-auto">
                            <input class="form-control" style="width: 120px;" type="text" value="{$DATAFORM.max_number ?? '1000'}" name="max_number_length" id="max_number_length" maxlength="11" required autocomplete="off">
                        </div>
                    </div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>
    </div>

    {* Date Options *}
    <div class="card border-secondary border-3 border-bottom-0 border-start-0 border-end-0 mb-4{if not $DATAFORM.display_datefields} d-none{/if}" id="datefields">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('field_options_date')}</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('field_default_value')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="current_date" value="1" id="current_date_1"{if ($DATAFORM.current_date ?? 0) eq 1} checked{/if}>
                        <label class="form-check-label" for="current_date_1">{$LANG->getModule('field_current_date')}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="current_date" value="0" id="current_date_0"{if ($DATAFORM.current_date ?? 0) eq 0} checked{/if}>
                        <label class="form-check-label" for="current_date_0">{$LANG->getModule('field_default_date')}</label>
                        <input class="form-control mt-1 datepicker" style="width:120px" type="text" value="{$DATAFORM.default_date ?? ''}" name="default_date" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="min_date" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_min_date')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="row g-2">
                        <div class="col-auto">
                            <input class="form-control datepicker" style="width:120px" type="text" value="{$DATAFORM.min_date ?? ''}" name="min_date" id="min_date" maxlength="10" autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <label for="max_date" class="col-form-label">{$LANG->getModule('field_max_date')}</label>
                        </div>
                        <div class="col-auto">
                            <input class="form-control datepicker" style="width:120px" type="text" value="{$DATAFORM.max_date ?? ''}" name="max_date" id="max_date" maxlength="10" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {* Choice Type Selection *}
    <div class="card border-secondary border-3 border-bottom-0 border-start-0 border-end-0 mb-4{if not $DATAFORM.display_choicetypes} d-none{/if}" id="choicetypes">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('field_choicetypes_title')}</div>
        <div class="card-body">
            {if $SHOW_CHOICE_TYPES_SELECT}
            <select class="form-select" name="choicetypes" style="max-width: 300px;">
                {foreach from=$CHOICE_TYPE_LIST item=ct}
                <option value="{$ct.key}">{$ct.value}</option>
                {/foreach}
            </select>
            {else}
            {$FIELD_TYPE_SQL}
            <input type="hidden" name="choicetypes" value="{$CHOICETYPES_HIDDEN_VALUE}">
            {/if}
        </div>
    </div>

    {* SQL Choice Options *}
    <div class="card border-secondary border-3 border-bottom-0 border-start-0 border-end-0 mb-4{if not $DATAFORM.display_choicesql} d-none{/if}" id="choicesql">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('field_options_choicesql')}</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('field_options_choicesql_module')}</th>
                            <th class="text-nowrap" style="width: 30%;">{$LANG->getModule('field_options_choicesql_table')}</th>
                            <th class="text-nowrap" style="width: 50%;">{$LANG->getModule('field_options_choicesql_column')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span id="choicesql_module">&nbsp;</span></td>
                            <td><span id="choicesql_table">&nbsp;</span></td>
                            <td><span id="choicesql_column">&nbsp;</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {* Text Choice Options *}
    <div class="card border-secondary border-3 border-bottom-0 border-start-0 border-end-0 mb-4{if not $DATAFORM.display_choiceitems} d-none{/if}" id="choiceitems">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('field_options_choice')}</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" id="choiceitems_table">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap">{$LANG->getModule('field_number')}</th>
                            <th class="text-center text-nowrap">{$LANG->getModule('field_value')}</th>
                            <th class="text-center text-nowrap">{$LANG->getModule('field_text')} (*)</th>
                            <th class="text-center text-nowrap">{$LANG->getModule('field_default_value')}</th>
                        </tr>
                    </thead>
                    <tbody class="uncheckRadio">
                        {foreach from=$FIELD_CHOICES_LIST item=fc}
                        <tr class="text-center">
                            <td>{$fc.number}</td>
                            <td><input class="form-control" type="text" value="{$fc.key}" name="field_choice[{$fc.number}]" placeholder="{$LANG->getModule('field_match_type_alphanumeric')}"{if $fc.readonly} readonly{/if} data-field-choice></td>
                            <td><input class="form-control" type="text" value="{$fc.value}" name="field_choice_text[{$fc.number}]"{if $fc.readonly} readonly{/if}></td>
                            <td><input class="form-check-input" type="radio" name="default_value_choice" value="{$fc.number}"{if $fc.checked} checked{/if}></td>
                        </tr>
                        {/foreach}
                    </tbody>
                    {if $ADD_FIELD_CHOICE}
                    <tfoot>
                        <tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-secondary" id="add_field_choice">{$LANG->getModule('field_add_choice')}</button>
                                <div class="form-text mt-2">(*) {$LANG->getModule('value_empty_note')}</div>
                            </td>
                        </tr>
                    </tfoot>
                    {/if}
                </table>
            </div>
        </div>
    </div>

    {* File Options *}
    <div class="card border-secondary border-3 border-bottom-0 border-start-0 border-end-0 mb-4{if not $DATAFORM.display_filefields} d-none{/if}" id="filefields">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('field_options_file')}</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('field_file_exts')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-8">
                    <div class="row g-3">
                        {foreach from=$FILETYPE_LIST item=ft}
                        <div class="col-md-6 col-lg-4 filetype">
                            <input type="checkbox" class="d-none" name="filetype[]" value="{$ft.key}"{if $ft.checked} checked{/if}>
                            <p class="fw-bold">{$ft.key}</p>
                            <div class="d-flex flex-wrap gap-1">
                                {foreach from=$ft.mimes item=mime}
                                <label class="d-inline-flex gap-1 align-items-center btn btn-sm btn-outline-secondary filemime">
                                    <input type="checkbox" data-toggle="mimecheck" name="mime[]" value="{$mime.key}"{if $mime.checked} checked{/if}> {$mime.key}
                                </label>
                                {/foreach}
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="file_max_size" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_file_max_size')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select name="file_max_size" class="form-select" id="file_max_size" style="max-width: 200px;">
                        {foreach from=$SIZE_LIST item=size}
                        <option value="{$size.key}"{if $size.sel} selected{/if}>{$size.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="maxnum" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('field_file_maxnum')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select name="maxnum" class="form-select" id="maxnum" style="max-width: 120px;">
                        {foreach from=$MAXNUM_LIST item=mn}
                        <option value="{$mn.key}"{if $mn.sel} selected{/if}>{$mn.key}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="row mb-3 photo_max_size{if not in_array('images', $DATAFILE.filetype)} d-none{/if}">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('field_photo_max_size')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-8">
                    <div class="mb-3">
                        <div class="form-label fw-medium">{$LANG->getModule('field_photo_width')}</div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <div class="input-group" style="width: 120px;">
                                <span class="input-group-text">=</span>
                                <input type="text" class="form-control" name="widthlimit[equal]" value="{$DATAFILE.widthlimit.equal}" maxlength="4" autocomplete="off">
                            </div>
                            <div class="input-group" style="width: 120px;">
                                <span class="input-group-text">≥</span>
                                <input type="text" class="form-control" name="widthlimit[greater]" value="{$DATAFILE.widthlimit.greater}" maxlength="4" autocomplete="off">
                            </div>
                            <div class="input-group" style="width: 120px;">
                                <span class="input-group-text">≤</span>
                                <input type="text" class="form-control" name="widthlimit[less]" value="{$DATAFILE.widthlimit.less}" maxlength="4" autocomplete="off">
                            </div>
                            <span>px</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-label fw-medium">{$LANG->getModule('field_photo_height')}</div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <div class="input-group" style="width: 120px;">
                                <span class="input-group-text">=</span>
                                <input type="text" class="form-control" name="heightlimit[equal]" value="{$DATAFILE.heightlimit.equal}" maxlength="4" autocomplete="off">
                            </div>
                            <div class="input-group" style="width: 120px;">
                                <span class="input-group-text">≥</span>
                                <input type="text" class="form-control" name="heightlimit[greater]" value="{$DATAFILE.heightlimit.greater}" maxlength="4" autocomplete="off">
                            </div>
                            <div class="input-group" style="width: 120px;">
                                <span class="input-group-text">≤</span>
                                <input type="text" class="form-control" name="heightlimit[less]" value="{$DATAFILE.heightlimit.less}" maxlength="4" autocomplete="off">
                            </div>
                            <span>px</span>
                        </div>
                    </div>
                    <div class="form-text">{$LANG->getModule('field_photo_max_size_note')}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
    </div>
</form>

{if $LOAD_SQLCHOICE}
<input type="hidden" id="sql_data_choice" data-module="{$SQL_DATA_CHOICE[0]}" data-table="{$SQL_DATA_CHOICE[1]}" data-column-key="{$SQL_DATA_CHOICE[2]}" data-column-val="{$SQL_DATA_CHOICE[3]}" data-column-order="{$SQL_DATA_CHOICE[4]}" data-column-sort="{$SQL_DATA_CHOICE[5]}">
{/if}
