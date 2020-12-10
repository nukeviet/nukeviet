<!-- BEGIN: smtp -->
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
<div class="panel panel-default">
    <div class="panel-body">
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-horizontal">
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
    </div>
</div>
<!-- BEGIN: testmail1 -->
<div class="alert alert-info">{LANG.smtp_test_note}</div>
<!-- END: testmail1 -->
<!-- END: smtp -->
