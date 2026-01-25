<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            <div class="alert alert-info">{$LANG->getModule('vmodule_blockquote')}</div>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-9">
                    {$LANG->getGlobal('required')}
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_title" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('vmodule_name')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_title" name="title" value="" maxlength="50">
                    <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                    <div class="form-text">{$LANG->getModule('vmodule_maxlength')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_m_file" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('vmodule_file')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_m_file" name="m_file">
                        <option value="">{$LANG->getModule('vmodule_select')}</option>
                        {foreach from=$MODFILE item=m_file}
                        <option value="{$m_file}">{$m_file}</option>
                        {/foreach}
                    </select>
                    <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_note" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('vmodule_note')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <textarea type="text" class="form-control" id="element_note" name="note" rows="5"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
