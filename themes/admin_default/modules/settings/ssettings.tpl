<!-- BEGIN: main -->
<div class="panel-group" role="tablist" id="ssettings" aria-multiselectable="true">
    <div class="panel panel-primary">
        <a class="panel-heading" role="tab" style="display: block;text-decoration:none" id="general_info_tab-heading" data-toggle="collapse" data-parent="#ssettings" href="#general_info_tab" aria-expanded="true" aria-controls="general_info_tab">
            <i class="fa fa-bell"></i> <strong>{LANG.general_info_tab}</strong>
        </a>
        <div id="general_info_tab" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="general_info_tab-heading">
            <ul class="list-group type2n1">
                <li class="list-group-item">
                    <strong>{LANG.server_software}:</strong>
                    {INFO.server_software}
                </li>

                <li class="list-group-item">
                    <strong>{LANG.php_sapi_name}:</strong>
                    {INFO.php_sapi}
                </li>

                <li class="list-group-item">
                    <strong>{LANG.rewrite_support}:</strong>
                    {INFO.rewrite_support}
                </li>

                <!-- BEGIN: sconfig_file -->
                <li class="list-group-item">
                    <strong>{LANG.sconfig_file}:</strong>
                    <button type="button" class="btn btn-default active" data-toggle="view_sconfig_file" data-url="{FORM_ACTION}"><i class="fa fa-file-text-o"></i>&nbsp;{INFO.sconfig_file}</button>
                </li>
                <!-- END: sconfig_file -->
            </ul>
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
        <a class="panel-heading collapsed" role="tab" id="settings_tab-heading" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#ssettings" href="#settings_tab" aria-expanded="false" aria-controls="settings_tab">
            <i class="fa fa-cogs"></i> <strong>{LANG.settings_tab}</strong>
        </a>
        <div id="settings_tab" class="panel-collapse collapse" role="tabpanel" aria-labelledby="settings_tab-heading">
            <div class="alert alert-warning" style="margin-bottom:0;border-radius:0">{LANG.ssettings_note}</div>
            <form action="{FORM_ACTION}" method="post" class="form-horizontal" id="ssetings-form">
                <ul class="list-group type2n1 mb-0">
                    <li class="list-group-item text-center title"><strong>{LANG.general_settings}</strong></li>

                    <li class="list-group-item" style="<!-- BEGIN: if_not_iis -->display: none;<!-- END: if_not_iis -->">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.compress_types}</strong></div>
                                <div class="help-block">{LANG.compress_types_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <textarea class="form-control" name="compress_types" rows="5">{DATA.compress_types_format}</textarea>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.mime_types_on_site}</strong></div>
                                <div class="help-block">{LANG.mime_types_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <textarea class="form-control" name="site_mimetypes" rows="5">{DATA.site_mimetypes_format}</textarea>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.compress_file_exts}</strong></div>
                                <div class="help-block">{LANG.mime_list_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <textarea class="form-control nonewline" name="compress_file_exts" style="height:70px;overflow:hidden;resize: none;">{DATA.compress_file_exts_format}</textarea>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.charset_types}</strong></div>
                                <div class="help-block">{LANG.mime_list_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <textarea class="form-control nonewline" name="charset_types" style="height:70px;overflow:hidden;resize: none;">{DATA.charset_types_format}</textarea>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.access_control_allow_origin}</strong></div>
                                <div class="help-block">{LANG.access_control_allow_origin_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <div class="input-group">
                                    <label class="input-group-addon">
                                        <input type="checkbox" name="any_origin" style="background-color: white;" value="1" {DATA.cors_origins_any}> {LANG.any_origin}
                                    </label>
                                    <input type="text" class="form-control" name="cors_origins" value="{DATA.cors_origins_list}">
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10 hidden-xs">
                                <strong>{LANG.remove_x_powered_by}</strong>
                            </div>
                            <div class="col-sm-14">
                                <label>
                                    <input type="checkbox" class="form-control" name="remove_x_powered_by" value="1" {DATA.remove_x_powered_by_checked} /> <strong class="visible-xs-inline">{LANG.remove_x_powered_by}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10 hidden-xs">
                                <strong>{LANG.disable_server_signature}</strong>
                            </div>
                            <div class="col-sm-14">
                                <label>
                                    <input type="checkbox" class="form-control" name="disable_server_signature" value="1" {DATA.disable_server_signature_checked} /> <strong class="visible-xs-inline">{LANG.disable_server_signature}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10 hidden-xs">
                                <strong>{LANG.remove_etag}</strong>
                            </div>
                            <div class="col-sm-14">
                                <label>
                                    <input type="checkbox" class="form-control" name="remove_etag" value="1" {DATA.remove_etag_checked} /> <strong class="visible-xs-inline">{LANG.remove_etag}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10 hidden-xs">
                                <strong>{LANG.not_cache_and_snippet}</strong>
                            </div>
                            <div class="col-sm-14">
                                <label>
                                    <input type="checkbox" class="form-control" name="not_cache_and_snippet" value="1" {DATA.not_cache_and_snippet_checked} /> <strong class="visible-xs-inline">{LANG.not_cache_and_snippet}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.strict_transport_security}</strong></div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="strict_transport_security" value="{DATA.strict_transport_security}" maxlength="150">
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.x_content_type_options}</strong></div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="x_content_type_options" value="{DATA.x_content_type_options}" maxlength="150">
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"> <strong>{LANG.x_frame_options}</strong></div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="x_frame_options" value="{DATA.x_frame_options}" maxlength="150">
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.x_xss_protection}</strong></div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="x_xss_protection" value="{DATA.x_xss_protection}" maxlength="150">
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.referrer_policy}</strong></div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="referrer_policy" value="{DATA.referrer_policy}" maxlength="150">
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item text-center title"><strong>{LANG.access_denied}</strong></li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.file_deny_access}</strong></div>
                                <div class="help-block">{LANG.deny_access_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <textarea class="form-control" name="file_deny_access" rows="5">{DATA.file_deny_access_format}</textarea>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.dir_deny_access}</strong></div>
                                <div class="help-block">{LANG.deny_access_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <textarea class="form-control" name="dir_deny_access" rows="5">{DATA.dir_deny_access_format}</textarea>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.include_dirs_deny_access}</strong></div>
                                <div class="help-block">{LANG.deny_access_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <textarea class="form-control" name="include_dirs_deny_access" rows="5">{DATA.include_dirs_deny_access_format}</textarea>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.include_exec_files_deny_access}</strong></div>
                                <div class="help-block">{LANG.deny_access_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <textarea class="form-control" name="include_exec_files_deny_access" rows="5">{DATA.include_exec_files_deny_access_format}</textarea>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.exec_files}</strong></div>
                                <div class="help-block">{LANG.comma_separated}</div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="exec_files" value="{DATA.exec_files_format}" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.deny_access_code}</strong></div>
                            </div>
                            <div class="col-sm-14">
                                <select name="deny_access_code" class="form-control">
                                    <!-- BEGIN: deny_access_code -->
                                    <option value="{CODE.num}" {CODE.sel}>{CODE.name}</option>
                                    <!-- END: deny_access_code -->
                                </select>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item text-center title"><strong>{LANG.error_pages}</strong></li>

                    <!-- BEGIN: error_document -->
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{EDOC.title}</strong></div>
                            </div>
                            <div class="col-sm-14">
                                <div class="input-group">
                                    <span class="input-group-addon">&lbrace;NV_BASE_SITEURL&rbrace;</span>
                                    <input type="text" class="form-control" name="error_document[{EDOC.code}]" value="{EDOC.val}" maxlength="255" />
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- END: error_document -->

                    <li class="list-group-item text-center title"><strong>{LANG.js_css_files}</strong></li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.mime_types}</strong></div>
                                <div class="help-block">{LANG.mime_list_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="js_css_mime_types" value="{DATA.js_css_mime_types}" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.cache_control}</strong></div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="js_css_cache_control" value="{DATA.js_css_files.cache_control}" maxlength="100" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.expires}</strong></div>
                                <div class="help-block">{LANG.expires_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="js_css_expires" value="{DATA.js_css_files.expires}" maxlength="10" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item text-center title"><strong>{LANG.image_files}</strong></li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.mime_types}</strong></div>
                                <div class="help-block">{LANG.mime_list_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="image_mime_types" value="{DATA.image_mime_types}" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.cache_control}</strong></div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="image_cache_control" value="{DATA.image_files.cache_control}" maxlength="100" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.expires}</strong></div>
                                <div class="help-block">{LANG.expires_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="image_expires" value="{DATA.image_files.expires}" maxlength="10" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10 hidden-xs">
                                <strong>{LANG.prevent_image_hot_linking}</strong>
                            </div>
                            <div class="col-sm-14">
                                <label>
                                    <input type="checkbox" class="form-control" name="prevent_image_hot_linking" value="1" {DATA.prevent_image_hot_linking_checked} /> <strong class="visible-xs-inline">{LANG.prevent_image_hot_linking}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item text-center title"><strong>{LANG.font_files}</strong></li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.mime_types}</strong></div>
                                <div class="help-block">{LANG.mime_list_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="font_mime_types" value="{DATA.font_mime_types}" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.cache_control}</strong></div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="font_cache_control" value="{DATA.font_files.cache_control}" maxlength="100" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <div class="col-sm-10">
                                <div class="mb-sm"><strong>{LANG.expires}</strong></div>
                                <div class="help-block">{LANG.expires_note}</div>
                            </div>
                            <div class="col-sm-14">
                                <input type="text" class="form-control" name="font_expires" value="{DATA.font_files.expires}" maxlength="10" />
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="panel-body text-center">
                    <input type="hidden" name="_csrf" value="{CHECKSS}" />
                    <input type="hidden" name="save" value="1" />
                    <button type="submit" class="btn btn-primary">{LANG.submit}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- BEGIN: rewrite_support -->
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/highlightjs/default.min.css">
<script src="{ASSETS_STATIC_URL}/js/highlightjs/highlight.min.js"></script>
<script src="{ASSETS_STATIC_URL}/js/highlightjs/lang/{HIGHLIGHT_LANG}.min.js"></script>
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
<!-- END: rewrite_support -->
<!-- END: main -->