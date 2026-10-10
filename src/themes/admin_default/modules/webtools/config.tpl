<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="autocheckupdate" value="1"{if not empty($GCONFIG.autocheckupdate)} checked{/if} role="switch" id="element_autocheckupdate">
                        <label class="form-check-label" for="element_autocheckupdate">{$LANG->getModule('autocheckupdate')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_autoupdatetime" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('updatetime')}</label>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <div class="d-inline-flex align-items-center">
                        <div>
                            <select class="form-select" id="element_autoupdatetime" name="autoupdatetime">
                                {for $value=1 to 100}
                                <option value="{$value}"{if $value eq $GCONFIG.autoupdatetime} selected{/if}>{$value}</option>
                                {/for}
                            </select>
                        </div>
                        <div class="ms-2">({$LANG->getModule('hour')})</div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
