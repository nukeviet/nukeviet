<div class="card mb-3 shadow-sm border-0 bg-body-tertiary">
    <div class="card-header bg-body py-3">
        <h5 class="mb-0 text-primary"><i class="fa-solid fa-gears me-2"></i>{$LANG->getModule('config_step1_title')}</h5>
    </div>
    <div class="card-body">
        <form action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="get" class="row g-3 align-items-end">
            <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
            <div class="col-md-5">
                <label for="target_module_sel" class="form-label fw-bold text-muted small uppercase">{$LANG->getModule('select_module')}</label>
                <select name="target_module" id="target_module_sel" class="form-select border-primary-subtle" onchange="this.form.submit()">
                    <option value="">--- {$LANG->getModule('select_module')} ---</option>
                    {foreach from=$MODULE_LIST key=mod_name item=mod_title}
                    <option value="{$mod_name}"{if $TARGET_MODULE eq $mod_name} selected{/if}>{$mod_title} ({$mod_name})</option>
                    {/foreach}
                </select>
            </div>
        </form>
    </div>
</div>

{if !empty($TARGET_MODULE)}
<form action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post" class="ajax-submit">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="target_module" value="{$TARGET_MODULE}">
    <input type="hidden" name="checkss" value="{$CHECKSS}">

    <input type="hidden" name="continue" id="continue_flag" value="0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">{$LANG->getModule('groups_list')}</h3>
        <button type="button" class="btn btn-success shadow-sm btn-add-group px-4">
            <i class="fa-solid fa-folder-plus me-2"></i>{$LANG->getModule('add_group')}
        </button>
    </div>

    <div id="groups_master_container">
        {foreach from=$DATA.groups key=g_idx item=group}
        <div class="group-container mb-5" data-gidx="{$g_idx}">
            <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-primary-subtle rounded-3 shadow-sm border-start border-4 border-primary group-header">
                <div class="d-flex align-items-center flex-grow-1 me-3">
                    <div class="group-handle cursor-move me-3 text-primary opacity-50"><i class="fa-solid fa-grip-vertical fs-3"></i></div>
                    <i class="fa-solid fa-layer-group me-3 fs-3 text-primary"></i>
                    <input type="text" name="groups[{$g_idx}][title]" value="{$group.title}" class="form-control form-control-lg border-0 bg-transparent fw-bold text-primary p-0" placeholder="Group Label Key" style="box-shadow: none;">
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-add-field" data-gidx="{$g_idx}">
                        <i class="fa-solid fa-plus me-1"></i>{$LANG->getModule('add_field')}
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-remove-group" title="Remove Group">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>

            <div class="row g-4 fields-container min-vh-10">
                {foreach from=$group.fields key=f_name item=field}
                <div class="col-12 col-md-6 col-xl-4 field-row">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition-all field-card">
                        <div class="card-header bg-body py-2 border-bottom d-flex justify-content-between align-items-center">
                            <div class="field-handle cursor-move me-2 text-muted"><i class="fa-solid fa-grip-vertical"></i></div>
                            <div class="d-flex align-items-center flex-grow-1 me-2 overflow-hidden">
                                <span class="badge bg-primary-subtle text-primary me-2">KEY</span>
                                <input type="text" name="groups[{$g_idx}][fields][{$f_name}][key]" value="{$f_name}" class="form-control form-control-sm border-0 fw-bold text-primary p-0 bg-transparent text-truncate" placeholder="Key">
                            </div>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-link link-danger p-0 btn-remove-field" title="Remove"><i class="fa-solid fa-circle-xmark fs-5"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted mb-1 d-flex justify-content-between">
                                    <span>{$LANG->getModule('ui_type')}</span>
                                    <i class="fa-solid fa-microchip text-info"></i>
                                </label>
                                <select name="groups[{$g_idx}][fields][{$f_name}][type]" class="form-select form-select-sm border-info-subtle config-type-selector">
                                    <option value="number"{if $field.type eq 'number'} selected{/if}>{$LANG->getModule('type_number')}</option>
                                    <option value="date"{if $field.type eq 'date'} selected{/if}>{$LANG->getModule('type_date')}</option>
                                    <option value="textbox"{if $field.type eq 'textbox'} selected{/if}>{$LANG->getModule('type_textbox')}</option>
                                    <option value="textarea"{if $field.type eq 'textarea'} selected{/if}>{$LANG->getModule('type_textarea')}</option>
                                    <option value="editor"{if $field.type eq 'editor'} selected{/if}>{$LANG->getModule('type_editor')}</option>
                                    <option value="selectbox"{if $field.type eq 'selectbox'} selected{/if}>{$LANG->getModule('type_selectbox')}</option>
                                    <option value="radio"{if $field.type eq 'radio'} selected{/if}>{$LANG->getModule('type_radio')}</option>
                                    <option value="checkbox"{if $field.type eq 'checkbox'} selected{/if}>{$LANG->getModule('type_checkbox')}</option>
                                    <option value="checkbox_single"{if $field.type eq 'checkbox_single'} selected{/if}>{$LANG->getModule('type_checkbox_single')}</option>
                                    <option value="multiselect"{if $field.type eq 'multiselect'} selected{/if}>{$LANG->getModule('type_multiselect')}</option>
                                    <option value="file"{if $field.type eq 'file'} selected{/if}>{$LANG->getModule('type_file')}</option>
                                </select>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted mb-1">{$LANG->getModule('label_key')}</label>
                                    <input type="text" name="groups[{$g_idx}][fields][{$f_name}][label]" value="{$field.label}" class="form-control form-control-sm" placeholder="Label key">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted mb-1">{$LANG->getModule('default')}</label>
                                    <div class="default-input-area">
                                        {if $field.type eq 'checkbox_single'}
                                            <div class="form-check form-switch p-0 ms-4">
                                                <input type="checkbox" name="groups[{$g_idx}][fields][{$f_name}][default]" value="1" {if $field.default eq '1'}checked{/if} class="form-check-input" style="cursor: pointer; width: 2.5em; height: 1.25em;">
                                            </div>
                                        {else}
                                            <input type="text" name="groups[{$g_idx}][fields][{$f_name}][default]" value="{$field.default}" class="form-control form-control-sm" placeholder="Default value">
                                        {/if}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-2 opacity-10">

                            <div class="type-attributes">
                                <div class="attr-group-number {if $field.type neq 'number'}d-none{/if}">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label x-small fw-bold text-muted mb-1">Min</label>
                                            <input type="text" name="groups[{$g_idx}][fields][{$f_name}][min]" value="{$field.min}" class="form-control form-control-sm" placeholder="Any">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label x-small fw-bold text-muted mb-1">Max</label>
                                            <input type="text" name="groups[{$g_idx}][fields][{$f_name}][max]" value="{$field.max}" class="form-control form-control-sm" placeholder="Any">
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-group-date {if $field.type neq 'date'}d-none{/if}">
                                    <label class="form-label x-small fw-bold text-muted mb-1">Display</label>
                                    <select name="groups[{$g_idx}][fields][{$f_name}][display]" class="form-select form-select-sm">
                                        <option value="datepicker"{if $field.display eq 'datepicker'} selected{/if}>Datepicker</option>
                                        <option value="datetimepicker"{if $field.display eq 'datetimepicker'} selected{/if}>Datetimepicker</option>
                                    </select>
                                </div>
                                <div class="attr-group-textbox attr-group-textarea {if $field.type neq 'textbox' and $field.type neq 'textarea'}d-none{/if}">
                                    <label class="form-label x-small fw-bold text-muted mb-1">Validation</label>
                                    <select name="groups[{$g_idx}][fields][{$f_name}][validate]" class="form-select form-select-sm">
                                        <option value="">-- None --</option>
                                        <option value="az09"{if $field.validate eq 'az09'} selected{/if}>{$LANG->getModule('validate_az09')}</option>
                                        <option value="name"{if $field.validate eq 'name'} selected{/if}>{$LANG->getModule('validate_name')}</option>
                                        <option value="email"{if $field.validate eq 'email'} selected{/if}>{$LANG->getModule('validate_email')}</option>
                                        <option value="url"{if $field.validate eq 'url'} selected{/if}>{$LANG->getModule('validate_url')}</option>
                                        <option value="regex"{if $field.validate eq 'regex'} selected{/if}>{$LANG->getModule('validate_regex')}</option>
                                        <option value="func"{if $field.validate eq 'func'} selected{/if}>{$LANG->getModule('validate_func')}</option>
                                    </select>
                                </div>
                                <div class="attr-group-choices {if !in_array($field.type, ['selectbox','radio','checkbox','multiselect'])}d-none{/if}">
                                    <div class="mb-2 btn-group w-100" role="group">
                                        <input type="radio" class="btn-check btn-toggle-source" name="groups[{$g_idx}][fields][{$f_name}][source]" id="source_{$g_idx}_{$f_name}_static" value="static" {if $field.source neq 'db'}checked{/if}>
                                        <label class="btn btn-outline-secondary btn-sm" for="source_{$g_idx}_{$f_name}_static">Static</label>

                                        <input type="radio" class="btn-check btn-toggle-source" name="groups[{$g_idx}][fields][{$f_name}][source]" id="source_{$g_idx}_{$f_name}_db" value="db" {if $field.source eq 'db'}checked{/if}>
                                        <label class="btn btn-outline-secondary btn-sm" for="source_{$g_idx}_{$f_name}_db">Database</label>
                                    </div>

                                    <div class="source-static-area {if $field.source eq 'db'}d-none{/if}">
                                        <table class="table table-sm table-borderless mb-2">
                                            <thead>
                                                <tr class="x-small text-muted">
                                                    <th style="width: 40px">STT</th>
                                                    <th>Key</th>
                                                    <th>Value</th>
                                                    <th style="width: 40px" title="Default"><i class="fa-solid fa-check-double"></i></th>
                                                    <th style="width: 30px"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="static-options-body">
                                                {if !empty($field.options_static)}
                                                    {foreach from=$field.options_static key=opt_idx item=opt}
                                                    <tr class="option-row">
                                                        <td class="text-center align-middle small text-muted">{$opt_idx + 1}</td>
                                                        <td><input type="text" name="groups[{$g_idx}][fields][{$f_name}][options_static][{$opt_idx}][key]" value="{$opt.key}" class="form-control form-control-sm" placeholder="Key"></td>
                                                        <td><input type="text" name="groups[{$g_idx}][fields][{$f_name}][options_static][{$opt_idx}][val]" value="{$opt.val}" class="form-control form-control-sm" placeholder="Value"></td>
                                                        <td class="text-center align-middle">
                                                            <input type="{if in_array($field.type, ['selectbox','radio'])}radio{else}checkbox{/if}" name="groups[{$g_idx}][fields][{$f_name}][options_static][{$opt_idx}][default]" value="1" {if $opt.default}checked{/if} class="form-check-input">
                                                        </td>
                                                        <td><button type="button" class="btn btn-link link-danger p-0 btn-remove-option"><i class="fa-solid fa-minus-circle"></i></button></td>
                                                    </tr>
                                                    {/foreach}
                                                {/if}
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100 btn-add-option" data-gidx="{$g_idx}" data-fname="{$f_name}">
                                            <i class="fa-solid fa-plus me-1"></i> Add Option
                                        </button>
                                    </div>

                                    <div class="source-db-area {if $field.source neq 'db'}d-none{/if}">
                                        <div class="mb-2">
                                            <select name="groups[{$g_idx}][fields][{$f_name}][options_db][module]" class="form-select form-select-sm db-module-select">
                                                <option value="">-- Select Module --</option>
                                                {foreach from=$MODULE_LIST key=m_name item=m_title}
                                                <option value="{$m_name}" {if $field.options_db.module eq $m_name}selected{/if}>{$m_title}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <select name="groups[{$g_idx}][fields][{$f_name}][options_db][table]" class="form-select form-select-sm db-table-select" data-selected="{$field.options_db.table}">
                                                <option value="">-- Select Table --</option>
                                            </select>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <select name="groups[{$g_idx}][fields][{$f_name}][options_db][key_col]" class="form-select form-select-sm db-column-select" data-selected="{$field.options_db.key_col}" data-type="key">
                                                    <option value="">-- ID Col --</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <select name="groups[{$g_idx}][fields][{$f_name}][options_db][val_col]" class="form-select form-select-sm db-column-select" data-selected="{$field.options_db.val_col}" data-type="val">
                                                    <option value="">-- Val Col --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {/foreach}
            </div>
        </div>
        {/foreach}
    </div>

    <div class="text-center sticky-bottom bg-body py-3 border-top shadow-lg d-flex justify-content-center gap-3" style="bottom: 0px; z-index: 1000; margin-left: -1rem; margin-right: -1rem;">
        <button type="button" class="btn btn-lg btn-success px-4 btn-save-only shadow">
            <i class="fa-solid fa-floppy-disk me-2"></i>{$LANG->getGlobal('save')}
        </button>
        <button type="button" class="btn btn-lg btn-primary px-4 btn-save-continue shadow">
            <i class="fa-solid fa-circle-arrow-right me-2"></i>{$LANG->getGlobal('save')} Metadata & Continue
        </button>
    </div>
