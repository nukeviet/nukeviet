<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 10, 2010 10:08:08 AM
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

/**
 * nv_aleditor()
 *
 * @param mixed $textareaname
 * @param string $width
 * @param string $height
 * @param string $val
 * @return
 */
function nv_aleditor($textareaname, $width = '100%', $height = '450px', $val = '', $customtoolbar = '', $path = '', $currentpath = '')
{
    global $global_config, $module_upload, $module_data, $admin_info;

    $textareaid = preg_replace('/[^a-z0-9\-\_ ]/i', '_', $textareaname);
    $return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaid . '" name="' . $textareaname . '">' . $val . '</textarea>';
    if (!defined('CKEDITOR')) {
        define('CKEDITOR', true);
        $return .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js?t=' . $global_config['timestamp'] . '"></script>';
        $return .= '<script type="text/javascript">CKEDITOR.timestamp=CKEDITOR.timestamp+' . $global_config['timestamp'] . ';</script>';
        if (defined('NV_IS_ADMIN')) {
            $return .= "<script type=\"text/javascript\">
            CKEDITOR.on('dialogDefinition', function(e) {
                if (e.data.name == 'image2' || e.data.name == 'video' || e.data.name == 'docviewer' || e.data.name == 'link') {
                    var contents;
                    if (e.data.name == 'docviewer') {
                        contents = e.data.definition.getContents('settingsTab');
                    } else {
                        contents = e.data.definition.getContents('info');
                    }
                    var dialogID = e.data.definition.dialog.parts.contents.$.id;
                    var fileButtons = new Array();
                    var textDOMOffset = -1;
                    $.each(contents.elements, function(k, v) {
                        if (v.type == 'text') {
                            textDOMOffset++;
                        } else if (v.type == 'vbox1') {
                            $.each(v.children[0].children, function(sk, sv) {
                                if (sv.type == 'text') {
                                    textDOMOffset++;
                                } else if (sv.type == 'button') {
                                    fileButtons.push([textDOMOffset, sv]);
                                }
                            });
                        } else if (v.type == 'hbox' || v.type == 'vbox') {
                            $.each(v.children, function(sk, sv) {
                                if (sv.type == 'text') {
                                    textDOMOffset++;
                                } else if (sv.type == 'button') {
                                    fileButtons.push([textDOMOffset, sv]);
                                }
                                if (typeof sv.children == 'object') {
                                    $.each(sv.children, function(ssk, ssv) {
                                        if (ssv.type == 'text') {
                                            textDOMOffset++;
                                        } else if (ssv.type == 'button') {
                                            fileButtons.push([textDOMOffset, ssv]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                    $.each(fileButtons, function(k, v) {
                        var btn = v[1];
                        if (typeof btn.filebrowser != 'undefined') {
                            var offset = v[0];
                            var orgclickevent = btn.onClick;
                            btn.onClick = function(type, element) {
                                var textInputs = $('#' + dialogID).find('input[type=text].cke_dialog_ui_input_text');
                                var input = $(textInputs[offset]);
                                if (input.length == 1) {
                                    btn.filebrowser.url = btn.filebrowser.url.replace(/\&currentfile\=.*?$/g, '');
                                    btn.filebrowser.url = btn.filebrowser.url + '&currentfile=' + encodeURIComponent(input.val());
                                }
                                orgclickevent.call(this, type, element);
                            };
                        }
                    });
                }
            });
            </script>";
        }
    }

    $replaces = [];
    !empty($customtoolbar) && $replaces[] = "toolbar : '" . $customtoolbar . "'";
    $replaces[] = "width:'" . $width . "'";
    $replaces[] = "height:'" . $height . "'";
    $replaces[] = "contentsCss:'" . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/nv.css?t=' . $global_config['timestamp'] . "'";
    if (defined('NV_IS_ADMIN')) {
        $replaces[] = 'clipboard_handleImages: false';

        if (empty($path) and empty($currentpath)) {
            $path = NV_UPLOADS_DIR;
            $currentpath = NV_UPLOADS_DIR;

            if (!empty($module_upload) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . date('Y_m'))) {
                $currentpath = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y_m');
                $path = NV_UPLOADS_DIR . '/' . $module_upload;
            } elseif (!empty($module_upload) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload)) {
                $currentpath = NV_UPLOADS_DIR . '/' . $module_upload;
            }
        }

        if (!empty($admin_info['allow_files_type'])) {
            $replaces[] = "filebrowserUploadUrl:'" . NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_NAME_VARIABLE . '=upload&' . NV_OP_VARIABLE . '=upload&editor=ckeditor&path=' . $currentpath . "'";
        }
        if (in_array('images', $admin_info['allow_files_type'], true)) {
            $replaces[] = "filebrowserImageUploadUrl:'" . NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_NAME_VARIABLE . '=upload&' . NV_OP_VARIABLE . '=upload&editor=ckeditor&path=' . $currentpath . "&type=image'";
        }
        $replaces[] = "filebrowserBrowseUrl:'" . NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&path=' . $path . '&currentpath=' . $currentpath . "'";
        $replaces[] = "filebrowserImageBrowseUrl:'" . NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&type=image&path=' . $path . '&currentpath=' . $currentpath . "'";
    } else {
        // Không có quyền admin (upload file) thì gỡ các plugin upload để không bị báo lỗi
        $replaces[] = "removePlugins:'uploadfile,uploadimage'";
    }
    if (!empty($global_config['allowed_html_tags'])) {
        $allowedContent = [];
        foreach ($global_config['allowed_html_tags'] as $tag) {
            $allowedContent[] = $tag . '[*]{*}(*)';
        }
        $replaces[] = "allowedContent:'" . implode(';', $allowedContent) . "'";
    }
    $replaces[] = "disallowedContent:'script; *[on*,action,background,codebase,dynsrc,lowsrc,allownetworking,allowscriptaccess,fscommand,seeksegmenttime]'";
    $return .= "<script>CKEDITOR.replace( '" . $module_data . '_' . $textareaid . "', {" . implode(',', $replaces) . '});</script>';

    return $return;
}
