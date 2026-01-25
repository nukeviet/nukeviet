<div class="accordion" id="ssettings">
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-general" aria-expanded="true" aria-controls="collapse-general">
                <span class="fw-medium fs-5"><i class="fa-solid fa-bell"></i> {$LANG->getModule('general_info_tab')}</span>
            </button>
        </div>
        <div id="collapse-general" class="accordion-collapse collapse show" data-bs-parent="#ssettings">
            <ul class="list-group list-group-flush list-group-accordion">
                <li class="list-group-item">
                    <span class="fw-medium">{$LANG->getModule('server_software')}:</span> {$INFO.server_software}
                </li>
                <li class="list-group-item">
                    <span class="fw-medium">{$LANG->getModule('php_sapi_name')}:</span> {$INFO.php_sapi}
                </li>
                <li class="list-group-item">
                    <span class="fw-medium">{$LANG->getModule('rewrite_support')}:</span> {$INFO.rewrite_support}
                </li>
                {if not empty($SYS_INFO.supports_rewrite) and not empty($INFO.sconfig_file)}
                <li class="list-group-item">
                    <span class="fw-medium">{$LANG->getModule('sconfig_file')}:</span>
                    <button type="button" class="btn btn-secondary" data-toggle="view_sconfig_file" data-checkss="{$CHECKSS}"><i class="fa-solid fa-file-lines"></i> {$INFO.sconfig_file}</button>
                </li>
                {/if}
            </ul>
        </div>
    </div>
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-settings" aria-expanded="false" aria-controls="collapse-settings">
                <span class="fw-medium fs-5"><i class="fa-solid fa-gears"></i> {$LANG->getModule('settings_tab')}</span>
            </button>
        </div>
        <div id="collapse-settings" class="accordion-collapse collapse" data-bs-parent="#ssettings">
            <form class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post" id="ssetings-form">
                <div class="accordion-body border-bottom">
                    <div class="alert alert-warning mb-0" role="alert">{$LANG->getModule('ssettings_note')}</div>
                </div>
                <ul class="list-group list-group-flush list-group-accordion list-group-striped">
                    <li class="list-group-item bg-body-secondary fw-medium">{$LANG->getModule('general_settings')}</li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_compress_types" class="fw-medium">{$LANG->getModule('compress_types')}</label>
                                <div class="form-text">{$LANG->getModule('compress_types_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="element_compress_types" name="compress_types" rows="5">{$DATA.compress_types_format}</textarea>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_site_mimetypes" class="fw-medium">{$LANG->getModule('mime_types_on_site')}</label>
                                <div class="form-text">{$LANG->getModule('mime_types_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="element_site_mimetypes" name="site_mimetypes" rows="5">{$DATA.site_mimetypes_format}</textarea>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_compress_file_exts" class="fw-medium">{$LANG->getModule('compress_file_exts')}</label>
                                <div class="form-text">{$LANG->getModule('mime_list_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <textarea class="form-control nonewline" id="element_compress_file_exts" name="compress_file_exts" rows="4">{$DATA.compress_file_exts_format}</textarea>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_charset_types" class="fw-medium">{$LANG->getModule('charset_types')}</label>
                                <div class="form-text">{$LANG->getModule('mime_list_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <textarea class="form-control nonewline" id="element_charset_types" name="charset_types" rows="4">{$DATA.charset_types_format}</textarea>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_cors_origins" class="fw-medium">{$LANG->getModule('access_control_allow_origin')}</label>
                                <div class="form-text">{$LANG->getModule('access_control_allow_origin_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <label class="input-group-text">
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="checkbox" name="any_origin" id="element_any_origin" value="1"{if !empty($DATA.cors_origins) and in_array('*', $DATA.cors_origins)} checked{/if}>
                                            <label class="form-check-label" for="element_any_origin">{$LANG->getModule('any_origin')}</label>
                                        </div>
                                    </label>
                                    <input type="text" class="form-control" id="element_cors_origins" name="cors_origins" value="{$DATA.cors_origins_list}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_remove_x_powered_by" class="fw-medium">{$LANG->getModule('remove_x_powered_by')}</label>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="element_remove_x_powered_by" name="remove_x_powered_by" value="1"{if not empty($DATA.remove_x_powered_by)} checked{/if}>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_disable_server_signature" class="fw-medium">{$LANG->getModule('disable_server_signature')}</label>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="element_disable_server_signature" name="disable_server_signature" value="1"{if not empty($DATA.disable_server_signature)} checked{/if}>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_remove_etag" class="fw-medium">{$LANG->getModule('remove_etag')}</label>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="element_remove_etag" name="remove_etag" value="1"{if not empty($DATA.remove_etag)} checked{/if}>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_not_cache_and_snippet" class="fw-medium">{$LANG->getModule('not_cache_and_snippet')}</label>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="element_not_cache_and_snippet" name="not_cache_and_snippet" value="1"{if not empty($DATA.not_cache_and_snippet)} checked{/if}>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_strict_transport_security" class="fw-medium">{$LANG->getModule('strict_transport_security')}</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="element_strict_transport_security" name="strict_transport_security" value="{$DATA.strict_transport_security}" maxlength="150">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_x_content_type_options" class="fw-medium">{$LANG->getModule('x_content_type_options')}</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="element_x_content_type_options" name="x_content_type_options" value="{$DATA.x_content_type_options}" maxlength="150">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_x_frame_options" class="fw-medium">{$LANG->getModule('x_frame_options')}</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="element_x_frame_options" name="x_frame_options" value="{$DATA.x_frame_options}" maxlength="150">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_x_xss_protection" class="fw-medium">{$LANG->getModule('x_xss_protection')}</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="element_x_xss_protection" name="x_xss_protection" value="{$DATA.x_xss_protection}" maxlength="150">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="element_referrer_policy" class="fw-medium">{$LANG->getModule('referrer_policy')}</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="element_referrer_policy" name="referrer_policy" value="{$DATA.referrer_policy}" maxlength="150">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item bg-body-secondary fw-medium">{$LANG->getModule('access_denied')}</li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="file_deny_access" class="fw-medium">{$LANG->getModule('file_deny_access')}</label>
                                <div class="form-text">{$LANG->getModule('deny_access_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="file_deny_access" name="file_deny_access" rows="5">{$DATA.file_deny_access_format}</textarea>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="dir_deny_access" class="fw-medium">{$LANG->getModule('dir_deny_access')}</label>
                                <div class="form-text">{$LANG->getModule('deny_access_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="dir_deny_access" name="dir_deny_access" rows="5">{$DATA.dir_deny_access_format}</textarea>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="include_dirs_deny_access" class="fw-medium">{$LANG->getModule('include_dirs_deny_access')}</label>
                                <div class="form-text">{$LANG->getModule('deny_access_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="include_dirs_deny_access" name="include_dirs_deny_access" rows="5">{$DATA.include_dirs_deny_access_format}</textarea>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="include_exec_files_deny_access" class="fw-medium">{$LANG->getModule('include_exec_files_deny_access')}</label>
                                <div class="form-text">{$LANG->getModule('deny_access_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="include_exec_files_deny_access" name="include_exec_files_deny_access" rows="5">{$DATA.include_exec_files_deny_access_format}</textarea>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="exec_files" class="fw-medium">{$LANG->getModule('exec_files')}</label>
                                <div class="form-text">{$LANG->getModule('comma_separated')}</div>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="exec_files" name="exec_files" value="{$DATA.exec_files_format}">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="deny_access_code" class="fw-medium">{$LANG->getModule('deny_access_code')}</label>
                            </div>
                            <div class="col-sm-7">
                                <select id="deny_access_code" name="deny_access_code" class="form-select">
                                    {foreach from=$DENY_ACCESS_CODES item=code}
                                    <option value="{$code}"{if $code eq $DATA.deny_access_code} selected{/if}>{$LANG->getModule("deny_access_code_`$code`")}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item bg-body-secondary fw-medium">{$LANG->getModule('error_pages')}</li>
                    {foreach from=$ERRORS item=error_code}
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="error_document_{$error_code}" class="fw-medium">{$LANG->getModule("error_pages_`$error_code`")}</label>
                            </div>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <span class="input-group-text">&lbrace;NV_BASE_SITEURL&rbrace;</span>
                                    <input type="text" class="form-control" id="error_document_{$error_code}" name="error_document[{$error_code}]" value="{$DATA.error_document[$error_code]}" maxlength="255">
                                </div>
                            </div>
                        </div>
                    </li>
                    {/foreach}
                    <li class="list-group-item bg-body-secondary fw-medium">{$LANG->getModule('js_css_files')}</li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="js_css_mime_types" class="fw-medium">{$LANG->getModule('mime_types')}</label>
                                <div class="form-text">{$LANG->getModule('mime_list_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="js_css_mime_types" name="js_css_mime_types" value="{$DATA.js_css_mime_types}">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="js_css_cache_control" class="fw-medium">{$LANG->getModule('cache_control')}</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="js_css_cache_control" name="js_css_cache_control" value="{$DATA.js_css_files.cache_control}" maxlength="100">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="js_css_expires" class="fw-medium">{$LANG->getModule('expires')}</label>
                                <div class="form-text">{$LANG->getModule('expires_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="js_css_expires" name="js_css_expires" value="{$DATA.js_css_files.expires}" maxlength="10">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item bg-body-secondary fw-medium">{$LANG->getModule('image_files')}</li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="image_mime_types" class="fw-medium">{$LANG->getModule('mime_types')}</label>
                                <div class="form-text">{$LANG->getModule('mime_list_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="image_mime_types" name="image_mime_types" value="{$DATA.image_mime_types}">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="image_cache_control" class="fw-medium">{$LANG->getModule('cache_control')}</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="image_cache_control" name="image_cache_control" value="{$DATA.image_files.cache_control}" maxlength="100">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="image_expires" class="fw-medium">{$LANG->getModule('expires')}</label>
                                <div class="form-text">{$LANG->getModule('expires_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="image_expires" name="image_expires" value="{$DATA.image_files.expires}" maxlength="10">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="prevent_image_hot_linking" class="fw-medium">{$LANG->getModule('prevent_image_hot_linking')}</label>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="prevent_image_hot_linking" name="prevent_image_hot_linking" value="1"{if not empty($DATA.image_files.prevent_image_hot_linking)} checked{/if}>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item bg-body-secondary fw-medium">{$LANG->getModule('font_files')}</li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="font_mime_types" class="fw-medium">{$LANG->getModule('mime_types')}</label>
                                <div class="form-text">{$LANG->getModule('mime_list_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="font_mime_types" name="font_mime_types" value="{$DATA.font_mime_types}">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="font_cache_control" class="fw-medium">{$LANG->getModule('cache_control')}</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="font_cache_control" name="font_cache_control" value="{$DATA.font_files.cache_control}" maxlength="100">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-sm-5">
                                <label for="font_expires" class="fw-medium">{$LANG->getModule('expires')}</label>
                                <div class="form-text">{$LANG->getModule('expires_note')}</div>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="font_expires" name="font_expires" value="{$DATA.font_files.expires}" maxlength="10">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item bg-body">
                        <div class="row g-2">
                            <div class="col-sm-7 offset-sm-5 text-center text-sm-start">
                                <input type="hidden" name="_csrf" value="{$CHECKSS}">
                                <input type="hidden" name="save" value="1">
                                <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                            </div>
                        </div>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-sample" aria-expanded="false" aria-controls="collapse-sample">
                <span class="fw-medium fs-5"><i class="fa-solid fa-file-code"></i> {$LANG->getModule('sample_tab')}</span>
            </button>
        </div>
        <div id="collapse-sample" class="accordion-collapse collapse" data-bs-parent="#ssettings">
            <div class="accordion-body">
                <div class="alert alert-warning" role="alert">{$LANG->getModule('server_configuration_by_settings_note')}</div>
                <form action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post" id="sample-form">
                    <div class="row mb-3">
                        <label for="rewrite_supporter" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('rewrite_support')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <select id="rewrite_supporter" name="rewrite_supporter" class="form-select">
                                <option value="rewrite_mode_apache" data-highlight-lang="language-apache">rewrite_mode_apache</option>
                                <option value="rewrite_mode_iis" data-highlight-lang="language-xml">rewrite_mode_iis</option>
                                <option value="nginx" data-highlight-lang="language-nginx">nginx</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3">
                            <input type="hidden" name="getSconfigBySettings" value="1">
                            <input type="hidden" name="checkss" value="{$CHECKSS}">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-file-lines"></i> {$LANG->getModule('server_configuration_by_settings')}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/highlightjs/default.min.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/highlightjs/highlight.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/highlightjs/lang/apache.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/highlightjs/lang/xml.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/highlightjs/lang/nginx.min.js"></script>
<div class="modal fade" tabindex="-1" id="sDefaultModal" aria-labelledby="sDefaultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="sDefaultModalLabel">{$LANG->getModule('sample_tab')}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <pre class="pre-wrap mb-0"><code></code></pre>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="sConfigModal" aria-labelledby="sConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="sConfigModalLabel">{$LANG->getModule('sconfig_file')}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <pre class="pre-wrap mb-0"><code class="language-{$HIGHLIGHT_LANG}"></code></pre>
            </div>
        </div>
    </div>
</div>
