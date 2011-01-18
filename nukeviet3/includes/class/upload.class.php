<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 18/1/2011, 1:11
 */

if ( defined( 'NV_CLASS_UPLOAD_PHP' ) ) return;
define( 'NV_CLASS_UPLOAD_PHP', true );

define( "NV_MIME_INI_FILE", str_replace( "\\", "/", realpath( dirname( __file__ ) . "/.." ) . '/ini/mime.ini' ) );
define( "NV_LOOKUP_FILE", str_replace( "\\", "/", realpath( dirname( __file__ ) . "/.." ) . '/utf8/lookup.php' ) );

if ( ! defined( 'UPLOAD_CHECKING_MODE' ) ) define( 'UPLOAD_CHECKING_MODE', 'strong' );

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
 * @author VINADES.,JSC
 * @copyright 2011
 * @version $Id$
 * @access public
 */
class upload
{
    private $config = array( //
        'allowed_files' => array(), //
        'upload_checking_mode' => 'strong', //
        'maxsize' => 0, //
        'maxwidth' => 0, //
        'maxheight' => 0, //
        'magic_path' => '' //
        );

    private $file_extension = '';
    private $file_mime = '';
    private $is_img = false;
    private $img_info = array();

    /**
     * upload::__construct()
     * 
     * @param mixed $allowed_filetypes
     * @param mixed $forbid_extensions
     * @param mixed $forbid_mimes
     * @param integer $maxsize
     * @param integer $maxwidth
     * @param integer $maxheight
     * @param string $magic_path
     * @return
     */
    public function __construct( $allowed_filetypes = array( 'any' ), $forbid_extensions = array( "php" ), $forbid_mimes = array(), $maxsize = 0, $maxwidth = 0, $maxheight = 0, $magic_path = '' )
    {
        if ( ! is_array( $allowed_filetypes ) ) $allowed_filetypes = array( $allowed_filetypes );
        if ( ! empty( $allowed_filetypes ) and in_array( "any", $allowed_filetypes ) ) $allowed_filetypes = array( 'any' );
        if ( ! is_array( $forbid_extensions ) ) $forbid_extensions = array( $forbid_extensions );
        if ( ! is_array( $forbid_mimes ) ) $forbid_mimes = array( $forbid_mimes );

        $this->config['allowed_files'] = $this->get_ini( $allowed_filetypes, $forbid_extensions, $forbid_mimes );
        $this->config['maxsize'] = intval( $maxsize );
        $this->config['maxwidth'] = intval( $maxwidth );
        $this->config['maxheight'] = intval( $maxheight );
        $this->config['upload_checking_mode'] = UPLOAD_CHECKING_MODE;
        $this->config['magic_path'] = $magic_path;
    }