</form>

{* Template for new Group - Hidden *}
<div id="group_template" class="d-none">
    <div class="group-container mb-5" data-gidx="G_IDX">
        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-primary-subtle rounded-3 shadow-sm border-start border-4 border-primary group-header">
            <div class="d-flex align-items-center flex-grow-1 me-3">
                <div class="group-handle cursor-move me-3 text-primary opacity-50"><i class="fa-solid fa-grip-vertical fs-3"></i></div>
                <i class="fa-solid fa-layer-group me-3 fs-3 text-primary"></i>
                <input type="text" name="groups[G_IDX][title]" value="" class="form-control form-control-lg border-0 bg-transparent fw-bold text-primary p-0" placeholder="New Group Label Key" style="box-shadow: none;">
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary btn-add-field" data-gidx="G_IDX">
                    <i class="fa-solid fa-plus me-1"></i>{$LANG->getModule('add_field')}
                </button>
                <button type="button" class="btn btn-outline-danger btn-remove-group" title="Remove Group">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>
        <div class="row g-4 fields-container min-vh-10"></div>
    </div>
</div>

{* Template for new Card - Hidden *}
<div id="field_template" class="d-none">
    <div class="col-12 col-md-6 col-xl-4 field-row">
        <div class="card h-100 border-0 shadow-sm hover-shadow transition-all field-card">
            <div class="card-header bg-body py-2 border-bottom d-flex justify-content-between align-items-center">
                <div class="field-handle cursor-move me-2 text-muted"><i class="fa-solid fa-grip-vertical"></i></div>
                <div class="d-flex align-items-center flex-grow-1 me-2 overflow-hidden">
                    <span class="badge bg-primary-subtle text-primary me-2">KEY</span>
                    <input type="text" name="groups[G_IDX][fields][F_IDX][key]" value="" class="form-control form-control-sm border-0 fw-bold text-primary p-0 bg-transparent text-truncate" placeholder="Key">
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-link link-danger p-0 btn-remove-field" title="Remove"><i class="fa-solid fa-circle-xmark fs-5"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted mb-1 d-flex justify-content-between">
                        <span>{$LANG->getModule('ui_type')}</span>
                        <i class="fa-solid fa-microchip text-info"></i>
                    </label>
                    <select name="groups[G_IDX][fields][F_IDX][type]" class="form-select form-select-sm border-info-subtle config-type-selector">
                        <option value="number">{$LANG->getModule('type_number')}</option>
                        <option value="date">{$LANG->getModule('type_date')}</option>
                        <option value="textbox" selected>{$LANG->getModule('type_textbox')}</option>
                        <option value="textarea">{$LANG->getModule('type_textarea')}</option>
                        <option value="editor">{$LANG->getModule('type_editor')}</option>
                        <option value="selectbox">{$LANG->getModule('type_selectbox')}</option>
                        <option value="radio">{$LANG->getModule('type_radio')}</option>
                        <option value="checkbox">{$LANG->getModule('type_checkbox')}</option>
                        <option value="checkbox_single">{$LANG->getModule('type_checkbox_single')}</option>
                        <option value="multiselect">{$LANG->getModule('type_multiselect')}</option>
                        <option value="file">{$LANG->getModule('type_file')}</option>
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted mb-1">{$LANG->getModule('label_key')}</label>
                        <input type="text" name="groups[G_IDX][fields][F_IDX][label]" value="" class="form-control form-control-sm">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted mb-1">{$LANG->getModule('default')}</label>
                        <div class="default-input-area">
                            <input type="text" name="groups[G_IDX][fields][F_IDX][default]" value="" class="form-control form-control-sm" placeholder="Default value">
                        </div>
                    </div>
                </div>

                <hr class="my-2 opacity-10">
                <div class="type-attributes">
                    <div class="attr-group-number d-none">
                        <div class="row g-2">
                            <div class="col-6"><input type="text" name="groups[G_IDX][fields][F_IDX][min]" class="form-control form-control-sm" placeholder="Min"></div>
                            <div class="col-6"><input type="text" name="groups[G_IDX][fields][F_IDX][max]" class="form-control form-control-sm" placeholder="Max"></div>
                        </div>
                    </div>
                    <div class="attr-group-date d-none">
                        <select name="groups[G_IDX][fields][F_IDX][display]" class="form-select form-select-sm">
                            <option value="datepicker">Datepicker</option>
                            <option value="datetimepicker">Datetimepicker</option>
                        </select>
                    </div>
                    <div class="attr-group-textbox attr-group-textarea d-none">
                        <select name="groups[G_IDX][fields][F_IDX][validate]" class="form-select form-select-sm">
                            <option value="">-- No Validate --</option>
                            <option value="az09">{$LANG->getModule('validate_az09')}</option>
                            <option value="name">{$LANG->getModule('validate_name')}</option>
                            <option value="email">{$LANG->getModule('validate_email')}</option>
                            <option value="url">{$LANG->getModule('validate_url')}</option>
                            <option value="regex">{$LANG->getModule('validate_regex')}</option>
                            <option value="func">{$LANG->getModule('validate_func')}</option>
                        </select>
                    </div>
                    <div class="attr-group-choices d-none">
                        <div class="mb-2 btn-group w-100" role="group">
                            <input type="radio" class="btn-check btn-toggle-source" name="groups[G_IDX][fields][F_IDX][source]" id="source_G_IDX_F_IDX_static" value="static" checked>
                            <label class="btn btn-outline-secondary btn-sm" for="source_G_IDX_F_IDX_static">Static</label>

                            <input type="radio" class="btn-check btn-toggle-source" name="groups[G_IDX][fields][F_IDX][source]" id="source_G_IDX_F_IDX_db" value="db">
                            <label class="btn btn-outline-secondary btn-sm" for="source_G_IDX_F_IDX_db">Database</label>
                        </div>

                        <div class="source-static-area">
                            <table class="table table-sm table-borderless mb-2">
                                <thead>
                                    <tr class="x-small text-muted">
                                        <th style="width: 40px">STT</th>
                                        <th>Key</th>
                                        <th>Value</th>
                                        <th style="width: 40px" title="Default"><i class="fa-solid fa-check-double"></i></th>
                                        <th style="width: 30px"></th>
                                    </tr>
                                </thead>
                                <tbody class="static-options-body"></tbody>
                            </table>
                            <button type="button" class="btn btn-outline-primary btn-sm w-100 btn-add-option" data-gidx="G_IDX" data-fname="F_IDX">
                                <i class="fa-solid fa-plus me-1"></i> Add Option
                            </button>
                        </div>

                        <div class="source-db-area d-none">
                            <div class="mb-2">
                                <select name="groups[G_IDX][fields][F_IDX][options_db][module]" class="form-select form-select-sm db-module-select">
                                    <option value="">-- Select Module --</option>
                                    {foreach from=$MODULE_LIST key=m_name item=m_title}
                                    <option value="{$m_name}">{$m_title}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="mb-2">
                                <select name="groups[G_IDX][fields][F_IDX][options_db][table]" class="form-select form-select-sm db-table-select">
                                    <option value="">-- Select Table --</option>
                                </select>
                            </div>
                            <div class="row g-2">
                                <div class="col-6"><select name="groups[G_IDX][fields][F_IDX][options_db][key_col]" class="form-select form-select-sm db-column-select"><option value="">-- ID Col --</option></select></div>
                                <div class="col-6"><select name="groups[G_IDX][fields][F_IDX][options_db][val_col]" class="form-select form-select-sm db-column-select"><option value="">-- Val Col --</option></select></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{* Template for new Option Row *}
