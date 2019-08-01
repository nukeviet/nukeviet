<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 10, 2010 10:08:08 AM
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

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
            $return .= '<script type="text/javascript" src="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=upload&amp;js"></script>';
            $return .= "<script type=\"text/javascript\">
            // Xác định các nút ấn, offset của các input text fill dữ liệu vào. Quy luật là cái input trước nút ấn
            function nvCkeditorGetDialogDefinitionBtns(contents) {
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
                                textDOMOffset++;
                            }
                        });
                    } else if (v.type == 'hbox' || v.type == 'vbox') {
                        $.each(v.children, function(sk, sv) {
                            if (sv.type == 'text') {
                                textDOMOffset++;
                            } else if (sv.type == 'button') {
                                fileButtons.push([textDOMOffset, sv]);
                                textDOMOffset++;
                            }
                            if (typeof sv.children == 'object') {
                                $.each(sv.children, function(ssk, ssv) {
                                    if (ssv.type == 'text') {
                                        textDOMOffset++;
                                    } else if (ssv.type == 'button') {
                                        fileButtons.push([textDOMOffset, ssv]);
                                        textDOMOffset++;
                                    }
                                });
                            }
                        });
                    }
                });
                return fileButtons;
            }
            CKEDITOR.on('dialogDefinition', function(e) {
                if (e.data.name == 'image2' || e.data.name == 'video' || e.data.name == 'flash' || e.data.name == 'googledocs' || e.data.name == 'link' || e.data.name == 'tbvdownloadDialog') {
                    var contents;
                    if (e.data.name == 'googledocs') {
                        contents = e.data.definition.getContents('settingsTab');
                    } else if (e.data.name == 'tbvdownloadDialog') {
                        contents = e.data.definition.getContents('rhi_main');
                    } else {
                        contents = e.data.definition.getContents('info');
                    }
                    var dialogID = e.data.definition.dialog.parts.contents.$.id;
                    var fileButtons = nvCkeditorGetDialogDefinitionBtns(contents);
                    $.each(fileButtons, function(k, v) {
                        var btn = v[1];
                        if (typeof btn.filebrowser != 'undefined') {
                            // Hủy bỏ action mặc định khi ấn vào các nút duyệt file
                            btn.onClick = function(type, element) {
                                return true;
                            };
                        }
                    });
                    var dialog = e.data.definition.dialog;
                    dialog.on('show', function (e) {
                        var thisDialog = this
                        var dialogName = thisDialog._.name;
                        var config = thisDialog._.editor.config;
                        if (dialogName == 'image2' || dialogName == 'video' || dialogName == 'flash' || dialogName == 'googledocs' || dialogName == 'link' || dialogName == 'tbvdownloadDialog') {
                            var contents;
                            if (dialogName == 'googledocs') {
                                contents = thisDialog.definition.getContents('settingsTab');
                            } else if (dialogName == 'tbvdownloadDialog') {
                                contents = thisDialog.definition.getContents('rhi_main');
                            } else {
                                contents = thisDialog.definition.getContents('info');
                            }
                            var dialogID = thisDialog.definition.dialog.parts.contents.$.id;
                            var fileButtons = nvCkeditorGetDialogDefinitionBtns(contents);
                            var textInputsBtns = $('#' + dialogID).find('input[type=text].cke_dialog_ui_input_text,a.cke_dialog_ui_button');
                            var fileType;
                            if (dialogName == 'image2') {
                                fileType = 'image';
                            } else if (dialogName == 'flash') {
                                fileType = 'flash';
                            } else {
                                fileType = 'file';
                            }
                            $.each(fileButtons, function(k, v) {
                                var btn = v[1];
                                if (typeof btn.filebrowser != 'undefined') {
                                    var offsetInput = v[0];
                                    var offsetBtn = offsetInput + 1;
                                    var _btn = $(textInputsBtns[offsetBtn]);
                                    var _input = $(textInputsBtns[offsetInput]);
                                    if (_btn.length == 1 && _input.length == 1) {
                                        $('#' + _btn.attr('id')).nvBrowseFile({
                                            adminBaseUrl: '" . NV_BASE_ADMINURL . "',
                                            path: config.filebrowserPath,
                                            currentpath: config.filebrowserCurrentPath,
                                            type: fileType,
                                            restype: (dialogName == 'tbvdownloadDialog' ? 'folderpath' : 'filepath'),
                                            area: '#' + _input.attr('id')
                                        });
                                    }
                                }
                            });
                        }
                    });
                }
            });
            </script>";
        }
    }

    $return .= "<script type=\"text/javascript\">";
    if (defined('NV_IS_ADMIN')) {
        $return .= "$(document).on('nv.upload.ready', function() {";
    }
    $return .= "CKEDITOR.replace( '" . $module_data . "_" . $textareaid . "', {" . (!empty($customtoolbar) ? 'toolbar : "' . $customtoolbar . '",' : '') . " width: '" . $width . "',height: '" . $height . "',";
    $return .= "contentsCss: '" . NV_BASE_SITEURL . NV_EDITORSDIR . "/ckeditor/nv.css?t=" . $global_config['timestamp'] . "',";

    if (defined('NV_IS_ADMIN')) {
        if (empty($path) and empty($currentpath)) {
            $path = NV_UPLOADS_DIR;
            $currentpath = NV_UPLOADS_DIR;

            if (!empty($module_upload) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . date("Y_m"))) {
                $currentpath = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y_m');
                $path = NV_UPLOADS_DIR . '/' . $module_upload;
            } elseif (!empty($module_upload) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload)) {
                $currentpath = NV_UPLOADS_DIR . '/' . $module_upload;
            }
        }

        if (!empty($admin_info['allow_files_type'])) {
            $return .= "filebrowserUploadUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&" . NV_OP_VARIABLE . "=upload&editor=ckeditor&path=" . $currentpath . "',";
        }

        if (in_array('images', $admin_info['allow_files_type'])) {
            $return .= "filebrowserImageUploadUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&" . NV_OP_VARIABLE . "=upload&editor=ckeditor&path=" . $currentpath . "&type=image',";
        }

        if (in_array('flash', $admin_info['allow_files_type'])) {
            $return .= "filebrowserFlashUploadUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&" . NV_OP_VARIABLE . "=upload&editor=ckeditor&path=" . $currentpath . "&type=flash',";
        }
        $return .= "
        filebrowserBrowseUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&popup=1&path=" . $path . "&currentpath=" . $currentpath . "',
        filebrowserImageBrowseUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&popup=1&type=image&path=" . $path . "&currentpath=" . $currentpath . "',
        filebrowserFlashBrowseUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&popup=1&type=flash&path=" . $path . "&currentpath=" . $currentpath . "',
        filebrowserPath: '" . $path . "',
        filebrowserCurrentPath: '" . $currentpath . "'
        ";
    } else {
        // Không có quyền admin (upload file) thì gỡ các plugin upload để không bị báo lỗi
        $return .= "removePlugins: 'uploadfile,uploadimage'";
    }

    $return .= "});";
    if (defined('NV_IS_ADMIN')) {
        $return .= "});";
    }
    $return .= "</script>";

    return $return;
}
