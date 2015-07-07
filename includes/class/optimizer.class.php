<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 27/11/2010, 22:45
 */

class optimizer
{
	private $_content;
	private $_conditon = array();
	private $_conditonCss = array();
	private $_conditonJs = array();
	private $_condCount = 0;
	private $_meta = array();
	private $_title = '<title></title>';
	private $_style = array();
	private $_links = array();
	private $_cssLinks = array();
	private $_cssIgnoreLinks = array();
	private $_jsLinks = array();
	private $_jsInline = array();
	private $_jsMatches = array();
	private $_jsCount = 0;
	private $siteRoot;
	private $base_siteurl;
	private $cssDir = 'files/css';
	private $eol = "\r\n";
	private $cssImgNewPath;
	private $_tidySupport = false;
	private $opt_css_file = false;
	private $_cssImportCharset = null;
	private $tidy_options = array( //
		'doctype' => 'transitional', // Chuan HTML: omit, auto, strict, transitional, user
		'input-encoding' => 'utf8', // Bang ma nguon
		'output-encoding' => 'utf8', //Bang ma dich
		'output-xhtml' => true, // Chuan xhtml
		'drop-empty-paras' => true, // Xoa cac tags p rong
		'drop-proprietary-attributes' => false, // Xoa tat ca nhung attributes dac thu cua microsoft (vi du: tu word)
		'word-2000' => true, //Xoa tat ca nhung ma cua word khong phu hop voi chuan html
		'enclose-block-text' => true, // Tat ca cac block-text duoc dong bang tag p
		'enclose-text' => true, // Tat ca cac text nam trong khu vuc body nhung khong nam trong bat ky mot tag nao khac se duoc cho vao <p>text</p>
		'hide-comments' => false, // Xoa cac chu thich
		'hide-endtags' => true, // Xoa tat ca ve^' dong khong cua nhung tag khong doi hoi phai dong
		'indent' => false, // Thut dau dong
		'indent-spaces' => 4, //1 don vi indent = 4 dau cach
		'logical-emphasis' => true, // Thay cac tag i va b bang em va strong
		'lower-literals' => true, // Tat ca cac html-tags duoc bien thanh dang chu thuong
		'markup' => true, // Sua cac loi Markup
		'preserve-entities' => true, // Giu nguyen cac chu da duoc ma hoa trong nguon
		'quote-ampersand' => true, // Thay & bang &amp;
		'quote-marks' => true, // Thay cac dau ngoac bang ma html tuong ung
		'quote-nbsp' => true, // Thay dau cach bang to hop &nbsp;
		'show-warnings' => false, // Hien thi thong bao loi
		'wrap' => 0, // Moi dong khong qua 150 ky tu
		'alt-text' => true //Bat buoc phai co alt trong IMG
	);

