<!-- BEGIN: content -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>{CAPTION}</strong>
    </div>
    <div class="panel-body">
        <form method="post" action="{FORM_ACTION}" class="form-horizontal" autocomplete="off">
            <div class="form-group">
                <label class="col-sm-6 control-label" for="credential_title">{LANG.api_cr_title} <span class="text-danger">(*)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" id="credential_title" name="credential_title" value="{DATA.credential_title}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="credential_ips">{LANG.api_cr_ips}:</label>
                <div class="col-sm-18 col-lg-10">
                    <textarea rows="3" class="form-control" name="credential_ips">{DATA.credential_ips}</textarea>
                    <div class="form-text text-muted">{LANG.credential_ips_help}</div>
                </div>
            </div>
            <!-- BEGIN: for_admin -->
            <div class="form-group">
                <label class="col-sm-6 control-label" for="admin_id">{LANG.api_cr_for_admin} <span class="text-danger">(*)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <div class="form-inline">
                        <select id="admin_id" name="admin_id" class="form-control select2-admin">
                            <option value="0">--</option>
                            <!-- BEGIN: admin -->
                            <option value="{ADMIN.admin_id}"{ADMIN.selected}>{ADMIN.username}<!-- BEGIN: full_name --> ({ADMIN.full_name})<!-- END: full_name --></option>
                            <!-- END: admin -->
                        </select>
                    </div>
                </div>
            </div>
            <!-- END: for_admin -->
            <div class="form-group">
                <label class="col-sm-6 control-label" for="admin_id">{LANG.api_cr_roles} <span class="text-danger">(*)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <div class="row">
                        <!-- BEGIN: role -->
                        <div class="col-sm-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="api_roles[]" value="{ROLE.role_id}"{ROLE.checked}><span class="d-inline-block text-truncate" title="{ROLE.role_title}">{ROLE.role_title}</span>
                                </label>
                            </div>
                        </div>
                        <!-- END: role -->
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.api_cr_auth_method} <span class="text-danger">(*)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <!-- BEGIN: auth_method -->
                    <div class="radio">
                        <label>
                            <input type="radio" name="auth_method" value="{AUTH_METHOD.key}"{AUTH_METHOD.checked}><span class="d-inline-block text-truncate" title="{AUTH_METHOD.title}">{AUTH_METHOD.title}</span>
                        </label>
                    </div>
                    <!-- END: auth_method -->
                </div>
            </div>
            <div class="row">
                <div class="col-sm-18 col-sm-offset-6">
                    <input type="hidden" name="save" value="1">
                    <button type="submit" value="submit" class="btn btn-primary">{GLANG.save}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('[name="admin_id"]').select2();
});
</script>
<!-- END: content -->

<!-- BEGIN: result -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/clipboard/clipboard.min.js"></script>
<div class="credential-result">
    <div class="panel panel-primary">
        <div class="panel-heading">
            {LANG.api_cr_result}
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="credential_ident"><strong>{LANG.api_cr_credential_ident}:</strong></label>
                <div class="input-group">
                    <input type="text" name="credential_ident" id="credential_ident" value="{CREDENTIAL_IDENT}" class="form-control">
                    <div class="input-group-btn">
                        <button class="btn btn-primary" type="button" data-clipboard-target="#credential_ident" id="credential_ident_btn" data-title="{LANG.value_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="credential_secret"><strong>{LANG.api_cr_credential_secret}:</strong></label>
                <div class="input-group">
                    <input type="text" name="credential_secret" id="credential_secret" value="{CREDENTIAL_SECRET}" class="form-control">
                    <div class="input-group-btn">
                        <button class="btn btn-primary" type="button" data-clipboard-target="#credential_secret" id="credential_secret_btn" data-title="{LANG.value_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
                    </div>
                </div>
            </div>
            <div class="clearfix">
                <p><span class="text-danger">{LANG.api_cr_notice}.</span></p>
                <a href="{URL_BACK}" class="btn btn-primary float-right">{LANG.api_cr_back}</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var clipboard1 = new ClipboardJS('#credential_ident_btn');
    var clipboard2 = new ClipboardJS('#credential_secret_btn');
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

<!-- BEGIN: main -->
<!-- BEGIN: remote_off -->
<div class="alert alert-info">{REMOTE_OFF}</div>
<!-- END: remote_off -->
<div class="text-right m-bottom">
    <a href="{LINK_ADD}" class="btn btn-success">{LANG.api_cr_add}</a>
</div>
<form>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><i class="fa fa-file-text-o"></i> {LANG.api_roles_list}</caption>
            <thead>
                <tr>
                    <th style="width:26%;">{LANG.api_cr_credential_ident}</th>
                    <th style="width:15%;">{LANG.api_cr_title}</th>
                    <th style="width:20%;">{LANG.users}</th>
                    <th style="width:15%;">{LANG.api_cr_roles1}</th>
                    <th style="width:12%;">{LANG.api_cr_last_access}</th>
                    <th style="width:12%;" class="text-center text-nowrap">{LANG.funcs}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>{ROW.credential_ident}</td>
                    <td>{ROW.credential_title}</td>
                    <td class="text-nowrap"><img alt="Admin level" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/admin{ROW.lev}.png" width="38" height="18" /> {ROW.username}</td>
                    <td>{ROW.api_roles_show}</td>
                    <td>{ROW.last_access}</td>
                    <td class="text-center text-nowrap">
                        <a href="{ROW.url_edit}" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i> {GLANG.edit}</a>
                        <a href="#" data-id="{ROW.credential_ident}" data-toggle="apicerdel" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> {GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: main -->
