<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 22/11/2010, 6:25
 */

if ( ! defined( "NV_CSSFILE" ) ) die();

/**
 * Czip
 * 
 * @package NUKEVIET 3.0
 * @author VINADES.,JSC
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class Czip
{
    private $cssFiles = array();
    private $cssdir;
    private $expire = 2592000; //30 ngay
    private $module;
    private $template;
    private $cacheDir;
    private $modCssDir;
    private $cssModFile = "";
    private $realPathCssModDir;
    private $cssModContent = "";
    private $lastmod = 0;
    private $files = "";
    private $encoding = "none";
    private $cachefile;
    private $currenttime;
    private $md5files;

    /**
     * Czip::__construct()
     * 
     * @param mixed $cssFiles
     * @param mixed $cssdir
     * @return
     */
    public function __construct( $cssFiles, $cssdir )
    {
        $this->cssdir = $cssdir;
        $cssFiles = ( array )$cssFiles;
        $this->cssFiles = $cssFiles;
        foreach ( $cssFiles as $file )
        {
            $filemtime = @filemtime( $this->cssdir . "/" . $file );
            if ( $filemtime )
            {
                $this->cssFiles[] = $this->cssdir . "/" . $file;
                $this->lastmod = max( $this->lastmod, $filemtime );
                $this->files .= $this->cssdir . "/" . $file;
            }
        }

        if ( empty( $this->cssFiles ) )
        {
            $this->browseInfo( 404 );
        }

        $this->module = ( isset( $_GET['mod'] ) and preg_match( "/^[a-z0-9\-]+$/", $_GET['mod'] ) ) ? $_GET['mod'] : "";
        $this->cacheDir = str_replace( '\\', '/', realpath( dirname( __file__ ) . '/../../cache' ) ) . '/';
        $this->currenttime = time();

        if ( ! empty( $this->module ) )
        {
            $this->template = ( isset( $_GET['templ'] ) and preg_match( "/^[a-z0-9\-]+$/", $_GET['templ'] ) ) ? $_GET['templ'] : "";
            $this->modCssDir = ! empty( $this->template ) ? "../../" . $this->template . "/css" : "";
            $this->realPathCssModDir = ! empty( $this->modCssDir ) ? realpath( $this->cssdir . "/" . $this->modCssDir ) : $this->cssdir;

            if ( file_exists( $this->realPathCssModDir . '/' . $this->module . '.css' ) )
            {
                $this->cssModFile = $this->realPathCssModDir . '/' . $this->module . '.css';
            } elseif ( file_exists( $this->realPathCssModDir . '/' . $this->module . '.css.php' ) )
            {
                $this->cssModFile = $this->realPathCssModDir . '/' . $this->module . '.css.php';
            }

            if ( ! empty( $this->cssModFile ) )
            {
                $filemtime = @filemtime( $this->cssModFile );
                $this->lastmod = max( $this->lastmod, $filemtime );
                $this->files .= $this->cssModFile;
            }
        }
    }

    /**
     * Czip::browseInfo()
     * 
     * @param mixed $num
     * @return
     */
    public function browseInfo( $num )
    {
        switch ( $num )
        {
            case 304:
                $info = "HTTP/1.1 304 Not Modified";
                break;

            case 403:
                $info = "HTTP/1.1 403 Forbidden";
                break;

            default:
                $info = "HTTP/1.1 404 Not Found";

        }
        header( $info );
        header( 'Content-Length: 0' );
        exit();
    }

    /**
     * Czip::is_notModified()
     * 
     * @param mixed $hash
     * @return
     */
    private function is_notModified( $hash )
    {
        return ( isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) && stripslashes( $_SERVER['HTTP_IF_NONE_MATCH'] ) == '"' . $hash . '"' );
    }

    /**
     * Czip::check_encode()
     * 
     * @return
     */
    private function check_encode()
    {
        $encoding = strstr( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) ? 'gzip' : ( strstr( $_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate' ) ? 'deflate' : 'none' );

        if ( $encoding != 'none' )
        {
            unset( $matches );
            if ( ! strstr( $_SERVER['HTTP_USER_AGENT'], 'Opera' ) && preg_match( '/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches ) )
            {
                $version = floatval( $matches[1] );
                if ( $version < 6 || ( $version == 6 && ! strstr( $_SERVER['HTTP_USER_AGENT'], 'EV1' ) ) ) $encoding = 'none';
            }
        }

        return $encoding;
    }

    /**
     * Czip::outputContent()
     * 
     * @return
     */
    private function outputContent()
    {
        header( "Content-Type: text/css; charset=utf-8" );
        header( 'Cache-Control: public; max-age=' . $this->expire );
        header( 'Last-Modified: ' . gmdate( "D, d M Y H:i:s", $this->lastmod ) . " GMT" );
        header( "expires: " . gmdate( "D, d M Y H:i:s", $this->currenttime + $this->expire ) . " GMT" );
    }

    /**
     * Czip::loadCacheData()
     * 
     * @return
     */
    private function loadCacheData()
    {
        $data = false;
        if ( file_exists( $this->cacheDir . $this->cachefile ) )
        {
            if ( $fp = fopen( $this->cacheDir . $this->cachefile, 'rb' ) )
            {
                if ( $this->encoding != 'none' )
                {
                    header( "Content-Encoding: " . $this->encoding );
                }
                $this->outputContent();
                $data = fpassthru( $fp );
                fclose( $fp );
                exit();
            }
        }

        if ( ! $data )
        {
            $fs = glob( $this->cacheDir . 'css_' . $this->md5files . '.*.' . $this->encoding . '.cache' );
            if ( ! empty( $fs ) )
            {
                foreach ( $fs as $f )
                {
                    if ( preg_match( "/css\_" . $this->md5files . "\.([\d]+)\." . $this->encoding . ".cache$/", $f ) )
                    {
                        @unlink( $f );
                    }
                }
            }
            return false;
        }
    }

    /**
     * Czip::callback()
     * 
     * @param mixed $matches
     * @return
     */
    private function callback( $matches )
    {
        $m2 = str_replace( '\\', '/', realpath( $this->realPathCssModDir . "/" . $matches[2] ) );
        $dir = str_replace( '\\', '/', $this->cssdir );
        $m2 = explode( "/", $m2 );
        $dir = explode( "/", $dir );
        $relative = array();
        foreach ( $dir as $index => $part )
        {
            if ( isset( $m2[$index] ) && $m2[$index] == $part )
            {
                continue;
            }

            $relative[] = '..';
        }

        foreach ( $m2 as $index => $part )
        {
            if ( isset( $dir[$index] ) && $dir[$index] == $part )
            {
                continue;
            }

            $relative[] = $part;
        }

        $relative = implode( "/", $relative );

        return $matches[1] . $relative . $matches[4];
    }

    /**
     * Czip::compress_css()
     * 
     * @param mixed $cssContent
     * @return
     */
    private function compress_css( $cssContent )
    {
        $cssContent = preg_replace( '/(\/\*.*?\*\/|^ | $)/is', '', $cssContent );
        $cssContent = preg_replace( '/[\s\t\r\n]+/', ' ', $cssContent );
        $cssContent = preg_replace( '/[\s]*(\:|\,|\;|\{|\})[\s]*/', "$1", $cssContent );
        $cssContent = preg_replace( "/[\#]+/", "#", $cssContent );
        $cssContent = str_replace( array( ' 0px', ':0px', ';}', ':0 0 0 0', ':0.', ' 0.' ), array( ' 0', ':0', '}', ':0', ':.', ' .' ), $cssContent );
        $cssContent = preg_replace( "/[^\}]+\{[\s|\;]*\}[\s]*/", "", $cssContent );
        $cssContent = preg_replace( "/[\s]+/", " ", $cssContent );
        return $cssContent;
    }

    /**
     * Czip::loadData()
     * 
     * @return
     */
    private function loadData()
    {
        ob_start();
        foreach ( $this->cssFiles as $file )
        {
            include ( $file );
        }
        $data = ob_get_contents();
        ob_end_clean();
        $data = preg_replace( "/url[\s]*\([\s]*\'(.*)?\'[\s]*\)/", "url($1)", $data );

        ob_start();
        include ( $this->cssModFile );
        $data2 = ob_get_contents();
        ob_end_clean();

        $data2 = preg_replace( "/url[\s]*\([\s]*\'(.*)?\'[\s]*\)/", "url($1)", $data2 ); //xoa cac dau ngoac don

        if ( $this->realPathCssModDir != $this->cssdir )
        {
            $data2 = preg_replace_callback( "/(url\()((?!http(s?)|ftp\:\/\/)[^\)]+)(\))/", "Czip::callback", $data2 );
        }

        $data .= $data2;

        $data = $this->compress_css( $data );

        if ( $this->encoding != 'none' )
        {
            $data = gzencode( $data, 6, $this->encoding == 'gzip' ? FORCE_GZIP : FORCE_DEFLATE );
            header( "Content-Encoding: " . $this->encoding );
        }

        if ( $fp = fopen( $this->cacheDir . $this->cachefile, 'wb' ) )
        {
            fwrite( $fp, $data );
            fclose( $fp );
        }

        $this->outputContent();
        echo $data;
        exit();
    }

    /**
     * Czip::loadFile()
     * 
     * @return
     */
    public function loadFile()
    {
        $this->md5files = md5( $this->files );
        $hash = $this->lastmod . '-' . $this->md5files;
        header( "Etag: \"" . $hash . "\"" );

        if ( $this->is_notModified( $hash ) ) $this->browseInfo( 304 );

        $this->encoding = $this->check_encode();
        $this->cachefile = 'css_' . $this->md5files . '.' . $this->lastmod . '.' . $this->encoding . '.cache';
        if ( ! $this->loadCacheData() )
        {
            $this->loadData();
        }
    }
}

$Czip = new Czip( $cssFiles, $cssdir );
$Czip->loadFile();

?>