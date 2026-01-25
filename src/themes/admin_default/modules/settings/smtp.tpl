<!-- BEGIN: smtp -->
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div class="panel-group" id="accordion-settings" role="tablist" aria-multiselectable="true">
    <div class="panel panel-primary">
        <a class="panel-heading" role="tab" id="settings-sector-heading" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settings" href="#settings-sector" aria-expanded="true" aria-controls="settings-sector">
            {LANG.general_settings}
        </a>
        <div id="settings-sector" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="settings-sector-heading">
            <form id="sendmail-settings" action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" data-mailer-mode-default="{MAILER_MODE_DEFAULT}">
                <ul class="list-group type2n1 mb-0">
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-11 control-label"><strong>{LANG.mail_sender_name}</strong></label>
                            <div class="col-sm-13">
                                <input type="text" class="form-control" id="sender_name" name="sender_name" value="{DATA.sender_name}" maxlength="200">
                                <div class="help-block mb-0">{LANG.mail_sender_name_default}</div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-11 control-label"><strong>{LANG.mail_sender_email}</strong></label>
                            <div class="col-sm-13">
                                <input type="email" class="form-control" id="sender_email" name="sender_email" value="{DATA.sender_email}" maxlength="200">
                                <div class="help-block mb-0">{LANG.mail_sender_email_default}</div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-11 control-label hidden-xs"><strong>{LANG.mail_force_sender}</strong></label>
                            <div class="col-sm-13">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="force_sender" value="1" {DATA.force_sender}>
                                    <strong class="visible-xs-inline-block">{LANG.mail_force_sender}</strong>
                                </label>
                                <div class="help-block mb-0">{LANG.mail_force_sender_note}</div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-11 control-label"><strong>{LANG.mail_reply_name}</strong></label>
                            <div class="col-sm-13">
                                <input type="text" class="form-control" id="reply_name" name="reply_name" value="{DATA.reply_name}" maxlength="200">
                                <div class="help-block mb-0">{LANG.mail_reply_name_default}</div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-11 control-label"><strong>{LANG.mail_reply_email}</strong></label>
                            <div class="col-sm-13">
                                <input type="email" class="form-control" id="reply_email" name="reply_email" value="{DATA.reply_email}" maxlength="200">
                                <div class="help-block mb-0">{LANG.mail_reply_email_default}</div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-11 control-label hidden-xs"><strong>{LANG.mail_force_reply}</strong></label>
                            <div class="col-sm-13">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="force_reply" value="1" {DATA.force_reply}>
                                    <strong class="visible-xs-inline-block">{LANG.mail_force_reply}</strong>
                                </label>
                                <div class="help-block mb-0">{LANG.mail_force_reply_note}</div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-11 control-label hidden-xs"><strong>{LANG.mail_tpl}</strong></label>
                            <div class="col-sm-13">
                                <select name="mail_tpl" class="form-control" style="width: fit-content;">
                                    <!-- BEGIN: mail_tpl -->
                                    <option value="{MAIL_TPL.val}"{MAIL_TPL.sel}>{MAIL_TPL.name}</option>
                                    <!-- END: mail_tpl -->
                                </select>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-11 control-label hidden-xs"><strong>{LANG.notify_email_error}</strong></label>
                            <div class="col-sm-13">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="notify_email_error" value="1" {DATA.notify_email_error}>
                                    <strong class="visible-xs-inline-block">{LANG.notify_email_error}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-11 control-label"><strong>{LANG.dkim_included}</strong></label>
                            <div class="col-sm-13">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="dkim_included[]" value="smtp" {DATA.smtp_dkim_included} /> {LANG.type_smtp}
                                </label>
                                <!-- BEGIN: mailhost2 -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="dkim_included[]" value="sendmail" {DATA.sendmail_dkim_included} /> {LANG.type_linux}
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="dkim_included[]" value="mail" {DATA.mail_dkim_included} /> {LANG.type_phpmail}
                                </label>
                                <!-- END: mailhost2 -->
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-11 control-label"><strong>{LANG.smime_included}</strong></label>
                            <div class="col-sm-13">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="smime_included[]" value="smtp" {DATA.smtp_smime_included} /> {LANG.type_smtp}
                                </label>
                                <!-- BEGIN: mailhost3 -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="smime_included[]" value="sendmail" {DATA.sendmail_smime_included} /> {LANG.type_linux}
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="smime_included[]" value="mail" {DATA.mail_smime_included} /> {LANG.type_phpmail}
                                </label>
                                <!-- END: mailhost3 -->
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-11 control-label"><strong>{LANG.mail_config}</strong></label>
                            <div class="col-sm-13">
                                <label class="radio-inline">
                                    <input type="radio" class="form-control" name="mailer_mode" value="smtp" {DATA.mailer_mode_smtpt}> {LANG.type_smtp}
                                </label>
                                <!-- BEGIN: mailhost -->
                                <label class="radio-inline">
                                    <input type="radio" class="form-control" name="mailer_mode" value="sendmail" {DATA.mailer_mode_sendmail}> {LANG.type_linux}
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" class="form-control" name="mailer_mode" value="mail" {DATA.mailer_mode_phpmail}> {LANG.type_phpmail}
                                </label>
                                <!-- END: mailhost -->
                                <label class="radio-inline">
                                    <input type="radio" class="form-control" name="mailer_mode" value="no" {DATA.mailer_mode_no}> {LANG.verify_peer_ssl_no}
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item title smtp" {DATA.mailer_mode_smtpt_show}>
                        <h4 class="text-center mb-0"><strong>{LANG.smtp_server}</strong></h4>
                    </li>

                    <li class="list-group-item smtp" {DATA.mailer_mode_smtpt_show}>
                        <div class="form-group mb-0">
                            <label class="col-sm-13 control-label"><strong>{LANG.outgoing}</strong></label>
                            <div class="col-sm-11">
                                <input class="form-control" type="text" name="smtp_host" id="smtp_host" value="{DATA.smtp_host}">
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item smtp" {DATA.mailer_mode_smtpt_show}>
                        <div class="form-group mb-0">
                            <label class="col-sm-13 control-label"><strong>{LANG.outgoing_port}</strong></label>
                            <div class="col-sm-11">
                                <input class="form-control w100 number" type="text" name="smtp_port" id="smtp_port" value="{DATA.smtp_port}">
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item smtp" {DATA.mailer_mode_smtpt_show}>
                        <div class="form-group mb-0">
                            <label class="col-sm-13 control-label"><strong>{LANG.incoming_ssl}</strong></label>
                            <div class="col-sm-11">
                                <!-- BEGIN: encrypted_connection -->
                                <label class="radio-inline">
                                    <input type="radio" class="form-control" name="smtp_ssl" value="{EMCRYPTED.id}" {EMCRYPTED.checked}> {EMCRYPTED.value}
                                </label>
                                <!-- END: encrypted_connection -->
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item smtp" {DATA.mailer_mode_smtpt_show}>
                        <div class="form-group mb-0">
                            <label class="col-sm-13 control-label"><strong>{LANG.verify_peer_ssl}</strong></label>
                            <div class="col-sm-11">
                                <label class="radio-inline">
                                    <input type="radio" name="verify_peer_ssl" value="1" {PEER_SSL_YES}> {LANG.verify_peer_ssl_yes}
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="verify_peer_ssl" value="0" {PEER_SSL_NO}> {LANG.verify_peer_ssl_no}
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item smtp" {DATA.mailer_mode_smtpt_show}>
                        <div class="form-group mb-0">
                            <label class="col-sm-13 control-label"><strong>{LANG.verify_peer_name_ssl}</strong></label>
                            <div class="col-sm-11">
                                <label class="radio-inline">
                                    <input type="radio" name="verify_peer_name_ssl" value="1" {PEER_NAME_SSL_YES}> {LANG.verify_peer_ssl_yes}
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="verify_peer_name_ssl" value="0" {PEER_NAME_SSL_NO}> {LANG.verify_peer_ssl_no}
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item title smtp" {DATA.mailer_mode_smtpt_show}>
                        <h4 class="text-center mb-0"><strong>{LANG.smtp_username}</strong></h4>
                    </li>

                    <li class="list-group-item smtp" {DATA.mailer_mode_smtpt_show}>
                        <div class="form-group mb-0">
                            <label class="col-sm-13 control-label"><strong>{LANG.smtp_login}</strong></label>
                            <div class="col-sm-11">
                                <input class="form-control" type="text" name="smtp_username" id="smtp_username" value="{DATA.smtp_username}">
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item smtp" {DATA.mailer_mode_smtpt_show}>
                        <div class="form-group mb-0">
                            <label class="col-sm-13 control-label"><strong>{LANG.smtp_pass}</strong></label>
                            <div class="col-sm-11">
                                <input class="form-control" type="password" name="smtp_password" id="smtp_password" value="{DATA.smtp_password}" autocomplete="new-password">
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item text-center">
                        <input type="hidden" name="submitsave" value="1" />
                        <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        <button type="submit" class="btn btn-primary">{LANG.submit}</button>
                        <button type="button" class="btn btn-default" data-toggle="smtp_test">{LANG.smtp_test}</button>
                        <button type="button" class="btn btn-default" data-toggle="form_reset">{GLANG.reset}</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="panel panel-primary">
        <a id="dkim-sector-heading" class="panel-heading collapsed" style="display: block;text-decoration:none" role="tab" data-toggle="collapse" data-parent="#accordion-settings" href="#dkim-sector" aria-expanded="false" aria-controls="dkim-sector">
            {LANG.DKIM_signature}
        </a>
        <div id="dkim-sector" class="panel-collapse collapse" role="tabpanel" aria-labelledby="dkim-sector-heading" data-loaded="false">
            <div class="list-group" id="dkim_list"></div>
            <form class="form-horizontal" id="dkimaddForm" action="{FORM_ACTION}" method="post">
                <input type="hidden" name="checkss" value="{DATA.checkss}" />
                <input type="hidden" name="dkimadd" value="1" />
                <ul class="list-group type2n1 mb-0">
                    <li class="list-group-item title">
                        <h4 class="text-center mb-0"><strong>{LANG.DKIM_add}</strong></h4>
                    </li>

                    <li class="list-group-item form-inline text-center">
                        <label><strong>{LANG.DKIM_domain}</strong></label>
                        <div class="input-group">
                            <input type="text" name="domain" class="form-control" maxlength="255" />
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary">{LANG.DKIM_add_button}</button>
                            </span>
                        </div>
                        <div class="help-block mb-0">{LANG.DKIM_note}</div>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="panel panel-primary">
        <a id="cert-sector-heading" class="panel-heading collapsed" style="display: block;text-decoration:none" role="tab" data-toggle="collapse" data-parent="#accordion-settings" href="#cert-sector" aria-expanded="false" aria-controls="cert-sector">
            {LANG.smime_certificate}
        </a>
        <div id="cert-sector" class="panel-collapse collapse" role="tabpanel" aria-labelledby="cert-sector-heading" data-loaded="false">
            <div class="list-group" id="cert_list"></div>
            <ul class="list-group type2n1 mb-0">
                <li class="list-group-item title">
                    <h4 class="text-center mb-0"><strong>{LANG.smime_add}</strong></h4>
                </li>

                <li class="list-group-item">
                    <div class="help-block mb-0">{LANG.smime_note}</div>
                </li>

                <li class="list-group-item">
                    <form class="form-horizontal" id="certAddForm" action="{FORM_ACTION}" method="post" enctype="multipart/form-data" data-prompt="{LANG.smime_passphrase}">
                        <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        <input type="hidden" name="smimeadd" value="1" />
                        <input type="hidden" name="overwrite" value="0" />
                        <input type="hidden" name="passphrase" value="" />
                        <div class="form-inline text-center">
                            <label><strong>{LANG.smime_pkcs12}</strong></label>
                            <input type="file" name="pkcs12" class="form-control" accept=".p12, .pfx" />
                        </div>
                    </form>
                </li>

                <li class="list-group-item">
                    <div class="text-center">
                        <button type="button" class="btn btn-default active" data-toggle="cert_other_add_show">{LANG.smime_self_declare}</button>
                    </div>
                    <form class="form-horizontal" id="certOtherAddForm" action="{FORM_ACTION}" method="post" style="display:none">
                        <div class="form-group">
                            <label class="required"><strong>{LANG.smime_certificate_content}</strong></label>
                            <textarea class="form-control" name="smime_certificate" style="height: 150px;" placeholder="-----BEGIN CERTIFICATE-----&#10;...&#10;-----END CERTIFICATE-----"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="required"><strong>{LANG.smime_private_key}</strong></label>
                            <textarea class="form-control" name="smime_private_key" style="height: 150px;" placeholder="-----BEGIN PRIVATE KEY-----&#10;...&#10;-----END PRIVATE KEY-----"></textarea>
                        </div>
                        <div class="form-group">
                            <label><strong>{LANG.smime_chain_certificates}</strong></label>
                            <textarea class="form-control" name="smime_chain" style="height: 150px;" placeholder="-----BEGIN CERTIFICATE-----&#10;...&#10;-----END CERTIFICATE-----&#10;-----BEGIN CERTIFICATE-----&#10;...&#10;-----END CERTIFICATE-----&#10;..."></textarea>
                            <div class="help-block">{LANG.smime_chain_certificates_note}</div>
                        </div>
                        <div class="text-center">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                            <input type="hidden" name="smimeadd" value="1" />
                            <input type="hidden" name="overwrite" value="0" />
                            <button type="submit" class="btn btn-primary">{LANG.smime_add_button}</button>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="sign-read" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- END: smtp -->
