<!-- BEGIN: off -->
<div class="alert alert-info" role="alert">
    <div class="text-center">
        <i class="fa fa-lock fa-3x" aria-hidden="true"></i>
        <h1 class="margin-bottom-lg">{LANG.title_2step_off}</h1>
        <p>{LANG.title_2step_off_note}</p>
        <a class="btn btn-primary" href="{LINK_TURNON}">{LANG.title_2step_turnon}</a>
    </div>
</div>
<!-- END: off -->

<!-- BEGIN: main -->
<script src="{NV_STATIC_URL}themes/{TEMPLATE_JS}/js/users.passkey.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="h2 margin-bottom-sm"><strong>{LANG.preferred_2fa_method}</strong></div>
        <p>{LANG.preferred_2fa_method_help}</p>
        <select class="form-control w-fix-content" name="preferred_2fa_method" data-toggle="preferred_2fa_method" data-current="{DATA.pref_2fa}" data-checkss="{NV_CHECK_SESSION}">
            <!-- BEGIN: pref_2fa_key -->
            <option value="2"{PREF_2FA_2}>{LANG.tstep_key}</option>
            <!-- END: pref_2fa_key -->
            <option value="1"{PREF_2FA_1}>{LANG.tstep_app}</option>
        </select>
    </div>
</div>
<ul class="list-group">
    <li class="list-group-item active">
        <div class="tstep-flex tstep-gap-2 tstep-justify-between tstep-align-center">
            <div class="h2"><strong>{LANG.title_2step}</strong></div>
            <div class="dropdown">
                <a id="tstep-turnoff-btn" href="#" class="tstep-flex tstep-align-center tstep-justify-center" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h text-white" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tstep-turnoff-btn">
                    <li><a id="btn-turnoff-2step" href="#md-turnoff-2step" data-target="#md-turnoff-2step" data-toggle="modal"><i class="fa fa-ban text-danger" data-icon="fa-ban" aria-hidden="true"></i> {LANG.turnoff_2step}</a></li>
                </ul>
            </div>
        </div>
    </li>
    <li class="list-group-item tstep-flex tstep-gap-2" id="container-edit-app"<!-- BEGIN: scroll_app --> data-autoscroll="1"<!-- END: scroll_app -->>
        <div>
            <i class="fa fa-mobile fa-3x fa-fw text-center" aria-hidden="true"></i>
        </div>
        <div class="tstep-grow tstep-shrink">
            <h3>
                <strong>{LANG.tstep_app}</strong>
                <span class="label label-success">{LANG.configured}</span>
            </h3>
            <div class="text-muted">{LANG.tstep_app_note}.</div>
            <!-- BEGIN: edit_app -->
            <div class="padding-top">
                {FILE "form-setup-app.tpl"}
            </div>
            <!-- END: edit_app -->
        </div>
        <div>
            <a href="{DATA.page_url}&amp;type=app" class="btn btn-default btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i> {GLANG.edit}</a>
        </div>
    </li>
    <li class="list-group-item tstep-flex tstep-gap-2" data-toggle="ctn">
        <div>
            <i class="fa fa-key fa-3x fa-fw text-center" aria-hidden="true"></i>
        </div>
        <div class="tstep-grow tstep-shrink">
            <form method="post" action="{DATA.form_url}" id="passkey-form">
                <input type="hidden" name="checkss" value="{DATA.checkss}">
                <div class="tstep-flex tstep-gap-2">
                    <div class="tstep-grow tstep-shrink">
                        <h3>
                            <strong>{LANG.security_keys}</strong>
                            <!-- BEGIN: configured_key -->
                            <span class="label label-success">{LANG.configured}</span>
                            <span class="label label-default">{NUMBER_KEYS}</span>
                            <!-- END: configured_key -->
                        </h3>
                        <div class="text-muted">{LANG.security_keys_note}.</div>
                        <!-- BEGIN: note_login_keys -->
                        <div class="alert alert-info mb-0 mt-2" role="alert">{MESSAGE}</div>
                        <!-- END: note_login_keys -->
                        <div class="text-danger hidden" data-toggle="passkey-not-supported">{LANG.passkey_not_supported}</div>
                        <div class="text-danger margin-top hidden" data-toggle="error"></div>
                    </div>
                    <div>
                        <!-- BEGIN: btn_add_key -->
                        <button type="button" class="btn btn-default btn-sm hidden" data-toggle="passkey-add" data-enable-login="0"><i class="fa fa-plus" data-icon="fa-plus" aria-hidden="true"></i> {GLANG.add}</button>
                        <!-- END: btn_add_key -->
                        <!-- BEGIN: btn_show_key -->
                        <button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#security-keys" aria-expanded="{CSS_SHOW_KEYS2}" aria-controls="security-keys"><i class="fa fa-eye" aria-hidden="true"></i> {GLANG.view}</button>
                        <!-- END: btn_show_key -->
                    </div>
                </div>
                <!-- BEGIN: seckeys -->
                <div class="collapse{CSS_SHOW_KEYS1}" id="security-keys" data-show-keys-url="{DATA.page_url}&amp;type=key" data-page-url="{DATA.page_url}">
                    <div class="padding-top">
                        <!-- BEGIN: loop -->
                        <div class="tstep-flex tstep-justify-between tstep-gap-2 item">
                            <div>
                                <strong>{SECKEY.nickname}</strong>
                                <!-- BEGIN: this_client --><span class="label label-default">{LANG.passkey_seenthis}</span><!-- END: this_client -->
                                <div class="margin-top-sm text-muted">
                                    {LANG.passkey_created_at}: {SECKEY.created_at} |
                                    {LANG.passkey_last_used_at}: {SECKEY.last_used_at}.
                                </div>
                            </div>
                            <div>
                                <div class="tstep-flex tstep-gap-1">
                                    <button type="button" class="btn btn-xs btn-default" data-toggle="edit" data-id="{SECKEY.id}" data-nickname="{SECKEY.nickname}"><i class="fa fa-pencil" data-icon="fa-pencil" aria-hidden="true"></i> {GLANG.edit}</button>
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="del" data-id="{SECKEY.id}"><i class="fa fa-trash" data-icon="fa-trash" aria-hidden="true"></i> {GLANG.delete}</button>
                                </div>
                            </div>
                        </div>
                        <hr class="margin-bottom margin-top">
                        <!-- END: loop -->
                        <button class="btn btn-default" type="button" data-toggle="passkey-add" data-enable-login="0"><i class="fa fa-plus" data-icon="fa-plus" aria-hidden="true"></i> {LANG.security_keys_add}</button>
                    </div>
                </div>
                <!-- END: seckeys -->
            </form>
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
            <h3>
                <strong>{LANG.recovery_codes}</strong>
                <span class="label label-default">{REMAIN_CODE}</span>
            </h3>
            <div class="text-muted">{LANG.recovery_codes_note}.</div>
            <!-- BEGIN: usedup_code -->
            <div class="alert alert-danger mb-0 mt-2" role="alert">{LANG.usedup_code}</div>
            <!-- END: usedup_code -->
            <!-- BEGIN: lack_code -->
            <div class="alert alert-warning mb-0 mt-2" role="alert">{LANG.lack_code}</div>
            <!-- END: lack_code -->
            <div class="collapse{CSS_SHOW_CODES1}" id="recovery-codes" data-show-codes-url="{DATA.page_url}&amp;type=code" data-page-url="{DATA.page_url}">
                <div class="row">
                    <!-- BEGIN: code -->
                    <div class="col-xs-12 text-center">
                        <div class="recovery-code">
                            <!-- BEGIN: unuse --><i class="fa fa-check-circle text-success" aria-hidden="true" title="{LANG.code_is_available}" aria-label="{LANG.code_is_available}"></i><!-- END: unuse -->
                            <!-- BEGIN: used --><i class="fa fa-ban text-danger" aria-hidden="true" title="{LANG.code_is_used}" aria-label="{LANG.code_is_used}"></i><!-- END: used -->
                            <span>{CODE.code}</span>
                        </div>
                    </div>
                    <!-- END: code -->
                </div>
                <div class="text-center margin-top-lg">
                    <a class="btn btn-primary confirmed-codes" href="{DATA.download_code_url}"><i class="fa fa-download" aria-hidden="true"></i> {GLANG.download}</a>
                    <a class="btn btn-primary confirmed-codes" href="{DATA.print_code_url}" data-toggle="print-codes"><i class="fa fa-print" aria-hidden="true"></i> {GLANG.print}</a>
                    <button class="btn btn-primary confirmed-codes" type="button" data-toggle="copy-codes" data-clipboard-text="{DATA.text_codes}" data-copied="{GLANG.copied}"><i class="fa fa-clipboard" aria-hidden="true"></i> <span>{GLANG.copy_to_clipboard}</span></button>
                </div>
                <hr>
                <p class="text-muted">{LANG.creat_other_note}</p>
                <button type="button" class="btn btn-default" data-toggle="changecode2step" data-tokend="{NV_CHECK_SESSION}"><i class="fa fa-refresh" aria-hidden="true"></i> {LANG.creat_other_code}</button>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#recovery-codes" aria-expanded="{CSS_SHOW_CODES2}" aria-controls="recovery-codes"><i class="fa fa-eye"></i> {GLANG.view}</button>
        </div>
    </li>
