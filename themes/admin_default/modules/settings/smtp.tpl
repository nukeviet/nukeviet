<!-- BEGIN: smtp -->
<div class="panel panel-default">
    <div class="panel-body">
        <!-- BEGIN: error -->
        <div class="alert alert-danger">{ERROR}</div>
        <!-- END: error -->
        <!-- BEGIN: testmail_fail -->
        <div class="alert alert-danger">
            <strong>{LANG.smtp_test_fail}: </strong> {TEST_MESSAGE}
        </div>
        <!-- END: testmail_fail -->
        <!-- BEGIN: testmail_success -->
        <div class="alert alert-success">{LANG.smtp_test_success}</div>
        <!-- END: testmail_success -->
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-horizontal m-bottom">
            <div class="form-group">
                <label for="sender_name" class="col-sm-6 control-label">{LANG.mail_sender_name}:</label>
                <div class="col-sm-18 col-md-14 col-lg-10">
                    <input type="text" class="form-control" id="sender_name" name="sender_name" value="{DATA.sender_name}" maxlength="200">
                    <div class="help-block mb-0">{LANG.mail_sender_name_default}</div>
                </div>
            </div>
            <div class="form-group">
                <label for="sender_email" class="col-sm-6 control-label">{LANG.mail_sender_email}:</label>
                <div class="col-sm-18 col-md-14 col-lg-10">
                    <input type="email" class="form-control" id="sender_email" name="sender_email" value="{DATA.sender_email}" maxlength="200">
                    <div class="help-block mb-0">{LANG.mail_sender_email_default}</div>
                </div>
            </div>
            <div class="form-group">
                <label for="reply_name" class="col-sm-6 control-label">{LANG.mail_reply_name}:</label>
                <div class="col-sm-18 col-md-14 col-lg-10">
                    <input type="text" class="form-control" id="reply_name" name="reply_name" value="{DATA.reply_name}" maxlength="200">
                    <div class="help-block mb-0">{LANG.mail_reply_name_default}</div>
                </div>
            </div>
            <div class="form-group">
                <label for="reply_email" class="col-sm-6 control-label">{LANG.mail_reply_email}:</label>
                <div class="col-sm-18 col-md-14 col-lg-10">
                    <input type="email" class="form-control" id="reply_email" name="reply_email" value="{DATA.reply_email}" maxlength="200">
                    <div class="help-block mb-0">{LANG.mail_reply_email_default}</div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-18 col-md-14 col-lg-10 col-sm-offset-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="force_sender" value="1"{DATA.force_sender}> {LANG.mail_force_sender}
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-18 col-md-14 col-lg-10 col-sm-offset-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="force_reply" value="1"{DATA.force_reply}> {LANG.mail_force_reply}
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-18 col-md-14 col-lg-10 col-sm-offset-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="notify_email_error" value="1"{DATA.notify_email_error}> {LANG.notify_email_error}
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.mail_config}:</label>
                <div class="col-sm-18">
                    <div class="radio">
                        <label class="radio-inline">
                            <input type="radio" name="mailer_mode" value="smtp"{DATA.mailer_mode_smtpt}> {LANG.type_smtp}
                        </label>
                        <!-- BEGIN: mailhost -->
                        <label class="radio-inline">
                            <input type="radio" name="mailer_mode" value="sendmail"{DATA.mailer_mode_sendmail}> {LANG.type_linux}
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="mailer_mode" value="mail"{DATA.mailer_mode_phpmail}> {LANG.type_phpmail}
                        </label>
                        <!-- END: mailhost -->
                        <label class="radio-inline">
                            <input type="radio" name="mailer_mode" value="no"{DATA.mailer_mode_no}> {LANG.verify_peer_ssl_no}
                        </label>
                    </div>
                </div>
            </div>
            <div {DATA.mailer_mode_smtpt_show} id="smtp">
                <div class="form-group">
                    <div class="col-sm-18 col-md-14 col-lg-10 col-sm-offset-6">
                        <h3 class="mb-0"><strong>{LANG.smtp_server}</strong></h3>
                    </div>
                </div>
                <div class="form-group">
                    <label for="smtp_host" class="col-sm-6 control-label">{LANG.outgoing}:</label>
                    <div class="col-sm-18 col-md-14 col-lg-10">
                        <input class="form-control" type="text" name="smtp_host" id="smtp_host" value="{DATA.smtp_host}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="smtp_port" class="col-sm-6 control-label">{LANG.outgoing_port}:</label>
                    <div class="col-sm-18 col-md-14 col-lg-10">
                        <div class="form-inline">
                            <input class="form-control" type="number" name="smtp_port" id="smtp_port" value="{DATA.smtp_port}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="smtp_ssl" class="col-sm-6 control-label">{LANG.incoming_ssl}:</label>
                    <div class="col-sm-18 col-md-14 col-lg-10">
                        <div class="form-inline">
                            <select name="smtp_ssl" id="smtp_ssl" class="form-control">
                                <!-- BEGIN: encrypted_connection -->
                                <option value="{EMCRYPTED.id}" {EMCRYPTED.sl}>{EMCRYPTED.value}</option>
                                <!-- END: encrypted_connection -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-6 control-label">{LANG.verify_peer_ssl}:</label>
                    <div class="col-sm-18">
                        <div class="radio">
                            <label class="radio-inline">
                                <input type="radio" name="verify_peer_ssl" value="1"{PEER_SSL_YES}> {LANG.verify_peer_ssl_yes}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="verify_peer_ssl" value="0"{PEER_SSL_NO}> {LANG.verify_peer_ssl_no}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-6 control-label">{LANG.verify_peer_name_ssl}:</label>
                    <div class="col-sm-18">
                        <div class="radio">
                            <label class="radio-inline">
                                <input type="radio" name="verify_peer_name_ssl" value="1"{PEER_NAME_SSL_YES}> {LANG.verify_peer_ssl_yes}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="verify_peer_name_ssl" value="0"{PEER_NAME_SSL_NO}> {LANG.verify_peer_ssl_no}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-18 col-md-14 col-lg-10 col-sm-offset-6">
                        <h3 class="mb-0"><strong>{LANG.smtp_username}</strong></h3>
                    </div>
                </div>
                <div class="form-group">
                    <label for="smtp_username" class="col-sm-6 control-label">{LANG.smtp_login}:</label>
                    <div class="col-sm-18 col-md-14 col-lg-10">
                        <input class="form-control" type="text" name="smtp_username" id="smtp_username" value="{DATA.smtp_username}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="smtp_password" class="col-sm-6 control-label">{LANG.smtp_pass}:</label>
                    <div class="col-sm-18 col-md-14 col-lg-10">
                        <input class="form-control" type="password" name="smtp_password" id="smtp_password" value="{DATA.smtp_password}" autocomplete="new-password">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-18 col-md-14 col-lg-10 col-sm-offset-6">
                    <input type="hidden" name="checkss" value="{DATA.checkss}" />
                    <input type="submit" name="submitsave" value="{LANG.submit}" class="btn btn-primary">
                    <!-- BEGIN: testmail -->
                    <input type="submit" name="submittest" value="{LANG.smtp_test}" class="btn btn-default">
                    <!-- END: testmail -->
                </div>
            </div>
        </form>
        <!-- BEGIN: testmail1 -->
        <div class="alert alert-info m-bottom-none">{LANG.smtp_test_note}</div>
        <!-- END: testmail1 -->
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="m-bottom-none"><strong>{LANG.smime_certificate}</strong></h3> 
    </div>
    <div class="panel-body">
        <div class="m-bottom">{LANG.smime_note}</div>
        <!-- BEGIN: cert_list -->
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <!-- BEGIN: loop -->
            <div class="loop panel panel-default">
                <a class="panel-heading btn-block" role="button" id="certificate{CERT.num}" data-toggle="collapse" data-parent="#accordion" href="#cert-collapse{CERT.num}" aria-expanded="false" aria-controls="cert-collapse{CERT.num}">
                    <i class="fa fa-certificate"></i> {CERT.email}
                </a>
                <div id="cert-collapse{CERT.num}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="certificate{CERT.num}" data-email="{CERT.email}" data-loaded="false">
                    <div class="panel-body cert-content"></div>
                </div>
            </div>
            <!-- END: loop -->
        </div>
