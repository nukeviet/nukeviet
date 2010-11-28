<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 23/11/2010, 20:46
 */

/**
 * CJzip
 * 
 * @package   
 * @author NUKEVIET 3.0
 * @copyright VINADES.,JSC
 * @version 2010
 * @access public
 */
class CJzip
{
    private $getName = "file";
    private $file = array();
    private $maxAge = 2592000;

    private $cacheDir;
    private $encoding = 'none';
    private $currenttime;
    private $cachefile;
    private $siteRoot;
    private $base_siteurl;
    private $isOptimized = false;

    /**
     * CJzip::__construct()
     * 
     * @return
     */
    public function __construct()
    {
        if ( ! isset( $_GET[$this->getName] ) )
        {
            $this->browseInfo( 404 );
        }

        $this->siteRoot = str_replace( '\\', '/', realpath( dirname( __file__ ) ) );
        $base_siteurl = pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME );
        if ( $base_siteurl == '\\' or $base_siteurl == '/' ) $base_siteurl = '';
        if ( ! empty( $base_siteurl ) ) $base_siteurl = str_replace( '\\', '/', $base_siteurl );
        if ( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "/[\/]+$/", '', $base_siteurl );
        if ( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "/^[\/]*(.*)$/", '/\\1', $base_siteurl );
        $this->base_siteurl = $base_siteurl . '/';
        $this->cacheDir = $this->siteRoot . '/cache/';

        $filename = $_GET[$this->getName];
        if ( preg_match( "/^\//", $filename ) ) $filename = preg_replace( "#^" . $this->base_siteurl . "#", "", $filename );

        $this->file['path'] = $this->siteRoot . '/' . $filename;
        $this->file['lastmod'] = @filemtime( $this->file['path'] );
        if ( ! $this->file['lastmod'] )
        {
            $this->browseInfo( 404 );
        }

        unset( $matches );
        preg_match( "/(.*?)\.(css|js)$/", $this->file['path'], $matches );
        if ( ! $matches )
        {
            $this->browseInfo( 403 );
        }

        $this->file['ext'] = $matches[2];
        $this->file['contenttype'] = ( $this->file['ext'] == "css" ) ? "css" : "javascript";
        if ( preg_match( "/\.opt$/", $matches[1] ) )
        {
            $this->isOptimized = true;
        }

