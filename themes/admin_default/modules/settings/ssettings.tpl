<!-- BEGIN: main -->
<div class="panel-group" role="tablist" aria-multiselectable="true">
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="general_info_tab-heading">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" href="#general_info_tab" aria-expanded="true" aria-controls="general_info_tab">
                    {LANG.general_info_tab}
                </a>
            </h4>
        </div>
        <div id="general_info_tab" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="general_info_tab-heading">
            <table class="table table-striped table-bordered table-first">
                <colgroup>
                    <col style="width: 40%" />
                    <col style="width: 60%" />
                </colgroup>
                <tbody>
                    <tr>
                        <td><strong>{LANG.server_software}</strong></td>
                        <td>{INFO.server_software}</td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.php_sapi_name}</strong></td>
                        <td>{INFO.php_sapi}</td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.rewrite_support}</strong></td>
                        <td>{INFO.rewrite_support}</td>
                    </tr>
                    <!-- BEGIN: sconfig_file -->
                    <tr>
                        <td><strong>{LANG.sconfig_file}</strong></td>
                        <td>
                            <button type="button" class="btn btn-default active" data-toggle="view_sconfig_file" data-url="{FORM_ACTION}"><i class="fa fa-file-text-o"></i>&nbsp;{INFO.sconfig_file}</button>
                        </td>
                    </tr>
                    <!-- END: sconfig_file -->
                </tbody>
            </table>
            <!-- BEGIN: tools -->
            <div class="list-group" style="border-top:0">
                <a class="list-group-item" style="border-top:0" href="#" data-toggle="server_config_by_settings" data-url="{FORM_ACTION}"><em class="fa fa-arrow-circle-right"></em>&nbsp;{LANG.server_configuration_by_settings}</a>
                <!-- BEGIN: change_configs --><a class="list-group-item" href="#" data-toggle="change_configs_to_default" data-url="{FORM_ACTION}"><em class="fa fa-arrow-circle-right"></em>&nbsp;{LANG.change_configs_to_default}</a><!-- END: change_configs -->
                <!-- BEGIN: change_rewrite --><a class="list-group-item" href="#" data-toggle="change_rewrite_to_default" data-url="{FORM_ACTION}"><em class="fa fa-arrow-circle-right"></em>&nbsp;{LANG.change_rewrite_to_default}</a><!-- END: change_rewrite -->
                <!-- BEGIN: change_all --><a class="list-group-item" href="#" data-toggle="change_all_to_default" data-url="{FORM_ACTION}"><em class="fa fa-arrow-circle-right"></em>&nbsp;{LANG.change_all_to_default} ({LANG.change_all_to_default_note})</a><!-- END: change_all -->
            </div>
            <!-- END: tools -->
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="settings_tab-heading">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" href="#settings_tab" aria-expanded="true" aria-controls="settings_tab">
                    {LANG.settings_tab}
                </a>
            </h4>
        </div>
        <div id="settings_tab" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="settings_tab-heading">
            <div class="panel-body">
                <div class="alert alert-warning" style="margin-bottom:0">{LANG.ssettings_note}</div>
            </div>
            <form action="{FORM_ACTION}" method="post" data-toggle="ssetings_form_submit">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-first">
                        <colgroup>
                            <col style="width: 40%" />
                            <col style="width: 60%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="bg-info" colspan="2">{LANG.general_settings}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <p><strong>{LANG.mime_types_on_site}</strong></p>{LANG.mime_types_note}
                                </td>
                                <td><textarea class="form-control" name="site_mimetypes" rows="5">{DATA.site_mimetypes_format}</textarea></td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>{LANG.compress_file_exts}</strong><br />{LANG.mime_list_note}
                                </td>
                                <td><textarea class="form-control nonewline" name="compress_file_exts" style="height:70px;overflow:hidden;resize: none;">{DATA.compress_file_exts_format}</textarea></td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>{LANG.charset_types}</strong><br />{LANG.mime_list_note}
                                </td>
                                <td><textarea class="form-control nonewline" name="charset_types" style="height:70px;overflow:hidden;resize: none;">{DATA.charset_types_format}</textarea></td>
                            </tr>
                            <tr<!-- BEGIN: if_not_iis --> style="display: none;"
                                <!-- END: if_not_iis -->>
                                <td>
                                    <p><strong>{LANG.compress_types}</strong></p>{LANG.compress_types_note}
                                </td>
                                <td><textarea class="form-control" name="compress_types" rows="5">{DATA.compress_types_format}</textarea></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{LANG.access_control_allow_origin}</strong><br />{LANG.access_control_allow_origin_note}
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <label class="input-group-addon">
                                                <input type="checkbox" name="any_origin" style="background-color: white;" value="1" {DATA.cors_origins_any}> {LANG.any_origin}
                                            </label>
                                            <input type="text" class="form-control" name="cors_origins" value="{DATA.cors_origins_list}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.remove_x_powered_by}</strong></td>
                                    <td><input type="checkbox" class="form-control" name="remove_x_powered_by" value="1" {DATA.remove_x_powered_by_checked} /></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.disable_server_signature}</strong></td>
                                    <td><input type="checkbox" class="form-control" name="disable_server_signature" value="1" {DATA.disable_server_signature_checked} /></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.remove_etag}</strong></td>
                                    <td><input type="checkbox" class="form-control" name="remove_etag" value="1" {DATA.remove_etag_checked} /></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.not_cache_and_snippet}</strong></td>
                                    <td><input type="checkbox" class="form-control" name="not_cache_and_snippet" value="1" {DATA.not_cache_and_snippet_checked} /></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.strict_transport_security}</strong></td>
                                    <td><input type="text" class="form-control" name="strict_transport_security" value="{DATA.strict_transport_security}" maxlength="150"></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.x_content_type_options}</strong></td>
                                    <td><input type="text" class="form-control" name="x_content_type_options" value="{DATA.x_content_type_options}" maxlength="150"></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.x_frame_options}</strong></td>
                                    <td><input type="text" class="form-control" name="x_frame_options" value="{DATA.x_frame_options}" maxlength="150"></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.x_xss_protection}</strong></td>
                                    <td><input type="text" class="form-control" name="x_xss_protection" value="{DATA.x_xss_protection}" maxlength="150"></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.referrer_policy}</strong></td>
                                    <td><input type="text" class="form-control" name="referrer_policy" value="{DATA.referrer_policy}" maxlength="150"></td>
                                </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-first">
                        <colgroup>
                            <col style="width: 40%" />
                            <col style="width: 60%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="bg-info" colspan="2">{LANG.access_denied}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <p><strong>{LANG.file_deny_access}</strong></p>{LANG.deny_access_note}
                                </td>
                                <td><textarea class="form-control" name="file_deny_access" rows="5">{DATA.file_deny_access_format}</textarea></td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>{LANG.dir_deny_access}</strong></p>{LANG.deny_access_note}
                                </td>
                                <td><textarea class="form-control" name="dir_deny_access" rows="5">{DATA.dir_deny_access_format}</textarea></td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>{LANG.include_dirs_deny_access}</strong></p>{LANG.deny_access_note}
                                </td>
                                <td><textarea class="form-control" name="include_dirs_deny_access" rows="5">{DATA.include_dirs_deny_access_format}</textarea></td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>{LANG.include_exec_files_deny_access}</strong></p>{LANG.deny_access_note}
                                </td>
                                <td><textarea class="form-control" name="include_exec_files_deny_access" rows="5">{DATA.include_exec_files_deny_access_format}</textarea></td>
                            </tr>
                            <tr>
                                <td><strong>{LANG.exec_files}</strong><br />({LANG.comma_separated})</td>
                                <td><input type="text" class="form-control" name="exec_files" value="{DATA.exec_files_format}" /></td>
                            </tr>
                            <tr>
                                <td><strong>{LANG.deny_access_code}</strong></td>
                                <td>
                                    <select name="deny_access_code" class="form-control">
                                        <!-- BEGIN: deny_access_code -->
                                        <option value="{CODE.num}" {CODE.sel}>{CODE.name}</option>
                                        <!-- END: deny_access_code -->
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-first">
                        <colgroup>
                            <col style="width: 40%" />
                            <col style="width: 60%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="bg-info" colspan="2">{LANG.error_pages}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: error_document -->
                            <tr>
                                <td><strong>{EDOC.title}</strong></td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">&lbrace;NV_BASE_SITEURL&rbrace;</span>
                                        <input type="text" class="form-control" name="error_document[{EDOC.code}]" value="{EDOC.val}" maxlength="255" />
                                    </div>
                                </td>
                            </tr>
                            <!-- END: error_document -->
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-first">
                        <colgroup>
                            <col style="width: 40%" />
                            <col style="width: 60%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="bg-info" colspan="2">{LANG.js_css_files}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>{LANG.mime_types}</strong><br />{LANG.mime_list_note}
                                </td>
                                <td><input type="text" class="form-control" name="js_css_mime_types" value="{DATA.js_css_mime_types}" /></td>
                            </tr>
                            <tr>
                                <td><strong>{LANG.cache_control}</strong></td>
                                <td><input type="text" class="form-control" name="js_css_cache_control" value="{DATA.js_css_files.cache_control}" maxlength="100" /></td>
                            </tr>
                            <tr>
                                <td><strong>{LANG.expires}</strong><br />{LANG.expires_note}</td>
                                <td><input type="text" class="form-control" name="js_css_expires" value="{DATA.js_css_files.expires}" maxlength="10" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-first">
                        <colgroup>
                            <col style="width: 40%" />
                            <col style="width: 60%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="bg-info" colspan="2">{LANG.image_files}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>{LANG.mime_types}</strong><br />{LANG.mime_list_note}
                                </td>
                                <td><input type="text" class="form-control" name="image_mime_types" value="{DATA.image_mime_types}" /></td>
                            </tr>
                            <tr>
                                <td><strong>{LANG.cache_control}</strong></td>
                                <td><input type="text" class="form-control" name="image_cache_control" value="{DATA.image_files.cache_control}" maxlength="100" /></td>
                            </tr>
                            <tr>
                                <td><strong>{LANG.expires}</strong><br />{LANG.expires_note}</td>
                                <td><input type="text" class="form-control" name="image_expires" value="{DATA.image_files.expires}" maxlength="10" /></td>
                            </tr>
                            <tr>
                                <td><strong>{LANG.prevent_image_hot_linking}</strong></td>
                                <td><input type="checkbox" class="form-control" name="prevent_image_hot_linking" value="1" {DATA.prevent_image_hot_linking_checked} /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-first">
                        <colgroup>
                            <col style="width: 40%" />
                            <col style="width: 60%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="bg-info" colspan="2">{LANG.font_files}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>{LANG.mime_types}</strong><br />{LANG.mime_list_note}
                                </td>
                                <td><input type="text" class="form-control" name="font_mime_types" value="{DATA.font_mime_types}" /></td>
                            </tr>
                            <tr>
                                <td><strong>{LANG.cache_control}</strong></td>
                                <td><input type="text" class="form-control" name="font_cache_control" value="{DATA.font_files.cache_control}" maxlength="100" /></td>
                            </tr>
                            <tr>
                                <td><strong>{LANG.expires}</strong><br />{LANG.expires_note}</td>
                                <td><input type="text" class="form-control" name="font_expires" value="{DATA.font_files.expires}" maxlength="10" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="panel-body text-center">
                    <input type="hidden" name="_csrf" value="{CHECKSS}" />
                    <input type="hidden" name="save" value="1" />
                    <button type="submit" class="btn btn-primary w100">{LANG.submit}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- BEGIN: rewrite_support -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/styles/default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/languages/{HIGHLIGHT_LANG}.min.js"></script>