<!-- BEGIN: smimeread -->
<form method="post" action="{FORM_ACTION}" data-email="{EMAIL}" data-prompt="{LANG.smime_download_passphrase}" data-confirm="{LANG.smime_del_confirm}">
    <input type="hidden" name="email" value="{EMAIL}" />
    <input type="hidden" name="passphrase" value="" />
    <input type="hidden" name="smimedownload" value="1" />
    <table class="table table-condensed table-bordered m-bottom-none">
        <tbody>
            <tr>
                <td>{LANG.smime_cn}:</td>
                <td>{SMIMEREAD.subject.CN}</td>
            </tr>
            <tr>
                <td>{LANG.smime_issuer_cn}:</td>
                <td>{SMIMEREAD.issuer.O}</td>
            </tr>
            <tr>
                <td>{LANG.smime_subjectAltName}:</td>
                <td>{SMIMEREAD.extensions.subjectAltName}</td>
            </tr>
            <tr>
                <td>{LANG.smime_validFrom}:</td>
                <td>{SMIMEREAD.validFrom_format}</td>
            </tr>
            <tr>
                <td>{LANG.smime_validTo}:</td>
                <td>{SMIMEREAD.validTo_format}</td>
            </tr>
            <tr>
                <td>{LANG.smime_signatureTypeSN}:</td>
                <td>{SMIMEREAD.signatureTypeSN}</td>
            </tr>
            <tr>
                <td>{LANG.smime_purposes}:</td>
                <td>{SMIMEREAD.purposes_list}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-center">
                    <button type="button" class="btn btn-primary" data-toggle="smimedownload">{LANG.smime_download}</button>
                    <button type="button" class="btn btn-danger" data-toggle="smimedel">{LANG.smime_del}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
                </td>
            </tr>
        </tfoot>
    </table>
