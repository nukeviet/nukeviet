<!-- BEGIN: smtp -->
<div class="panel panel-primary">
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
                <label class="col-sm-6 control-label">{LANG.dkim_included}:</label>
                <div class="col-sm-18">
                    <div class="checkbox">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="dkim_included[]" value="smtp"{DATA.smtp_dkim_included}/> {LANG.type_smtp}
                        </label>
                        <!-- BEGIN: mailhost2 -->
                        <label class="checkbox-inline">
                            <input type="checkbox" name="dkim_included[]" value="sendmail"{DATA.sendmail_dkim_included}/> {LANG.type_linux}
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="dkim_included[]" value="mail"{DATA.mail_dkim_included}/> {LANG.type_phpmail}
                        </label>
                        <!-- END: mailhost2 -->
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.smime_included}:</label>
                <div class="col-sm-18">
                    <div class="checkbox">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="smime_included[]" value="smtp"{DATA.smtp_smime_included}/> {LANG.type_smtp}
                        </label>
                        <!-- BEGIN: mailhost3 -->
                        <label class="checkbox-inline">
                            <input type="checkbox" name="smime_included[]" value="sendmail"{DATA.sendmail_smime_included}/> {LANG.type_linux}
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="smime_included[]" value="mail"{DATA.mail_smime_included}/> {LANG.type_phpmail}
                        </label>
                        <!-- END: mailhost3 -->
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

<a id="dkim"></a>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="m-bottom-none"><strong>{LANG.DKIM_signature}</strong></h3> 
    </div>
    <div class="panel-body">
        <div class="m-bottom">{LANG.DKIM_note}</div>
        <!-- BEGIN: dkim_list -->
        <div class="panel-group" id="dkim-accordion" role="tablist" aria-multiselectable="true">
            <!-- BEGIN: loop -->
            <div class="loop panel panel-default">
                <a title="{DKIM.title}" class="panel-heading btn-block" role="button" id="dkim{DKIM.num}" data-toggle="collapse" data-parent="#dkim-accordion" href="#dkim-collapse{DKIM.num}" aria-expanded="false" aria-controls="dkim-collapse{DKIM.num}">
                    <span>
                        <!-- BEGIN: if_verified -->
                        <i class="fa fa-check"></i>
                        <!-- END: if_verified -->
                        <!-- BEGIN: if_unverified -->
                        <i class="fa fa-question-circle"></i>
                        <!-- END: if_unverified -->
                    </span>
                    {DKIM.domain}
                </a>
                <div id="dkim-collapse{DKIM.num}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="dkim{DKIM.num}" data-domain="{DKIM.domain}" data-loaded="false">
                    <div class="panel-body dkim-content"></div>
                </div>
            </div>
            <!-- END: loop -->
        </div>
<script>
function dkimverify(domain) {
    $.ajax({
        url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=smtp",
        type: 'POST',
        data: {'dkimverify':1,'domain':domain},
        cache: false,
        dataType: "json"
    }).done(function(a) {
        alert(a.mess);
        if ('OK' == a.status) {
            window.location.href = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=smtp&s=dkim&d=" + domain
        }
    });
}
function dkimdel(domain) {
    if (confirm('{LANG.dkim_del_confirm}') == true) {
        $.ajax({
            type: 'POST',
            url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=smtp",
            data: {'dkimdel':1,'domain':domain},
            success: function() {
                window.location.href = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=smtp&s=dkim"
            }
        })
    }
}
$(function() {
    $('[id*=dkim-collapse]').on('show.bs.collapse', function () {
        $(this).parent().toggleClass('panel-default panel-info');
        if ($(this).attr('data-loaded') === 'false') {
            var t = $(this);
            $.ajax({
                type: 'POST',
                url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=smtp",
                data: {'dkimread':1,'domain':t.data('domain')},
                success: function(data) {
    				$('.dkim-content', t).html(data);
                    $(t).attr('data-loaded', 'true')
                }
            });
        }
    }).on('hidden.bs.collapse', function () {
        $(this).parent().toggleClass('panel-default panel-info')
    })
})
</script>
        <!-- END: dkim_list -->
        <form class="form-horizontal" id="dkimaddForm" action="{DKIMADD_ACTION}" method="post">
            <div class="form-group">
                <div class="col-sm-18 col-sm-push-6 col-md-14 col-md-push-6 col-lg-10 col-lg-push-6">
                    <strong>{LANG.DKIM_add}</strong>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.DKIM_domain}:</label>
                <div class="col-sm-18 col-md-14 col-lg-10">
                    <input type="text" name="domain" class="form-control" maxlength="255"/>
                </div>
            </div>
            <div class="form-group m-bottom-none">
                <div class="col-sm-18 col-sm-push-6 col-md-14 col-md-push-6 col-lg-10 col-lg-push-6">
                    <input type="hidden" name="checkss" value="{DATA.checkss}" />
                    <input type="hidden" name="dkimadd" value="1" />
                    <input type="submit" name="submitsave" value="{LANG.DKIM_add_button}" class="btn btn-primary"/>
                </div>
            </div>
        </form>
    </div>
