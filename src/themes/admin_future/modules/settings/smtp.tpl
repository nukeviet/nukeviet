<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div class="accordion" id="accordion-settings">
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-general" aria-expanded="true" aria-controls="collapse-general">
                <span class="fw-medium fs-5">{$LANG->getModule('general_settings')}</span>
            </button>
        </div>
        <div id="collapse-general" class="accordion-collapse collapse show" data-bs-parent="#accordion-settings">
            <div class="accordion-body">
                <form id="sendmail-settings" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post" class="ajax-submit" data-mailer-mode-default="{$DATA.mailer_mode}" novalidate>
                    <div class="row mb-3">
                        <label for="element_sender_name" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('mail_sender_name')}</label>
                        <div class="col-sm-6 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="element_sender_name" name="sender_name" value="{$DATA.sender_name}" maxlength="200">
                            <div class="form-text">{$LANG->getModule('mail_sender_name_default')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_sender_email" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('mail_sender_email')}</label>
                        <div class="col-sm-6 col-lg-6 col-xxl-5">
                            <input type="email" class="form-control" id="element_sender_email" name="sender_email" value="{$DATA.sender_email}" maxlength="200">
                            <div class="form-text">{$LANG->getModule('mail_sender_email_default')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6 col-lg-6 col-xxl-5 offset-sm-6 offset-lg-4 offset-xxl-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="force_sender" value="1"{if $DATA.force_sender} checked{/if} role="switch" id="element_force_sender">
                                <label class="form-check-label" for="element_force_sender">{$LANG->getModule('mail_force_sender')}</label>
                            </div>
                            <div class="form-text">{$LANG->getModule('mail_force_sender_note')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_reply_name" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('mail_reply_name')}</label>
                        <div class="col-sm-6 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="element_reply_name" name="reply_name" value="{$DATA.reply_name}" maxlength="200">
                            <div class="form-text">{$LANG->getModule('mail_reply_name_default')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_reply_email" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('mail_reply_email')}</label>
                        <div class="col-sm-6 col-lg-6 col-xxl-5">
                            <input type="email" class="form-control" id="element_reply_email" name="reply_email" value="{$DATA.reply_email}" maxlength="200">
                            <div class="form-text">{$LANG->getModule('mail_reply_email_default')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6 col-lg-6 col-xxl-5 offset-sm-6 offset-lg-4 offset-xxl-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="force_reply" value="1"{if $DATA.force_reply} checked{/if} role="switch" id="element_force_reply">
                                <label class="form-check-label" for="element_force_reply">{$LANG->getModule('mail_force_reply')}</label>
                            </div>
                            <div class="form-text">{$LANG->getModule('mail_force_reply_note')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_mail_tpl" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('mail_tpl')}</label>
                        <div class="col-sm-6 col-lg-6 col-xxl-5">
                            <select name="mail_tpl" id="element_mail_tpl" class="form-select w-auto mw-100">
                                {foreach from=$MAIL_TPL_OPT key=key item=opt}
                                <option value="{$key}"{if not empty($key) and $key eq $DATA.mail_tpl} selected{/if}>{$opt}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6 col-lg-6 col-xxl-5 offset-sm-6 offset-lg-4 offset-xxl-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="notify_email_error" value="1"{if $DATA.notify_email_error} checked{/if} role="switch" id="element_notify_email_error">
                                <label class="form-check-label" for="element_notify_email_error">{$LANG->getModule('notify_email_error')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end pt-0">{$LANG->getModule('dkim_included')}</div>
                        <div class="col-sm-6 col-lg-6 col-xxl-5">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="dkim_included_smtp" name="dkim_included[]" value="smtp"{if in_array('smtp', $DATA.dkim_included)} checked{/if}>
                                <label class="form-check-label" for="dkim_included_smtp">{$LANG->getModule('type_smtp')}</label>
                            </div>
                            {if empty($GCONFIG.idsite)}
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="dkim_included_sendmail" name="dkim_included[]" value="sendmail"{if in_array('sendmail', $DATA.dkim_included)} checked{/if}>
                                <label class="form-check-label" for="dkim_included_sendmail">{$LANG->getModule('type_linux')}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="dkim_included_mail" name="dkim_included[]" value="mail"{if in_array('mail', $DATA.dkim_included)} checked{/if}>
                                <label class="form-check-label" for="dkim_included_mail">{$LANG->getModule('type_phpmail')}</label>
                            </div>
                            {/if}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end pt-0">{$LANG->getModule('smime_included')}</div>
                        <div class="col-sm-6 col-lg-6 col-xxl-5">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="smime_included_smtp" name="smime_included[]" value="smtp"{if in_array('smtp', $DATA.smime_included)} checked{/if}>
                                <label class="form-check-label" for="smime_included_smtp">{$LANG->getModule('type_smtp')}</label>
                            </div>
                            {if empty($GCONFIG.idsite)}
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="smime_included_sendmail" name="smime_included[]" value="sendmail"{if in_array('sendmail', $DATA.smime_included)} checked{/if}>
                                <label class="form-check-label" for="smime_included_sendmail">{$LANG->getModule('type_linux')}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="smime_included_mail" name="smime_included[]" value="mail"{if in_array('mail', $DATA.smime_included)} checked{/if}>
                                <label class="form-check-label" for="smime_included_mail">{$LANG->getModule('type_phpmail')}</label>
                            </div>
                            {/if}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end pt-0">{$LANG->getModule('mail_config')}</div>
                        <div class="col-sm-6 col-lg-6 col-xxl-5">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="mailer_mode_smtp" name="mailer_mode" value="smtp"{if $DATA.mailer_mode eq 'smtp'} checked{/if}>
                                <label class="form-check-label" for="mailer_mode_smtp">{$LANG->getModule('type_smtp')}</label>
                            </div>
                            {if empty($GCONFIG.idsite)}
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="mailer_mode_sendmail" name="mailer_mode" value="sendmail"{if $DATA.mailer_mode eq 'sendmail'} checked{/if}>
                                <label class="form-check-label" for="mailer_mode_sendmail">{$LANG->getModule('type_linux')}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="mailer_mode_mail" name="mailer_mode" value="mail"{if $DATA.mailer_mode eq 'mail'} checked{/if}>
                                <label class="form-check-label" for="mailer_mode_mail">{$LANG->getModule('type_phpmail')}</label>
                            </div>
                            {/if}
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="mailer_mode_no" name="mailer_mode" value="no"{if $DATA.mailer_mode eq 'no'} checked{/if}>
                                <label class="form-check-label" for="mailer_mode_no">{$LANG->getModule('verify_peer_ssl_no')}</label>
                            </div>
                        </div>
                    </div>
                    <div id="ctn_mailer_mode_smtp"{if $DATA.mailer_mode neq 'smtp'} class="d-none"{/if}>
                        <div class="pb-3">
                            <div class="vstack gap-3">
                                <div class="row">
                                    <label for="element_smtp_host" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('outgoing')}</label>
                                    <div class="col-sm-6 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control" id="element_smtp_host" name="smtp_host" value="{$DATA.smtp_host}">
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="element_smtp_port" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('outgoing_port')}</label>
                                    <div class="col-sm-6 col-lg-6 col-xxl-5">
                                        <input type="number" min="0" class="form-control w-auto mw-100" id="element_smtp_port" name="smtp_port" value="{$DATA.smtp_port}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end pt-0">{$LANG->getModule('incoming_ssl')}</div>
                                    <div class="col-sm-6 col-lg-6 col-xxl-5">
                                        {foreach from=$SMTP_ENCRYPTED_ARRAY key=key item=value}
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="smtp_ssl_{$key}" name="smtp_ssl" value="{$key}"{if $DATA.smtp_ssl eq $key} checked{/if}>
                                            <label class="form-check-label" for="smtp_ssl_{$key}">{$value}</label>
                                        </div>
                                        {/foreach}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end pt-0">{$LANG->getModule('verify_peer_ssl')}</div>
                                    <div class="col-sm-6 col-lg-6 col-xxl-5">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="verify_peer_ssl_1" name="verify_peer_ssl" value="1"{if $DATA.verify_peer_ssl eq 1} checked{/if}>
                                            <label class="form-check-label" for="verify_peer_ssl_1">{$LANG->getModule('verify_peer_ssl_yes')}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="verify_peer_ssl_0" name="verify_peer_ssl" value="0"{if $DATA.verify_peer_ssl eq 0} checked{/if}>
                                            <label class="form-check-label" for="verify_peer_ssl_0">{$LANG->getModule('verify_peer_ssl_no')}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end pt-0">{$LANG->getModule('verify_peer_name_ssl')}</div>
                                    <div class="col-sm-6 col-lg-6 col-xxl-5">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="verify_peer_name_ssl_1" name="verify_peer_name_ssl" value="1"{if $DATA.verify_peer_name_ssl eq 1} checked{/if}>
                                            <label class="form-check-label" for="verify_peer_name_ssl_1">{$LANG->getModule('verify_peer_ssl_yes')}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="verify_peer_name_ssl_0" name="verify_peer_name_ssl" value="0"{if $DATA.verify_peer_name_ssl eq 0} checked{/if}>
                                            <label class="form-check-label" for="verify_peer_name_ssl_0">{$LANG->getModule('verify_peer_ssl_no')}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="element_smtp_username" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('smtp_login')}</label>
                                    <div class="col-sm-6 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control" id="element_smtp_username" name="smtp_username" value="{$DATA.smtp_username}">
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="element_smtp_password" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('smtp_pass')}</label>
                                    <div class="col-sm-6 col-lg-6 col-xxl-5">
                                        <input type="password" class="form-control" id="element_smtp_password" name="smtp_password" value="{$DATA.smtp_password}" autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 offset-sm-6 offset-lg-4 offset-xxl-3">
                            <input type="hidden" name="submitsave" value="1">
                            <input type="hidden" name="checkss" value="{$DATA.checkss}">
                            <div class="hstack gap-1 flex-wrap">
                                <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                                <button type="button" class="btn btn-secondary" data-toggle="smtp_test">{$LANG->getModule('smtp_test')}</button>
                                <button type="button" class="btn btn-secondary" data-toggle="form_reset">{$LANG->getGlobal('reset')}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-dkim" aria-expanded="false" aria-controls="collapse-dkim">
                <span class="fw-medium fs-5">{$LANG->getModule('DKIM_signature')}</span>
            </button>
        </div>
        <div id="collapse-dkim" data-loaded="false" class="accordion-collapse collapse" data-bs-parent="#accordion-settings">
            <div id="dkim_list"></div>
            <ul class="list-group list-group-flush list-group-accordion">
                <li class="list-group-item bg-body-secondary fw-medium text-center">{$LANG->getModule('DKIM_add')}</li>
            </ul>
            <div class="accordion-body">
                <form id="dkimaddForm" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post">
                    <input type="hidden" name="checkss" value="{$DATA.checkss}">
                    <input type="hidden" name="dkimadd" value="1">
                    <div class="row mb-3">
                        <label for="element_domain" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('DKIM_domain')}</label>
                        <div class="col-sm-6 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="element_domain" name="domain" value="" maxlength="255">
                            <div class="form-text">{$LANG->getModule('DKIM_note')}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 offset-sm-6 offset-lg-4 offset-xxl-3">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('DKIM_add_button')}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-cert" aria-expanded="false" aria-controls="collapse-cert">
                <span class="fw-medium fs-5">{$LANG->getModule('smime_certificate')}</span>
            </button>
        </div>
        <div id="collapse-cert" data-loaded="false" class="accordion-collapse collapse" data-bs-parent="#accordion-settings">
            <div id="cert_list"></div>
            <ul class="list-group list-group-flush list-group-accordion">
                <li class="list-group-item bg-body-secondary fw-medium text-center">{$LANG->getModule('smime_add')}</li>
            </ul>
            <div class="accordion-body">
                <form id="certAddForm" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post" enctype="multipart/form-data" data-prompt="{$LANG->getModule('smime_passphrase')}">
                    <input type="hidden" name="checkss" value="{$DATA.checkss}">
                    <input type="hidden" name="smimeadd" value="1">
                    <input type="hidden" name="overwrite" value="0">
                    <div class="row mb-3">
                        <label for="element_pkcs12" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('smime_pkcs12')}</label>
                        <div class="col-sm-6 col-lg-6 col-xxl-5">
                            <input type="file" class="form-control" id="element_pkcs12" name="pkcs12" accept=".p12, .pfx">
                            <div class="form-text">{$LANG->getModule('smime_note')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_passphrase" class="col-sm-6 col-lg-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('smime_passphrase')}</label>
                        <div class="col-sm-6 col-lg-6 col-xxl-5">
                            <input type="password" class="form-control" id="element_passphrase" name="passphrase" value="" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 offset-sm-6 offset-lg-4 offset-xxl-3">
                            <div class="hstack gap-1 flex-wrap">
                                <button type="submit" class="btn btn-primary">{$LANG->getModule('DKIM_add_button')}</button>
                                <button type="button" class="btn btn-secondary" data-toggle="cert_other_add_show">{$LANG->getModule('smime_self_declare')}</button>
                            </div>
                        </div>
                    </div>
                </form>
                <form class="d-none" id="certOtherAddForm" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post">
                    <div class="pt-3">
                        <hr />
                        <div class="mb-3">
                            <label class="form-label fw-medium" for="smime_certificate">{$LANG->getModule('smime_certificate_content')} <span class="text-danger">(*)</span></label>
                            <textarea class="form-control" id="smime_certificate" name="smime_certificate" rows="10" placeholder="-----BEGIN CERTIFICATE-----&#10;...&#10;-----END CERTIFICATE-----"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium" for="smime_private_key">{$LANG->getModule('smime_private_key')} <span class="text-danger">(*)</span></label>
                            <textarea class="form-control" id="smime_private_key" name="smime_private_key" rows="10" placeholder="-----BEGIN PRIVATE KEY-----&#10;...&#10;-----END PRIVATE KEY-----"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium" for="smime_chain">{$LANG->getModule('smime_chain_certificates')}</label>
                            <textarea class="form-control" id="smime_chain" name="smime_chain" rows="10" placeholder="-----BEGIN CERTIFICATE-----&#10;...&#10;-----END CERTIFICATE-----&#10;-----BEGIN CERTIFICATE-----&#10;...&#10;-----END CERTIFICATE-----&#10;..."></textarea>
                            <div class="form-text">{$LANG->getModule('smime_chain_certificates_note')}</div>
                        </div>
                        <div class="text-center">
                            <input type="hidden" name="checkss" value="{$DATA.checkss}">
                            <input type="hidden" name="smimeadd" value="1">
                            <input type="hidden" name="overwrite" value="0">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('smime_add_button')}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="sign-read" class="modal fade" tabindex="-1" aria-labelledby="sign-read-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="sign-read-label"></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