</form>
<!-- END: smimeread -->
<!-- BEGIN: dkimread -->
<div style="position: relative;" class="item" data-domain="{DOMAIN}" data-confirm="{LANG.dkim_del_confirm}">
    <!-- BEGIN: unverified -->
    <div class="m-bottom text-center"><strong>{LANG.DKIM_authentication}</strong></div>
    <div class="m-bottom">{VERIFY_NOTE}</div>
    <!-- END: unverified -->
    <!-- BEGIN: verified -->
    <div class="m-bottom text-center"><strong>{LANG.DKIM_verified}</strong></div>
    <!-- END: verified -->
    <div class="m-bottom">
        <label>{LANG.DKIM_TXT_host}:</label>
        <div class="input-group">
            <input type="text" class="form-control" id="dkim-txt-host" readonly="readonly" value="nv._domainkey" />
            <div class="input-group-btn">
                <button class="btn btn-default" type="button" data-clipboard-target="#dkim-txt-host" data-toggle="clipboard" data-title="{LANG.value_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
            </div>
        </div>
    </div>
    <div class="m-bottom">
        <label>{LANG.DKIM_TXT_value}:</label>
        <div class="input-group">
            <textarea class="form-control" readonly="readonly" id="pubkeyvalue" style="overflow:auto;word-break: break-all;width:100%;min-height:150px;resize: none;">{DNSVALUE}</textarea>
            <div class="input-group-btn">
                <button class="btn btn-default" type="button" data-clipboard-target="#pubkeyvalue" data-toggle="clipboard" data-title="{LANG.value_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false" style="min-height:150px"><i class="fa fa-copy"></i></button>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="button" class="btn btn-primary" data-toggle="dkimverify">
            <em class="fa fa-spinner fa-spin fa-fw load" style="display: none;"></em>
            <em class="fa fa-star-o ic"></em>
            <!-- BEGIN: unverified2 -->{LANG.dkim_verify}
            <!-- END: unverified2 -->
            <!-- BEGIN: verified2 -->{LANG.dkim_reverify}
            <!-- END: verified2 -->
        </button>
        <button type="button" class="btn btn-danger" data-toggle="dkimdel">{LANG.dkim_del}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
    </div>
