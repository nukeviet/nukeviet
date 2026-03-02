{if $IS_VALID}
<div class="d-flex justify-content-center py-5">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h2 class="h4 text-center mb-4">{$LANG->getModule('confirm_password')}</h2>
      <form action="{$FORM_ACTION}" method="post" data-toggle="confirmpass_validForm" autocomplete="off" novalidate>
        <div class="nv-info mb-3" data-default="{$LANG->getModule('confirm_password_info')}">{$LANG->getModule('confirm_password_info')}</div>
        <div class="mb-3">
          <div class="input-group">
            <span class="input-group-text"><i class="fa fa-key fa-lg" aria-hidden="true"></i></span>
            <input type="password" class="required form-control" name="password" placeholder="{$GLANG->getModule('password')}" maxlength="100" data-pattern="/^(.){1,}$/" data-toggle="valid2faErrorHidden" data-mess="" autocomplete="off">
          </div>
        </div>
        <div class="text-center">
          <input type="hidden" name="checkss" value="{$NV_CHECK_SESSION}">
          <button class="bsubmit btn btn-primary" type="submit">{$LANG->getModule('confirm')}</button>
        </div>
      </form>
    </div>
  </div>
 </div>
{else}
<div class="d-flex justify-content-center py-5">
  <div class="alert alert-danger m-0" role="alert">{$CHANGE_2STEP_NOTVALID}</div>
</div>
{/if}