        $this->file['md5file'] = md5( $this->file['path'] );
        $this->currenttime = time();
    }

    /**
     * CJzip::browseInfo()
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
     * CJzip::is_notModified()
     * 
     * @param mixed $hash
     * @return
     */
    private function is_notModified( $hash )
    {
        return ( isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) && stripslashes( $_SERVER['HTTP_IF_NONE_MATCH'] ) == '"' . $hash . '"' );
    }

    /**
     * CJzip::check_encode()
     * 
     * @return
     */
    private function check_encode()
    {
        if ( ! function_exists( 'gzencode' ) ) return 'none';

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
     * CJzip::outputContent()
     * 
     * @return
     */
    private function outputContent()
    {
        header( "Content-Type: text/" . $this->file['contenttype'] . "; charset=utf-8" );
        header( 'Cache-Control: public; max-age=' . $this->maxAge );
        header( 'Last-Modified: ' . gmdate( "D, d M Y H:i:s", $this->file['lastmod'] ) . " GMT" );
        header( "expires: " . gmdate( "D, d M Y H:i:s", $this->currenttime + $this->maxAge ) . " GMT" );
    }

    /**
     * CJzip::loadCacheData()
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
                    header( 'Vary: Accept-Encoding' );
                }
                $this->outputContent();
                $data = fpassthru( $fp );
                fclose( $fp );
                exit();
            }
        }

        if ( ! $data )
        {
            $fs = glob( $this->cacheDir . $this->file['contenttype'] . '_' . $this->file['md5file'] . '.*.' . $this->encoding . '.cache' );
            if ( ! empty( $fs ) )
            {
                foreach ( $fs as $f )
                {
                    if ( preg_match( "/" . $this->file['contenttype'] . "\_" . $this->file['md5file'] . "\.([\d]+)\." . $this->encoding . ".cache$/", $f ) )
                    {
                        @unlink( $f );
                    }
                }
            }
            return false;
        }
    }

    /**
     * CJzip::loadData()
     * 
     * @return
     */
    private function loadData()
    {
        $data = file_get_contents( $this->file['path'] );

        if ( ! $this->isOptimized )
        {
            $data = ( $this->file['contenttype'] == 'css' ) ? $this->compress_css( $data ) : $this->compress_javascript( $data );
        }

        if ( $this->encoding != 'none' )
        {
            $data = gzencode( $data, 6, $this->encoding == 'gzip' ? FORCE_GZIP : FORCE_DEFLATE );
            header( "Content-Encoding: " . $this->encoding );
            header( 'Vary: Accept-Encoding' );
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
     * CJzip::commentCB()
     * 
     * @param mixed $m
     * @return
     */
    private function commentCB( $m )
    {
        $hasSurroundingWs = ( trim( $m[0] ) !== $m[1] );
        $m = $m[1];
        if ( $m === 'keep' )
        {
            return '/**/';
        }
        if ( $m === '" "' )
        {
            return '/*" "*/';
        }
        if ( preg_match( '@";\\}\\s*\\}/\\*\\s+@', $m ) )
        {
            return '/*";}}/* */';
        }
        if ( preg_match( '@^/\\s*(\\S[\\s\\S]+?)\\s*/\\*@x', $m, $n ) )
        {
            return "/*/" . $n[1] . "/**/";
        }
        if ( substr( $m, -1 ) === '\\' )
        {
            return '/*\\*/';
        }
        if ( $m !== '' && $m[0] === '/' )
        {
            return '/*/*/';
        }
        return $hasSurroundingWs ? ' ' : '';
    }

    /**
     * CJzip::selectorsCB()
     * 
     * @param mixed $m
     * @return
     */
    private function selectorsCB( $m )
    {
        return preg_replace( '/\\s*([,>+~])\\s*/', '$1', $m[0] );
    }

    /**
     * CJzip::fontFamilyCB()
     * 
     * @param mixed $m
     * @return
     */
    private function fontFamilyCB( $m )
    {
        $m[1] = preg_replace( '/\\s*("[^"]+"|\'[^\']+\'|[\\w\\-]+)\\s*/x', '$1', $m[1] );
        return 'font-family:' . $m[1] . $m[2];
    }

    /**
     * CJzip::compress_css()
     * 
     * @param mixed $cssContent
     * @return
     */
    private function compress_css( $cssContent )
    {
        $cssContent = preg_replace( "/url[\s]*\([\s]*[\'|\"](.*)?[\'|\"][\s]*\)/", "url($1)", $cssContent );

        $cssContent = preg_replace( '@>/\\*\\s*\\*/@', '>/*keep*/', $cssContent );
        $cssContent = preg_replace( '@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $cssContent );
        $cssContent = preg_replace( '@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $cssContent );
        $cssContent = preg_replace_callback( '@\\s*/\\*([\\s\\S]*?)\\*/\\s*@', array( $this, 'commentCB' ), $cssContent );

        $cssContent = preg_replace( '/[\s\t\r\n]+/', ' ', $cssContent );
        $cssContent = preg_replace( '/[\s]*(\:|\,|\;|\{|\})[\s]*/', "$1", $cssContent );
        $cssContent = preg_replace( "/[\#]+/", "#", $cssContent );
        $cssContent = str_replace( array( ' 0px', ':0px', ';}', ':0 0 0 0', ':0.', ' 0.' ), array( ' 0', ':0', '}', ':0', ':.', ' .' ), $cssContent );
        $cssContent = preg_replace( '/\\s*([{;])\\s*([\\*_]?[\\w\\-]+)\\s*:\\s*(\\b|[#\'"-])/x', '$1$2:$3', $cssContent );

        $cssContent = preg_replace_callback( '/(?:\\s*[^~>+,\\s]+\\s*[,>+~])+\\s*[^~>+,\\s]+{/x', array( $this, 'selectorsCB' ), $cssContent );
        $cssContent = preg_replace( '/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i', '$1#$2$3$4$5', $cssContent );
        $cssContent = preg_replace_callback( '/font-family:([^;}]+)([;}])/', array( $this, 'fontFamilyCB' ), $cssContent );
        $cssContent = preg_replace( '/@import\\s+url/', '@import url', $cssContent );
        $cssContent = preg_replace( '/:first-l(etter|ine)\\{/', ':first-l$1 {', $cssContent );
        $cssContent = preg_replace( "/[^\}]+\{[\s|\;]*\}[\s]*/", "", $cssContent );
        $cssContent = preg_replace( "/[\s]+/", " ", $cssContent );
        $cssContent = trim( $cssContent );
        return $cssContent;
    }

    /**
     * CJzip::compress_javascript()
     * 
     * @param mixed $jsContent
     * @return
     */
    private function compress_javascript( $jsContent )
    {
        $jsContent = preg_replace( "/(\r\n)+|(\n|\r)+/", "\r\n", $jsContent );
        return $jsContent;
    }

    /**
     * CJzip::loadFile()
     * 
     * @return
     */
    public function loadFile()
    {
        $hash = $this->file['lastmod'] . '-' . $this->file['md5file'];
        header( "Etag: \"" . $hash . "\"" );

        if ( $this->is_notModified( $hash ) ) $this->browseInfo( 304 );

        $this->encoding = $this->check_encode();
        $this->cachefile = $this->file['contenttype'] . '_' . $this->file['md5file'] . '.' . $this->file['lastmod'] . '.' . $this->encoding . '.cache';
        if ( ! $this->loadCacheData() )
        {
            $this->loadData();
        }
    }
}

$CJzip = new CJzip;
$CJzip->loadFile();

?>