</div>
<script>
    $(function() {
        if ($('[data-toggle=clipboard]').length && ClipboardJS) {
            var clipboard = new ClipboardJS('[data-toggle=clipboard]');
            clipboard.on('success', function(e) {
                $(e.trigger).tooltip('show');
                setTimeout(function() {
                    $(e.trigger).tooltip('destroy');
                }, 1000);
            });
        }
    })
</script>
<!-- END: dkimread -->

<!-- BEGIN: dkim_list -->
<!-- BEGIN: loop -->
<button type="button" class="list-group-item" data-toggle="dkim_read" data-domain="{DKIM.domain}">
    <span class="d-flex justify-content-between">
        <strong>{DKIM.domain}</strong>
        <span style="margin-left: 5px">
            {DKIM.title}
            <!-- BEGIN: if_verified -->
            <i class="fa fa-check"></i>
            <!-- END: if_verified -->
            <!-- BEGIN: if_unverified -->
            <i class="fa fa-question-circle"></i>
            <!-- END: if_unverified -->
        </span>
    </span>
</button>
<!-- END: loop -->
<!-- END: dkim_list -->

<!-- BEGIN: cert_list -->
<!-- BEGIN: loop -->
<button type="button" class="list-group-item" data-toggle="cert_read" data-email="{CERT.email}">
    <i class="fa fa-certificate text-danger"></i> {CERT.email}
</button>
<!-- END: loop -->
<!-- END: cert_list -->