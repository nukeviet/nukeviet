<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 10, 2010 10:08:08 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

define( 'CKEDITOR', true );

/**
 * nv_aleditor()
 *
 * @param mixed $textareaname
 * @param string $width
 * @param string $height
 * @param string $val
 * @return
 */
function nv_aleditor( $textareaname, $width = '100%', $height = '450px', $val = '', $path = '', $currentpath = '' )
{
	global $module_name, $module_data, $admin_info, $client_info;

	if( empty( $path ) and empty( $currentpath ) )
	{
		$path = NV_UPLOADS_DIR;
		$currentpath = NV_UPLOADS_DIR;

		if( ! empty( $module_name ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . date( "Y_m" ) ) )
		{
			$currentpath = NV_UPLOADS_DIR . '/' . $module_name . '/' . date( "Y_m" );
			$path = NV_UPLOADS_DIR . '/' . $module_name;
		}
		elseif( ! empty( $module_name ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name ) )
		{
			$currentpath = NV_UPLOADS_DIR . '/' . $module_name;
		}
	}

	$return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
	$return .= "<script type=\"text/javascript\">
		CKEDITOR.replace( '" . $module_data . "_" . $textareaname . "', {width: '" . $width . "',height: '" . $height . "',";
	if( ! empty( $admin_info['allow_files_type'] ) )
	{
		$return .= "filebrowserUploadUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&" . NV_OP_VARIABLE . "=upload&editor=ckeditor&path=" . $currentpath . "',";
	}

	if( in_array( 'images', $admin_info['allow_files_type'] ) )
	{
		$return .= "filebrowserImageUploadUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&" . NV_OP_VARIABLE . "=upload&editor=ckeditor&path=" . $currentpath . "&type=image',";
	}

	if( in_array( 'flash', $admin_info['allow_files_type'] ) )
	{
		$return .= "filebrowserFlashUploadUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&" . NV_OP_VARIABLE . "=upload&editor=ckeditor&path=" . $currentpath . "&type=flash',";
	}
	$return .= "filebrowserBrowseUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&popup=1&path=" . $path . "&currentpath=" . $currentpath . "',
		 filebrowserImageBrowseUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&popup=1&type=image&path=" . $path . "&currentpath=" . $currentpath . "',
		 filebrowserFlashBrowseUrl: '" . NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=upload&popup=1&type=flash&path=" . $path . "&currentpath=" . $currentpath . "'
		});
		</script>";
	return $return;
}

/**
 * nv_add_editor_js()
 *
 * @return
 */
function nv_add_editor_js()
{
	global $global_config;
	return '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js?t=' . $global_config['timestamp'] . '"></script>';
}