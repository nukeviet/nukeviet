{foreach from=$FIELDS item=field}
    {if $field.field_type == 'textbox' or $field.field_type == 'number'}
    <div class="col-md-6">
        <label class="form-label" for="nvcf-{$field.field}">{$field.title}{if $field.required} <span class="text-danger">*</span>{/if}</label>
        <input type="text" class="form-control {$field.class}"
            placeholder="{$field.title}" value="{$field.value}"
            name="custom_fields[{$field.field}]" id="nvcf-{$field.field}"
            {if $field.field_type == 'number'}
                inputmode="{if $field.number_type == 2}decimal{else}numeric{/if}"
                {if $field.min_length < $field.max_length} data-min-value="{$field.min_length}" data-max-value="{$field.max_length}"{/if}
            {else}
                {if $field.min_length} minlength="{$field.min_length}"{/if}
                {if $field.max_length} maxlength="{$field.max_length}"{/if}
            {/if}
            {if $field.match_type == 'email'}data-valid="email"{else}data-valid{/if} data-error-type="feedback"
            {if not $field.required} data-allowed-empty="1"{/if}
            {if $field.pattern} data-pattern="{$field.pattern}"{/if}
            {if $field.callfunc} data-valid-callback="{$field.callfunc}"{/if}
            data-error-mess="{$field.errmess}"
        >
        <div class="invalid-feedback"></div>
        {if $field.description}<div class="form-text">{$field.description}</div>{/if}
    </div>
    {elseif $field.field_type == 'date'}
    <div class="col-md-6">
        <label class="form-label" for="nvcf-{$field.field}">{$field.title}{if $field.required} <span class="text-danger">*</span>{/if}</label>
        <div class="input-group">
            <input type="text" class="form-control {$field.class}"
                placeholder="{$field.title}" value="{$field.value}"
                name="custom_fields[{$field.field}]" id="nvcf-{$field.field}"
                data-provide="datepicker" data-valid data-error-type="feedback"
                {if not $field.required} data-allowed-empty="1"{/if}
                {if $field.min_date} data-min-date="{$field.min_date}" data-max-date="{$field.max_date}"{/if}
                data-error-mess="{$field.errmess}"
            >
            <button type="button" class="btn btn-secondary" data-toggle="datepickerBtn" aria-label="{$field.title}"><i class="fa-solid fa-calendar-days"></i></button>
        </div>
        <div class="invalid-feedback"></div>
        {if $field.description}<div class="form-text">{$field.description}</div>{/if}
    </div>
    {elseif $field.field_type == 'select'}
    <div class="col-md-6">
        <label class="form-label" for="nvcf-{$field.field}">{$field.title}{if $field.required} <span class="text-danger">*</span>{/if}</label>
        <select name="custom_fields[{$field.field}]" class="form-select {$field.class}" id="nvcf-{$field.field}"
            data-valid data-error-type="feedback"
            {if not $field.required} data-allowed-empty="1"{/if}
            data-error-mess="{$field.errmess}"
        >
            {foreach from=$field.choices item=choice}
            <option value="{$choice.key}"{if $choice.selected} selected{/if}>{$choice.value}</option>
            {/foreach}
        </select>
        <div class="invalid-feedback"></div>
        {if $field.description}<div class="form-text">{$field.description}</div>{/if}
    </div>
    {elseif $field.field_type == 'textarea'}
    <div class="col-12">
        <label class="form-label" for="nvcf-{$field.field}">{$field.title}{if $field.required} <span class="text-danger">*</span>{/if}</label>
        <textarea class="form-control {$field.class}" placeholder="{$field.title}"
            name="custom_fields[{$field.field}]" id="nvcf-{$field.field}" rows="3"
            {if $field.min_length} minlength="{$field.min_length}"{/if}
            {if $field.max_length} maxlength="{$field.max_length}"{/if}
            data-valid data-error-type="feedback"
            {if not $field.required} data-allowed-empty="1"{/if}
            {if $field.pattern} data-pattern="{$field.pattern}"{/if}
            {if $field.callfunc} data-valid-callback="{$field.callfunc}"{/if}
            data-error-mess="{$field.errmess}"
        >{$field.value}</textarea>
        <div class="invalid-feedback"></div>
        {if $field.description}<div class="form-text">{$field.description}</div>{/if}
    </div>
    {elseif $field.field_type == 'editor' and $field.is_editor}
    <div class="col-12">
        <div class="form-label">{$field.title}{if $field.required} <span class="text-danger">*</span>{/if}</div>
        <div data-name="custom_fields[{$field.field}]"
            data-valid="editor" data-error-type="feedback"
            {if not $field.required} data-allowed-empty="1"{/if}
            data-error-mess="{$field.errmess}"
        >{$field.editor}</div>
        <div class="invalid-feedback"></div>
        {if $field.description}<div class="form-text">{$field.description}</div>{/if}
    </div>
    {elseif $field.field_type == 'radio'}
    <div class="col-md-6">
        <div class="form-label d-block mb-1">{$field.title}{if $field.required} <span class="text-danger">*</span>{/if}</div>
        {foreach from=$field.choices item=choice}
        <div class="form-check">
            <input class="form-check-input {$field.class}" type="radio"
                name="custom_fields[{$field.field}]" id="lb_{$choice.id}" value="{$choice.key}"{if $choice.checked} checked{/if}
                data-valid data-error-type="feedback"
                data-min="{if $field.required}1{else}0{/if}" data-max="1"
                data-error-mess="{$field.errmess}"
            >
            <label class="form-check-label" for="lb_{$choice.id}">{$choice.value}</label>
            {if $choice@last}<div class="invalid-feedback"></div>{/if}
        </div>
        {/foreach}
        {if $field.description}<div class="form-text">{$field.description}</div>{/if}
    </div>
    {elseif $field.field_type == 'checkbox'}
    <div class="col-md-6">
        <div class="form-label d-block mb-1">{$field.title}{if $field.required} <span class="text-danger">*</span>{/if}</div>
        {foreach from=$field.choices item=choice}
        <div class="form-check">
            <input class="form-check-input {$field.class}" type="checkbox"
                name="custom_fields[{$field.field}][]" id="lb_{$choice.id}" value="{$choice.key}"{if $choice.checked} checked{/if}
                data-valid data-error-type="feedback"
                data-min="{if $field.required}1{else}0{/if}" data-max="{$field.choices|@count}"
                data-error-mess="{$field.errmess}"
            >
            <label class="form-check-label" for="lb_{$choice.id}">{$choice.value}</label>
            {if $choice@last}<div class="invalid-feedback"></div>{/if}
        </div>
        {/foreach}
        {if $field.description}<div class="form-text">{$field.description}</div>{/if}
    </div>
    {elseif $field.field_type == 'multiselect'}
    <div class="col-12">
        <label class="form-label" for="nvcf-{$field.field}">{$field.title}{if $field.required} <span class="text-danger">*</span>{/if}</label>
        <select name="custom_fields[{$field.field}][]" multiple class="form-select {$field.class}" id="nvcf-{$field.field}"
            data-valid data-error-type="feedback"
            {if not $field.required} data-allowed-empty="1"{/if}
            data-error-mess="{$field.errmess}"
        >
            {foreach from=$field.choices item=choice}
            <option value="{$choice.key}"{if $choice.selected} selected{/if}>{$choice.value}</option>
            {/foreach}
        </select>
        <div class="invalid-feedback"></div>
        {if $field.description}<div class="form-text">{$field.description}</div>{/if}
    </div>
    {elseif $field.field_type == 'file'}
    <div class="col-12">
        <div class="filelist" data-field="{$field.field}" data-oclass="{$field.class}" data-maxnum="{$field.filemaxnum}"
            data-name="custom_fields[{$field.field}]"
            data-valid="filelist" data-error-type="feedback"
            {if not $field.required} data-allowed-empty="1"{/if}
            data-error-mess="{$field.errmess}"
        >
            <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label mb-0" for="ipt_uploadfile_{$field.field}">{$field.title}{if $field.required} <span class="text-danger">*</span>{/if}</label>
                <button type="button" class="btn btn-secondary btn-sm" id="ipt_uploadfile_{$field.field}" data-toggle="addfilebtn" data-modal="uploadfile_{$field.field}"><i class="fa-solid fa-upload"></i> {$LANG->getModule('addfile')}</button>
            </div>
            <ul class="list-unstyled items mb-0"></ul>
            <div class="modal fade uploadfile" tabindex="-1" id="uploadfile_{$field.field}" data-url="{$field.url_module}" data-field="{$field.field}" data-csrf="{$field.csrf}" data-accept="{$field.fileaccept}" data-maxsize="{$field.filemaxsize}" data-ext-error="{$LANG->getModule('addfile_ext_error')}" data-size-error="{$LANG->getModule('addfile_size_error')}" data-size-error2="{$LANG->getModule('addfile_size_error2')}" data-delete="{$LANG->getGlobal('delete')}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title fw-medium">{$LANG->getModule('addfile')}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                        </div>
                        <div class="modal-body">
                            <p class="fileinput d-flex justify-content-center my-3"></p>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li>- {$LANG->getModule('accepted_extensions')}: {$field.fileaccept}</li>
                                <li>- {$LANG->getModule('field_file_max_size')}: {$field.filemaxsize_format}</li>
                                {if $field.widthlimit}<li>- {$field.widthlimit}</li>{/if}
                                {if $field.heightlimit}<li>- {$field.heightlimit}</li>{/if}
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/if}
{/foreach}
