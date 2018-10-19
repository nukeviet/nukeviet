{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <input name="checkss" type="hidden" value="{$NV_CHECK_SESSION}">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="vmodule_title">{$LANG->get('vmodule_name')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="vmodule_title" name="title" value="{$DATA.title}">
                    <span class="form-text text-muted">{$LANG->get('vmodule_blockquote')} {$LANG->get('vmodule_maxlength')}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="vmodule_module_file">{$LANG->get('vmodule_file')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="vmodule_module_file" name="module_file">
                        <option value="">{$LANG->get('vmodule_select')}</option>
                        {foreach from=$ARRAY_MODULE item=row}
                        <option value="{$row}"{if $row eq $DATA['modfile']} selected="selected"{/if}>{$row}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="vmodule_note">{$LANG->get('description')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <textarea name="note" id="vmodule_note" rows="2" class="form-control">{$DATA.note}</textarea>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit1">{$LANG->get('submit')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
