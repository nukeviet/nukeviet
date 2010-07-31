<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1-27-2010 11:16
 */

if ( defined( 'NV_CLASS_UPLOAD_PHP' ) ) return;
define( 'NV_CLASS_UPLOAD_PHP', true );

define( "NV_MIME_INI_FILE", str_replace( "\\", "/", realpath( dirname( __file__ ) . "/.." ) . '/ini/mime.ini' ) );

if ( ! defined( '_ERROR_UPLOAD_FAILED' ) ) define( '_ERROR_UPLOAD_FAILED', 'Upload failed' );
if ( ! defined( '_ERROR_UPLOAD_INI_SIZE' ) ) define( '_ERROR_UPLOAD_INI_SIZE', 'The uploaded file exceeds the upload_max_filesize directive in php.ini' );
if ( ! defined( '_ERROR_UPLOAD_FORM_SIZE' ) ) define( '_ERROR_UPLOAD_FORM_SIZE', 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form' );
if ( ! defined( '_ERROR_UPLOAD_PARTIAL' ) ) define( '_ERROR_UPLOAD_PARTIAL', 'The uploaded file was only partially uploaded' );
if ( ! defined( '_ERROR_UPLOAD_NO_FILE' ) ) define( '_ERROR_UPLOAD_NO_FILE', 'No file was uploaded' );
if ( ! defined( '_ERROR_UPLOAD_NO_TMP_DIR' ) ) define( '_ERROR_UPLOAD_NO_TMP_DIR', 'Missing a temporary folder' );
if ( ! defined( '_ERROR_UPLOAD_CANT_WRITE' ) ) define( '_ERROR_UPLOAD_CANT_WRITE', 'Failed to write file to disk' );
if ( ! defined( '_ERROR_UPLOAD_EXTENSION' ) ) define( '_ERROR_UPLOAD_EXTENSION', 'File upload stopped by extension' );
if ( ! defined( '_ERROR_UPLOAD_UNKNOWN' ) ) define( '_ERROR_UPLOAD_UNKNOWN', 'Unknown upload error' );
if ( ! defined( '_ERROR_UPLOAD_TYPE_NOT_ALLOWED' ) ) define( '_ERROR_UPLOAD_TYPE_NOT_ALLOWED', 'Files of this type are not allowed' );
if ( ! defined( '_ERROR_UPLOAD_MAX_USER_SIZE' ) ) define( '_ERROR_UPLOAD_MAX_USER_SIZE', "The file exceeds the maximum size allowed. Maximum size is %d byte" );
if ( ! defined( '_ERROR_UPLOAD_NOT_IMAGE' ) ) define( '_ERROR_UPLOAD_NOT_IMAGE', 'The file is not a known image format' );
if ( ! defined( '_ERROR_UPLOAD_IMAGE_WIDTH' ) ) define( '_ERROR_UPLOAD_IMAGE_WIDTH', "The image is not allowed because the width is greater than the maximum of %d pixels" );
if ( ! defined( '_ERROR_UPLOAD_IMAGE_HEIGHT' ) ) define( '_ERROR_UPLOAD_IMAGE_HEIGHT', "The image is not allowed because the height is greater than the maximum of %d pixels" );
if ( ! defined( '_ERROR_UPLOAD_FORBIDDEN' ) ) define( '_ERROR_UPLOAD_FORBIDDEN', "Upload forbidden" );
if ( ! defined( '_ERROR_UPLOAD_WRITABLE' ) ) define( '_ERROR_UPLOAD_WRITABLE', "Directory %s is not writable" );

/**
 * upload
 * 
 * @package NUKEVIET 3.0
 * @author VINADES
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class upload
{

    var $is_img = false;

    var $img_info = array();

    var $maxsize;

    var $maxwidth;

    var $maxheight;

    var $allowmimes = array();

    var $forbid_extensions = array( 
        'php' 
    );

    var $forbid_mimes = array();

    /**
     * upload::upload()
     * 
     * @param mixed $allowed_filetypes
     * @param mixed $forbid_extensions
     * @param mixed $forbid_mimes
     * @param integer $maxsize
     * @param integer $maxwidth
     * @param integer $maxheight
     * @return
     */
    function upload ( $allowed_filetypes = array('any'), $forbid_extensions = array("php"), $forbid_mimes = array(), $maxsize = 0, $maxwidth = 0, $maxheight = 0 )
    {
        $this->maxsize = intval( $maxsize );
        $this->maxwidth = intval( $maxwidth );
        $this->maxheight = intval( $maxheight );
        $this->forbid_extensions = is_array( $forbid_extensions ) ? $forbid_extensions : array( 
            $forbid_extensions 
        );
        $this->forbid_mimes = is_array( $forbid_mimes ) ? $forbid_mimes : array( 
            $forbid_mimes 
        );
        $this->allowmimes = $this->get_allow_ext_mimes( is_array( $allowed_filetypes ) ? $allowed_filetypes : array( 
            $allowed_filetypes 
        ) );
    }

    /**
     * upload::string_to_filename()
     * 
     * @param mixed $word
     * @return
     */
    function string_to_filename ( $word )
    {
        $word = nv_EncString( $word );
        $word = preg_replace( '/^\W+|\W+$/', '', $word );
        $word = preg_replace( '/\s+/', '-', $word );
        return strtolower( preg_replace( '/\W-/', '', $word ) );
    }

    /**
     * upload::get_ini_file()
     * 
     * @param mixed $filename
     * @param bool $process_sections
     * @return
     */
    function get_ini_file ( $filename, $process_sections = false )
    {
        $process_sections = ( bool )$process_sections;
        
        if ( ! file_exists( $filename ) || ! is_readable( $filename ) ) return false;
        
        $disable_functions = ( ini_get( "disable_functions" ) != "" and ini_get( "disable_functions" ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "disable_functions" ) ) ) : array();
        
        if ( function_exists( 'parse_ini_file' ) and ! in_array( 'parse_ini_file', $disable_functions ) )
        {
            return parse_ini_file( $filename, $process_sections );
        }
        
        $data = file( $filename );
        $ini = array();
        $section = '';
        foreach ( $data as $line )
        {
            $line = trim( $line );
            if ( empty( $line ) || preg_match( "/^;/", $line ) ) continue;
            unset( $match );
            if ( preg_match( "/^\[(.*?)\]$/", $line, $match ) )
            {
                $section = $match[1];
                continue;
            }
            if ( ! strpos( $line, "=" ) ) continue;
            list( $key, $value ) = explode( "=", $line );
            $key = trim( $key );
            $value = trim( $value );
            $value = str_replace( array( 
                '"', "'" 
            ), array( 
                "", "" 
            ), $value );
            
            if ( $process_sections && ! empty( $section ) )
            {
                unset( $match );
                if ( preg_match( "/^(.*?)\[\]$/", $key, $match ) )
                {
                    $ini[$section][$match[1]][] = $value;
                }
                else
                {
                    $ini[$section][$key] = $value;
                }
            }
            else
            {
                unset( $match );
                if ( preg_match( "/^(.*?)\[\]$/", $key, $match ) )
                {
                    $ini[$match[1]][] = $value;
                }
                else
                {
                    $ini[$key] = $value;
                }
            }
        }
        return $ini;
    }

    /**
     * upload::get_allow_ext_mimes()
     * 
     * @param mixed $allowed_filetypes
     * @return
     */
    function get_allow_ext_mimes ( $allowed_filetypes )
    {
        if ( $allowed_filetypes == "any" or ( ! empty( $allowed_filetypes ) and is_array( $allowed_filetypes ) and in_array( "any", $allowed_filetypes ) ) )
        {
            return "*";
        }
        
        $ini = $this->get_ini_file( NV_MIME_INI_FILE, true );
        
        $allowmimes = array();
        if ( ! empty( $allowed_filetypes ) )
        {
            foreach ( $allowed_filetypes as $type )
            {
                if ( isset( $ini[$type] ) )
                {
                    foreach ( $ini[$type] as $ext => $mimes )
                    {
                        if ( ! empty( $ext ) and ! empty( $mimes ) )
                        {
                            if ( is_array( $mimes ) )
                            {
                                foreach ( $mimes as $m )
                                {
                                    $allowmimes[$m] = $ext;
                                }
                            }
                            else
                            {
                                $allowmimes[$mimes] = $ext;
                            }
                        }
                    }
                }
            }
        }
        return $allowmimes;
    }

    /**
     * upload::verify_image()
     * 
     * @param mixed $file
     * @return
     */
    function verify_image ( $file )
    {
        $file = preg_replace( '/\0/uis', '', $file );
        $txt = file_get_contents( $file );
        if ( $txt === false ) return false;
        //if ( preg_match( '#&(quot|lt|gt|nbsp|amp);#i', $txt ) ) return false;
        //else
        if ( preg_match( "#&\#x([0-9a-f]+);#i", $txt ) ) return false;
        elseif ( preg_match( '#&\#([0-9]+);#i', $txt ) ) return false;
        elseif ( preg_match( "#([a-z]*)=([\`\'\"]*)script:#iU", $txt ) ) return false;
        elseif ( preg_match( "#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt ) ) return false;
        elseif ( preg_match( "#([a-z]*)=([\'\"]*)vbscript:#iU", $txt ) ) return false;
        elseif ( preg_match( "#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt ) ) return false;
        elseif ( preg_match( "#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt ) ) return false;
        elseif ( preg_match( "#</*(applet|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt ) ) return false;
        return true;
    }

    /**
     * upload::check_tmpfile()
     * 
     * @param mixed $userfile
     * @return
     */
    function check_tmpfile ( $userfile )
    {
        if ( empty( $userfile ) ) return _ERROR_UPLOAD_FAILED;
        
        $variables = array( 
            'name', 'tmp_name', 'type', 'size' 
        );
        foreach ( $variables as $val )
        {
            if ( ! isset( $userfile[$val] ) or empty( $userfile[$val] ) ) return _ERROR_UPLOAD_FAILED;
        }
        
        if ( ! isset( $userfile['error'] ) or $userfile['error'] != UPLOAD_ERR_OK )
        {
            switch ( $userfile['error'] )
            {
                case UPLOAD_ERR_INI_SIZE:
                    $er = _ERROR_UPLOAD_INI_SIZE;
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $er = _ERROR_UPLOAD_FORM_SIZE;
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $er = _ERROR_UPLOAD_PARTIAL;
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $er = _ERROR_UPLOAD_NO_FILE;
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $er = _ERROR_UPLOAD_NO_TMP_DIR;
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $er = _ERROR_UPLOAD_CANT_WRITE;
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $er = _ERROR_UPLOAD_EXTENSION;
                    break;
                default:
                    $er = _ERROR_UPLOAD_UNKNOWN;
            }
            return $er;
        }
        $extension = strtolower( strrchr( $userfile['name'], '.' ) );
        if ( ! empty( $extension ) and ! empty( $this->forbid_extensions ) and in_array( $extension, $this->forbid_extensions ) )
        {
            return _ERROR_UPLOAD_TYPE_NOT_ALLOWED;
        }
        if ( ! empty( $this->forbid_mimes ) and in_array( $userfile['type'], $this->forbid_mimes ) )
        {
            return _ERROR_UPLOAD_TYPE_NOT_ALLOWED;
        }
        if ( empty( $this->allowmimes ) )
        {
            return _ERROR_UPLOAD_TYPE_NOT_ALLOWED;
        }
        
        if ( $this->allowmimes != "*" )
        {
            if ( ! isset( $this->allowmimes[$userfile['type']] ) )
            {
                return _ERROR_UPLOAD_TYPE_NOT_ALLOWED;
            }
            if ( ! empty( $this->maxsize ) and $userfile['size'] > $this->maxsize )
            {
                return sprintf( _ERROR_UPLOAD_MAX_USER_SIZE, $this->maxsize );
            }
        }
        
        if ( preg_match( '#image\/[x\-]*([a-z]+)#', $userfile['type'] ) or preg_match( "#application\/[x\-]*(shockwave\-flash)#", $userfile['type'] ) )
        {
            $this->is_img = true;
            $this->img_info = @getimagesize( $userfile['tmp_name'] );
            
            if ( empty( $this->img_info ) or ! isset( $this->img_info[0] ) or empty( $this->img_info[0] ) or ! isset( $this->img_info[1] ) or empty( $this->img_info[1] ) ) return _ERROR_UPLOAD_NOT_IMAGE;
            
            if ( ! $this->verify_image( $userfile['tmp_name'] ) ) return _ERROR_UPLOAD_NOT_IMAGE;
            
            if ( ! empty( $this->maxwidth ) and $this->img_info[0] > $this->maxwidth ) return sprintf( _ERROR_UPLOAD_IMAGE_WIDTH, $this->maxwidth );
            
            if ( ! empty( $this->maxheight ) and $this->img_info[1] > $this->maxheight ) return sprintf( _ERROR_UPLOAD_IMAGE_HEIGHT, $this->maxheight );
        }
        return "";
    }

    /**
     * upload::check_save_path()
     * 
     * @param mixed $savepath
     * @return
     */
    function check_save_path ( $savepath )
    {
        if ( empty( $savepath ) or ! is_dir( $savepath ) ) return _ERROR_UPLOAD_FORBIDDEN;
        
        if ( ! is_writable( $savepath ) )
        {
            @chmod( $savepath, 0755 );
            if ( ! is_writable( $savepath ) )
            {
                return sprintf( _ERROR_UPLOAD_WRITABLE, $savepath );
            }
        }
        return "";
    }

    /**
     * upload::save_file()
     * 
     * @param mixed $userfile
     * @param mixed $savepath
     * @param bool $replace_if_exists
     * @return
     */
    function save_file ( $userfile, $savepath, $replace_if_exists = true )
    {
        $this->is_img = false;
        $this->img_info = array();
        
        $return = array();
        $return['error'] = $this->check_tmpfile( $userfile );
        if ( empty( $return['error'] ) )
        {
            $return['error'] = $this->check_save_path( $savepath );
        }
        if ( ! empty( $return['error'] ) )
        {
            return $return;
        }
        
        if ( $this->allowmimes == "*" )
        {
            $extension = strtolower( strrchr( $userfile['name'], '.' ) );
            $fileext = ! empty( $extension ) ? $extension : '';
            $filename = $this->string_to_filename( $userfile['name'] );
        }
        else
        {
            if ( preg_match( "/^(.+)\.[a-zA-Z]+$/", $userfile['name'], $f ) )
            {
                $fn = $f[1];
            }
            else
            {
                $fn = $userfile['name'];
            }
            
            $fn = $this->string_to_filename( $fn );
            $fileext = $this->allowmimes[$userfile['type']];
            $path_info = pathinfo( $userfile['name'] );
            $filename = $fn . '.' . $path_info['extension'];
        }
        if ( ! preg_match( '/\/$/', $savepath ) ) $savepath = $savepath . "/";
        if ( empty( $replace_if_exists ) )
        {
            $filename2 = $filename;
            $i = 1;
            while ( file_exists( $savepath . $filename2 ) )
            {
                $filename2 = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $filename );
                $i ++;
            }
            $filename = $filename2;
        }
        if ( ! @copy( $userfile['tmp_name'], $savepath . $filename ) )
        {
            @move_uploaded_file( $userfile['tmp_name'], $savepath . $filename );
        }
        
        if ( ! file_exists( $savepath . $filename ) )
        {
            $return['error'] = _ERROR_UPLOAD_FAILED;
            return $return;
        }
        $oldumask = umask( 0 );
        chmod( $savepath . $filename, 0777 );
        umask( $oldumask );
        $return['name'] = $savepath . $filename;
        $return['basename'] = $filename;
        $return['ext'] = $fileext;
        $return['mime'] = $userfile['type'];
        $return['size'] = $userfile['size'];
        $return['is_img'] = $this->is_img;
        if ( $this->is_img )
        {
            $return['img_info'] = $this->img_info;
        }
        return $return;
    }
}
?>