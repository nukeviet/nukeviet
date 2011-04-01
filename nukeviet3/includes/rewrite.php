<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_rewrite_change' ) )
{

    /**
     * nv_rewrite_change()
     * 
     * @param mixed $rewrite_optional
     * @return
     */
    function nv_rewrite_change ( $array_config_global )
    {
        global $sys_info, $lang_module;
        $rewrite_rule = $filename = '';
        if ( $sys_info['supports_rewrite'] == "rewrite_mode_iis" )
        {
            $filename = NV_ROOTDIR . "/web.config";
            $rulename = 1;
            $rewrite_rule .= "\n";
            $rewrite_rule .= "                <rule name=\"nv_rule_" . $rulename ++ . "\">\n";
            $rewrite_rule .= "                    <match url=\"^\" ignoreCase=\"false\" />\n";
            $rewrite_rule .= "                    <conditions>\n";
            $rewrite_rule .= "                    		<add input=\"{REQUEST_FILENAME}\" pattern=\"/robots.txt$\" />\n";
            $rewrite_rule .= "                    </conditions>\n";
            $rewrite_rule .= "                    <action type=\"Rewrite\" url=\"robots.php?action={HTTP_HOST}\" appendQueryString=\"false\" />\n";
            $rewrite_rule .= "                </rule>\n";
            $rewrite_rule .= "                <rule name=\"nv_rule_" . $rulename ++ . "\">\n";
            $rewrite_rule .= "                    <match url=\"^(.*?)Sitemap\.xml$\" ignoreCase=\"false\" />\n";
            $rewrite_rule .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "=SitemapIndex\" appendQueryString=\"false\" />\n";
            $rewrite_rule .= "                </rule>\n";
            $rewrite_rule .= "                <rule name=\"nv_rule_" . $rulename ++ . "\">\n";
            $rewrite_rule .= "                    <match url=\"^(.*?)Sitemap\-([a-z]{2})\.xml$\" ignoreCase=\"false\" />\n";
            $rewrite_rule .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:2}&amp;" . NV_NAME_VARIABLE . "=SitemapIndex\" appendQueryString=\"false\" />\n";
            $rewrite_rule .= "                </rule>\n";
            $rewrite_rule .= "                <rule name=\"nv_rule_" . $rulename ++ . "\">\n";
            $rewrite_rule .= "                    <match url=\"^(.*?)Sitemap\-([a-z]{2})\.([a-zA-Z0-9-]+)\.xml$\" ignoreCase=\"false\" />\n";
            $rewrite_rule .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:2}&amp;" . NV_NAME_VARIABLE . "={R:3}&amp;op=Sitemap\" appendQueryString=\"false\" />\n";
            $rewrite_rule .= "                </rule>\n";
            if ( $sys_info['zlib_support'] )
            {
                $rewrite_rule .= "                <rule name=\"nv_rule_" . $rulename ++ . "\">\n";
                $rewrite_rule .= "                    <match url=\"^((?!http(s?)|ftp\:\/\/).*)\.(css|js)$\" ignoreCase=\"false\" />\n";
                $rewrite_rule .= "                    <action type=\"Rewrite\" url=\"CJzip.php?file={R:1}.{R:3}\" appendQueryString=\"false\" />\n";
                $rewrite_rule .= "                </rule>\n";
            }
            $rewrite_rule .= "                <rule name=\"nv_rule_rewrite\">\n";
            $rewrite_rule .= "                	<match url=\"(.*)\" ignoreCase=\"false\" />\n";
            $rewrite_rule .= "                	<conditions logicalGrouping=\"MatchAll\">\n";
            $rewrite_rule .= "                		<add input=\"{REQUEST_FILENAME}\" matchType=\"IsFile\" ignoreCase=\"false\" negate=\"true\" />\n";
            $rewrite_rule .= "                 		<add input=\"{REQUEST_FILENAME}\" matchType=\"IsDirectory\" ignoreCase=\"false\" negate=\"true\" />\n";
            $rewrite_rule .= "                	</conditions>\n";
            $rewrite_rule .= "                	<action type=\"Rewrite\" url=\"index.php\" />\n";
            $rewrite_rule .= "                </rule>\n";
            $rewrite_rule = nv_rewrite_rule_iis7( $rewrite_rule );
        }
        elseif ( $sys_info['supports_rewrite'] == "rewrite_mode_apache" )
        {
            $filename = NV_ROOTDIR . "/.htaccess";
            $htaccess = "";
            
            $rewrite_rule = "##################################################################################\n";
            $rewrite_rule .= "#nukeviet_rewrite_start //Please do not change the contents of the following lines\n";
            $rewrite_rule .= "##################################################################################\n\n";
            $rewrite_rule .= "#Options +FollowSymLinks\n\n";
            $rewrite_rule .= "<IfModule mod_rewrite.c>\n";
            $rewrite_rule .= "RewriteEngine On\n";
            
            $rewrite_rule .= "RewriteBase " . NV_BASE_SITEURL . "\n";
            
            $rewrite_rule .= "RewriteCond %{REQUEST_FILENAME} /robots.txt$ [NC]\n";
            $rewrite_rule .= "RewriteRule ^ robots.php?action=%{HTTP_HOST} [L]\n";
            $rewrite_rule .= "RewriteRule ^(.*?)Sitemap\.xml$ index.php?" . NV_NAME_VARIABLE . "=SitemapIndex [L]\n";
            $rewrite_rule .= "RewriteRule ^(.*?)Sitemap\-([a-z]{2})\.xml$ index.php?" . NV_LANG_VARIABLE . "=$2&" . NV_NAME_VARIABLE . "=SitemapIndex [L]\n";
            $rewrite_rule .= "RewriteRule ^(.*?)Sitemap\-([a-z]{2})\.([a-zA-Z0-9-]+)\.xml$ index.php?" . NV_LANG_VARIABLE . "=$2&" . NV_NAME_VARIABLE . "=$3&op=Sitemap [L]\n";
            if ( $sys_info['zlib_support'] )
            {
                $rewrite_rule .= "RewriteRule ^((?!http(s?)|ftp\:\/\/).*)\.(css|js)$ CJzip.php?file=$1.$3 [L]\n";
            }
            $rewrite_rule .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
            $rewrite_rule .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
            $rewrite_rule .= "RewriteRule (.*) index.php\n";
            $rewrite_rule .= "</IfModule>\n\n";
            $rewrite_rule .= "#nukeviet_rewrite_end\n";
            $rewrite_rule .= "##################################################################################\n\n";
            
            if ( file_exists( $filename ) )
            {
                $htaccess = @file_get_contents( $filename );
                if ( ! empty( $htaccess ) )
                {
                    $htaccess = preg_replace( "/[\n]*[\#]+[\n]+\#nukeviet\_rewrite\_start(.*)\#nukeviet\_rewrite\_end[\n]+[\#]+[\n]*/s", "\n", $htaccess );
                    $htaccess = trim( $htaccess );
                }
            }
            $htaccess .= "\n\n" . $rewrite_rule;
            $rewrite_rule = $htaccess;
        }
        $errormess = false;
        if ( ! empty( $filename ) and ! empty( $rewrite_rule ) )
        {
            $savefile = true;
            try
            {
                file_put_contents( $filename, $rewrite_rule, LOCK_EX );
                if ( ! file_exists( $filename ) or filesize( $filename ) == 0 )
                {
                    $errormess .= sprintf( $lang_module['err_writable'], NV_BASE_SITEURL . $filename );
                    $savefile = false;
                }
            }
            catch ( Exception $e )
            {
                $savefile = false;
            }
            if ( ! $savefile )
            {
                $errormess .= sprintf( $lang_module['err_writable'], NV_BASE_SITEURL . basename( $filename ) );
            }
        }
        return $errormess;
    }

    function nv_rewrite_rule_iis7 ( $rewrite_rule = "" )
    {
        $filename = NV_ROOTDIR . "/web.config";
        if ( ! class_exists( 'DOMDocument' ) ) return false;
        
        // If configuration file does not exist then we create one.
        if ( ! file_exists( $filename ) )
        {
            $fp = fopen( $filename, 'w' );
            fwrite( $fp, '<configuration/>' );
            fclose( $fp );
        }
        
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        
        if ( $doc->load( $filename ) === false ) return false;
        
        $xpath = new DOMXPath( $doc );
        
        // Check the XPath to the rewrite rule and create XML nodes if they do not exist
        $xmlnodes = $xpath->query( '/configuration/system.webServer/rewrite/rules' );
        if ( $xmlnodes->length > 0 )
        {
            $child = $xmlnodes->item( 0 );
            $parent = $child->parentNode;
            $parent->removeChild( $child );
        }
        if ( ! empty( $rewrite_rule ) )
        {
            $rules_node = $doc->createElement( 'rules' );
            
            $xmlnodes = $xpath->query( '/configuration/system.webServer/rewrite' );
            if ( $xmlnodes->length > 0 )
            {
                $rewrite_node = $xmlnodes->item( 0 );
                $rewrite_node->appendChild( $rules_node );
            }
            else
            {
                $rewrite_node = $doc->createElement( 'rewrite' );
                $rewrite_node->appendChild( $rules_node );
                
                $xmlnodes = $xpath->query( '/configuration/system.webServer' );
                if ( $xmlnodes->length > 0 )
                {
                    $system_webServer_node = $xmlnodes->item( 0 );
                    $system_webServer_node->appendChild( $rewrite_node );
                }
                else
                {
                    $system_webServer_node = $doc->createElement( 'system.webServer' );
                    $system_webServer_node->appendChild( $rewrite_node );
                    
                    $xmlnodes = $xpath->query( '/configuration' );
                    if ( $xmlnodes->length > 0 )
                    {
                        $config_node = $xmlnodes->item( 0 );
                        $config_node->appendChild( $system_webServer_node );
                    }
                    else
                    {
                        $config_node = $doc->createElement( 'configuration' );
                        $doc->appendChild( $config_node );
                        $config_node->appendChild( $system_webServer_node );
                    }
                }
            }
            $rule_fragment = $doc->createDocumentFragment();
            $rule_fragment->appendXML( $rewrite_rule );
            $rules_node->appendChild( $rule_fragment );
        }
        $doc->formatOutput = true;
        return $doc->saveXML();
        return preg_replace( "/([^\r])\n/", "$1\r\n", $rewrite_rule );
    }
}

