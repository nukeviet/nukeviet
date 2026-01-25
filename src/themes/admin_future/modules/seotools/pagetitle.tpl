<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            <div class="row mb-3">
                <label for="element_pageTitleMode" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('pagetitle2')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_pageTitleMode" name="pageTitleMode" value="{$GCONFIG.pageTitleMode}">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="alert alert-info mb-0" role="alert">{$LANG->getModule('pagetitleNote')}</div>
