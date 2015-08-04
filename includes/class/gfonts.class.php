<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 30/07/2015 10:00
 */

class Gfonts
{
    private $cssdir = 'files/css';
    private $fontdir = 'files/fonts';
    private $fontsLang = '';
    private $fonts, $cssRealFile, $cssUrlFile;

    public function __construct( $gfonts, $client_info )
    {
        $this->cssdir = NV_ASSETS_DIR . '/css';
        $this->fontdir = NV_ASSETS_DIR . '/fonts';
        $this->fontsLang = ! empty( $gfonts['subset'] ) ? preg_replace( "/[^a-z0-9\,\-]/i", "", strtolower( $gfonts['subset'] ) ) : "";
        $stringFonts = $this->stringFonts( $gfonts['fonts'] );
        $this->fonts = "family=" . $stringFonts;
        $stringFonts = str_replace( ":", ".", $stringFonts );
        if ( ! empty( $this->fontsLang ) )
        {
            $this->fonts .= "&subset=" . $this->fontsLang;
            $stringFonts .= '.' . $this->fontsLang;
        }
        $this->fonts = "http://fonts.googleapis.com/css?" . $this->fonts;
        $stringFonts = preg_replace( "/[^a-z0-9\.]/i", "", $stringFonts );
        $cssFile = strtolower( $stringFonts . "." . $client_info['browser']['key'] . $client_info['browser']['version'] ) . '.css';
        $this->cssRealFile = NV_ROOTDIR . '/' . $this->cssdir . '/' . $cssFile;
        $this->cssUrlFile = NV_BASE_SITEURL . $this->cssdir . '/' . $cssFile;
    }

    public function getUrlCss()
    {
        if ( empty( $this->fonts ) ) return '';
        if ( file_exists( $this->cssRealFile ) ) return $this->cssUrlFile;
        return $this->addfile();
    }

    public function getDataCss()
    {
        if ( empty( $this->fonts ) ) return '';
        if ( file_exists( $this->cssRealFile ) )
        {
            return file_get_contents( $this->cssRealFile );
        }

        return $this->addfile( true );
    }

    private function downloadFont( $url, $dir, $filename )
    {
        $curlHandle = curl_init();
        curl_setopt( $curlHandle, CURLOPT_ENCODING, '' );
        curl_setopt( $curlHandle, CURLOPT_URL, $url );
        curl_setopt( $curlHandle, CURLOPT_HEADER, 0 );
        curl_setopt( $curlHandle, CURLOPT_USERAGENT, urlencode( NV_USER_AGENT ) );
        curl_setopt( $curlHandle, CURLOPT_REFERER, 'http://php.net/' );
        curl_setopt( $curlHandle, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $curlHandle, CURLOPT_MAXREDIRS, 10 );
        curl_setopt( $curlHandle, CURLOPT_TIMEOUT, 30 );

        if ( ( $fp = fopen( $dir . '/' . $filename, "wb" ) ) === false )
        {
            curl_close( $curlHandle );
            return false;
        }

        curl_setopt( $curlHandle, CURLOPT_FILE, $fp );
        curl_setopt( $curlHandle, CURLOPT_BINARYTRANSFER, true );

        if ( curl_exec( $curlHandle ) === false )
        {
            fclose( $fp );
            curl_close( $curlHandle );
            return false;
        }
        fclose( $fp );
        curl_close( $curlHandle );
        return true;
    }

    private function download_Callback( $matches )
    {
        $dir = NV_ROOTDIR . '/' . $this->fontdir;
        if ( file_exists( $dir . '/' . $matches[1] ) ) return $this->fontdir . '/' . $matches[1];
        if ( $this->downloadFont( $matches[0], $dir, $matches[1] ) ) return NV_BASE_SITEURL . $this->fontdir . '/' . $matches[1];
        return $matches[0];
    }

    private function addfile( $data = false )
    {
        $curlHandle = curl_init();
        curl_setopt( $curlHandle, CURLOPT_ENCODING, '' );
        curl_setopt( $curlHandle, CURLOPT_URL, $this->fonts );
        curl_setopt( $curlHandle, CURLOPT_HEADER, true );
        curl_setopt( $curlHandle, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curlHandle, CURLOPT_USERAGENT, urlencode( NV_USER_AGENT ) );
        curl_setopt( $curlHandle, CURLOPT_REFERER, 'http://php.net/' );
        curl_setopt( $curlHandle, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $curlHandle, CURLOPT_MAXREDIRS, 10 );
        curl_setopt( $curlHandle, CURLOPT_TIMEOUT, 30 );
        $result = curl_exec( $curlHandle );
        if ( curl_errno( $curlHandle ) == 23 || curl_errno( $curlHandle ) == 61 )
        {
            curl_setopt( $curlHandle, CURLOPT_ENCODING, 'none' );
            $result = curl_exec( $curlHandle );
        }
        curl_close( $curlHandle );
        list( $header, $result ) = preg_split( "/\r?\n\r?\n/", $result, 2 );
        if ( empty( $result ) ) return '';

        $Regex = "/http\:\/\/[^\) ]+\/([^\.\) ]+\.[^\) ]+)/";

        if ( preg_match_all( $Regex, $result, $matches ) )
        {
            $result = preg_replace_callback( $Regex, array( $this, 'download_Callback' ), $result );
        }

        $result = Minify_CSS_Compressor::process( $result );
        @file_put_contents( $this->cssRealFile, $result );

        return $data ? $result : $this->cssUrlFile;
    }

    private function stringFonts( $fonts )
    {
        if ( empty( $fonts ) ) return '';
        $_fonts = array();
        foreach ( $fonts as $k => $font )
        {
            $_fonts[$k] = urlencode( $font['family'] );
            if ( isset( $font['styles'] ) && ! empty( $font['styles'] ) ) $_fonts[$k] .= ':' . $font['styles'];
        }

        return implode( $_fonts, "|" );
    }
}
