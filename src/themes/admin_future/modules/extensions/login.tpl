<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card mt-xl-4">
            <div class="card-body">
                <h1 class="text-center">{$LANG->getModule('login_pagetitle')}</h1>
                <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
                    <div class="mb-3">
                        <label for="element_username" class="form-label fw-medium">{$LANG->getGlobal('username')} <span class="text-danger">(*)</span>:</label>
                        <input type="text" class="form-control" id="element_username" name="username" value="">
                        <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                    </div>
                    <div class="mb-3">
                        <label for="element_password" class="form-label fw-medium">{$LANG->getGlobal('password')} <span class="text-danger">(*)</span>:</label>
                        <input type="password" autocomplete="off" class="form-control" id="element_password" name="password" value="">
                        <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                    </div>
                    <input type="hidden" name="checksess" value="{$CHECKSESS}">
                    <input type="hidden" name="redirect" value="{$REQUEST.redirect}">
                    <button type="submit" class="btn btn-primary w-100 text-center">{$LANG->getGlobal('loginsubmit')}</button>
                </form>
                <div class="mt-4">{$LANG->getModule('login_creat_merchant')}</div>
            </div>
        </div>
    </div>
</div>
