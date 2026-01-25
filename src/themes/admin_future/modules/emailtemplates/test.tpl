{if not empty($ERROR)}
<div class="alert alert-danger" role="alert">{$ERROR|join:"<br />"}</div>
{/if}
{if $SUCCESS}
<div class="alert alert-success" role="alert">{$LANG->get('test_success')}.</div>
{/if}
<div class="card">
    <div class="card-header py-2">
        <div class="d-flex gap-2 align-items-center">
            <div class="flex-grow-1 flex-shrink-1 fs-5 fw-medium">{$DATA.title}</div>
            <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=contents&amp;emailid={$DATA.emailid}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pencil"></i> {$LANG->get('edit_template')}</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-7">
                <form method="post" action="{$FORM_ACTION}" autocomplete="off" class="form-horizontal">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-sm-end" for="test_tomail">{$LANG->get('test_tomail')}</label>
                        <div class="col-sm-9">
                            <textarea rows="3" class="form-control" id="test_tomail" name="test_tomail">{$DATA.test_tomail|join:"\n"}</textarea>
                            <div class="form-text">{$LANG->get('test_tomail_note')}</div>
                        </div>
                    </div>
                    {if not empty($MERGE_FIELDS)}
                    <div class="row">
                        <div class="col-sm-9 offset-sm-3">
                            <h3 class="mb-3"><strong>{$LANG->get('test_value_fields')}</strong></h3>
                        </div>
                    </div>
                    {foreach from=$MERGE_FIELDS key=fieldname item=field}
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-sm-end"{if not isset($field.type)} for="f_{$fieldname}"{/if}>{$field.name}</label>
                        <div class="col-sm-9">
                            {if isset($field.type) and $field.type eq 'array'}
                            {foreach from=$field.keys item=element}
                            <div class="input-group mb-1">
                                <span class="input-group-text" id="f_{$fieldname}_{$element}">{$element}</span>
                                <input type="text" class="form-control" aria-describedby="f_{$fieldname}_{$element}" name="f_{$fieldname}[{$element}]" value="{if isset($FIELD_DATA[$fieldname], $FIELD_DATA[$fieldname][$element])}{$FIELD_DATA[$fieldname][$element]}{/if}" placeholder="${$fieldname}.{$element}">
                            </div>
                            {/foreach}
                            {elseif isset($field.type) and $field.type eq 'list'}
                            {if isset($FIELD_DATA[$fieldname])}
                                {assign var="offsets" value=sizeof($FIELD_DATA[$fieldname]) nocache}{else}
                                {assign var="offsets" value=1 nocache}
                            {/if}
                            <div class="field-ctns">
                                {for $offset=0 to $offsets - 1}
                                <div class="input-group mb-1 item" data-offset="{$offset}">
                                    <input type="text" class="form-control" id="f_{$fieldname}_{$offset}" name="f_{$fieldname}[]" value="{if isset($FIELD_DATA[$fieldname], $FIELD_DATA[$fieldname][$offset])}{$FIELD_DATA[$fieldname][$offset]}{/if}" placeholder="${$fieldname}[]">
                                    <button data-toggle="delField" class="btn btn-danger" type="button"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                {/for}
                            </div>
                            <a href="#" class="btn btn-sm btn-success" data-toggle="addField" data-fieldname="{$fieldname}"><i class="fa-solid fa-plus"></i> {$LANG->getGlobal('add')}</a>
                            {else}
                            <input type="text" class="form-control" id="f_{$fieldname}" name="f_{$fieldname}" value="{if isset($FIELD_DATA[$fieldname])}{$FIELD_DATA[$fieldname]}{/if}" placeholder="${$fieldname}">
                            {/if}
                        </div>
                    </div>
                    {/foreach}
                    {/if}
                    <div class="row">
                        <div class="col-sm-9 offset-sm-3">
                            <input type="hidden" name="tokend" value="{$TOKEND}">
                            <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-5">
                <h3><strong>{$LANG->getGlobal('note')}:</strong></h3>
                <p>{$LANG->getModule('test_note1')}</p>
                <p>{$LANG->getModule('test_note2')}</p>
                {$LANG->getModule('test_note3')}
            </div>
        </div>
    </div>
</div>
