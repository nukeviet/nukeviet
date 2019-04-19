/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

var LANG = [];
LANG.upload_size = "{$LANG->get('upload_size')}";
LANG.pubdate = "{$LANG->get('pubdate')}";
LANG.download = "{$LANG->get('download')}";
LANG.preview = "{$LANG->get('preview')}";
LANG.addlogo = "{$LANG->get('addlogo')}";
LANG.select = "{$LANG->get('select')}";
LANG.upload_createimage = "{$LANG->get('upload_createimage')}";
LANG.move = "{$LANG->get('move')}";
LANG.move_multiple = "{$LANG->get('move_multiple')}";
LANG.rename = "{$LANG->get('rename')}";
LANG.upload_delfile = "{$LANG->get('upload_delfile')}";
LANG.createfolder = "{$LANG->get('createfolder')}";
LANG.recreatethumb = "{$LANG->get('recreatethumb')}";
LANG.recreatethumb_note = "{$LANG->get('recreatethumb_note')}";
LANG.recreatethumb_result = "{$LANG->get('recreatethumb_result')}";
LANG.renamefolder = "{$LANG->get('renamefolder')}";
LANG.deletefolder = "{$LANG->get('deletefolder')}";
LANG.delete_folder = "{$LANG->get('delete_folder')}";
LANG.rename_nonamefolder = "{$LANG->get('rename_nonamefolder')}";
LANG.folder_exists = "{$LANG->get('folder_exists')}";
LANG.name_folder_error = "{$LANG->get('name_folder_error')}";
LANG.rename_noname = "{$LANG->get('rename_noname')}";
LANG.upload_delimg_confirm = "{$LANG->get('upload_delimg_confirm')}";
LANG.upload_delimgs_confirm = "{$LANG->get('upload_delimgs_confirm')}";
LANG.origSize = "{$LANG->get('origSize')}";
LANG.errorMinX = "{$LANG->get('errorMinX')}";
LANG.errorMaxX = "{$LANG->get('errorMaxX')}";
LANG.errorMinY = "{$LANG->get('errorMinY')}";
LANG.errorMaxY = "{$LANG->get('errorMaxY')}";
LANG.errorEmptyX = "{$LANG->get('errorEmptyX')}";
LANG.errorEmptyY = "{$LANG->get('errorEmptyY')}";
LANG.crop = "{$LANG->get('crop')}";
LANG.rotate = "{$LANG->get('rotate')}";
LANG.notupload = "{$LANG->get('notupload')}";
LANG.upload_file = "{$LANG->get('upload_file')}";
LANG.upload_mode = "{$LANG->get('upload_mode')}";
LANG.upload_mode_remote = "{$LANG->get('upload_mode_remote')}";
LANG.upload_mode_local = "{$LANG->get('upload_mode_local')}";
LANG.upload_cancel = "{$LANG->get('upload_cancel')}";
LANG.upload_add_files = "{$LANG->get('upload_add_files')}";
LANG.file_name = "{$LANG->get('file_name')}";
LANG.upload_status = "{$LANG->get('upload_status')}";
LANG.upload_info = "{$LANG->get('upload_info')}";
LANG.upload_stop = "{$LANG->get('upload_stop')}";
LANG.upload_continue = "{$LANG->get('upload_continue')}";
LANG.upload_finish = "{$LANG->get('upload_finish')}";
LANG.crop_error_small = "{$LANG->get('crop_error_small')}";
LANG.crop_keep_original = "{$LANG->get('crop_keep_original')}";
LANG.save = "{$LANG->get('addlogosave')}";
LANG.notlogo = "{$LANG->get('notlogo')}";
LANG.addlogo_error_small = "{$LANG->get('addlogo_error_small')}";
LANG.altimage = "{$LANG->get('altimage')}";
LANG.upload_alt_note = "{$LANG->get('upload_alt_note')}";

var nv_my_domain = '{$NV_MY_DOMAIN}';
var nv_max_size_bytes = '{$NV_MAX_SIZE_BYTES}';
var nv_max_width = '{$NV_MAX_WIDTH}';
var nv_max_height = '{$NV_MAX_HEIGHT}';
var nv_min_width = '{$NV_MIN_WIDTH}';
var nv_min_height = '{$NV_MIN_HEIGHT}';
var nv_chunk_size = '{$NV_CHUNK_SIZE}';
var nv_module_url = "{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&{$NV_NAME_VARIABLE}={$MODULE_NAME}&{$NV_OP_VARIABLE}=";
var nv_namecheck = /^([a-zA-Z0-9_-])+$/;
var array_images = ["gif", "jpg", "jpeg", "pjpeg", "png"];
var nv_loading_data = '<p class="upload-loading"><em class="fa fa-spin fa-spinner fa-2x m-bottom"></em><br />{$LANG->get('waiting')}...</p>';

// Resize images on clientside if we can
{if $NV_AUTO_RESIZE}
var nv_resize = {
    width : {$NV_MAX_WIDTH},
    height : {$NV_MAX_HEIGHT},
    quality : 99,
    crop: false // crop to exact dimensions
};
{else}
var nv_resize = false;
{/if}

var nv_alt_require = {$UPLOAD_ALT_REQUIRE};
var nv_auto_alt = {$UPLOAD_AUTO_ALT};
