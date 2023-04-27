<!-- BEGIN: no_support-->
<div class="alert alert-warning">
    {LANG.ftp_error_support}
</div>
<!-- END: no_support-->
<!-- BEGIN: main-->
<form action="{FORM_ACTION}" method="post" id="form_edit_ftp" class="form-horizontal ajax-submit" data-error="{LANG.ftp_error_full}">
    <ul class="list-group type2n1">
        <li class="list-group-item">
            <div class="form-group mb-0">
                <label class="col-sm-10 control-label"><strong>{LANG.server}</strong></label>
                <div class="col-sm-14">
                    <div class="row">
                        <div class="col-md-16 col-lg-12">
                            <div class="input-group d-flex align-items-center">
                                <input class="form-control" type="text" name="ftp_server" value="{VALUE.ftp_server}" />
                                <span class="input-group-addon" style="width: fit-content;padding: 8.5px 12px;border-left:0;border-right:0">{LANG.port}</span>
                                <input type="text" value="{VALUE.ftp_port}" class="form-control text-center number w60" name="ftp_port" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="form-group mb-0">
                <label class="col-sm-10 control-label"><strong>{LANG.username}</strong></label>
                <div class="col-sm-14">
                    <div class="row">
                        <div class="col-md-16 col-lg-12">
                            <input class="form-control" type="text" name="ftp_user_name" id="ftp_user_name_iavim" value="{VALUE.ftp_user_name}" />
                        </div>
                    </div>
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="form-group mb-0">
                <label class="col-sm-10 control-label"><strong>{LANG.password}</strong></label>
                <div class="col-sm-14">
                    <div class="row">
                        <div class="col-md-16 col-lg-12">
                            <input class="form-control" type="password" autocomplete="off" name="ftp_user_pass" id="ftp_user_pass_iavim" value="{VALUE.ftp_user_pass}" />
                        </div>
                    </div>
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="form-group mb-0">
                <label class="col-sm-10 control-label"><strong>{LANG.ftp_path}</strong></label>
                <div class="col-sm-14">
                    <div class="row">
                        <div class="col-md-16 col-lg-12">
                            <div class="input-group">
                                <input class="form-control" type="text" name="ftp_path" id="ftp_path" value="{VALUE.ftp_path}" />
                                <span class="input-group-btn">
                                    <button type="button" id="autodetectftp" class="btn btn-info" title="{LANG.ftp_auto_detect_root}"><em class="fa fa-retweet"></em></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <div class="text-center">
        <input type="hidden" name="checkss" value="{CHECKSS}" />
        <input type="submit" value="{LANG.submit}" class="btn btn-primary" />
    </div>
</form>
<!-- END: main -->