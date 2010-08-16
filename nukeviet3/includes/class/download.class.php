<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 17/8/2010, 0:16
 */

/**********************************************************************
**
** A class to download files
** Version 1.0
** Features : 
**      - hide the real path to the file
**      - allow / disallow download resuming
**      - partial download (useful for download managers)
**      - rename the file on the fly
**      - limit download speed
**
** Author: Mourad Boufarguine / EPT <mourad.boufarguine@gmail.com>
**
** License: Public Domain
** Warranty: None
**
***********************************************************************/

/**
 * include("download.class.php");       // load the class file

 * $fichier = new download("example.zip");                          // use the original file name, disallow resuming, no speed limit                  
 * $fichier = new download("example.zip","My Example.zip") ;        // rename the file, disallow resuming, no speed limit
 * $fichier = new download("example.zip","My Example.zip",true) ;   // rename the file, allow resuming, no speed limit 
 * $fichier = new download("example.zip","My Example.zip",true,80) ;   // rename the file, allow resuming, speed limit 80ko/s

 * $fichier->download_file();
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_MIME_INI_FILE' ) )
{
    define( "NV_MIME_INI_FILE", str_replace( "\\", "/", realpath( dirname( __file__ ) . "/.." ) . '/ini/mime.ini' ) );
}

if ( ! defined( 'ALLOWED_SET_TIME_LIMIT' ) )
{
    if ( $sys_info['allowed_set_time_limit'] )
    {
        define( 'ALLOWED_SET_TIME_LIMIT', true );
    }
}

class download
{
    private $properties = array( // just one array to gather all the properties of a download
        "path" => "", // the real path to the file
        "name" => "", // to rename the file on the fly
        "extension" => "", // extension of the file
        "type" => "", // the type of the file
        "size" => "", // the file size
        "resume" => "", // allow / disallow resuming
        "max_speed" => "" // speed limit (ko) ( 0 = no limit)
        );

    /**
     * download::__construct()
     * 
     * @param mixed $path
     * @param string $name
     * @param string $resume
     * @param integer $max_speed
     * @return void
     *  by default, resuming is NOT allowed and there is no speed limit
     */
    public function __construct( $path, $name = '', $resume = false, $max_speed = 0 )
    {
        $this->properties = array( //
            "path" => $path, //
            "name" => ( $name == "" ) ? substr( strrchr( "/" . $path, "/" ), 1 ) : $name, // if "name" is not specified, th file won't be renamed
            "extension" => strtolower( array_pop( explode( '.', $path ) ) ), // the file extension
            "type" => $this->my_mime_content_type( $path ), // the file type
            "size" => intval( sprintf( "%u", filesize( $path ) ) ), // the file size
            "resume" => $resume, //
            "max_speed" => $max_speed //
            );
    }

    /**
     * download::my_mime_content_type()
     * 
     * @param mixed $path
     * @return
     */
    private function my_mime_content_type( $path )
    {
        if ( function_exists( 'mime_content_type' ) )
        {
            return mime_content_type( $path );
        }

        if ( function_exists( 'finfo_open' ) )
        {
            $finfo = finfo_open( FILEINFO_MIME );
            $mimetype = finfo_file( $finfo, $path );
            finfo_close( $finfo );
            return $mimetype;
        }

        $mime_types = nv_parse_ini_file( NV_MIME_INI_FILE );

        if ( array_key_exists( $this->properties['extension'], $mime_types ) )
        {
            if ( is_string( $mime_types[$ext] ) ) return $mime_types[$ext];
            else  return $mime_types[$ext][0];
        }

        return 'application/force-download';
    }

    /**
     * download::nv_getenv()
     * 
     * @param mixed $key
     * @return
     */
    private function nv_getenv( $key )
    {
        if ( isset( $_SERVER[$key] ) )
        {
            return $_SERVER[$key];
        } elseif ( isset( $_ENV[$key] ) )
        {
            return $_ENV[$key];
        } elseif ( @getenv( $key ) )
        {
            return @getenv( $key );
        } elseif ( function_exists( 'apache_getenv' ) && apache_getenv( $key, true ) )
        {
            return apache_getenv( $key, true );
        }
        return "";
    }

    /**
     * download::get_property()
     * 
     * @param mixed $property
     * @return
     * public function to get the value of a property
     */
    public function get_property( $property )
    {
        if ( array_key_exists( $property, $this->properties ) ) // check if the property do exist
                 return $this->properties[$property]; // get its value
        else  return null; // else return null
    }

    /**
     * download::set_property()
     * 
     * @param mixed $property
     * @param mixed $value
     * @return
     * public function to set the value of a property
     */
    public function set_property( $property, $value )
    {
        if ( array_key_exists( $property, $this->properties ) )
        { // check if the property do exist
            $this->properties[$property] = $value; // set the new value
            return true;
        }
        else  return false;
    }

    /**
     * download::download_file()
     * 
     * @return void
     * public function to start the download
     */
    public function download_file()
    {
        // if the path is unset, then error !
        if ( empty( $this->properties['path'] ) or ! file_exists( $this->properties['path'] ) or ! $this->properties['size'] )
        {
            die( "Nothing to download!" );
        }
        else
        {
            // if resuming is allowed ...
            if ( $this->properties['resume'] )
            {
                // check if http_range is sent by browser (or download manager)
                if ( ( $http_range = nv_getenv( 'HTTP_RANGE' ) ) != "" )
                {
                    list( $a, $range ) = explode( "=", $http_range );
                    preg_match( "/([0-9]+)\-([0-9]*)\/?([0-9]*)/", $range, $range_parts ); // parsing Range header
                    $byte_from = $range_parts[1]; // the download range : from $byte_from ...
                    $byte_to = $range_parts[2]; // ... to $byte_to
                    
                    header( 'HTTP/1.1 206 Partial Content', true, 206 );
                    header( 'Status: 206 Partial content' );
                }
                else
                {
                    $byte_from = 0; // if no range header is found, download the whole file from byte 0 ...
                    $byte_to = $this->properties['size'] - 1; // ... to the last byte
                    header( 'HTTP/1.1 200 OK', true, 200 );
                    header( 'Status: 200 OK' );
                }

                // if the end byte is not specified, ...
                if ( $byte_to == "" )
                {
                    $byte_to = $this->properties['size'] - 1; // ... set it to the last byte of the file
                }

                //header( "HTTP/1.1 206 Partial Content" ); // send the partial content header
                // ... else, download the whole file
            }
            else
            {
                $byte_from = 0;
                $byte_to = $this->properties['size'] - 1;
            }

            $download_range = $byte_from . '-' . $byte_to . '/' . $this->properties['size']; // the download range
            $download_size = $byte_to - $byte_from; // the download length

            // download speed limitation
            if ( ( $speed = $this->properties['max_speed'] ) > 0 )
            { // determine the max speed allowed ...
                $sleep_time = ( 8 / $speed ) * 1e6; // ... if "max_speed" = 0 then no limit (default)
            }
            else
            {
                $sleep_time = 0;
            }

            // send the headers
            header( "Pragma: public" ); // purge the browser cache
            header( "Expires: 0" );
            header( "Cache-Control:" );
            header( "Cache-Control: public" );
            header( "Content-Description: File Transfer" );
            header( "Content-Type: " . $this->properties['type'] ); // file type
            if ( strstr( $this->nv_getenv('HTTP_USER_AGENT'), "MSIE" ) != false )
            {
                header( 'Content-Disposition: attachment; filename="' . urlencode($this->properties['name']) . '";' );
            }
            else
            {
                header( 'Content-Disposition: attachment; filename="' . $this->properties['name'] . '";' );
            }
            header( "Content-Transfer-Encoding: binary" ); // transfer method
            header( "Content-Range: " . $download_range ); // download range
            header( "Content-Length: " . $download_size ); // download length

            // send the file content
            $fp = fopen( $this->properties['path'], "r" ); // open the file
            if ( ! $fp )
            {
                die( 'File error' ); // if $fp is not a valid stream resource, exit
            }

            fseek( $fp, $byte_from ); // seek to start of missing part
            while ( ! feof( $fp ) )
            { // start buffered download
                if ( defined( 'ALLOWED_SET_TIME_LIMIT' ) )
                {
                    set_time_limit( 0 ); // reset time limit for big files (has no effect if php is executed in safe mode)
                }
                print ( fread( $fp, 1024 * 8 ) ); // send 8ko
                flush();
                usleep( $sleep_time ); // sleep (for speed limitation)
            }
            fclose( $fp ); // close the file
            exit;
        }
    }
}

?>