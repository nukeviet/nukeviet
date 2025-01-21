<!-- BEGIN: main -->
<script src="{NV_STATIC_URL}themes/{TEMPLATE_JS}/js/users.passkey.js"></script>

<div class="alert alert-info" role="alert">
    <i class="fa fa-lightbulb-o" aria-hidden="true"></i> Bạn chưa kích hoạt phương thức xác thực hai bước nào. Chúng tôi khuyến cáo bạn nên kích hoạt ít nhất một phương trước xác thực hai bước dưới đây để đảm bảo an toàn cho tài khoản.
</div>

<form method="post" action="{DATA.form_url}" id="passkey-form">
    <input type="hidden" name="checkss" value="{DATA.checkss}">
    <ul class="list-group">
        <li class="list-group-item active">
            <div class="h2"><strong>{LANG.title_2step}</strong></div>
        </li>
        <li class="list-group-item tstep-flex tstep-gap-2">
            <div>
                <i class="fa fa-mobile fa-3x fa-fw text-center" aria-hidden="true"></i>
            </div>
            <div class="tstep-grow tstep-shrink">
                <h3>
                    <strong>{LANG.tstep_app}</strong>
                    <!-- BEGIN: on --><span class="label label-success">{LANG.status_on}</span><!-- END: on -->
                    <!-- BEGIN: off --><span class="label label-default">{LANG.status_off}</span><!-- END: off -->
                </h3>
                <div class="text-muted">{LANG.tstep_app_note}.</div>
            </div>
            <div>
                <!-- BEGIN: turnoff -->
                <button class="btn btn-danger btn-sm" type="button" data-toggle="turnoff2step" data-tokend="{NV_CHECK_SESSION}">{GLANG.off}</button>
                <!-- END: turnoff -->
                <!-- BEGIN: turnon -->
                <a href="{LINK_TURNON}" class="btn btn-default btn-sm"><i class="fa fa-power-off" aria-hidden="true"></i> {GLANG.on}</a>
                <!-- END: turnon -->
            </div>
        </li>
        <li class="list-group-item tstep-flex tstep-gap-2" data-toggle="ctn">
            <div>
                <i class="fa fa-key fa-3x fa-fw text-center" aria-hidden="true"></i>
            </div>
            <div class="tstep-grow tstep-shrink">
                <h3><strong>{LANG.security_keys}</strong></h3>
                <div class="text-muted">{LANG.security_keys_note}.</div>
                <!-- BEGIN: note_login_keys -->
                <div class="alert alert-info mb-0 mt-2" role="alert">{LANG.rcode_note}</div>
                <!-- END: note_login_keys -->
                <div class="text-danger hidden" data-toggle="passkey-not-supported">{LANG.passkey_not_supported}</div>
                <div class="text-danger margin-top hidden" data-toggle="error"></div>
            </div>
            <div>
                <!-- BEGIN: btn_add_key -->
                <button type="button" class="btn btn-default btn-sm hidden" data-toggle="passkey-add" data-enable-login="0"><i class="fa fa-plus" data-icon="fa-plus" aria-hidden="true"></i> {GLANG.add}</button>
                <!-- END: btn_add_key -->
            </div>
        </li>
        <li class="list-group-item active">
            <div class="h2"><strong>{LANG.backup_methods}</strong></div>
        </li>
        <li class="list-group-item tstep-flex tstep-gap-2">
            <div>
                <i class="fa fa-terminal fa-3x fa-fw text-center" aria-hidden="true"></i>
            </div>
            <div class="tstep-grow tstep-shrink">
                <h3><strong>{LANG.recovery_codes}</strong></h3>
                <div class="text-muted">{LANG.recovery_codes_note}.</div>
                <div class="alert alert-warning mb-0 mt-2" role="alert">Bạn đã dùng hết mã dự phòng, vui lòng tạo mới mã dự phòng</div>
                <!-- BEGIN: bcodes -->
                <div class="collapse" id="recovery-codes">
                    <div class="row">
                        <!-- BEGIN: code -->
                        <div class="col-xs-12 text-center">
                            <div class="recovery-code">
                                <!-- BEGIN: unuse --><i class="fa fa-square-o" aria-hidden="true"></i><!-- END: unuse -->
                                <!-- BEGIN: used --><i class="fa fa-check-square-o" aria-hidden="true"></i><!-- END: used -->
                                <span>{CODE.code}</span>
                            </div>
                        </div>
                        <!-- END: code -->
                    </div>
                </div>
                <!-- END: bcodes -->
            </div>
            <div>
                <!-- BEGIN: btn_create_code -->
                <button type="button" class="btn btn-default btn-sm">{LANG.recovery_codes_creat}</button>
                <!-- END: btn_create_code -->
                <!-- BEGIN: btn_view_code -->
                <button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#recovery-codes" aria-expanded="false" aria-controls="recovery-codes">{GLANG.view}</button>
                <!-- END: btn_view_code -->
            </div>
        </li>
    </ul>
</form>

<div class="panel panel-default">
    <div class="panel-heading h2"><strong></strong></div>
    <div class="list-group">


        <div class="list-group-item">


        </div>
        <!-- BEGIN: backupcode -->
        <div class="list-group-item">
            <p>{NUM_CODE}: <strong><a href="#modal-backupcode" data-toggle="viewcode">{LANG.backupcode_2step_view}</a></strong></p>
            <div class="clearfix">
                <input class="btn btn-info" type="button" value="{LANG.creat_other_code}" data-toggle="changecode2step" data-tokend="{NV_CHECK_SESSION}"/>
            </div>
        </div>
        <!-- BEGIN: autoshowcode -->
        <script type="text/javascript">
        $(function() {
            $('[data-toggle="viewcode"]').click();
        });
        </script>
        <!-- END: autoshowcode -->
        <!-- END: backupcode -->
    </div>
</div>
<!-- BEGIN: backupcodeModal -->
<div id="modal-backupcode" title="{LANG.backupcode_2step_view}" class="hidden">
    <div class="clearfix">
        <div class="alert alert-warning">{LANG.backupcode_2step_note}</div>

    </div>
</div>
<!-- END: backupcodeModal -->
<!-- BEGIN: main -->