	/**
	 * optimizer::__construct()
	 *
	 * @param mixed $content
	 * @return
	 */
	public function __construct( $content, $opt_css_file )
	{
		$this->_content = $content;
		$this->opt_css_file = ( $opt_css_file ) ? true : false;
		$this->siteRoot = preg_replace( "/[\/]+$/", '', str_replace( '\\', '/', realpath( dirname( __file__ ) . '/../../' ) ) );
		$base_siteurl = pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME );
		if( $base_siteurl == '\\' or $base_siteurl == '/' ) $base_siteurl = '';
		if( ! empty( $base_siteurl ) ) $base_siteurl = str_replace( '\\', '/', $base_siteurl );
		if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "/[\/]+$/", '', $base_siteurl );
		if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "/^[\/]*(.*)$/", '/\\1', $base_siteurl );
		if( defined( 'NV_IS_UPDATE' ) ) // Update se bao gom ca admin nen update phai dat truoc
		{
			$base_siteurl = preg_replace( '#/install(.*)$#', '', $base_siteurl );
		}
		elseif( defined( 'NV_ADMIN' ) )
		{
			$base_siteurl = preg_replace( '#/' . NV_ADMINDIR . '(.*)$#', '', $base_siteurl );
		}
		elseif( ! empty( $base_siteurl ) )
		{
			$base_siteurl = preg_replace( '#/index\.php(.*)$#', '', $base_siteurl );
		}

		$this->base_siteurl = $base_siteurl . '/';

		if( defined( 'SYSTEM_FILES_DIR' ) )
		{
			$this->cssDir = SYSTEM_FILES_DIR . '/css';
		}

		if( class_exists( 'tidy', false ) )
		{
			$this->_tidySupport = true;
		}
	}

	/**
	 * optimizer::process()
	 *
	 * @return
	 */
	public function process()
    {
        $conditionRegex = "/<\!--\[if([^\]]+)\].*?\[endif\]-->/is";
        if ( preg_match_all( $conditionRegex, $this->_content, $conditonMatches ) )
        {
            $this->_conditon = $conditonMatches[0];
            $this->_content = preg_replace_callback( $conditionRegex, array( $this, 'conditionCallback' ), $this->_content );
        }

        $this->_content = preg_replace( "/<script[^>]+src\s*=\s*[\"|']([^\"']+jquery.min.js)[\"|'][^>]*>[\s\r\n\t]*<\/script>/is", "", $this->_content );
        $jsRegex = "/<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>/is";
        if ( preg_match_all( $jsRegex, $this->_content, $jsMatches ) )
        {
            $this->_jsMatches = $jsMatches[0];
            $this->_content = preg_replace_callback( $jsRegex, array( $this, 'jsCallback' ), $this->_content );
        }

        $this->_meta['http-equiv'] = $this->_meta['name'] = $this->_meta['other'] = array();
        $this->_meta['charset'] = "";

        if ( $this->opt_css_file )
        {
            $regex = "!<meta[^>]+>|<title>[^<]+<\/title>|<link[^>]+>|<style[^>]*>[^\<]*</style>!is";
        }
        else
        {
            $regex = "!<meta[^>]+>|<title>[^<]+<\/title>|<style[^>]*>[^\<]*</style>!is";
        }

        if ( preg_match_all( $regex, $this->_content, $matches ) )
        {
            foreach ( $matches[0] as $tag )
            {
                if ( preg_match( '/^<meta/', $tag ) )
                {
                    preg_match_all( "/([a-zA-Z\-\_]+)\s*=\s*[\"|']([^\"']+)/is", $tag, $matches2 );
                    if ( ! empty( $matches2 ) )
                    {
                        $combine = array_combine( $matches2[1], $matches2[2] );
                        if ( array_key_exists( 'http-equiv', $combine ) )
                        {
                            $this->_meta['http-equiv'][strtolower( $combine['http-equiv'] )] = $combine['content'];
                        }
                        elseif ( array_key_exists( 'name', $combine ) )
                        {
                            $this->_meta['name'][strtolower( $combine['name'] )] = $combine['content'];
                        }
                        elseif ( array_key_exists( 'charset', $combine ) )
                        {
                            $this->_meta['charset'] = $combine['charset'];
                        }
                        else
                        {
                            $this->_meta['other'][] = array( $matches2[1], $matches2[2] );
                        }

                    }
                }
                elseif ( preg_match( "/^<title>[^<]+<\/title>/is", $tag ) )
                {
                    $this->_title = $tag;
                }
                elseif ( preg_match( "/^<style[^>]*>([^<]*)<\/style>/is", $tag, $matches2 ) )
                {
                    $this->_style[] = $matches2[1];
                }
                elseif ( preg_match( "/^<link/", $tag ) )
                {
                    preg_match_all( "/([a-zA-Z]+)\s*=\s*[\"|']([^\"']+)/is", $tag, $matches2 );
                    $combine = array_combine( $matches2[1], $matches2[2] );
                    if ( isset( $combine['rel'] ) and preg_match( "/stylesheet/is", $combine['rel'] ) )
                    {
                        if ( ! isset( $combine['title'] ) and isset( $combine['href'] ) and preg_match( "/^(?!http(s?)|ftp\:\/\/)(.*?)\.css$/", $combine['href'], $matches3 ) )
                        {
                            $media = isset( $combine['media'] ) ? $combine['media'] : "";
                            $this->_cssLinks[$matches3[0]] = $media;
                        }
                        else
                        {
                            $this->_cssIgnoreLinks[] = $tag;
                        }
                    }
                    else
                    {
                        $this->_links[] = $tag;
                    }
                }
            }

            $this->_content = preg_replace( $regex, '', $this->_content );
        }

        if ( ! empty( $this->_conditon ) )
        {
            foreach ( $this->_conditon as $key => $value )
            {
                $this->_content = preg_replace( "/\{\|condition\_" . $key . "\|\}/", $value, $this->_content );
            }
        }

        $meta = array();
        if ( ! empty( $this->_meta['name'] ) )
        {
            foreach ( $this->_meta['name'] as $value => $content )
            {
                $meta[] = "<meta name=\"" . $value . "\" content=\"" . $content . "\" />";
            }
        }

        if ( ! empty( $this->_meta['charset'] ) )
        {
            $meta[] = "<meta charset=\"" . $this->_meta['charset'] . "\" />";
        }
        if ( ! empty( $this->_meta['http-equiv'] ) )
        {
            foreach ( $this->_meta['http-equiv'] as $value => $content )
            {
                $meta[] = "<meta http-equiv=\"" . $value . "\" content=\"" . $content . "\" />";
            }
        }
        if ( ! empty( $this->_meta['other'] ) )
        {
            foreach ( $this->_meta['other'] as $row )
            {
                $meta[] = "<meta " . $row[0][0] . "=\"" . $row[1][0] . "\" " . $row[0][1] . "=\"" . $row[1][1] . "\" />";
            }
        }

        $_jsBefore_internal = $_jsBefore_external = $_jsAfter_internal = $_jsAfter_external = "";
        $_jsSrc = array();
        $_test_beforeAfter = "/<\s*\bscript\b[^>]+data\-show\s*\=\s*[\"|'](after|before)[\"|'][^>]*>(.*?)<\s*\/\s*script\s*>/is";

        $this->_content = $this->removeQuotes( $this->_content );

        if ( ! empty( $this->_jsMatches ) )
        {
            foreach ( $this->_jsMatches as $key => $value )
            {
                unset( $matches2, $matches3 );

                if ( preg_match( "/<\s*\bscript\b[^>]+src\s*=\s*[\"|']([^\"']+)[\"|'][^>]*>[\s\r\n\t]*<\s*\/\s*script\s*>/is", $value, $matches2 ) )
                {
                    //Chi cho phep ket noi 1 lan doi voi 1 file JS
                    $external = trim( $matches2[1] );
                    if ( ! empty( $external ) )
                    {
                        if ( ! in_array( $external, $_jsSrc ) )
                        {
                            $_jsSrc[] = $external;

                            if ( preg_match( $_test_beforeAfter, $value, $matches3 ) )
                            {
                                if ( $matches3[1] == "before" ) $_jsBefore_external .= $value . $this->eol;
                                elseif ( $matches3[1] == "after" ) $_jsAfter_external .= $value . $this->eol;
                                $value = '';
                            }
                        }
                        else
                        {
                            $value = '';
                        }
                    }
                    else
                    {
                        $value = '';
                    }
                }
                elseif ( preg_match( "/<\s*\bscript\b([^>]*)>(.*?)<\s*\/\s*script\s*>/is", $value, $matches2 ) )
                {
                    $internal = trim( $matches2[2] );
                    if ( ! empty( $internal ) and ( empty( $matches2[1] ) or ! preg_match( "/^([^\W]*)$/is", $matches2[1] ) ) )
                    {
                        if ( preg_match( $_test_beforeAfter, $value, $matches3 ) )
                        {
                            if ( $matches3[1] == "before" ) $_jsBefore_internal .= $internal . $this->eol;
                            elseif ( $matches3[1] == "after" ) $_jsAfter_internal .= $internal . $this->eol;
                            $value = '';
                        }
                        else
                        {
                            $value = $this->minifyJsInline( $matches2 );
                        }
                    }
                    else
                    {
                        $value = '';
                    }
                }
                else
                {
                    $value = '';
                }

                $this->_content = preg_replace( "/\{\|js\_" . $key . "\|\}/", $this->eol . $value, $this->_content );
            }
        }

        $head = "";
        if ( ! empty( $meta ) ) $head .= implode( $this->eol, $meta ) . $this->eol;
        if ( ! empty( $this->_links ) ) $head .= implode( $this->eol, $this->_links ) . $this->eol;
        if ( ! empty( $this->_cssLinks ) ) $head .= "<link rel=\"Stylesheet\" href=\"" . $this->newCssLink() . "\" type=\"text/css\" />" . $this->eol;
        if ( ! empty( $this->_cssIgnoreLinks ) ) $head .= implode( $this->eol, $this->_cssIgnoreLinks ) . $this->eol;
        if ( ! empty( $this->_style ) ) $head .= "<style type=\"text/css\">" . $this->minifyCss( implode( $this->eol, $this->_style ) ) . "</style>" . $this->eol;
        $head = $this->removeQuotes( $head );

        if ( ! empty( $_jsBefore_internal ) ) $_jsBefore_internal = "<script>" . $this->eol . $this->_minifyJsInline( $_jsBefore_internal ) . $this->eol . "</script>";
        if ( ! empty( $_jsAfter_internal ) ) $_jsAfter_internal = "<script>" . $this->eol . $this->_minifyJsInline( $_jsAfter_internal ) . $this->eol . "</script>";

        if ( preg_match( "/\<head\>/", $this->_content ) )
        {
            $head = "<head>" . $this->eol . $this->_title . $this->eol . $head;
            $head .= "<script src=\"" . $this->base_siteurl . "js/jquery/jquery.min.js\"></script>" . $this->eol;
            $this->_content = trim( preg_replace( '/<head>/i', $head, $this->_content, 1 ) );
            $_jsBefore = $_jsBefore_external . $_jsBefore_internal;
            if ( ! empty( $_jsBefore ) ) $this->_content = preg_replace( '/\s*<\/head>/', $this->eol . $_jsBefore . $this->eol . "</head>", $this->_content, 1 );
        }
        else
        {
            $this->_content = $head . $_jsBefore_external . $_jsBefore_internal . $this->_content;
        }

        if ( preg_match( "/\<\/body\>/", $this->_content ) )
        {
            $this->_content = preg_replace( '/\s*<\/body>/', $this->eol . $_jsAfter_external . $_jsAfter_internal . $this->eol . "</body>", $this->_content, 1 );
        }
        else
        {
            $this->_content = $this->_content . $this->eol . $_jsAfter_external . $_jsAfter_internal;
        }

        if ( $this->_tidySupport )
        {
            if ( strncasecmp( $this->_content, '<!DOCTYPE html>', 15 ) === 0 )
            {
                return $this->tidy5( $this->_content );
            }
            else
            {
                return tidy_repair_string( $this->_content, $this->tidy_options, 'utf8' );
            }
        }

        return $this->minifyHTML( $this->_content );
    }

	/**
	 * optimizer::tidy5()
	 *
	 * @param mixed $string
	 * @return
	 */
	private function tidy5( $string )
	{
		$menu = array();

		if( strpos( $string, '<menu' ) !== false )
		{
	 		$menu = array(
				'<menu' => '<menutidy',
				'</menu' => '</menutidy',
			);
			$string = str_replace( array_keys( $menu ), $menu, $string );
		}

		$this->tidy_options['doctype'] = 'omit';
		//$this->tidy_options['output-html'] = true;
		//$this->tidy_options['output-xhtml'] = false;
		$this->tidy_options['drop-proprietary-attributes'] = false;
		$this->tidy_options['new-blocklevel-tags'] = 'article aside audio details dialog figcaption figure footer header hgroup menutidy nav section source summary track video';

		$string = tidy_repair_string( $string, $this->tidy_options, 'utf8' );

		if( empty( $string ) !== true )
		{
			if( ! empty( $menu ) )
			{
				$string = str_replace( $menu, array_keys( $menu ), $string );
			}

			return "<!DOCTYPE html>\n" . $string;
		}

		return false;
	}

	/**
	 * optimizer::conditionCallback()
	 *
	 * @param mixed $matches
	 * @return
	 */
	private function conditionCallback( $matches )
	{
		$num = $this->_condCount;
		++$this->_condCount;
		return '{|condition_' . $num . '|}';
	}

	/**
	 * optimizer::jsCallback()
	 *
	 * @param mixed $matches
	 * @return
	 */
	private function jsCallback( $matches )
	{
		$num = $this->_jsCount;
		++$this->_jsCount;
		return '{|js_' . $num . '|}';
	}

	/**
	 * optimizer::newCssLink()
	 *
	 * @return
	 */
	private function newCssLink()
	{
		$newCSSLink = md5( implode( array_keys( $this->_cssLinks ) ) );
		$newCSSLinkPath = $this->siteRoot . '/' . $this->cssDir . '/' . $newCSSLink . '.opt.css';

		if( ! file_exists( $newCSSLinkPath ) )
		{
			$contents = '';
			foreach( $this->_cssLinks as $link => $media )
			{
				$link = preg_replace( '#^' . $this->base_siteurl . '#', '', $link );

				if( ! empty( $media ) and ! preg_match( '/all/', $media ) ) $contents .= '@media ' . $media . '{' . $this->eol;
				$contents .= $this->getCssContent( $link ) . $this->eol;
				if( ! empty( $media ) and ! preg_match( '/all/', $media ) ) $contents .= '}' . $this->eol;
			}

			$contents = $this->minifyCss( $contents );
			file_put_contents( $newCSSLinkPath, $contents );
		}

		return $this->base_siteurl . $this->cssDir . '/' . $newCSSLink . '.opt.css';
	}

	/**
     * optimizer::_minifyJsInline()
     *
     * @param mixed $js
     * @return
     */
    private function _minifyJsInline( $js )
    {
        //http://stackoverflow.com/questions/19509863/how-to-remove-js-comments-using-php
        $js = preg_replace( "/(\/\*\*\/)(\/\/(?!([^\n\r]*?\*\/)).*)/", "$1", $js );
        $js = preg_replace( "/\\\\n/", "VQerT", $js );
        $js = preg_replace( "/\\\\r/", "ZQerT", $js );
        do
        {
            $js = preg_replace( "/(http(s)?\:)([^\r\n]*?)(\/\/)/", "$1$3qDdXX", $js, 1, $count );
        } while ( $count );
        // Remove all extra new lines after [ and \
        $js = preg_replace( "/(\*|[\r\n]|\'|\"|\,|\+|\{|;|\(|\)|\[|\]|\{|\}|\?|[^p|s]:|\&|\%|([^\\\\])[a-m-o-u-s-zA-Z]|\||-|=|[0-9])(\s*)(?!([^=\\\\\&\/\"\'\^\*:]))(\/)(\/)+(?!([\r\n\*\+\"]*?([^\r\n]*?\*\/|[^\r\n]*?\"\s*\+|([^\r\n]*?=\";))))([^\n\r]*)([^;\"\'\{\(\}\,]\s*[\\\\\[])(?=([\r\n]+))/", "$1$2$3", $js );
        // slash star followed by all except */ and star slash */ remove add start document!
        $js = preg_replace( "/(^^\/\*)[\s\S]*?(\*\/)/", "\n \n", $js );
        // /* followed by (not new line but) ... */ ... /* ... till */
        $js = preg_replace( "/((([\r\n]\s*)(\/\*[^\r\n]*?\*\/(?!([^\n\r]*?\"\s*\+)))([^\n\r]*?\/\*[^\n\r]*?\*\/(?!([^\n\r]*?\"\s*\+))[^\n\r]*?\/\*[^\n\r]*?\*\/(?!([^\n\r]*?\"\s*\+)))+)+(?!([\*]))(?=([^\n\r\/]*?\/\/\/)))/", "$3", $js );
        // slash slash remove start document! folowed by all exept new line!
        $js = preg_replace( "/(^^\/)+(\/)[^\r\n]*?[\r\n]/", "\n ", $js );
        // (slash slash) remove everything behinde it not if its followed by */ and /n/r or " + and /n/r
        $js = preg_replace( "/([\r\n]+?\s*)((\/)(\/)+)(?!([^\r\n]*?)([\\\\]|\*\/|[=]+\s*\";|[=]+\s*\';)).*/", "$1\n ", $js );
        // slash slash star between collons protect like: ' //* ' by TDdXX
        $js = preg_replace( "/(\'\s*)(\/\/\*)([^\r\n\*]*?(?!(\*\/))(\'))/", "$1TDdXX$3", $js );
        // slash slash star between collons protect like: ' //* ' by TDdXX
        $js = preg_replace( "/(\"\s*)(\/\/\*)([^\r\n\*]*?(?!(\*\/))(\"))/", "$1TDdXX$3", $js );
        // in regex star slash protect by: ODdPK
        $js = preg_replace( "/(\,\s*)(\*\/\*)(\s*[\}\"\'\;\)])/", "$1RDdPK$3", $js ); // , */* '
        $js = preg_replace( '/(\n|\r|\+|\&|\=|\|\||\(|[^\)]\:[^\=\,\/\$\\\\\<]|\(|return(?!(\/[a-zA-Z]+))|\!|\,)(?!(\s*\/\/|\n))(\s*\/)([^\]\)\}\*\;\)\,gi\.]\s*)([^\/\n]*?)(\*\/)/', '$1$4$5$6ODdPK', $js );
        // (slash r) (slash n) protect if followed by " + and new line
        $js = preg_replace( "/[\/][\/]+([\\\\r]+[\\\\n]+[\"]\s*[\+])/", "*/WQerT", $js );
        // Html Text protection!
        $js = preg_replace( "/([\r\n]\s*\/\/)[^\r\n]*?\/\*(?=(\/))[^\r\n]*?([\r\n])/", "$1 */$3", $js );
        $js = preg_replace( "/([\)]|[^\/|\\\\|\"])(\/\*)(?=([^\r\n]*?[\\\\][rn]([\\\\][nr])?\s*\"\s*\+\s*(\n|\r)?\s*\"))/", "$1pDdYX", $js );
        $js = preg_replace( '/([\"]\s*[\,\+][\r\n]\s*[\"])(\s*\/\/)((\/\/)|(\/))*/', '$1qDdXX', $js );
        $js = preg_replace( '/([\"]\s*[\,\+][\r\n]\s*[\"](qDdXX))[\\\\]*(\s*\/\/)*((\/\/)|(\/))*/', '$1', $js );
        // started by new line slash slash remove all not followed by */ and new line!
        $js = preg_replace( "/([\r\n]\s*)(?=([^\r\n\*\,\:\;a-zA-Z\"]*?))(\/)+(\/)[^\r\n\/][^\r\n\*\,]*?[\*]+(?!([^\r\n]*?(([^\r\n]*?\/|\"\s*\)\s*\;|\"\s*\;|\"\s*\,|\'\s*\)\s*\;|\'\s*\;|\'\s*\,))))[^\r\n]*(?!([\/\r\n]))[^\r\n]*/", "$1", $js );
        // removes all *.../ achter // leaves the ( // /* staan en */ ) 1 off 2
        $js = preg_replace( "/([\r\n](\/)*[^:\;\,\.\+])(\/\/[^\r\n]*?)(\*)?([^\r\n]+?)(\*)+([^\r\n\*\/])+?(\/[^\*])(?!([^\r\n]*?((\"\s*\)\s*\;|\"\s*\;|\"\s*\,|\'\s*\)\s*\;|\'\s*\;|\'\s*\,))))/", "$1$3$7$8", $js );
        // removes all /* after // leaves the ( // */ staan ) 2 off 2
        do
        {
            $js = preg_replace( "/([\r\n])((\/)*[^:\;\,\.\+])(\/\/[^\r\n]*?)(\*)?([^\r\n]+?)(\/|\*)([^\r\n]*?)(\*)[\r\n]/", "$1", $js, 1, $count );
        } while ( $count );
        // removes all (/* and */) combinations after // and everything behinde it! but leaves  ///* */ or example. ///*//*/ one times.
        $js = preg_replace( "/(((([\r\n](?=([^:;,\.\+])))(\/)+(\/))(\*))([^\r\n]*?)(\/\*)*([^\r\n])*?(\*\/)(?!([^\r\n]*?((\"\s*\)\s*\;|\"\s*\;|\"\s*\,|\'\s*\)\s*\;|\'\s*\;|\'\s*\,))))(((?=([^:\;\,\.\+])))(\/)*([^\r\n]*?)(\*|\/)?([^\r\n]*?)(\/\*)([^\r\n])*?(\*\/)(?!([^\r\n]*?((\"\s*\)\s*\;|\"\s*\;|\"\s*\,|\'\s*\)\s*\;|\'\s*\;|\'\s*\,)))))*)+[^\r\n]*/", "$2$7$9$10$11$12", $js );
        // removes /* ... followed by */ repeat even pairs till new line!
        $js = preg_replace( "/(\/\*[\r\n]\s*)(?!([^\/<>;:%~`#@&-_=,\.\$\^\{\[\(\|\)\*\+\?\'\"\a-zA-Z0-9]))(((\/\*)[^\r\n]*?(\*\/)?[^\r\n]*?(\/\*)[^\r\n]*?(\*\/))*((\/\*)[^\r\n]*?(\*\/)))+(?!([^\r\n]*?(\*\/|\/\*)))[^\r\n]*?[\r\n]/", "\n ", $js );
        // (Mark) Regex Find all "  Mark with = AwTc  and  CwRc // special cahacers are:  . \ + * ? ^ $ [ ] ( ) { } < > = ! | : " '
        $js = preg_replace( "/(?!([\r\n]))(\+|\?|&|\=|\|\||\(|\!|,|return(?!(\/[a-zA-Z]+))|[^\)]\:)(?!(\s*\/\/|\n|\/\*[^\r\n\*]*?\*\/))(\s*\/([\^]?))(?!([\r\n\*\/]|[\*]))(?!(\<\!\-\-))(([^\]\)\}\*;,g&\.\"\']?\s*)(?=([\]\)\}\*;,g&\.\/\"\']))?)((.*)(([\w\W])([\*]?\/\s*)(?=(\]))|([\w\W])([\*]?\/\s*)(?=(\}))|([^\\\\])([\*]?\/\s*)(?=(\)))|([\w\W])([\*]?\/\s*)(?=([i][g]?[\W]))|([\w\W])([\*]?\/\s*)(?=([g][i]?[\W]))|([\w\W])([\*]?\/\s*)(?=(\,))|([^\\\\]|[\/])([\*]?\/\s*)(?=(;))|([\w\W])([\*]?\/\:\s)(?!([@\[\)\(\}\{\.,#%\+-\=`~\*&\^;\:\'\"]))|([^\\\\])([\*]?\/\s*)(?=(\.[^\/]))|([^\\\\])([\*]?\/\s*)(?=([\r\n]\s*[;\.,\)\}\]]\s*[^\/]|[\r\n]\s*([i][g]?[\W])|[\r\n]\s*([g][i]?[\W]))))|([^\\\\])([\*]?\/\s*)(?=([;\.,\)\}\]]\s*[^\/]|([i][g]?[\W])|([g][i]?[\W]))))/", "$2$3$5AwTc$7$8$10$13$15$18$21$24$27$30$33$36$39$42$47CwRc$16$19$22$25$28$31$34$37$40$43$48", $js );
        // Remove all extra new lines after [ and \
        $js = preg_replace( "/([^;\"\'\{\(\}\,]\s*[\\\\\[]\s?)\s*([\r\n]+)/", "$1", $js );
        $js = preg_replace( "/([\|\[])\s*(\]\|)/", "$1$2", $js );
        // (star slash) or (slash star) 1 sentence! Protect! With pDdYX and ODdPK
        do
        {
            $js = preg_replace( '/(AwTc)([^\r\n]*?)(\/)(\*)(?=([^\r\n]*?CwRc))/', '$1$2pDdYX', $js, 1, $count );
        } while ( $count );
        do
        {
            $js = preg_replace( '/(AwTc)([^\r\n]*?)(\*)(\/)(?=([^\r\n]*?CwRc))/', '$1$2ODdPK', $js, 1, $count );
        } while ( $count );
        // (slash slash) 1 sentence! Protect with: qDdXX
        do
        {
            $js = preg_replace( '/(AwTc)([^\r\n]*?)(\/)(\/)(?=([^\r\n]*?CwRc))/', '$1$2qDdXX', $js, 1, $count );
        } while ( $count );

        // (Mark) Regex Find all "  Mark With :  YuKt  and   ZuKd
        $js = preg_replace( "/((join|split|match|replace|RegExp|return|regex)\s*)(\(\s*(\")?)(([^\r\n]*?)((\")?\s*\))(?!(\"\)|\[|\")|\())/", "$1$3YuKt$6ZuKd$7", $js );
        // (star slash) or (slash star) 1 sentence! Protect! With pDdYX and ODdPK
        do
        {
            $js = preg_replace( '/((\")?YuKt)([^\r\n]*?)(\/)(\*)(?=([^\r\n]*?ZuKd))/', '$1$3pDdYX', $js, 1, $count );
        } while ( $count );
        do
        {
            $js = preg_replace( '/((\")?YuKt)([^\r\n]*?)(\*)(\/)(?=([^\r\n]*?ZuKd))/', '$1$3ODdPK', $js, 1, $count );
        } while ( $count );
        // (slash slash) 1 sentence! Protect with: qDdXX
        do
        {
            $js = preg_replace( '/((\")?YuKt)([^\r\n]*?)(\/)(\/)(?=([^\r\n]*?ZuKd))/', '$1$3qDdXX', $js, 1, $count );
        } while ( $count );
        // (slash slash) 2 sentences! Protect ' and "
        do
        {
            $js = preg_replace( "/(=|\+|\(|[a-z]|\,)(\s*)(\")([^\r\n\;\/\'\)\,\]\}\*]*?)(\/)(\/)([^\r\n\;\"\*]*?)(\")/", "$1$2$3$4qDdXX$7$8", $js, 1, $count );
        } while ( $count );

        do
        {
            $js = preg_replace( "/(=|\+|\(|[a-z]|\,)(\s*)(\')([^\r\n\;\/\'\)\,\]\}\*]*?)(\/)(\/)([^\r\n\*\;\']*?)(\')/", "$1$2$3$4qDdXX$7$8", $js, 1, $count );
        } while ( $count );
        // (slash slash) 2 sentences! Protect ' and "
        do
        {
            $js = preg_replace( "/(\"[^\r\n\;]*?)(\/)(\/)([^\r\n\"\;]*?([\"]\s*(\;|\)|\,)))/", "$1qDdXX$4", $js, 1, $count );
        } while ( $count );
        do
        {
            $js = preg_replace( "/(\'[^\r\n\;]*?)(\/)(\/)([^\r\n\'\;]*?([\']\s*(\;|\)|\,)))/", "$1qDdXX$4", $js, 1, $count );
        } while ( $count );
        // Remove all slar slash achter \n
        $js = preg_replace( "/([\n\r])([^\n\r\*\,\"\']*?)(?=([^\*\,\:\;a-zA-Z\"]*?))(\/)(\/)+(?=([^\n\r]*?\*\/))([^\n\r]*?(\*\/)).*/", "$1$4$5 $8", $js );
        do
        {
            $js = preg_replace( "/([\r\n]\s*)((\/\*(?!(\*\/)))([^\r\n]+?)(\*\/))(?!([^\n\r\/]*?(\/)(\/)+\*))/", "$1$3$6", $js, 1, $count );
        } while ( $count );
        $js = preg_replace( "/([\n\r]\/)(\/)+([^\n\r]*?)(\*\/)([^\n\r]*?(\*\/))(?!([^\n\r]*?(\*\/)|[^\n\r]*?(\/\*))).*/", "$1/ $4", $js );
        do
        {
            $js = preg_replace( "/([\n\r]\s*\/\*\*\/)([^\n\r=]*?\/\*[^\n\r]*?\*\/)(?=([\n\r]|\/\/))/", "$1", $js, 1, $count );
        } while ( $count );
        $js = preg_replace( "/([\n\r]\s*\/\*\*\/)([^\n\r=]*?)(\/\/.*)/", "$1$2", $js );
        // Remove all slash slash achter = '...'; //......
        do
        {
            $js = preg_replace( "/(\=\s*)(?=([^\r\n\'\"]*?\'[^\n\r\']*?\'))([^\n\r;]*?[;]\s*)(\/\/[^\r\n][^\r\n]*)[\n\r]/", "$1$3", $js, 1, $count );
        } while ( $count );
        // protect slash slash '...abc//...abc'!
        do
        {
            $js = preg_replace( "/(\=)(\s*\')([^\r\n\'\"]*?)(\/)(\/)([^\r\n]*?[\'])/", "$1$2$3qDdXX$6", $js, 1, $count );
        } while ( $count );
        //(slash star) or (star slash) : no dubble senteces here! Protect with: pDdYX and ODdPK
        do
        {
            $js = preg_replace( "/(\"[^\r\n\;\,\"]*?)(\/)(\*)([^\r\n;\,\"]*?)(\")/", "$1pDdYX$4$5", $js, 1, $count );
        } while ( $count ); // open
        do
        {
            $js = preg_replace( "/([^\"]\"[^\r\n\;\/\,\"]*?)(\s*)(\*)(\/)([^\r\n;\,\"=]*?)(\")/", "$1$2ODdPK$5$6", $js, 1, $count );
        } while ( $count ); // close
        do
        {
            $js = preg_replace( "/(\'[^\r\n\;\,\']*?)(\/)(\*)([^\r\n;\,\']*?)(\')/", "$1pDdYX$4$5", $js, 1, $count );
        } while ( $count ); // open
        do
        {
            $js = preg_replace( "/(\'[^\r\n\;\/\,\']*?)(\s*)(\*)(\/)([^\r\n;\,\']*?)(\')/", "$1$2ODdPK$5$6", $js, 1, $count );
        } while ( $count ); // close
        // protect star slash '...abc*/...abc'!
        do
        {
            $js = preg_replace( "/(\'[^\r\n\;\,\']*?)(\*)(\/)([^\r\n;\,\']*?)(\')(?!([^\n\r\+]*?[\']))/", "$1ODdPK$4$5", $js, 1, $count );
        } while ( $count );
        // protect star slash '...abc*/...abc'!
        do
        {
            $js = preg_replace( "/(\"[^\r\n\;\,\"]*?)(\*)(\/)([^\r\n;\,\"]*?)(\")(?!([^\n\r\+]*?[\"]))/", "$1ODdPK$4$5", $js, 1, $count );
        } while ( $count );
        // \n protect

        do
        {
            $js = preg_replace( "/(=\s*\"[^\n\r\"]*?)(\/\/)(?=([^\n\r]*?\"\s*;))/", "$1qDdXX", $js, 1, $count );
        } while ( $count );
        do
        {
            $js = preg_replace( "/(=\s*\"[^\n\r\"]*?)(\/\*)(?=([^\n\r]*?\"\s*;))/", "$1pDdYX", $js, 1, $count );
        } while ( $count );
        do
        {
            $js = preg_replace( "/(=\s*\"[^\n\r\"]*?)(\*\/)(?=([^\n\r]*?\"\s*;))/", "$1ODdPK", $js, 1, $count );
        } while ( $count );
        do
        {
            $js = preg_replace( "/(=\s*\'[^\n\r\']*?)(\/\/)(?=([^\n\r]*?\'\s*;))/", "$1qDdXX", $js, 1, $count );
        } while ( $count );
        do
        {
            $js = preg_replace( "/(=\s*\'[^\n\r\']*?)(\/\*)(?=([^\n\r]*?\'\s*;))/", "$1pDdYX", $js, 1, $count );
        } while ( $count );
        do
        {
            $js = preg_replace( "/(=\s*\'[^\n\r\']*?)(\*\/)(?=([^\n\r]*?\'\s*;))/", "$1ODdPK", $js, 1, $count );
        } while ( $count );
        // (Slash Slash) alle = " // " and = ' // ' replace by! qDdXX
        do
        {
            $js = preg_replace( "/(\=|\()(\s*\")([^\r\n\'\"]*?[\'][^\r\n\'\"]*?)(\/)(\/)([^\r\n\'\"]*?[\'])(\s*\'[^\r\n\'\"]*?)(\/\/|qDdXX)?([^\r\n\'\"]*?[\'][^\r\n\'\"]*?[\"])(?!(\'\)|\s*[\)]?\s*\+|\'))/", "$1$2$3qDdXX$6$7qDdXX$9$10", $js, 1, $count );
        } while ( $count );
        do
        {
            $js = preg_replace( "/(\=|\()(\s*\')([^\r\n\'\"]*?[\"][^\r\n\'\"]*?)(\/)(\/)([^\r\n\'\"]*?[\"])(\s*\"[^\r\n\'\"]*?)(\/\/|qDdXX)?([^\r\n\'\"]*?[\"][^\r\n\'\"]*?[\'])(?!(\'\)|\s*[\)]?\s*\+|\'))/", "$1$2$3qDdXX$6$7qDdXX$9$10", $js, 1, $count );
        } while ( $count );
        // (slash slash) Remove all also , or + not followed by */ and newline
        $js = preg_replace( "/(\*|[\r\n]|[^\\\\]\'|[^\\\\]\"|\,|\+|\{|;|\(|\)|\[|\]|\{|\}|\?|[^p|s]:|\&|\%|([^\\\\])[a-m-o-u-s-zA-Z]|\||-|=|[0-9])(\s*)(?!([^=\\\\\&\/\"\'\^\*:]))(\/)(\/)+(?!([\r\n\*\+\"]*?([^\r\n]*?\*\/|[^\r\n]*?\"\s*\+|([^\r\n]*?=\";)))).*/", "$1", $js );
        // (slash slash star slash) Remove everhing behinde it not followed by */ or new line
        $js = preg_replace( "/(\/\/\*\/)(?!([\r\n\*\+\"]*?([^\r\n]*?\*\/|[^\r\n]*?\"\s*\+|([^\r\n]*?=\";)))).*/", "", $js );
        // Remove almost all star comments except colon/**/
        $js = preg_replace( "/(?!([^\n\r]*?[\'\"]))(\s*<!--.*-->)(?!(<\/div>))[^\n\r]*?(\*\/)?.*/", "$2$4", $js );
        $js = preg_replace( "/\/\*/", "\n/*dddpp", $js );
        $js = preg_replace( '/((\{\s*|\(\s*|:\s*)[\"\']\s*)(([^\n\r\{\};\"\']*)dddpp)/', '$1$4', $js );
        $js = preg_replace( "/\*\//", "xxxpp*/\n", $js );
        $js = preg_replace( '/([^\"\'](\(\s*|:\s*|\[\s*)[\"\']\s*)(([^\};\"\']*)xxxpp(?=([^\n\r]*?[\"\'])))/', '$1$4', $js );
        $js = preg_replace( '/([\"\'])\s*\/\*/', '$1/*', $js );
        $js = preg_replace( '/(\n)[^\'"]?\/\*dddpp.*?xxxpp\*\//s', '', $js );
        $js = preg_replace( '/\n\/\*dddpp([^\s]*)/', '$1', $js );
        $js = preg_replace( '/xxxpp\*\/\n([^\s]*)/', '*/$1', $js );
        $js = preg_replace( '/xxxpp\*\/\n([\"])/', '$1', $js );
        $js = preg_replace( '/(\*)\n*\s*(\/\*)\s*/', '$1$2', $js );
        $js = preg_replace( '/(\*\/)\s*(\")/', '$1$2', $js );
        $js = preg_replace( '/\/\*dddpp(\s*)/', '/*', $js );
        $js = preg_replace( '/\n\s*\n/', "\n", $js );
        $js = preg_replace( '/\s+(\*\/)\s*/', "$1\n", $js );
        $js = preg_replace( "/([\n\r][^\n\r\*\,\"\']*?)(?=([^\*\,\:\;a-zA-Z\"]*?))(\/)(\/)+(?!([\r\n\*\+\"]*?([^\r\n]*?\*\/|[^\r\n]*?\"\s*\+|([^\r\n]*?=\";)))).*/", "$1", $js );
        $js = preg_replace( "/(?!([^\n\r]*?[\'\"]))(\s*<!--.*-->)(?!(<\/div>))[^\n\r]*?(\*\/)?.*/", "", $js );
        // Restore all
        $js = preg_replace( '/TOtX/', '"', $js ); // Restore "
        $js = preg_replace( "/TOtH/", "'", $js ); // Restore '
        $js = preg_replace( "/qDdXX/", "//", $js ); // Restore //
        $js = preg_replace( "/pDdYX/", "/*", $js ); // Restore
        $js = preg_replace( "/ODdPK/", "*/", $js ); // Restore
        $js = preg_replace( "/RDdPK/", "*/*", $js ); // Restore
        $js = preg_replace( "/TDdXX/", "//*", $js ); // Restore */
        $js = preg_replace( '/\*\/WQerT/', '\\\\r\\\\n" +', $js ); // Restore \r\n" +
        $js = preg_replace( '/VQerT/', '\\\\n', $js ); // Restore \n"
        $js = preg_replace( '/ZQerT/', '\\\\r', $js ); // Restore \n"
        // Remove all markings!
        $js = preg_replace( '/(AwTc)/', '', $js ); // Start most Regex!
        $js = preg_replace( '/(CwRc)/', '', $js ); // End Most regex!
        $js = preg_replace( '/(qDdu)/', '', $js ); // //
        $js = preg_replace( '/ZXKd/', '', $js ); // End Rexex (join|split|match|replace|RegExp|return|regex)
        $js = preg_replace( '/(YuKt)/', '', $js ); //   Start Regex (join|split|match|replace|RegExp|return|regex)
        $js = preg_replace( '/(ZuKd)/', '', $js ); //  End Rexex (join|split|match|replace|RegExp|return|regex)
        // all \s and [\n\r] repair like they where!

        $js = preg_replace( "/([a-zA-Z0-9]\s?)\s*[\n\r]+(\s*[\)\,&]\s?)(\s*[\r\n]+\s*[\{])/", "$1$2$3", $js );
        $js = preg_replace( "/([a-zA-Z0-9\(]\s?)\s*[\n\r]+(\s*[;\)\,&\+\-a-zA-Z0-9]\s?)(\s*[\{;a-zA-Z0-9\,&\n\r])/", "$1$2$3", $js );
        $js = preg_replace( "/(\(\s?)\s*[\n\r]+(\s*function)/", "$1$2", $js );
        $js = preg_replace( "/(=\s*\[[a-zA-Z0-9]\s?)\s*([\r\n]+)/", "$1", $js );
        //-----------------------------------------------
        $js = preg_replace( "/([^\*\/\'\"]\s*)(\/\/\s*\*\/)/", "$1", $js );
        // Remove all /**/// .... Remove expept /**/ and followed by */ till newline!
        $js = preg_replace( "/(\/\*\*\/)(\/\/(?!([^\n\r]*?\*\/)).*)/", "$1", $js );
        $js = preg_replace( "/(\/\/\\\\\*[^\n\r\"\'\/]*?[\n\r])/", "\r\n", $js );
        $js = preg_replace( "/([\r\n]\s*)(\/\*[^\r\n]*?\*\/(?!([^\r\n]*?\"\s*\+)))/", "$1", $js );
        //Remove colon /**/
        $js = preg_replace( "/(\=\s*)(?=([^\r\n\'\"]*?\'[^\n\r\'\"]*?\'))([^\n\r\/]*?)(\/\/[^\r\n\"\'][^\r\n]*[\'\"])(\/\*\*\/)[\n\r]/", "$1$3$4\n", $js );
        $js = preg_replace( "/(\=\s*)(?=([^\r\n\'\"]*?\"[^\n\r\'\"]*?\"))([^\n\r\/]*?)(\/\/[^\r\n\"\'][^\r\n]*[\'\"])(\/\*\*\/)[\n\r]/", "$1$3$4\n", $js );
        //Remove colon //
        $js = preg_replace( "/([^\'\"ps\s]\s*)(\:[^\r\n\'\"\[\]]*?\'[^\n\r\'\"]*?\')([^\n\r\/a-zA-Z0-9]*?)(\/\/)[^\r\n\/\'][^\r\n]*/", "$1$2", $js );
        $js = preg_replace( "/([^\'\"ps\s]\s*)(\:[^\r\n\'\"\[\]]*?\"[^\n\r\'\"]*?\")([^\n\r\/a-zA-Z0-9]*?)(\/\/)[^\r\n\/\"][^\r\n]*/", "$1$2", $js );
        //Remove all after ; slah slah+
        $js = preg_replace( "/([^\n\r;]*?[;]\s*)(\/\/[^\r\n](?!([^\n\r]*?\"\s*;))[^\r\n]*?)[\n\r]/", "$1", $js );
        $js = preg_replace( '/\s*(\*\/)\s+/', "$1", $js );
        //END Remove comments.
        //START Remove all whitespaces
        $js = preg_replace( '/\s+/', ' ', $js );
        $js = preg_replace( '/\s*(?:(?=[=\-\+\|%&\*\)\[\]\{\};:\,\.\<\>\!\@\#\^`~]))/', '', $js );
        $js = preg_replace( '/(?:(?<=[=\-\+\|%&\*\)\[\]\{\};:\,\.\<\>\?\!\@\#\^`~]))\s*/', '', $js );
        $js = preg_replace( '/([^a-zA-Z0-9\s\-=+\|!@#$%^&*()`~\[\]{};:\'",<.>\/?])\s+([^a-zA-Z0-9\s\-=+\|!@#$%^&*()`~\[\]{};:\'",<.>\/?])/', '$1$2', $js );
        //END Remove all whitespaces

        //NUKEVIET
        $replace = array(
            '#(?<![\+\-])\s*([\+\-])(?![\+\-])#' => '$1',
            '#(?<![\+\-])([\+\-])\s*(?![\+\-])#' => '$1',
            '#(for\([^;]*;[^;]*;[^;\{]*\));(\}|$)#s' => '$1;;$2',
            '#;+\s*([};])#' => '$1',
            '#;(\}|$)#s' => '$1',
            '#([^\'"]*)true([^\'"]*)#i' => "$1!0$2",
            '#([^\'"]*)false([^\'"]*)#i' => "$1!1$2",
            '#\s*(\{|\()\s*#' => '$1',
            '#\s*(\}|\))\s*#' => '$1',
            '#(\;|\,)[ ]+#' => '$1',
            '#[ ]+([\=\<\>\!\:\?\|\&]+)#' => '$1',
            '#([\=\<\>\!\:\?\|\&]+)[ ]+#' => '$1' );

        $search = array_keys( $replace );
        $js = preg_replace( $search, $replace, $js );
        $js = str_replace( '$(document).ready', '$', $js );

        if ( ! $this->_tidySupport )
        {
            $js = "//<![CDATA[" . $this->eol . trim( $js ) . $this->eol . "//]]>";
        }
        return $js;
    }

    /**
     * optimizer::minifyJsInline()
     *
     * @param mixed $jsInline
     * @return
     */
    private function minifyJsInline( $matches )
    {
        $jsInline = $this->_minifyJsInline( $matches[2] );
        return '<script' . $matches[1] . '>' . $this->eol . $jsInline . $this->eol . '</script>';
    }

	/**
	 * optimizer::getCssContent()
	 *
	 * @param mixed $link
	 * @return
	 */
	private function getCssContent( $link )
	{
		$content = file_get_contents( $this->siteRoot . '/' . $link );
		$this->cssImgNewPath = '../../' . str_replace( '\\', '/', dirname( $link ) ) . '/';
		$content = preg_replace_callback( "/url[\s]*\([\s]*[\'\"]*([^\'\"\)]+)[\'\"]*[\s]*\)/", array( $this, 'changeCssURL' ), $content );
		return $content;
	}

	/**
	 * optimizer::changeCssURL()
	 *
	 * @param mixed $matches
	 * @return
	 */
	private function changeCssURL( $matches )
	{
		if( preg_match( '/^(http(s?)|ftp\:\/\/)/', $matches[1] ) )
		{
			$url = $matches[1];
		}
		else
		{
			$url = $this->cssImgNewPath . $matches[1];
			while( preg_match( '/([^\/(\.\.)]+)\/\.\.\//', $url ) )
			{
				$url = preg_replace( '/([^\/(\.\.)]+)\/\.\.\//', '', $url );
			}
		}
		return 'url(' . $url . ')';
	}

	/**
	 * optimizer::minifyCss()
	 *
	 * @param mixed $cssContent
	 * @return
	 */
	private function minifyCss( $cssContent )
	{
		$cssContent = preg_replace( '@>/\\*\\s*\\*/@', '>/*keep*/', $cssContent );
		$cssContent = preg_replace( '@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $cssContent );
		$cssContent = preg_replace( '@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $cssContent );
		$cssContent = preg_replace_callback( '@\\s*/\\*([\\s\\S]*?)\\*/\\s*@', array( $this, 'commentCB' ), $cssContent );
		$cssContent = preg_replace( '/[\s\t\r\n]+/', ' ', $cssContent );
		$cssContent = preg_replace( '/[\s]*(\:|\,|\;|\{|\})[\s]*/', "$1", $cssContent );
		$cssContent = preg_replace( "/[\#]+/", "#", $cssContent );
		$cssContent = str_replace( array( ' 0px', ':0px', ';}', ':0 0 0 0', ':0.', ' 0.' ), array( ' 0', ':0', '}', ':0', ':.', ' .' ), $cssContent );
		$cssContent = preg_replace( '/\\s*([{;])\\s*([\\*_]?[\\w\\-]+)\\s*:\\s*(\\b|[#\'"-])/x', '$1$2:$3', $cssContent );

		//$cssContent = preg_replace_callback( '/(?:\\s*[^~>+,\\s]+\\s*[,>+~])+\\s*[^~>+,\\s]+{/x', array( $this, 'selectorsCB' ), $cssContent );
		$cssContent = preg_replace( '/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i', '$1#$2$3$4$5', $cssContent );
		$cssContent = preg_replace_callback( '/font-family:([^;}]+)([;}])/', array( $this, 'fontFamilyCB' ), $cssContent );
		$cssContent = preg_replace( '/@import\\s+url/', '@import url', $cssContent );
		$cssContent = preg_replace( '/:first-l(etter|ine)\\{/', ':first-l$1 {', $cssContent );
		$cssContent = preg_replace( "/[^\}]+\{[\s|\;]*\}[\s]*/", "", $cssContent );
		$cssContent = preg_replace( "/[ ]+/", " ", $cssContent );

		$cssContent = str_replace( "\xEF\xBB\xBF", "", $cssContent ); // Remove BOM signature

		// Kiem tra charset va import CSS de chuyen len tren
		unset( $matchs );
		preg_match_all( '/\@(charset|import)(.*?)[\'|"|\)]\;/is', $cssContent, $matchs );
		preg_replace( '/\@(charset|import)(.*?)[\'|"|\)]\;/is', '', $cssContent );
		if( ! empty( $matchs[0] ) )
		{
			$this->_cssImportCharset = '';

			foreach( $matchs[0] as $v )
			{
				$this->_cssImportCharset .= $v;
			}

			$cssContent = $this->_cssImportCharset . $cssContent;
		}

		$cssContent = trim( $cssContent );
		return $cssContent;
	}

	/**
	 * optimizer::commentCB()
	 *
	 * @param mixed $m
	 * @return
	 */
	private function commentCB( $m )
	{
		$hasSurroundingWs = ( trim( $m[0] ) !== $m[1] );
		$m = $m[1];
		if( $m === 'keep' )
		{
			return '/**/';
		}
		if( $m === '" "' )
		{
			return '/*" "*/';
		}
		if( preg_match( '@";\\}\\s*\\}/\\*\\s+@', $m ) )
		{
			return '/*";}}/* */';
		}
		if( preg_match( '@^/\\s*(\\S[\\s\\S]+?)\\s*/\\*@x', $m, $n ) )
		{
			return "/*/" . $n[1] . "/**/";
		}
		if( substr( $m, -1 ) === '\\' )
		{
			return '/*\\*/';
		}
		if( $m !== '' && $m[0] === '/' )
		{
			return '/*/*/';
		}
		return $hasSurroundingWs ? ' ' : '';
	}

	/**
	 * optimizer::selectorsCB()
	 *
	 * @param mixed $m
	 * @return
	 */
	private function selectorsCB( $m )
	{
		return preg_replace( '/\\s*([,>+~])\\s*/', '$1', $m[0] );
	}

	/**
	 * optimizer::fontFamilyCB()
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
	 * optimizer::checkImg()
	 *
	 * @param mixed $m
	 * @return
	 */
	private function checkImg( $m )
    {
        if ( ! preg_match( '/alt=[\'|"]?(.*?)[\'|"]?/i', $m[1] ) )
        {
            return ( "<img alt=\"\"" . $m[1] . "/>" );
        }
        return $m[0];
    }

    /**
     * optimizer::removeQuotes()
     *
     * @param mixed $content
     * @return
     */
    private function removeQuotes($content)
    {
        return preg_replace('/\s+(type|title|data-[a-z0-9]+|colspan|scope|role|media|name|rel|id|class|rel|alt|value|selected)\s*=\s*(\"|\')([a-z0-9_-]+)\2([\> ])/i', ' $1=$3$4',$content);
    }

	/**
	 * optimizer::minifyHTML()
	 *
	 * @param mixed $content
	 * @return
	 */
	private function minifyHTML( $content )
    {
        $content = preg_replace_callback( '/<!--([\s\S]*?)-->/', array( $this, 'HTMLCommentCB' ), $content );
        $content = preg_replace_callback( '/<img([^>]+)\/?>/', array( $this, 'checkImg' ), $content );
        $replace = array(
            '/<\s+/' => '<',
            '/\s+>/' => '>',
            '/>[ ]+</' => '><',
            '/<([^\>]+)\s+\/\s*\>/' => '<$1>',
            '/\s+type\s*\=\s*[\"\']text\/(javascript|css)[\"\']/' => '',
            '/\s+data\-show\s*\=\s*[\"\'](before|after)[\"\']/' => '',
            '/[ ]{2,}/' => ' ',
            '/^\s+|\s+$/m' => '',
            '/^\s+/' => '',
            '/>(\s(?:\s*))?([^<]+)(\s(?:\s*))?</' => '>$1$2$3<' );
        $search = array_keys( $replace );
        $content = preg_replace( $search, $replace, $content );

        return $content;
    }

	/**
	 * optimizer::HTMLCommentCB()
	 *
	 * @param mixed $m
	 * @return
	 */
	private function HTMLCommentCB( $m )
	{
		return ( strpos( $m[1], '[' ) === 0 || strpos( $m[1], '<![' ) !== false ) ? $m[0] : '';
	}

	/**
	 * optimizer::HTMLoutsideTagCB()
	 *
	 * @param mixed $m
	 * @return
	 */
	private function HTMLoutsideTagCB( $m )
	{
		return '>' . preg_replace( '/^\\s+|\\s+$/', ' ', $m[1] ) . '<';
	}
}
