<!-- BEGIN: main -->
<!-- BEGIN: remote_api_off -->
<div class="alert alert-info">{MESSAGE}.</div>
<!-- END: remote_api_off -->
<div class="form-group">
    <a href="{LINK_ADD}" class="btn btn-primary">{LANG.api_cr_add}</a>
</div>
<form>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width:26%;">{LANG.api_cr_credential_ident}</th>
                    <th style="width:15%;">{LANG.api_cr_title}</th>
                    <th style="width:20%;">{LANG.users}</th>
                    <th style="width:15%;">{LANG.api_cr_roles1}</th>
                    <th style="width:12%;">{LANG.api_cr_last_access}</th>
                    <th style="width:12%;" class="text-center">{LANG.funcs}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>{ROW.credential_ident}</td>
                    <td>{ROW.credential_title}</td>
                    <td>
                        <img class="refresh" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/admin{ROW.lev}.png" width="38" height="18" />{ROW.username}
                    </td>
                    <td>{API_ROLES}</td>
                    <td>{ROW.last_access}</td>
                    <td>
                        <a href="{ROW.link_edit}" class="btn btn-xs btn-default"><i class="fa fa-fw fa-edit"></i>{GLANG.edit}</a>
                        <a href="#" data-id="{ROW.credential_ident}" data-toggle="apicerdel" class="btn btn-xs btn-danger"><i class="fa fa-fw fa-trash"></i>{GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: main -->

<!-- BEGIN: contents -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css"/>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form method="post" action="{FORM_ACTION}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><i class="fa fa-fw fa-file-o"></i>{TABLE_CAPTION}</caption>
            <tbody>
                <tr>
                    <td class="w200"><strong>{LANG.api_cr_title}</strong></td>
                    <td><input type="text" name="credential_title" value="{DATA.credential_title}" class="form-control w350"/></td>
                </tr>
                <!-- BEGIN: admin -->
                <tr>
                    <td><strong>{LANG.api_cr_for_admin}</strong></td>
                    <td>
                        <select name="admin_id" class="form-control w350">
                            <option value="0">--</option>
                            <!-- BEGIN: loop -->
                            <option value="{ADMIN.admin_id}"{ADMIN.selected}>{ADMIN.username}</option>
                            <!-- END: loop -->
                        </select>
                    </td>
                </tr>
                <!-- END: admin -->
                <tr>
                    <td><strong>{LANG.api_cr_roles}</strong></td>
                    <td>
                        <div class="row">
                            <!-- BEGIN: api_role -->
                            <div class="col-xs-12">
                                <label><input type="checkbox" name="api_roles[]" value="{API_ROLE.role_id}"{API_ROLE.checked}/>&nbsp;{API_ROLE.role_title}</label>
                            </div>
                            <!-- END: api_role -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="submit" value="{GLANG.save}" class="btn btn-primary"/></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function() {
    $('[name="admin_id"]').select2();
});
</script>
<!-- END: contents -->

<!-- BEGIN: result -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/clipboard/clipboard.min.js"></script>
<div class="credential-result">
    <div class="panel panel-default">
        <div class="panel-body">
            <p>{LANG.api_cr_result}</p>
            <div class="form-group">
                <label for="credential_ident"><strong>{LANG.api_cr_credential_ident}:</strong></label>
                <div class="input-group">
                    <input type="text" name="credential_ident" id="credential_ident" value="{CREDENTIAL_IDENT}" class="form-control"/>
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button" data-clipboard-target="#credential_ident" id="credential_ident_btn" data-title="{LANG.value_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="0"><i class="fa fa-copy"></i></button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="credential_secret"><strong>{LANG.api_cr_credential_secret}:</strong></label>
                <div class="input-group">
                    <input type="text" name="credential_secret" id="credential_secret" value="{CREDENTIAL_SECRET}" class="form-control"/>
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button" data-clipboard-target="#credential_secret" id="credential_secret_btn" data-title="{LANG.value_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="0"><i class="fa fa-copy"></i></button>
                    </div>
                </div>
            </div>
            <span class="help-block"><span class="text-danger">{LANG.api_cr_notice}.</span></span>
            <div class="clearfix">
                <a href="{URL_BACK}" class="btn btn-primary pull-right">{LANG.api_cr_back}</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var clipboard1 = new Clipboard('#credential_ident_btn');
    var clipboard2 = new Clipboard('#credential_secret_btn');
    clipboard1.on('success', function(e) {
        $(e.trigger).tooltip('show');
    });
    clipboard2.on('success', function(e) {
        $(e.trigger).tooltip('show');
    });
    $('#credential_ident_btn,#credential_secret_btn').mouseleave(function() {
        $(this).tooltip('destroy');
    });
});
</script>
<!-- END: result -->