<div class="modal fade" tabindex="-1" role="dialog" id="sConfigModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <pre><code class="language-{HIGHLIGHT_LANG}" style="white-space: pre;padding: 0;background-color: transparent;"></code></pre>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="sDefaultModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><strong>{LANG.server_configuration_by_settings_note}</strong></h3>
            </div>
            <div class="modal-body">
                <pre><code class="language-{HIGHLIGHT_LANG}" style="white-space: pre;padding: 0;background-color: transparent;"></code></pre>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="sChangeModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><strong>{LANG.change_confirm}:</strong></h3>
            </div>
            <div class="modal-body">
                <pre><code class="language-{HIGHLIGHT_LANG}" style="white-space: pre;padding: 0;background-color: transparent;"></code></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary confirm" data-toggle="confirm_change" data-url="{FORM_ACTION}" data-change-type="">{LANG.confirm_change}</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('[data-toggle=view_sconfig_file]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'getSconfigContents=1',
            dataType: "html",
            success: function(b) {
                $('#sConfigModal .modal-body>pre>code').text(b);
                $('#sConfigModal').modal('show')
            }
        })
    });
    $('[data-toggle=server_config_by_settings]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'getSconfigBySettings=1',
            dataType: "html",
            success: function(b) {
                $('#sDefaultModal .modal-body>pre>code').text(b);
                $('#sDefaultModal').modal('show')
            }
        })
    });
    $('[data-toggle=change_configs_to_default]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'changeConfigs=1',
            dataType: "html",
            success: function(b) {
                $('#sChangeModal .modal-body>pre>code').text(b);
                $('#sChangeModal .confirm').attr('data-change-type', 'changeConfigs');
                $('#sChangeModal').modal('show')
            }
        })
    });
    $('[data-toggle=change_rewrite_to_default]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'changeRewrite=1',
            dataType: "html",
            success: function(b) {
                $('#sChangeModal .modal-body>pre>code').text(b);
                $('#sChangeModal .confirm').attr('data-change-type', 'changeRewrite');
                $('#sChangeModal').modal('show')
            }
        })
    });
    $('[data-toggle=change_all_to_default]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'changeAll=1',
            dataType: "html",
            success: function(b) {
                $('#sChangeModal .modal-body>pre>code').text(b);
                $('#sChangeModal .confirm').attr('data-change-type', 'changeAll');
                $('#sChangeModal').modal('show')
            }
        })
    });
    $('[data-toggle=confirm_change]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url'),
            type = $(this).data('change-type');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: type + '=1&confirm=1',
            dataType: "html",
            success: function(b) {
                alert(b);
                window.location.href = url
            }
        })
    });
    $('#sConfigModal, #sDefaultModal, #sChangeModal').on('show.bs.modal', function(e) {
        hljs.debugMode();
        hljs.highlightAll();
    })
</script>
<!-- END: rewrite_support -->
<script>
    function any_origin_check() {
        if ($('[name=any_origin]').is(':checked')) {
            $('[name=cors_origins]').prop('readonly', true)
        } else {
            $('[name=cors_origins]').prop('readonly', false)
        }
    }
    $(function() {
        $('[name=any_origin]').on('change', function(e) {
            any_origin_check()
        });
        any_origin_check()
    })
</script>
<!-- END: main -->