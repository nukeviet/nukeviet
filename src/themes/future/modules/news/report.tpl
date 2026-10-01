<!-- START FORFOOTER -->
<div class="modal fade" tabindex="-1" aria-labelledby="newsReportModalLabel" aria-hidden="true" data-toggle="newsReportModal" data-truncated="{$LANG->getModule('text_truncated')}">
    <div class="modal-dialog">
        <form class="modal-content" action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}" method="post" data-form="newsReport" data-toggle="ajax-form" data-precheck="nv_precheck_form" data-callback="newsReportCallback"{$CAPTCHA_ATTRS} novalidate>
            <div class="modal-header">
                <div class="fs-5 fww-medium modal-title" id="newsReportModalLabel">{$LANG->getModule('report_error_content')}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="newsReportContent" class="form-label">{$LANG->getModule('error_text')}</label>
                    <textarea rows="1" class="form-control" id="newsReportContent" name="report_content" maxlength="250" data-toggle="newsReportAutoResize" readonly></textarea>
                </div>
                <div class="mb-3">
                    <label for="newsReportFix" class="form-label">{$LANG->getModule('proposal_text')}</label>
                    <textarea rows="1" class="form-control" id="newsReportFix" name="report_fix" maxlength="250" data-toggle="newsReportAutoResize" data-valid data-allowed-empty="1" data-valid-callback="newsReportFixCheck" data-error-type="feedback" data-error-mess="{$LANG->getModule('report_same_values')}"></textarea>
                    <div class="invalid-feedback"></div>
                </div>
                {if empty($smarty.const.NV_IS_USER)}
                <div class="form-text mb-2">{$LANG->getModule('post_email_note')}</div>
                <div class="input-group">
                    <label class="input-group-text fw-bold" for="newsReportEmail">{$LANG->getModule('post_email')}</label>
                    <input type="email" class="form-control" id="newsReportEmail" name="report_email" maxlength="100" value="" data-valid data-allowed-empty="1" data-error-type="feedback" data-error-mess="{$LANG->getModule('post_email_error')}">
                </div>
                <div class="invalid-feedback"></div>
                {/if}
            </div>
            <div class="modal-footer">
                <input type="hidden" name="newsid" value="{$NEWSID}">
                <input type="hidden" name="_csrf" value="{$NEWSCHECKSS}">
                <input type="hidden" name="action" value="report">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
            </div>
        </form>
    </div>
</div>
<!-- END FORFOOTER -->