<table class="d-none">
    <tbody id="option_row_template">
        <tr class="option-row">
            <td class="text-center align-middle small text-muted">0</td>
            <td><input type="text" name="groups[G_IDX][fields][F_IDX][options_static][OPT_IDX][key]" class="form-control form-control-sm" placeholder="Key"></td>
            <td><input type="text" name="groups[G_IDX][fields][F_IDX][options_static][OPT_IDX][val]" class="form-control form-control-sm" placeholder="Value"></td>
            <td class="text-center align-middle">
                <input type="radio" name="groups[G_IDX][fields][F_IDX][options_static][OPT_IDX][default]" value="1" class="form-check-input">
            </td>
            <td><button type="button" class="btn btn-link link-danger p-0 btn-remove-option" title="Remove"><i class="fa-solid fa-minus-circle"></i></button></td>
        </tr>
    </tbody>
</table>

<style>
.group-handle { color: #0d6efd; transition: color 0.2s; }
.group-handle:hover { color: #fd7e14; }
.field-handle { color: #6c757d; transition: color 0.2s; padding: 5px; margin-left: -5px; }
.field-handle:hover { color: #0d6efd; }
.hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
.transition-all { transition: all .2s ease-in-out; }
.uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
.field-card { border-left: 3px solid transparent!important; }
.field-card:focus-within { border-left-color: #0d6efd!important; }
.x-small { font-size: 0.75rem; }
.cursor-move { cursor: move; }
.min-vh-10 { min-height: 50px; }
.ghost-class { opacity: 0.5; background: #c8ebfb; border: 2px dashed #0d6efd; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(function() {
    var field_idx = 3000;
    var group_idx = 100;

    // Initialize Sortable for Groups
    var groupsContainer = document.getElementById('groups_master_container');
    if (groupsContainer) {
        new Sortable(groupsContainer, {
            animation: 150,
            handle: '.group-handle',
            ghostClass: 'ghost-class',
            onEnd: function() {
                reindexGroups();
            }
        });
    }

    // Initialize Sortable for Fields
    function initFieldSortable(container) {
        new Sortable(container, {
            group: 'fields',
            animation: 150,
            handle: '.field-handle',
            ghostClass: 'ghost-class',
            onEnd: function(evt) {
                var targetGroupIdx = $(evt.to).closest('.group-container').data('gidx');
                reindexFieldsInGroup(evt.to, targetGroupIdx);
                if (evt.from !== evt.to) {
                    var sourceGroupIdx = $(evt.from).closest('.group-container').data('gidx');
                    reindexFieldsInGroup(evt.from, sourceGroupIdx);
                }
            }
        });
    }

    $('.fields-container').each(function() {
        initFieldSortable(this);
    });


    function reindexGroups() {
        // Optional
    }

    function reindexFieldsInGroup(container, gidx) {
        $(container).find('.field-row').each(function() {
            var inputs = $(this).find('input, select, textarea');
            inputs.each(function() {
                var name = $(this).attr('name');
                if (name) {
                    var newName = name.replace(/groups\[.*?\]/g, 'groups[' + gidx + ']');
                    $(this).attr('name', newName);
                }
            });
        });
    }

    $(document).on('change', '.btn-toggle-source', function() {
        var val = $(this).val();
        var card = $(this).closest('.attr-group-choices');
        if (val === 'static') {
            card.find('.source-static-area').removeClass('d-none');
            card.find('.source-db-area').addClass('d-none');
        } else {
            card.find('.source-static-area').addClass('d-none');
            card.find('.source-db-area').removeClass('d-none');
            // Init load tables if empty
            var modSelect = card.find('.db-module-select');
            if (!modSelect.val()) {
                modSelect.val('{$TARGET_MODULE}').trigger('change');
            }
        }
    });

    $(document).on('click', '.btn-add-option', function() {
        var gidx = $(this).data('gidx');
        var fname = $(this).data('fname');
        var container = $(this).closest('.source-static-area').find('.static-options-body');
        var opt_idx = container.find('.option-row').length;

        var type = $(this).closest('.card-body').find('.config-type-selector').val();
        var inputType = (type === 'selectbox' || type === 'radio') ? 'radio' : 'checkbox';

        var html = $('#option_row_template').html()
            .replace(/G_IDX/g, gidx)
            .replace(/F_IDX/g, fname)
            .replace(/OPT_IDX/g, opt_idx)
            .replace('type="radio"', 'type="' + inputType + '"');

        var $row = $(html);
        $row.find('td:first').text(opt_idx + 1);
        container.append($row);
    });

    $(document).on('click', '.btn-remove-option', function() {
        var body = $(this).closest('.static-options-body');
        $(this).closest('.option-row').remove();
        // Re-index STT
        body.find('.option-row').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    });

    $(document).on('change', '.db-module-select', function() {
        var mod = $(this).val();
        var tableSelect = $(this).closest('.source-db-area').find('.db-table-select');
        var selectedTable = tableSelect.data('selected');

        if (!mod) {
            tableSelect.html('<option value="">-- Select Table --</option>');
            return;
        }

        var url = '{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&{$smarty.const.NV_OP_VARIABLE}={$OP}&mode=get_tables&mod=' + mod;
        $.getJSON(url, function(res) {
            var html = '<option value="">-- Select Table --</option>';
            res.forEach(function(t) {
                html += '<option value="' + t + '" ' + (t === selectedTable ? 'selected' : '') + '>' + t + '</option>';
            });
            tableSelect.html(html).trigger('change');
        });
    });

    $(document).on('change', '.db-table-select', function() {
        var table = $(this).val();
        var container = $(this).closest('.source-db-area');
        var colSelects = container.find('.db-column-select');

        if (!table) {
            colSelects.html('<option value="">-- Col --</option>');
            return;
        }

        var url = '{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&{$smarty.const.NV_OP_VARIABLE}={$OP}&mode=get_columns&table=' + table;
        $.getJSON(url, function(res) {
            colSelects.each(function() {
                var self = $(this);
                var currentlySelected = self.data('selected');
                var type = self.data('type');
                var html = '<option value="">-- ' + (type === 'key' ? 'ID' : 'Val') + ' Col --</option>';
                res.forEach(function(c) {
                    html += '<option value="' + c + '" ' + (c === currentlySelected ? 'selected' : '') + '>' + c + '</option>';
                });
                self.html(html);
            });
        });
    });

    $(document).on('change', '.config-type-selector', function() {
        var type = $(this).val();
        var row = $(this).closest('.field-row');
        var container = row.find('.type-attributes');
        var defaultArea = row.find('.default-input-area');
        var gidx = row.closest('.group-container').data('gidx');
        var fname = row.find('input[name*="[key]"]').val() || 'TEMP_F';

        // Cập nhật giao diện Default cho checkbox_single
        if (type === 'checkbox_single') {
            var name = 'groups[' + gidx + '][fields][' + fname + '][default]';
            defaultArea.html('<div class="form-check form-switch p-0 ms-4"><input type="checkbox" name="' + name + '" value="1" class="form-check-input" style="cursor: pointer; width: 2.5em; height: 1.25em;"></div>');
        } else {
            var name = 'groups[' + gidx + '][fields][' + fname + '][default]';
            var currentVal = defaultArea.find('input').val() || '';
            if (defaultArea.find('input[type="checkbox"]').length) currentVal = defaultArea.find('input').is(':checked') ? '1' : '0';
            defaultArea.html('<input type="text" name="' + name + '" value="' + currentVal + '" class="form-control form-control-sm" placeholder="Default value">');
        }

        container.find('> div').addClass('d-none');
        if (type === 'number') container.find('.attr-group-number').removeClass('d-none');
        if (type === 'date') container.find('.attr-group-date').removeClass('d-none');
        if (type === 'textbox' || type === 'textarea') container.find('.attr-group-textbox').removeClass('d-none');
        if (['selectbox','radio','checkbox','multiselect'].indexOf(type) !== -1) container.find('.attr-group-choices').removeClass('d-none');
    });

    $('.btn-add-group').on('click', function() {
        var gidx = 'new_g_' + (group_idx++);
        var template = $('#group_template').html();
        var html = template.replace(/G_IDX/g, gidx);
        var $newGroup = $(html);
        $('#groups_master_container').append($newGroup);
        initFieldSortable($newGroup.find('.fields-container')[0]);
    });

    $(document).on('click', '.btn-remove-group', function() {
        if (confirm('{$LANG->getModule('confirm_remove_group')}')) {
            $(this).closest('.group-container').fadeOut(200, function() { $(this).remove(); });
        }
    });

    $(document).on('click', '.btn-save-only, .btn-save-continue', function(e) {
        e.preventDefault();
        var is_continue = $(this).hasClass('btn-save-continue') ? 1 : 0;
        $('#continue_flag').val(is_continue);
        $(this).closest('form').submit();
    });

    $(document).on('click', '.btn-add-field', function() {
        var gidx = $(this).data('gidx');
        var fidx = 'new_f_' + (field_idx++);
        var template = $('#field_template').html();
        var html = template.replace(/G_IDX/g, gidx).replace(/F_IDX/g, fidx);
        $(this).closest('.group-container').find('.fields-container').append(html);
    });

    $(document).on('click', '.btn-remove-field', function() {
        if (confirm('{$LANG->getGlobal('delete_confirm')}')) {
            $(this).closest('.field-row').fadeOut(200, function() { $(this).remove(); });
        }
    });

    // --- INITIALIZATION ---
    setTimeout(function() {
        // Trigger display for existing fields
        $('.config-type-selector').trigger('change');
        $('.btn-toggle-source:checked').trigger('change');

        // Auto-load DB tables/cols for existing fields
        $('.db-module-select').each(function() {
            var val = $(this).val();
            if (val) {
                $(this).trigger('change');
            }
        });
    }, 100);
});
</script>
{/if}