$rewrite = array();
if ( $global_config['rewrite_optional'] && $global_config['is_url_rewrite'] )
{
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\3/\\4/\\5";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\3/\\4";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)([\"|\'])#"] = "\\1\\3";
    
    $rewrite["#([\"|\'|\>]" . $global_config['site_url'] . "/" . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'|\<])#"] = "\\1\\3/\\4/\\5\\6";
    $rewrite["#([\"|\'|\>]" . $global_config['site_url'] . "/" . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'|\<])#"] = "\\1\\3/\\4\\5";
    $rewrite["#([\"|\'|\>]" . $global_config['site_url'] . "/" . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)([\"|\'|\<])#"] = "\\1\\3\\4";
}
else
{
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\2/\\3/\\4/\\5";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\2/\\3/\\4";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)([\"|\'])#"] = "\\1\\2/\\3";
    
    $rewrite["#([\"|\'|\>]" . $global_config['site_url'] . "/" . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'|\<])#"] = "\\1\\2/\\3/\\4/\\5\\6";
    $rewrite["#([\"|\'|\>]" . $global_config['site_url'] . "/" . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'|\<])#"] = "\\1\\2/\\3/\\4\\5";
    $rewrite["#([\"|\'|\>]" . $global_config['site_url'] . "/" . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)([\"|\'|\<])#"] = "\\1\\2/\\3\\4";
}
?>