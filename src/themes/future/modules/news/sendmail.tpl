<form class="modal-content" action="{$SENDMAIL.action}" method="post" data-toggle="ajax-form" data-precheck="nv_precheck_form" data-callback="newsSendMailCallback"{$CAPTCHA_ATTRS} novalidate>
    <div class="modal-header">
        <h5 class="modal-title">{$LANG->getModule('sendmail')}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label for="newsSendMailFriendEmail" class="form-label">{$LANG->getModule('sendmail_email')} <span class="text-danger">(*)</span></label>
            <input type="email" class="form-control" id="newsSendMailFriendEmail" name="friend_email" value="" maxlength="100" data-valid data-error-type="feedback">
            <div class="invalid-feedback"></div>
        </div>
        <div class="mb-3">
            <label for="newsSendMailYourName" class="form-label">{$LANG->getModule('sendmail_name')} <span class="text-danger">(*)</span></label>
            <input type="text" class="form-control" id="newsSendMailYourName" name="your_name" value="{$SENDMAIL.your_name}" maxlength="100" data-valid data-error-type="feedback" data-error-mess="{$LANG->getModule('sendmail_err_name')}">
            <div class="invalid-feedback"></div>
        </div>
        {if not empty($smarty.const.NV_IS_USER)}
        <div class="mb-3">
            <label for="newsSendMailYourEmail" class="form-label">{$LANG->getModule('sendmail_youremail')}</label>
            <input type="email" class="form-control" id="newsSendMailYourEmail" name="your_email" value="{$SENDMAIL.your_email}" maxlength="100" readonly>
        </div>
        <div class="mb-3">
            <label for="newsSendMailYourMessage" class="form-label">{$LANG->getModule('sendmail_content')}</label>
            <textarea class="form-control" id="newsSendMailYourMessage" name="your_message" rows="3" maxlength="500"></textarea>
        </div>
        {/if}
        {if not empty($GCONFIG.data_warning) or not empty($GCONFIG.antispam_warning)}
        <div class="alert alert-info vstack gap-2 mb-0">
            {if not empty($GCONFIG.data_warning)}
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="data_permission_confirm" value="1" id="newsSendMailDataConfirm" data-valid="checkbox" data-min="1" data-max="1" data-error-type="feedback">
                <label class="form-check-label" for="newsSendMailDataConfirm"><small>{$GCONFIG.data_warning_content|default:$LANG->getGlobal('data_warning_content')}</small></label>
                <div class="invalid-feedback">{$LANG->getGlobal('data_warning_error')}</div>
            </div>
            {/if}
            {if not empty($GCONFIG.antispam_warning)}
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="antispam_confirm" value="1" id="newsSendMailAntispamConfirm" data-valid="checkbox" data-min="1" data-max="1" data-error-type="feedback">
                <label class="form-check-label" for="newsSendMailAntispamConfirm"><small>{$GCONFIG.antispam_warning_content|default:$LANG->getGlobal('antispam_warning_content')}</small></label>
                <div class="invalid-feedback">{$LANG->getGlobal('antispam_warning_error')}</div>
            </div>
            {/if}
        </div>
        {/if}
    </div>
    <div class="modal-footer">
        <input type="hidden" name="checkss" value="{$SENDMAIL.checkss}">
        <input type="hidden" name="send" value="1">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('cancel')}</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> {$LANG->getModule('sendmail_submit')}</button>
    </div>
</form>
