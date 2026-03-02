{if $IS_VALID}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-9">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <h1 class="h3 text-center fw-semibold mb-3">{$LANG->getModule('confirm_password')}</h1>
                    <div class="text-center mb-4" data-default="{$LANG->getModule('confirm_password_info')}">{$LANG->getModule('confirm_password_info')}</div>
                    <form action="{$FORM_ACTION}" method="post" data-toggle="confirmpass_validForm" autocomplete="off" novalidate>
                        <div class="mb-4">
                            <div class="position-relative">
                                <i class="fa fa-key position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" aria-hidden="true"></i>
                                <input type="password" autocomplete="off" class="required form-control form-control-lg rounded-4 ps-5 shadow-sm" placeholder="{$LANG->getGlobal('password')}" name="password" maxlength="100" data-pattern="/^(.){ldelim}1,{rdelim}$/" data-toggle="valid2faErrorHidden" data-mess="">
                            </div>
                        </div>
                        <input type="hidden" name="checkss" value="{$NV_CHECK_SESSION}">
                        <button class="bsubmit btn btn-primary btn-lg w-100 rounded-pill py-3 shadow-sm" type="submit">{$LANG->getModule('confirm')}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{else}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="alert alert-danger">{$CHANGE_2STEP_NOTVALID}</div>
        </div>
    </div>
</div>
{/if}