    /**
     * upload::func_exists()
     * 
     * @param mixed $funcName
     * @return
     */
    private function func_exists( $funcName )
    {
        $disable_functions = ( ini_get( "disable_functions" ) != "" and ini_get( "disable_functions" ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "disable_functions" ) ) ) : array();
        return ( function_exists( $funcName ) and ! in_array( $funcName, $disable_functions ) );
    }

    /**
     * upload::cl_exists()
     * 
     * @param mixed $clName
     * @return
     */
    private function cl_exists( $clName )
    {
        $disable_classes = ( ini_get( "disable_classes" ) != "" and ini_get( "disable_classes" ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "disable_classes" ) ) ) : array();
        return ( class_exists( $clName ) and ! in_array( $clName, $disable_classes ) );
    }

    /**
     * upload::getextension()
     * 
     * @param mixed $filename
     * @return
     */
    private function getextension( $filename )
    {
        if ( strpos( $filename, '.' ) === false ) return '';
        $filename = basename( strtolower( $filename ) );
        $filename = explode( '.', $filename );
        return array_pop( $filename );
    }

    /**
     * upload::get_ini()
     * 
     * @param mixed $allowed_filetypes
     * @param mixed $forbid_extensions
     * @param mixed $forbid_mimes
     * @return
     */
    private function get_ini( $allowed_filetypes, $forbid_extensions, $forbid_mimes )
    {
        $all_ini = array();

        $data = file( NV_MIME_INI_FILE );
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
            $value = str_replace( array( '"', "'" ), array( "", "" ), $value );

            unset( $match );
            if ( preg_match( "/^(.*?)\[\]$/", $key, $match ) )
            {
                $all_ini[$section][$match[1]][] = $value;
            }
            else
            {
                $all_ini[$section][$key][] = $value;
            }
        }

        $ini = array();
        foreach ( $all_ini as $section => $line )
        {
            if ( $allowed_filetypes == array( 'any' ) or in_array( $section, $allowed_filetypes ) )
            {
                $ini = array_merge( $ini, $line );
            }
        }

        if ( ! empty( $forbid_extensions ) )
        {
            foreach ( $forbid_extensions as $extension )
            {
                unset( $ini[$extension] );
            }
        }

        if ( ! empty( $forbid_mimes ) )
        {
            $new_ini = array();
            foreach ( $ini as $key => $i )
            {
                $new = array();
                $new[$key] = array();
                foreach ( $i as $i2 )
                {
                    if ( ! in_array( $i2, $forbid_mimes ) )
                    {
                        $new[$key][] = $i2;
                    }
                }
                if ( ! empty( $new[$key] ) )
                {
                    $new_ini = array_merge( $new_ini, $new );
                }
            }
            $ini = $new_ini;
        }

        return $ini;
    }

    /**
     * upload::get_mime_type()
     * 
     * @param mixed $userfile
     * @return
     */
    private function get_mime_type( $userfile )
    {
        $mime = "";
        if ( $this->func_exists( 'finfo_open' ) )
        {
            if ( empty( $this->config['magic_path'] ) )
            {
                $finfo = finfo_open( FILEINFO_MIME );
            } elseif ( $this->config['magic_path'] != "auto" )
            {
                $finfo = finfo_open( FILEINFO_MIME, $this->config['magic_path'] );
            }
            else
            {
                if ( ( $magic = getenv( 'MAGIC' ) ) !== false )
                {
                    $finfo = finfo_open( FILEINFO_MIME, $magic );
                }
                else
                {
                    if ( substr( PHP_OS, 0, 3 ) == 'WIN' )
                    {
                        $path = realpath( ini_get( 'extension_dir' ) . '/../' ) . 'extras/magic';
                        $finfo = finfo_open( FILEINFO_MIME, $path );
                    }
                    else
                    {
                        $finfo = finfo_open( FILEINFO_MIME, '/usr/share/file/magic' );
                    }
                }
            }

            if ( is_resource( $finfo ) )
            {
                $mime = finfo_file( $finfo, realpath( $userfile['tmp_name'] ) );
                finfo_close( $finfo );
                $mime = preg_replace( "/^([\.-\w]+)\/([\.-\w]+)(.*)$/i", '$1/$2', trim( $mime ) );
            }
        }

        if ( empty( $mime ) or $mime == "application/octet-stream" )
        {
            if ( $this->cl_exists( "finfo" ) )
            {
                $finfo = new finfo( FILEINFO_MIME );
                if ( $finfo )
                {
                    $mime = $finfo->file( realpath( $userfile['tmp_name'] ) );
                    $mime = preg_replace( "/^([\.-\w]+)\/([\.-\w]+)(.*)$/i", '$1/$2', trim( $mime ) );
                }
            }
        }

        if ( empty( $mime ) or $mime == "application/octet-stream" )
        {
            if ( substr( PHP_OS, 0, 3 ) != 'WIN' )
            {
                if ( $this->func_exists( 'system' ) )
                {
                    ob_start();
                    system( "file -i -b " . escapeshellarg( $userfile['tmp_name'] ) );
                    $m = ob_get_clean();
                    $m = trim( $m );
                    if ( ! empty( $m ) )
                    {
                        $mime = preg_replace( "/^([\.-\w]+)\/([\.-\w]+)(.*)$/i", '$1/$2', $m );
                    }
                } elseif ( $this->func_exists( 'exec' ) )
                {
                    $m = @exec( "file -bi " . escapeshellarg( $userfile['tmp_name'] ) );
                    $m = trim( $m );
                    if ( ! empty( $m ) )
                    {
                        $mime = preg_replace( "/^([\.-\w]+)\/([\.-\w]+)(.*)$/i", '$1/$2', $m );
                    }
                }
            }
        }

        if ( empty( $mime ) or $mime == "application/octet-stream" )
        {
            if ( $this->func_exists( 'mime_content_type' ) )
            {
                $mime = mime_content_type( $userfile['tmp_name'] );
                $mime = preg_replace( "/^([\.-\w]+)\/([\.-\w]+)(.*)$/i", '$1/$2', trim( $mime ) );
            }
        }

        if ( empty( $mime ) or $mime == "application/octet-stream" )
        {
            $img_exts = array( 'png', 'gif', 'jpg', 'bmp', 'tiff', 'swf', 'psd' );
            if ( in_array( $this->file_extension, $img_exts ) )
            {
                $img_info = @getimagesize( $userfile['tmp_name'] );
                if ( ( $img_info = @getimagesize( $userfile['tmp_name'] ) ) !== false )
                {
                    $this->img_info = $img_info;
                    if ( is_array( $this->img_info ) && array_key_exists( 'mime', $this->img_info ) )
                    {
                        $mime = trim( $this->img_info['mime'] );
                        if ( ! empty( $mime ) )
                        {
                            $mime = preg_replace( "/^([\.-\w]+)\/([\.-\w]+)(.*)$/i", '$1/$2', $mime );
                        } elseif ( array_key_exists( 2, $this->img_info ) )
                        {
                            $mime = image_type_to_mime_type( $this->img_info[2] );
                        }
                    }
                }
            }
        }

        if ( empty( $mime ) or $mime == "application/octet-stream" )
        {
            if ( $this->config['upload_checking_mode'] != "strong" )
            {
                if ( ! empty( $userfile['type'] ) )
                {
                    $mime = preg_replace( "/^([\.-\w]+)\/([\.-\w]+)(.*)$/i", '$1/$2', trim( $userfile['type'] ) );
                }
            }
        }

        if ( empty( $mime ) or $mime == "application/octet-stream" )
        {
            if ( $this->config['upload_checking_mode'] != "strong" and $this->config['upload_checking_mode'] != "mild" )
            {
                $mime = $this->config['allowed_files'][$this->file_extension][0];
            }
        }

        if ( ! empty( $mime ) and ! in_array( $mime, $this->config['allowed_files'][$this->file_extension] ) )
        {
            $mime = "";
        }

        return $mime;
    }

    /**
     * upload::verify_image()
     * 
     * @param mixed $file
     * @return
     */
    private function verify_image( $file )
    {
        $file = preg_replace( '/\0/uis', '', $file );
        $txt = file_get_contents( $file );
        if ( $txt === false ) return false;

        if ( preg_match( "#&\#x([0-9a-f]+);#i", $txt ) ) return false;
        elseif ( preg_match( '#&\#([0-9]+);#i', $txt ) ) return false;
        elseif ( preg_match( "#([a-z]*)=([\`\'\"]*)script:#iU", $txt ) ) return false;
        elseif ( preg_match( "#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt ) ) return false;
        elseif ( preg_match( "#([a-z]*)=([\'\"]*)vbscript:#iU", $txt ) ) return false;
        elseif ( preg_match( "#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt ) ) return false;
        elseif ( preg_match( "#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt ) ) return false;
        elseif ( preg_match( "#</*(applet|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt ) ) return false;
        elseif ( preg_match( "#<\?php(.*)\?>#ms", $txt ) ) return false;
        return true;
    }

    /**
     * upload::check_tmpfile()
     * 
     * @param mixed $userfile
     * @return
     */
    private function check_tmpfile( $userfile )
    {
        if ( empty( $userfile ) ) return _ERROR_UPLOAD_FAILED . " (userfile is empty)";

        if ( ! isset( $userfile['name'] ) or empty( $userfile['name'] ) ) return _ERROR_UPLOAD_FAILED . " (userfile name is empty)";
        if ( ! isset( $userfile['size'] ) or empty( $userfile['size'] ) ) return _ERROR_UPLOAD_FAILED . " (userfile size is empty)";
        if ( ! empty( $this->config['maxsize'] ) and $userfile['size'] > $this->config['maxsize'] )
        {
            return sprintf( _ERROR_UPLOAD_MAX_USER_SIZE, $this->config['maxsize'] );
        }
        if ( ! isset( $userfile['tmp_name'] ) or empty( $userfile['tmp_name'] ) or ! file_exists( $userfile['tmp_name'] ) ) return _ERROR_UPLOAD_FAILED . " (userfile size is empty)";
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

        $extension = $this->getextension( $userfile['name'] );
        if ( empty( $extension ) or ! isset( $this->config['allowed_files'][$extension] ) )
        {
            return _ERROR_UPLOAD_TYPE_NOT_ALLOWED;
        }

        $this->file_extension = $extension;
        $this->file_mime = $this->get_mime_type( $userfile );
        if ( empty( $this->file_mime ) )
        {
            return _ERROR_UPLOAD_FAILED . " (userfile mimetype are not allowed)";
        }

        if ( preg_match( '#image\/[x\-]*([a-z]+)#', $this->file_mime ) or preg_match( "#application\/[x\-]*(shockwave\-flash)#", $this->file_mime ) )
        {
            $this->is_img = true;
            if ( empty( $this->img_info ) ) $this->img_info = @getimagesize( $userfile['tmp_name'] );

            if ( empty( $this->img_info ) or ! isset( $this->img_info[0] ) or empty( $this->img_info[0] ) or ! isset( $this->img_info[1] ) or empty( $this->img_info[1] ) ) return _ERROR_UPLOAD_NOT_IMAGE . " (imgInfo is empty)";

            if ( ! $this->verify_image( $userfile['tmp_name'] ) ) return _ERROR_UPLOAD_NOT_IMAGE . " (imgContent is failed)";

            if ( ! empty( $this->config['maxwidth'] ) and $this->img_info[0] > $this->config['maxwidth'] ) return sprintf( _ERROR_UPLOAD_IMAGE_WIDTH, $this->config['maxwidth'] );

            if ( ! empty( $this->config['maxheight'] ) and $this->img_info[1] > $this->config['maxheight'] ) return sprintf( _ERROR_UPLOAD_IMAGE_HEIGHT, $this->config['maxheight'] );
        }

        return "";
    }

    /**
     * upload::check_save_path()
     * 
     * @param mixed $savepath
     * @return
     */
    private function check_save_path( $savepath )
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
     * upload::string_to_filename()
     * 
     * @param mixed $word
     * @return
     */
    private function string_to_filename( $word )
    {
        $utf8_lookup = false;
        include ( NV_LOOKUP_FILE );
        $word = strtr( $word, $utf8_lookup['romanize'] );
        $word = preg_replace( '/[^a-z0-9\.\-\_ ]/i', '', $word );
        $word = preg_replace( '/^\W+|\W+$/', '', $word );
        $word = preg_replace( '/\s+/', '-', $word );
        return strtolower( preg_replace( '/\W-/', '', $word ) );
    }

    /**
     * upload::save_file()
     * 
     * @param mixed $userfile
     * @param mixed $savepath
     * @param bool $replace_if_exists
     * @return
     */
    public function save_file( $userfile, $savepath, $replace_if_exists = true )
    {
        $this->file_extension = '';
        $this->file_mime = '';
        $this->is_img = false;
        $this->img_info = array();

        $return = array();
        $return['error'] = $this->check_tmpfile( $userfile );
        if ( ! empty( $return['error'] ) )
        {
            return $return;
        }

        $savepath = str_replace( "\\", "/", realpath( $savepath ) );
        $return['error'] = $this->check_save_path( $savepath );
        if ( ! empty( $return['error'] ) )
        {
            return $return;
        }

        unset( $f );
        preg_match( "/^(.*)\.[a-zA-Z0-9]+$/", $userfile['name'], $f );
        $fn = $this->string_to_filename( $f[1] );
        $filename = $fn . "." . $this->file_extension;
        if ( ! preg_match( '/\/$/', $savepath ) ) $savepath = $savepath . "/";

        if ( empty( $replace_if_exists ) )
        {
            $filename2 = $filename;
            $i = 1;
            while ( file_exists( $savepath . $filename2 ) )
            {
                $filename2 = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $filename );
                $i++;
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

        if ( substr( PHP_OS, 0, 3 ) != 'WIN' )
        {
            $oldumask = umask( 0 );
            chmod( $savepath . $filename, 0777 );
            umask( $oldumask );
        }

        $return['name'] = $savepath . $filename;
        $return['basename'] = $filename;
        $return['ext'] = $this->file_extension;
        $return['mime'] = $this->file_mime;
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