</ul>
<!-- START FORFOOTER -->
<div class="modal fade" tabindex="-1" role="dialog" data-toggle="md-edit-passkey">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{GLANG.close}"><span aria-hidden="true">&times;</span></button>
                <div class="modal-title h3"><strong>{LANG.passkey_nickname_edit}</strong></div>
            </div>
            <div class="modal-body">
                <form action="{DATA.form_url}" id="passkey-form-nickname" method="post" autocomplete="off" novalidate>
                    <input type="hidden" name="checkss" value="{DATA.checkss}">
                    <input type="hidden" name="id" value="0">
                    <div class="form-group">
                        <label for="element_nickname" class="control-label">{LANG.passkey_nickname} <span class="text-danger">(*)</span>:</label>
                        <input type="text" class="form-control" name="nickname" data-nickname="" id="element_nickname" value="" maxlength="100">
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" data-icon="fa-floppy-o" aria-hidden="true"></i> {GLANG.save}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="md-turnoff-2step" aria-labelledby="btn-turnoff-2step">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{GLANG.close}"><span aria-hidden="true">&times;</span></button>
                <div class="modal-title h3"><strong>{LANG.deactive_mess}</strong></div>
            </div>
            <div class="modal-body">
                <p>{LANG.title_2step_off_note2}.</p>
                <p>{LANG.title_2step_off_note3}.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-toggle="turnoff2step" data-tokend="{NV_CHECK_SESSION}"><i class="fa fa-ban" data-icon="fa-ban" aria-hidden="true"></i> {LANG.turnoff_2step}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> {GLANG.close}</button>
            </div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
<!-- BEGIN: main -->
