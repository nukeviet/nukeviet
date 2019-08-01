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
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <table class="table table-striped">
        <col class="w150"/>
        <col style="white-space: nowrap" />
        <tr>
            <td>{LANG.mail_config}</td>
            <td><label style="margin-right: 10px"><input type="radio" name="mailer_mode" value="smtp" {DATA.mailer_mode_smtpt} /> {LANG.type_smtp}</label><label style="margin-right: 10px"> <input type="radio" name="mailer_mode" value="sendmail" {DATA.mailer_mode_sendmail} /> {LANG.type_linux}</label><label> <input type="radio" name="mailer_mode" value="" {DATA.mailer_mode_phpmail} /> {LANG.type_phpmail}</label></td>
        </tr>
    </table>
    <div {DATA.mailer_mode_smtpt_show} id="smtp">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.smtp_server} </caption>
                <col style="width: 40%" />
                <col style="width: 60%" />
                <tbody>
                    <tr>
                        <td>{LANG.outgoing}</td>
                        <td><input class="w250 form-control" type="text" name="smtp_host" value="{DATA.smtp_host}" /></td>
                    </tr>
                    <tr>
                        <td>{LANG.outgoing_port}</td>
                        <td><input class="w50 form-control" type="text" name="smtp_port" value="{DATA.smtp_port}" /></td>
                    </tr>
                    <tr>
                        <td>{LANG.incoming_ssl}</td>
                        <td>
                        <select name="smtp_ssl" class="form-control w100">
                            <!-- BEGIN: encrypted_connection -->
                            <option value="{EMCRYPTED.id}" {EMCRYPTED.sl}>{EMCRYPTED.value}</option>
                            <!-- END: encrypted_connection -->
                        </select></td>
                    </tr>
                    <tr>
                        <td>{LANG.verify_peer_ssl}</td>
                        <td>
                        <label style="margin-right: 10px"><input type="radio" name="verify_peer_ssl" value="1" {PEER_SSL_YES}> {LANG.verify_peer_ssl_yes}</label>
                        <label style="margin-right: 10px"><input type="radio" name="verify_peer_ssl" value="0" {PEER_SSL_NO}> {LANG.verify_peer_ssl_no}</label>
                    </tr>
                      <tr>
                        <td>{LANG.verify_peer_name_ssl}</td>
                        <td>
                        <label style="margin-right: 10px"><input type="radio" name="verify_peer_name_ssl" value="1" {PEER_NAME_SSL_YES}> {LANG.verify_peer_ssl_yes}</label>
                        <label style="margin-right: 10px"><input type="radio" name="verify_peer_name_ssl" value="0" {PEER_NAME_SSL_NO}> {LANG.verify_peer_ssl_no}</label>
                       </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.smtp_username} </caption>
                <col style="width: 40%" />
                <col style="width: 60%" />
                <tbody>
                    <tr>
                        <td>{LANG.smtp_login}</td>
                        <td><input class="w250 form-control" type="text" name="smtp_username" value="{DATA.smtp_username}" /></td>
                    </tr>
                    <tr>
                        <td>{LANG.smtp_pass}</td>
                        <td><input class="w250 form-control" type="password" autocomplete="new-password" name="smtp_password" value="{DATA.smtp_password}" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-group text-center">
        <input type="submit" name="submitsave" value="{LANG.submit}" class="btn btn-primary" />
        <!-- BEGIN: testmail -->
        <input type="submit" name="submittest" value="{LANG.smtp_test}" class="btn btn-default">
        <!-- END: testmail -->
    </div>
</form>
<!-- BEGIN: testmail1 -->
<p>{LANG.smtp_test_note}</p>
<!-- END: testmail1 -->
<!-- END: smtp -->
