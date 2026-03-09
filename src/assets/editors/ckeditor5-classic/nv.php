<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * nv_aleditor()
 *
 * @param string $textareaname Tên của thẻ textarea
 * @param string $width Chiều rộng của trình soạn thảo, ví dụ '100%'
 * @param string $height Chiều cao của trình soạn thảo, ví dụ '450px'
 * @param string $val Nội dung khởi tạo ban đầu
 * @param string $customtoolbar Chuỗi json_encode cấu hình toolbar, hoặc 'responsive' để tự động co dãn thanh toolbar
 * @param string $path Thư mục upload file, bắt đầu từ NV_UPLOADS_DIR
 * @param string $currentpath Thư mục hiện tại khi mở trình duyệt file, bắt đầu từ NV_UPLOADS_DIR, bỏ trống để lấy theo $path
 * @param string $init_callback Tên hàm hoặc code js chạy sau khi khởi tạo editor xong nếu có
 * @return string
 */
function nv_aleditor($textareaname, $width = '100%', $height = '450px', string $val = '', string $customtoolbar = '', string $path = '', string $currentpath = '', string $init_callback = ''): string
{
    global $global_config, $module_upload, $module_data, $admin_info;

    $textareaid = preg_replace('/[^a-z0-9\-\_ ]/i', '_', $textareaname);
    $editor_id = $module_data . '_' . $textareaid;

    $return = '<div id="outer_' . $editor_id . '" class="nv-ckeditor5classic"><textarea class="form-control" style="width: ' . $width . '; height:' . $height . ';" id="' . $editor_id . '" name="' . $textareaname . '">' . $val . '</textarea></div>';

    if (!defined('CKEDITOR5_CLASSIC')) {
        define('CKEDITOR5_CLASSIC', true);
        $return .= '<link rel="stylesheet" href="' . NV_STATIC_URL . NV_EDITORSDIR . '/ckeditor5-classic/ckeditor.css?t=' . $global_config['timestamp'] . '">';
        $return .= '<script type="text/javascript" src="' . NV_STATIC_URL . NV_EDITORSDIR . '/ckeditor5-classic/language/' . NV_LANG_INTERFACE . '.js?t=' . $global_config['timestamp'] . '"></script>';
        $return .= '<script type="text/javascript" src="' . NV_STATIC_URL . NV_EDITORSDIR . '/ckeditor5-classic/ckeditor.js?t=' . $global_config['timestamp'] . '"></script>';
    }

    $create = [];
    $create[] = 'language: "' . NV_LANG_INTERFACE . '"';

    $nukeviet_config = [];
    $nukeviet_config[] = 'editorId: "' . $editor_id . '"';
    $nukeviet_config[] = 'height: "' . $height . '"';
    if (!empty($init_callback)) {
        $nukeviet_config[] = 'initCallback: ' . $init_callback;
    }
    $create[] = 'nukeviet: {' . implode(', ', $nukeviet_config) . '}';

    $custom_toolbar = false;
    $responsive_editor = $customtoolbar == 'responsive' ? true : false;
    if (!empty($customtoolbar)) {
        $customtoolbar = json_decode($customtoolbar, true);
        if (is_array($customtoolbar)) {
            $custom_toolbar = true;
            $create[] = "toolbar : " . json_encode($customtoolbar);
        }
    }
    $toolbars = [
        'undo',
        'redo',
        'selectAll',
        '|',
        'link',
        'bookmark',
        'imageInsert',
        'nvmediaInsert',
        'nvbox',
        'insertTable',
        'nviframeInsert',
        'nvdocsInsert',
        'code',
        'codeBlock',
        'horizontalLine',
        'specialCharacters',
        'pageBreak',
        '|',
        'findAndReplace',
        'showBlocks',
        '|',
        'bulletedList',
        'numberedList',
        'outdent',
        'indent',
        'blockQuote',
        'heading',
        'fontSize',
        'fontFamily',
        'fontColor',
        'fontBackgroundColor',
        'highlight',
        'alignment',
        '|',
        'bold',
        'italic',
        'underline',
        'emoji',
        'strikethrough',
        'subscript',
        'superscript',
        '|',
        'sourceEditing',
        'nvtools',
        'removeFormat',
        'fullscreen'
    ];

    // Thiết lập nvbox, nvmedia và simpleUpload
    if (defined('NV_IS_ADMIN')) {
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
            $create[] = 'simpleUpload: {
                uploadUrl: "' . (NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=upload&' . NV_OP_VARIABLE . '=upload&editor=ckeditor5-classic&path=' . $currentpath) . '",
                withCredentials: true
            }';
        }
        $create[] = 'nvbox: {
            browseUrl: "' . (NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=upload&popup=1&editor_id=' . $editor_id . '&path=' . $path . '&currentpath=' . $currentpath) . '",
            pickerUrl: "' . (NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=upload&' . NV_OP_VARIABLE . '=js&t=' . $global_config['timestamp']) . '",
            options: {
                noCache: false
            }
        }';
    } else {
        // Không có quyền upload thì bỏ duyệt file và nút upload ảnh, media
        $create[] = 'removePlugins: ["NVBox"]';
        $create[] = 'image: {insert: {integrations: ["url"]}}';
        $create[] = 'nvmedia: {insert: {integrations: ["url"]}}';
        $toolbars = array_diff($toolbars, ['nvbox']);
    }
    if (!$custom_toolbar) {
        $create[] = "toolbar: {
            items: ['" . implode("', '", $toolbars) . "'],
            shouldNotGroupWhenFull: " . ($responsive_editor ? 'false' : 'true') . "
        }";
    }
    $create[] = "htmlSupport: {
		disallow: [{
			name: /.*/,
			attributes: [ /^on.*/ ]
		}, {
			name: 'script',
			attributes: [
				'action',
				'background',
				'codebase',
				'dynsrc',
				'lowsrc',
				'allownetworking',
				'allowscriptaccess',
				'fscommand',
				'seeksegmenttime'
			]
		}, {
			name: /^(script|style|link)$/
		}],
		allow: [{
			name: /" . (!empty($global_config['allowed_html_tags']) ? ('^(' . implode('|', $global_config['allowed_html_tags']) . ')$') : '.*') . "/,
			attributes: true,
			classes: true,
			styles: true
		}]
	}";

    $return .= '<script>
        (async () => {
            const editorId = "' . $editor_id . '";
            await ClassicEditor
            .create(document.getElementById("' . $editor_id . '"), {' . implode(', ', $create) . '}).catch(error => {
                console.error(error);
            });
        })();
    </script>';

    return $return;
}
