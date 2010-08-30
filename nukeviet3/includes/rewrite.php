<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_rewrite_change()
 * 
 * @param mixed $rewrite_optional
 * @return
 */
function nv_rewrite_change ( $array_config_global )
{
    global $sys_info, $lang_module;
    $reval = $filename = "";
    if ( $sys_info['supports_rewrite'] == "rewrite_mode_iis" )
    {
        $filename = "web.config";
        $reval = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $reval .= "<configuration>\n";
        $reval .= "    <system.webServer>\n";
        $reval .= "        <rewrite>\n";
        $reval .= "            <rules>\n";
        if ( $array_config_global['rewrite_optional'] )
        {
            $reval .= "                <rule name=\"Imported Rule 4\">\n";
            $reval .= "                    <match url=\"^([a-z0-9-]+)/([a-zA-Z0-9-/]+)/$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "={R:1}&amp;" . NV_OP_VARIABLE . "={R:2}\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
            $reval .= "                <rule name=\"Imported Rule 42\">\n";
            $reval .= "                    <match url=\"^([a-z0-9-]+)/([a-zA-Z0-9-/]+)$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "={R:1}&amp;" . NV_OP_VARIABLE . "={R:2}\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
            
            $reval .= "                <rule name=\"Imported Rule 3\">\n";
            $reval .= "                    <match url=\"" . NV_ADMINDIR . "[/]*$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"" . NV_ADMINDIR . "/index.php\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
            if ( defined( 'DIR_FORUM' ) and DIR_FORUM != "" and is_dir( NV_ROOTDIR . "/" . DIR_FORUM ) )
            {
                $reval .= "                <rule name=\"Imported Rule 32\">\n";
                $reval .= "                    <match url=\"" . DIR_FORUM . "[/]*$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"" . DIR_FORUM . "/index.php\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
            }
            $reval .= "                <rule name=\"Imported Rule 2\">\n";
            $reval .= "                    <match url=\"^([a-z0-9-]+)/$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "={R:1}\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
            
            $reval .= "                <rule name=\"Imported Rule 22\">\n";
            $reval .= "                    <match url=\"^([a-z0-9-]+)$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "={R:1}\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
        }
        else
        {
            $reval .= "                <rule name=\"Imported Rule 4\">\n";
            $reval .= "                    <match url=\"^([a-z-]+)/([a-z0-9-]+)/([a-zA-Z0-9-/]+)/$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "={R:2}&amp;" . NV_OP_VARIABLE . "={R:3}\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
            $reval .= "                <rule name=\"Imported Rule 42\">\n";
            $reval .= "                    <match url=\"^([a-z-]+)/([a-z0-9-]+)/([a-zA-Z0-9-/]+)$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "={R:2}&amp;" . NV_OP_VARIABLE . "={R:3}\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
            $reval .= "                <rule name=\"Imported Rule 3\">\n";
            $reval .= "                    <match url=\"^([a-z-]+)/([a-z0-9-]+)/$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "={R:2}\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
            $reval .= "                <rule name=\"Imported Rule 32\">\n";
            $reval .= "                    <match url=\"^([a-z-]+)/([a-z0-9-]+)$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "={R:2}\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
            $reval .= "                <rule name=\"Imported Rule 2\">\n";
            $reval .= "                    <match url=\"" . NV_ADMINDIR . "[/]*$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"" . NV_ADMINDIR . "/index.php\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
            if ( defined( 'DIR_FORUM' ) and DIR_FORUM != "" and is_dir( NV_ROOTDIR . "/" . DIR_FORUM ) )
            {
                $reval .= "                <rule name=\"Imported Rule 22\">\n";
                $reval .= "                    <match url=\"" . DIR_FORUM . "[/]*$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"" . DIR_FORUM . "/index.php\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
            }
            $reval .= "                <rule name=\"Imported Rule 1\">\n";
            $reval .= "                    <match url=\"^([a-z0-9-]+)/$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
            $reval .= "                <rule name=\"Imported Rule 12\">\n";
            $reval .= "                    <match url=\"^([a-z0-9-]+)$\" ignoreCase=\"false\" />\n";
            $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}\" appendQueryString=\"false\" />\n";
            $reval .= "                </rule>\n";
        
        }
        $reval .= "            </rules>\n";
        $reval .= "        </rewrite>\n";
        $reval .= "    </system.webServer>\n";
        $reval .= "</configuration>\n";
    }
    elseif ( $sys_info['supports_rewrite'] == "rewrite_mode_apache" )
    {
        $filename = ".htaccess";
        $htaccess = "";
        
        $reval = "##################################################################################\n";
        $reval .= "#nukeviet_rewrite_start //Please do not change the contents of the following lines\n";
        $reval .= "##################################################################################\n\n";
        $reval .= "#Options +FollowSymLinks\n\n";
        $reval .= "<IfModule mod_rewrite.c>\n";
        $reval .= "RewriteEngine On\n";
        $reval .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
        $reval .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
        if ( $array_config_global['rewrite_optional'] )
        {
            $reval .= "RewriteRule ^([a-z0-9-]+)/([a-zA-Z0-9-/]+)/$ index.php?" . NV_NAME_VARIABLE . "=$1&" . NV_OP_VARIABLE . "=$2\n";
            $reval .= "RewriteRule ^([a-z0-9-]+)/([a-zA-Z0-9-/]+)$ index.php?" . NV_NAME_VARIABLE . "=$1&" . NV_OP_VARIABLE . "=$2\n";
            $reval .= "RewriteRule ^" . NV_ADMINDIR . "[/]*$ " . NV_ADMINDIR . "/index.php\n";
            if ( defined( 'DIR_FORUM' ) and DIR_FORUM != "" and is_dir( NV_ROOTDIR . "/" . DIR_FORUM ) )
            {
                $reval .= "RewriteRule ^" . DIR_FORUM . "[/]*$ " . DIR_FORUM . "/index.php\n";
            }
            $reval .= "RewriteRule ^([a-z0-9-]+)/$ index.php?" . NV_NAME_VARIABLE . "=$1\n";
            $reval .= "RewriteRule ^([a-z0-9-]+)$ index.php?" . NV_NAME_VARIABLE . "=$1\n";
        }
        else
        {
            $reval .= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)/([a-zA-Z0-9-/]+)/$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=$2&" . NV_OP_VARIABLE . "=$3\n";
            $reval .= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)/([a-zA-Z0-9-/]+)$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=$2&" . NV_OP_VARIABLE . "=$3\n";
            $reval .= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)/$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=$2\n";
            $reval .= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=$2\n";
            $reval .= "RewriteRule ^" . NV_ADMINDIR . "[/]*$ " . NV_ADMINDIR . "/index.php\n";
            if ( defined( 'DIR_FORUM' ) and DIR_FORUM != "" and is_dir( NV_ROOTDIR . "/" . DIR_FORUM ) )
            {
                $reval .= "RewriteRule ^" . DIR_FORUM . "[/]*$ " . DIR_FORUM . "/index.php\n";
            }
            $reval .= "RewriteRule ^([a-z-]+)/$ index.php?" . NV_LANG_VARIABLE . "=$1\n";
            $reval .= "RewriteRule ^([a-z-]+)$ index.php?" . NV_LANG_VARIABLE . "=$1\n";
        }
        $reval .= "</IfModule>\n\n";
        $reval .= "#nukeviet_rewrite_end\n";
        $reval .= "##################################################################################\n\n";
        
        if ( file_exists( NV_ROOTDIR . '/' . $filename ) )
        {
            $htaccess = @file_get_contents( NV_ROOTDIR . '/' . $filename );
            if ( ! empty( $htaccess ) )
            {
                $htaccess = preg_replace( "/[\n]*[\#]+[\n]+\#nukeviet\_rewrite\_start(.*)\#nukeviet\_rewrite\_end[\n]+[\#]+[\n]*/s", "\n", $htaccess );
                $htaccess = trim( $htaccess );
            }
        }
        $htaccess .= "\n\n" . $reval;
        $reval = $htaccess;
    }
    $errormess = false;
    if ( ! empty( $filename ) and ! empty( $reval ) )
    {
        $savefile = true;
        try
        {
            file_put_contents( NV_ROOTDIR . "/" . $filename, $reval, LOCK_EX );
            if ( ! file_exists( NV_ROOTDIR . "/" . $filename ) or filesize( NV_ROOTDIR . "/" . $filename ) == 0 )
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
            $errormess .= sprintf( $lang_module['err_writable'], NV_BASE_SITEURL . $filename );
        }
    }
    return $errormess;
}

$rewrite = array();
if ( $global_config['rewrite_optional'] && $global_config['is_url_rewrite'] )
{
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\3/\\4/\\5";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\3/\\4";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)([\"|\'])#"] = "\\1\\3";
    
    $rewrite["#([\"|\'|\>]" . $global_config['site_url'] . "/" . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'|\<])#"] = "\\1\\3/\\4/\\5\\6";
}
else
{
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\2/\\3/\\4/\\5";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\2/\\3/\\4";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)([\"|\'])#"] = "\\1\\2/\\3";
    
    $rewrite["#([\"|\'|\>]" . $global_config['site_url'] . "/" . ")index.php*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'|\<])#"] = "\\1\\2/\\3/\\4/\\5\\6";
}

?>