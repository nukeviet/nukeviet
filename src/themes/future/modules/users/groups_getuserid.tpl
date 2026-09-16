<form method="get" action="{$FORM_ACTION}" data-form="groupGetUid" class="mb-3">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="guid_user_id">{$LANG->getModule('user_id')}</label>
            <input type="text" class="form-control" id="guid_user_id" name="user_id" value="" maxlength="100">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="guid_username">{$LANG->getModule('username')}</label>
            <input type="text" class="form-control" id="guid_username" name="username" value="" maxlength="100">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="guid_full_name">{$LANG->getModule('fullname')}</label>
            <input type="text" class="form-control" id="guid_full_name" name="full_name" value="" maxlength="100">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="guid_email">{$LANG->getModule('email')}</label>
            <input type="text" class="form-control" id="guid_email" name="email" value="" maxlength="100">
        </div>
    </div>
    <div class="text-center mt-3">
        <input type="hidden" name="fsubmit" value="1">
        <input type="hidden" name="checkss" value="{$CHECKSS}">
        <button type="reset" class="btn btn-secondary">
            <i class="fa-solid fa-rotate-left"></i> {$LANG->getModule('reset')}
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-magnifying-glass" data-icon="fa-magnifying-glass"></i> {$LANG->getModule('search')}
        </button>
    </div>
</form>
<div id="resultdata"></div>