<script>
function smimedownload(email) {
    var person = prompt("{LANG.smime_download_passphrase}", "");
    if (person != null && person != '') {
        window.location.href = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=smtp&smimedownload=1&email=" + email + "&passphrase=" + encodeURIComponent(person);
    }
}
function smimedel(email) {
    if (confirm('{LANG.smime_del_confirm}') == true) {
        $.ajax({
            type: 'POST',
            url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=smtp",
            data: {'smimedel':1,'email':email},
            success: function() {
                window.location.href = window.location.href
            }
        })
    }
}
$(function() {
    $('[id*=cert-collapse]').on('show.bs.collapse', function () {
        $(this).parent().toggleClass('panel-default, panel-primary');
        if ($(this).attr('data-loaded') === 'false') {
            var t = $(this);
            $.ajax({
                type: 'POST',
                url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=smtp",
                data: {'smimeread':1,'email':t.data('email')},
                success: function(data) {
    				$('.cert-content', t).html(data);
                    $(t).attr('data-loaded', 'true')
                }
            });
        }
    }).on('hidden.bs.collapse', function () {
        $(this).parent().toggleClass('panel-default, panel-primary')
    })
})
</script>
        <!-- END: cert_list -->
        <form class="form-horizontal m-bottom" id="certaddForm" action="{SMIMEADD_ACTION}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div class="col-sm-18 col-sm-push-6 col-md-14 col-md-push-6 col-lg-10 col-lg-push-6">
                    <strong>{LANG.smime_add}</strong>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.smime_pkcs12}:</label>
                <div class="col-sm-18 col-md-14 col-lg-10">
                    <input type="file" name="pkcs12" class="form-control" accept=".p12, .pfx"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.smime_passphrase}:</label>
                <div class="col-sm-18 col-md-14 col-lg-10">
                    <input type="password" name="passphrase" class="form-control" maxlength="100"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-18 col-sm-push-6 col-md-14 col-md-push-6 col-lg-10 col-lg-push-6">
                    <input type="hidden" name="checkss" value="{DATA.checkss}" />
                    <input type="hidden" name="smimeadd" value="1" />
                    <input type="hidden" name="overwrite" value="0" />
                    <input type="submit" name="submitsave" value="{LANG.smime_add_button}" class="btn btn-primary"/>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
$(function() {
	$("#certaddForm").submit(function(e) {
		e.preventDefault();
        var data = new FormData(this),
            th = this;
        if ('' == $('[name=pkcs12]', this).val()) {
            return false
        }
        $('input, button', this).prop('disabled', true);
		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			data: data,
			cache: false,
			processData: false,
			contentType: false,
			dataType: "json"
		}).done(function(a) {
			if ('error' == a.status) {
                alert(a.mess);
                $('input, button', th).prop('disabled', false);
			} else if ('overwrite' == a.status) {
                $('input, button', th).prop('disabled', false);
                if (confirm(a.mess) == true) {
                    $('[name=overwrite]', th).val('1');
                    $(th).submit()
                }
			} else {
                window.location.href = window.location.href
			}
		});
	})
})
</script>
<!-- END: smtp -->
<!-- BEGIN: smimeread -->
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
                <button type="button" class="btn btn-danger btn-xs" onclick="smimedel('{EMAIL}');">{LANG.smime_del}</button>
                <button type="button" class="btn btn-primary btn-xs" onclick="smimedownload('{EMAIL}');">{LANG.smime_download}</button>
            </td>
        </tr>
    </tfoot>
</table>
<!-- END: smimeread -->