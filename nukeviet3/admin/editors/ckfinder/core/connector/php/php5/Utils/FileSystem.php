<?php

/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2010, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 */

/**
 * @package CKFinder
 * @subpackage Utils
 * @copyright CKSource - Frederico Knabben
 */

/**
 * @package CKFinder
 * @subpackage Utils
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_Utils_FileSystem
{

    /**
     * This function behaves similar to System.IO.Path.Combine in C#, the only diffrenece is that it also accepts null values and treat them as empty string
     *
     * @static
     * @access public
     * @param string $path1 first path
     * @param string $path2 scecond path
     * @return string
     */
    public static function combinePaths ( $path1, $path2 )
    {
        if ( is_null( $path1 ) )
        {
            $path1 = "";
        }
        if ( is_null( $path2 ) )
        {
            $path2 = "";
        }
        if ( ! strlen( $path2 ) )
        {
            if ( strlen( $path1 ) )
            {
                $_lastCharP1 = substr( $path1, - 1, 1 );
                if ( $_lastCharP1 != "/" && $_lastCharP1 != "\\" )
                {
                    $path1 .= DIRECTORY_SEPARATOR;
                }
            }
        }
        else
        {
            $_firstCharP2 = substr( $path2, 0, 1 );
            if ( strlen( $path1 ) )
            {
                if ( strpos( $path2, $path1 ) === 0 )
                {
                    return $path2;
                }
                $_lastCharP1 = substr( $path1, - 1, 1 );
                if ( $_lastCharP1 != "/" && $_lastCharP1 != "\\" && $_firstCharP2 != "/" && $_firstCharP2 != "\\" )
                {
                    $path1 .= DIRECTORY_SEPARATOR;
                }
            }
            else
            {
                return $path2;
            }
        }
        return $path1 . $path2;
    }

    /**
     * Check whether $fileName is a valid file name, return true on success
     *
     * @static
     * @access public
     * @param string $fileName
     * @return boolean
     */
    public static function checkFileName ( $fileName )
    {
        if ( is_null( $fileName ) || ! strlen( $fileName ) || substr( $fileName, - 1, 1 ) == "." || false !== strpos( $fileName, ".." ) )
        {
            return false;
        }
        
        if ( preg_match( CKFINDER_REGEX_INVALID_FILE, $fileName ) )
        {
            return false;
        }
        
        return true;
    }

    /**
     * Unlink file/folder
     *
     * @static
     * @access public
     * @param string $path
     * @return boolean
     */
    public static function unlink ( $path )
    {
        /*    make sure the path exists    */
        if ( ! file_exists( $path ) )
        {
            return false;
        }
        
        /*    If it is a file or link, just delete it    */
        if ( is_file( $path ) || is_link( $path ) )
        {
            return @unlink( $path );
        }
        
        /*    Scan the dir and recursively unlink    */
        $files = scandir( $path );
        if ( $files )
        {
            foreach ( $files as $filename )
            {
                if ( $filename == '.' || $filename == '..' )
                {
                    continue;
                }
                $file = str_replace( '//', '/', $path . '/' . $filename );
                CKFinder_Connector_Utils_FileSystem::unlink( $file );
            }
        }
        
        /*    Remove the parent dir    */
        if ( ! @rmdir( $path ) )
        {
            return false;
        }
        
        return true;
    }

    /**
     * Return file name without extension (without dot & last part after dot)
     *
     * @static
     * @access public
     * @param string $fileName
     * @return string
     */
    public static function getFileNameWithoutExtension ( $fileName )
    {
        $dotPos = strrpos( $fileName, '.' );
        if ( false === $dotPos )
        {
            return $fileName;
        }
        
        return substr( $fileName, 0, $dotPos );
    }

    /**
     * Get file extension (only last part - e.g. extension of file.foo.bar.jpg = jpg)
     *
     * @static
     * @access public
     * @param string $fileName
     * @return string
     */
    public static function getExtension ( $fileName )
    {
        $dotPos = strrpos( $fileName, '.' );
        if ( false === $dotPos )
        {
            return "";
        }
        
        return substr( $fileName, strrpos( $fileName, '.' ) + 1 );
    }

    /**
     * Read file, split it into small chunks and send it to the browser
     *
     * @static
     * @access public
     * @param string $filename
     * @return boolean
     */
    public static function readfileChunked ( $filename )
    {
        $chunksize = 1024 * 10; // how many bytes per chunk
        

        $handle = fopen( $filename, 'rb' );
        if ( $handle === false )
        {
            return false;
        }
        while ( ! feof( $handle ) )
        {
            echo fread( $handle, $chunksize );
            @ob_flush();
            flush();
            set_time_limit( 8 );
        }
        fclose( $handle );
        return true;
    }

    /**
     * Replace accented UTF-8 characters by unaccented ASCII-7 "equivalents".
     * The purpose of this function is to replace characters commonly found in Latin
     * alphabets with something more or less equivalent from the ASCII range. This can
     * be useful for converting a UTF-8 to something ready for a filename, for example.
     * Following the use of this function, you would probably also pass the string
     * through utf8_strip_non_ascii to clean out any other non-ASCII chars
     *
     * For a more complete implementation of transliteration, see the utf8_to_ascii package
     * available from the phputf8 project downloads:
     * http://prdownloads.sourceforge.net/phputf8
     *
     * @param string UTF-8 string
     * @param string UTF-8 with accented characters replaced by ASCII chars
     * @return string accented chars replaced with ascii equivalents
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see http://sourceforge.net/projects/phputf8/
     */
    public static function convertToAscii ( $text )
    {
        $text = html_entity_decode( $text );
        //thay thế chữ thuong
        $text = preg_replace( "/(å|ä|ā|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|ä|ą)/", 'a', $text );
        $text = str_replace( "/(ß|ḃ)/", "b", $text );
        $text = preg_replace( "/(ç|ć|č|ĉ|ċ|¢|©)/", 'c', $text );
        $text = str_replace( "đ|ď|ḋ|đ", 'd', $text );
        $text = preg_replace( "/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|ę|ë|ě|ė)/", 'e', $text );
        $text = preg_replace( "/(ḟ|ƒ)/", "f", $text );
        $text = str_replace( "ķ", "k", $text );
        $text = preg_replace( "/(ħ|ĥ)/", "h", $text );
        $text = preg_replace( "/(ì|í|î|ị|ỉ|ĩ|ï|î|ī|¡|į)/", 'i', $text );
        $text = str_replace( "ĵ", "j", $text );
        $text = str_replace( "ṁ", "m", $text );
        
        $text = preg_replace( "/(ö|ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|ö|ø|ō)/", 'o', $text );
        $text = str_replace( "ṗ", "p", $text );
        $text = preg_replace( "/(ġ|ģ|ğ|ĝ)/", "g", $text );
        $text = preg_replace( "/(ü|ù|ú|ū|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|ü|ų|ů)/", 'u', $text );
        $text = preg_replace( "/(ỳ|ý|ỵ|ỷ|ỹ|ÿ)/", 'y', $text );
        $text = preg_replace( "/(ń|ñ|ň|ņ)/", 'n', $text );
        $text = preg_replace( "/(ŝ|š|ś|ṡ|ș|ş|³)/", 's', $text );
        $text = preg_replace( "/(ř|ŗ|ŕ)/", "r", $text );
        $text = preg_replace( "/(ṫ|ť|ț|ŧ|ţ)/", 't', $text );
        
        $text = preg_replace( "/(ź|ż|ž)/", 'z', $text );
        $text = preg_replace( "/(ł|ĺ|ļ|ľ)/", "l", $text );
        
        $text = preg_replace( "/(ẃ|ẅ)/", "w", $text );
        
        $text = str_replace( "æ", "ae", $text );
        $text = str_replace( "þ", "th", $text );
        $text = str_replace( "ð", "dh", $text );
        $text = str_replace( "£", "pound", $text );
        $text = str_replace( "¥", "yen", $text );
        
        $text = str_replace( "ª", "2", $text );
        $text = str_replace( "º", "0", $text );
        $text = str_replace( "¿", "?", $text );
        
        $text = str_replace( "µ", "mu", $text );
        $text = str_replace( "®", "r", $text );
        
        //thay thế chữ hoa
        $text = preg_replace( "/(Ä|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|Ą|Å|Ā)/", 'A', $text );
        $text = preg_replace( "/(Ḃ|B)/", 'B', $text );
        $text = preg_replace( "/(Ç|Ć|Ċ|Ĉ|Č)/", 'C', $text );
        $text = preg_replace( "/(Đ|Ď|Ḋ)/", 'D', $text );
        $text = preg_replace( "/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|Ę|Ë|Ě|Ė|Ē)/", 'E', $text );
        $text = str_replace( "Ḟ|Ƒ", "F", $text );
        $text = preg_replace( "/(Ì|Í|Ị|Ỉ|Ĩ|Ï|Į)/", 'I', $text );
        $text = str_replace( "Ĵ|J", "J", $text );
        
        $text = preg_replace( "/(Ö|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|Ø)/", 'O', $text );
        $text = preg_replace( "/(Ü|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|Ū|Ų|Ů)/", 'U', $text );
        $text = preg_replace( "/(Ỳ|Ý|Ỵ|Ỷ|Ỹ|Ÿ)/", 'Y', $text );
        $text = str_replace( "Ł", "L", $text );
        $text = str_replace( "Þ", "Th", $text );
        $text = str_replace( "Ṁ", "M", $text );
        
        $text = preg_replace( "/Ń|Ñ|Ň|Ņ/", "N", $text );
        $text = str_replace( "Ś|Š|Ŝ|Ṡ|Ș|Ş", "S", $text );
        $text = str_replace( "Æ", "AE", $text );
        $text = preg_replace( "/(Ź|Ż|Ž)/", 'Z', $text );
        
        $text = preg_replace( "/(Ř|R|Ŗ)/", 'R', $text );
        $text = preg_replace( "/(Ț|Ţ|T|Ť)/", 'T', $text );
        $text = preg_replace( "/(Ķ|K)/", 'K', $text );
        $text = preg_replace( "/(Ĺ|Ł|Ļ|Ľ)/", 'L', $text );
        
        $text = preg_replace( "/(Ħ|Ĥ)/", 'H', $text );
        $text = preg_replace( "/(Ṗ|P)/", 'P', $text );
        $text = preg_replace( "/(Ẁ|Ŵ|Ẃ|Ẅ)/", 'W', $text );
        $text = preg_replace( "/(Ģ|G|Ğ|Ĝ|Ġ)/", 'G', $text );
        $text = preg_replace( "/(Ŧ|Ṫ)/", 'T', $text );
        
        $text = preg_replace( '/^\W+|\W+$/', '', $text );
        $text = preg_replace( '/\s+/', '-', $text );
        $text = preg_replace( '/\W-/', '', $text );
        return $text;
    }

    /**
     * Convert file name from UTF-8 to system encoding
     *
     * @static
     * @access public
     * @param string $fileName
     * @return string
     */
    public static function convertToFilesystemEncoding ( $fileName )
    {
        $_config = & CKFinder_Connector_Core_Factory::getInstance( "Core_Config" );
        $encoding = $_config->getFilesystemEncoding();
        if ( is_null( $encoding ) || strcasecmp( $encoding, "UTF-8" ) == 0 || strcasecmp( $encoding, "UTF8" ) == 0 )
        {
            return $fileName;
        }
        
        if ( ! function_exists( "iconv" ) )
        {
            if ( strcasecmp( $encoding, "ISO-8859-1" ) == 0 || strcasecmp( $encoding, "ISO8859-1" ) == 0 || strcasecmp( $encoding, "Latin1" ) == 0 )
            {
                return str_replace( "\0", "_", utf8_decode( $fileName ) );
            }
            else if ( function_exists( 'mb_convert_encoding' ) )
            {
                /**
                 * @todo check whether charset is supported - mb_list_encodings
                 */
                $encoded = @mb_convert_encoding( $fileName, $encoding, 'UTF-8' );
                if ( @mb_strlen( $fileName, "UTF-8" ) != @mb_strlen( $encoded, $encoding ) )
                {
                    return str_replace( "\0", "_", preg_replace( "/[^[:ascii:]]/u", "_", $fileName ) );
                }
                else
                {
                    return str_replace( "\0", "_", $encoded );
                }
            }
            else
            {
                return str_replace( "\0", "_", preg_replace( "/[^[:ascii:]]/u", "_", $fileName ) );
            }
        }
        
        $converted = @iconv( "UTF-8", $encoding . "//IGNORE//TRANSLIT", $fileName );
        if ( $converted === false )
        {
            return str_replace( "\0", "_", preg_replace( "/[^[:ascii:]]/u", "_", $fileName ) );
        }
        
        return $converted;
    }

    /**
     * Convert file name from system encoding into UTF-8
     *
     * @static
     * @access public
     * @param string $fileName
     * @return string
     */
    public static function convertToConnectorEncoding ( $fileName )
    {
        $_config = & CKFinder_Connector_Core_Factory::getInstance( "Core_Config" );
        $encoding = $_config->getFilesystemEncoding();
        if ( is_null( $encoding ) || strcasecmp( $encoding, "UTF-8" ) == 0 || strcasecmp( $encoding, "UTF8" ) == 0 )
        {
            return $fileName;
        }
        
        if ( ! function_exists( "iconv" ) )
        {
            if ( strcasecmp( $encoding, "ISO-8859-1" ) == 0 || strcasecmp( $encoding, "ISO8859-1" ) == 0 || strcasecmp( $encoding, "Latin1" ) == 0 )
            {
                return utf8_encode( $fileName );
            }
            else
            {
                return $fileName;
            }
        }
        
        $converted = @iconv( $encoding, "UTF-8", $fileName );
        
        if ( $converted === false )
        {
            return $fileName;
        }
        
        return $converted;
    }

    /**
     * Find document root
     *
     * @return string
     * @access public
     */
    public function getDocumentRootPath ( )
    {
        /**
         * The absolute pathname of the currently executing script.
         * Notatka: If a script is executed with the CLI, as a relative path, such as file.php or ../file.php,
         * $_SERVER['SCRIPT_FILENAME'] will contain the relative path specified by the user.
         */
        if ( isset( $_SERVER['SCRIPT_FILENAME'] ) )
        {
            $sRealPath = dirname( $_SERVER['SCRIPT_FILENAME'] );
        }
        else
        {
            /**
             * realpath — Returns canonicalized absolute pathname
             */
            $sRealPath = realpath( './' );
        }
        
        /**
         * The filename of the currently executing script, relative to the document root.
         * For instance, $_SERVER['PHP_SELF'] in a script at the address http://example.com/test.php/foo.bar
         * would be /test.php/foo.bar.
         */
        $sSelfPath = dirname( $_SERVER['PHP_SELF'] );
        
        return substr( $sRealPath, 0, strlen( $sRealPath ) - strlen( $sSelfPath ) );
    }

    /**
     * Create directory recursively
     *
     * @access public
     * @static
     * @param string $dir
     * @return boolean
     */
    public static function createDirectoryRecursively ( $dir )
    {
        if ( DIRECTORY_SEPARATOR === "\\" )
        {
            $dir = str_replace( "/", "\\", $dir );
        }
        else if ( DIRECTORY_SEPARATOR === "/" )
        {
            $dir = str_replace( "\\", "/", $dir );
        }
        
        $_config = & CKFinder_Connector_Core_Factory::getInstance( "Core_Config" );
        if ( $perms = $_config->getChmodFolders() )
        {
            $oldUmask = umask( 0 );
            $bCreated = @mkdir( $dir, $perms, true );
            umask( $oldUmask );
        }
        else
        {
            $bCreated = @mkdir( $dir, 0777, true );
        }
        
        return $bCreated;
    }

    /**
     * Detect HTML in the first KB to prevent against potential security issue with
     * IE/Safari/Opera file type auto detection bug.
     * Returns true if file contain insecure HTML code at the beginning.
     *
     * @static
     * @access public
     * @param string $filePath absolute path to file
     * @return boolean
     */
    public static function detectHtml ( $filePath )
    {
        $fp = @fopen( $filePath, 'rb' );
        if ( $fp === false || ! flock( $fp, LOCK_SH ) )
        {
            return - 1;
        }
        $chunk = fread( $fp, 1024 );
        flock( $fp, LOCK_UN );
        fclose( $fp );
        
        $chunk = strtolower( $chunk );
        
        if ( ! $chunk )
        {
            return false;
        }
        
        $chunk = trim( $chunk );
        
        if ( preg_match( "/<!DOCTYPE\W*X?HTML/sim", $chunk ) )
        {
            return true;
        }
        
        $tags = array( 
            '<body', '<head', '<html', '<img', '<pre', '<script', '<table', '<title' 
        );
        
        foreach ( $tags as $tag )
        {
            if ( false !== strpos( $chunk, $tag ) )
            {
                return true;
            }
        }
        
        //type = javascript
        if ( preg_match( '!type\s*=\s*[\'"]?\s*(?:\w*/)?(?:ecma|java)!sim', $chunk ) )
        {
            return true;
        }
        
        //href = javascript
        //src = javascript
        //data = javascript
        if ( preg_match( '!(?:href|src|data)\s*=\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) )
        {
            return true;
        }
        
        //url(javascript
        if ( preg_match( '!url\s*\(\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) )
        {
            return true;
        }
        
        return false;
    }

    /**
     * Check file content.
     * Currently this function validates only image files.
     * Returns false if file is invalid.
     *
     * @static
     * @access public
     * @param string $filePath absolute path to file
     * @param string $extension file extension
     * @param integer $detectionLevel 0 = none, 1 = use getimagesize for images, 2 = use DetectHtml for images
     * @return boolean
     */
    public static function isImageValid ( $filePath, $extension )
    {
        if ( ! @is_readable( $filePath ) )
        {
            return - 1;
        }
        
        $imageCheckExtensions = array( 
            'gif', 'jpeg', 'jpg', 'png', 'psd', 'bmp', 'tiff' 
        );
        
        // version_compare is available since PHP4 >= 4.0.7
        if ( function_exists( 'version_compare' ) )
        {
            $sCurrentVersion = phpversion();
            if ( version_compare( $sCurrentVersion, "4.2.0" ) >= 0 )
            {
                $imageCheckExtensions[] = "tiff";
                $imageCheckExtensions[] = "tif";
            }
            if ( version_compare( $sCurrentVersion, "4.3.0" ) >= 0 )
            {
                $imageCheckExtensions[] = "swc";
            }
            if ( version_compare( $sCurrentVersion, "4.3.2" ) >= 0 )
            {
                $imageCheckExtensions[] = "jpc";
                $imageCheckExtensions[] = "jp2";
                $imageCheckExtensions[] = "jpx";
                $imageCheckExtensions[] = "jb2";
                $imageCheckExtensions[] = "xbm";
                $imageCheckExtensions[] = "wbmp";
            }
        }
        
        if ( ! in_array( $extension, $imageCheckExtensions ) )
        {
            return true;
        }
        
        if ( @getimagesize( $filePath ) === false )
        {
            return false;
        }
        
        return true;
    }

    /**
     * Returns true if directory is not empty
     *
     * @access public
     * @static
     * @param string $serverPath
     * @return boolean
     */
    public static function hasChildren ( $serverPath )
    {
        if ( ! is_dir( $serverPath ) || ( false === $fh = @opendir( $serverPath ) ) )
        {
            return false;
        }
        
        $hasChildren = false;
        while ( false !== ( $filename = readdir( $fh ) ) )
        {
            if ( $filename == '.' || $filename == '..' )
            {
                continue;
            }
            else if ( is_dir( $serverPath . DIRECTORY_SEPARATOR . $filename ) )
            {
                //we have found valid directory
                $hasChildren = true;
                break;
            }
        }
        
        closedir( $fh );
        
        return $hasChildren;
    }
}