</div>

<a id="dkim"></a>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="m-bottom-none"><strong>{LANG.smime_certificate}</strong></h3> 
    </div>
    <div class="panel-body">
        <div class="m-bottom">{LANG.smime_note}</div>
        <!-- BEGIN: cert_list -->
        <div class="panel-group" id="cert-accordion" role="tablist" aria-multiselectable="true">
            <!-- BEGIN: loop -->
            <div class="loop panel panel-default">
                <a class="panel-heading btn-block" role="button" id="certificate{CERT.num}" data-toggle="collapse" data-parent="#cert-accordion" href="#cert-collapse{CERT.num}" aria-expanded="false" aria-controls="cert-collapse{CERT.num}">
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
                window.location.href = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=smtp&s=smime"
            }
        })
    }
}
$(function() {
    $('[id*=cert-collapse]').on('show.bs.collapse', function () {
        $(this).parent().toggleClass('panel-default panel-info');
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
        $(this).parent().toggleClass('panel-default panel-info')
    })
})
</script>
        <!-- END: cert_list -->
        <form class="form-horizontal" id="certaddForm" action="{SMIMEADD_ACTION}" method="post" enctype="multipart/form-data">
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
            <div class="form-group m-bottom-none">
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
    $("#dkimaddForm").submit(function(e) {
		e.preventDefault();
        var domain = $('[name=domain]', this).val(),
            data = $(this).serialize(),
            th = this;
        if ('' == domain) {
            return false
        }
        $('input, button', this).prop('disabled', true);
		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			data: data,
			cache: false,
			dataType: "json"
		}).done(function(a) {
            alert(a.mess);
			if ('error' == a.status) {
                $('input, button', th).prop('disabled', false);
			} else {
                window.location.href = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=smtp&s=dkim&d=" + domain
			}
		});
	});
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
                window.location.href = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=smtp&s=smime"
			}
		});
	});
    <!-- BEGIN: scroll -->
    $("html, body").animate({
	   scrollTop: $("#{S}").offset().top
	}, 800)
    <!-- END: scroll -->
    <!-- BEGIN: dcl -->
    $("html, body").animate({
	   scrollTop: $("#dkim{DID}").offset().top
	}, 800, null, function() {
	   $("#dkim-collapse{DID}").collapse('show')
	})
    <!-- END: dcl -->
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
            <td colspan="2">
                <button type="button" class="btn btn-info btn-xs" onclick="smimedownload('{EMAIL}');">{LANG.smime_download}</button>
                <button type="button" class="btn btn-danger btn-xs" onclick="smimedel('{EMAIL}');">{LANG.smime_del}</button>
            </td>
        </tr>
    </tfoot>
</table>
<!-- END: smimeread -->
<!-- BEGIN: dkimread -->
<div style="position: relative;">
    <div class="m-bottom">
        <label>{LANG.DKIM_TXT_host}:</label>
        <input type="text" class="form-control" readonly="readonly" value="nv._domainkey"  onclick="this.focus();this.select();"/>
    </div>
    <div class="m-bottom">
        <label>{LANG.DKIM_TXT_value}:</label>
        <textarea class="form-control" readonly="readonly" id="pubkeyvalue" style="overflow:auto;word-wrap: break-word;width:100%;min-height:40px;resize: none;" onclick="this.focus();this.select();">{DNSVALUE}</textarea>
    </div>
    <!-- BEGIN: unverified -->
    <div class="m-bottom">{LANG.DKIM_verify_note}</div>
    <div class="text-center">
        <button type="button" class="btn btn-info active btn-xs" onclick="dkimverify('{DOMAIN}');">{LANG.dkim_verify}</button>
        <button type="button" class="btn btn-danger btn-xs" onclick="dkimdel('{DOMAIN}');">{LANG.dkim_del}</button>
    </div>
    <!-- END: unverified -->
    <!-- BEGIN: verified -->
    <div>
        <button type="button" class="btn btn-info btn-xs" onclick="dkimverify('{DOMAIN}');">{LANG.dkim_reverify}</button>
        <button type="button" class="btn btn-danger btn-xs" onclick="dkimdel('{DOMAIN}');">{LANG.dkim_del}</button>
    </div>
    <!-- END: verified -->
</div>
<!-- END: dkimread -->