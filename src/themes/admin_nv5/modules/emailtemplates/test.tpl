{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{"<br />"|implode:$ERROR}</div>
</div>
{/if}
{if $SUCCESS}
<div role="alert" class="alert alert-success alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-check"></i></div>
    <div class="message">{$LANG->get('test_success')}.</div>
</div>
{/if}
<div class="card card-border-color card-border-color-success">
    <div class="card-header card-header-divider">
        <div class="d-flex">
            <div class="flex-grow-1 flex-shrink-1">{$DATA.title}</div>
            <div class="ml-2">
                <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=contents&amp;emailid={$DATA.emailid}" class="btn btn-secondary"><i class="icon icon-left fas fa-globe"></i> {$LANG->get('edit_template')}</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="test_tomail">{$LANG->get('test_tomail')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <textarea rows="3" class="form-control" id="test_tomail" name="test_tomail">{"\n"|implode:$DATA.test_tomail}</textarea>
                    <div class="form-text text-muted">{$LANG->get('test_tomail_note')}</div>
                </div>
            </div>
            {if not empty($MERGE_FIELDS)}
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                    <h4 class="mb-0">{$LANG->get('test_value_fields')}</h4>
                </div>
            </div>
            <div class="card-divider"></div>
            {foreach from=$MERGE_FIELDS key=fieldname item=field}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="f_{$fieldname}">{$field.name}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="f_{$fieldname}" name="f_{$fieldname}" value="{if isset($FIELD_DATA[$fieldname])}{$FIELD_DATA[$fieldname]}{/if}" placeholder="${$fieldname}">
                </div>
            </div>
            {/foreach}
            {/if}
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
