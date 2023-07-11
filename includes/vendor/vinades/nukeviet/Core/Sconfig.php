<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

use DOMException;
use NukeViet\Site;

/**
 * NukeViet\Core\Sconfig
 * Class dùng để tạo các file cấu hình máy chủ
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class Sconfig
{
    private $my_domains = '';
    private $rewrite_exts = '';
    private $server_configs = [];

    public function __construct($global_configs)
    {
        $this->my_domains = self::genMyDomains($global_configs['my_domains']);
        $this->rewrite_exts = self::genRewriteExts([$global_configs['rewrite_endurl'], $global_configs['rewrite_exturl']]);

        $server_config_file = NV_ROOTDIR . '/' . NV_DATADIR . '/server_config.json';
        $server_configs = file_get_contents($server_config_file);
        $this->server_configs = json_decode($server_configs, true);
    }

    /**
     * setMyDomains()
     *
     * @param mixed $domains
     */
    public function setMyDomains($domains)
    {
        $this->my_domains = self::genMyDomains($domains);
    }

    /**
     * setRewriteExts()
     *
     * @param mixed $exts
     */
    public function setRewriteExts($exts)
    {
        $this->rewrite_exts = self::genRewriteExts($exts);
    }

    /**
     * genMyDomains()
     *
     * @param mixed $domains
     * @return string
     */
    private static function genMyDomains($domains)
    {
        $domains = array_map(function ($domain) {
            return preg_replace('/^www\./', '', $domain);
        }, $domains);
        $domains = array_unique($domains);
        $domains = array_map('preg_quote', $domains);

        return implode('|', $domains);
    }

    /**
     * genRewriteExts()
     *
     * @param mixed $exts
     * @return string
     */
    private static function genRewriteExts($exts)
    {
        $exts = array_unique($exts);
        $exts = array_map('preg_quote', $exts);

        return implode('|', $exts);
    }

    /**
     * setNginxContents()
     * Tạo nội dung khuyến cáo của file cấu hình máy chủ NGINX
     *
     * @return string
     */
    public function setNginxContents()
    {
        $t = '    ';
        $ftypes = ['js_css_files', 'image_files', 'font_files'];
        $sconfigs = $this->server_configs;
        foreach ($ftypes as $type) {
            $sconfigs[$type]['extslist'] = $this->extsList($this->server_configs[$type]['mime_types']);
        }

        $config_contents = '';
        $config_contents .= "server {\n";
        $config_contents .= $t . "#........................................\n";
        $config_contents .= "\n";

        if ($sconfigs['remove_etag']) {
            $config_contents .= $t . "#Disables automatic generation of the “ETag” response header field for static resources\n";
            $config_contents .= $t . "etag off;\n";
        }

        $config_contents .= $t . "#Disables the directory listing output\n";
        $config_contents .= $t . "autoindex off;\n";
        $config_contents .= "\n";

        if (!empty($sconfigs['site_mimetypes'])) {
            $config_contents .= $t . "#Maps file name extensions to MIME types of responses\n";
            $config_contents .= $t . "types {\n";
            foreach ($sconfigs['site_mimetypes'] as $mime => $exts) {
                $es = implode(' ', $exts);
                $config_contents .= $t . $t . $mime . ' ' . $es . ";\n";
            }
            $config_contents .= $t . "}\n";
            $config_contents .= "\n";
        }

        $texthtml = array_search('text/html', $sconfigs['compress_file_exts'], true);
        if ($texthtml !== false) {
            unset($sconfigs['compress_file_exts'][$texthtml]);
        }
        $compress_file_exts = !empty($sconfigs['compress_file_exts']) ? implode(' ', $sconfigs['compress_file_exts']) : '';

        $config_contents .= $t . "#Enables gzipping of responses\n";
        $config_contents .= $t . "gzip on;\n";
        $config_contents .= $t . "gzip_comp_level 6;\n";
        $config_contents .= $t . "gzip_disable \"msie6\";\n";
        $config_contents .= $t . "gzip_vary on;\n";
        $config_contents .= $t . "gzip_min_length 256;\n";
        if (!empty($compress_file_exts)) {
            $config_contents .= $t . 'gzip_types ' . $compress_file_exts . ";\n";
        }
        $config_contents .= "\n";

        $config_contents .= $t . "#Enables brotli compression of responses\n";
        $config_contents .= $t . "# brotli on;\n";
        $config_contents .= $t . "# brotli_comp_level 6;\n";
        if (!empty($compress_file_exts)) {
            $config_contents .= $t . '# brotli_types ' . $compress_file_exts . ";\n";
        }
        $config_contents .= "\n";

        $texthtml = array_search('text/html', $sconfigs['charset_types'], true);
        if ($texthtml !== false) {
            unset($sconfigs['charset_types'][$texthtml]);
        }
        $charset_types = !empty($sconfigs['charset_types']) ? implode(' ', $sconfigs['charset_types']) : '';

        $config_contents .= $t . "#Adds the specified charset to the \"Content-Type\" response header field\n";
        $config_contents .= $t . "charset UTF-8;\n";
        if (!empty($charset_types)) {
            $config_contents .= $t . 'charset_types ' . $charset_types . ";\n";
        }
        $config_contents .= "\n";

        if (!empty($sconfigs['error_document'])) {
            $config_contents .= $t . "#Defines the URI that will be shown for the specified errors;\n";
            foreach ($sconfigs['error_document'] as $code => $url) {
                $config_contents .= $t . 'error_page ' . $code . ' ' . NV_BASE_SITEURL . $url . ";\n";
            }
            $config_contents .= "\n";
        }

        $config_contents .= $t . "#Define special variables to add the specified fields to the response header\n";
        $config_contents .= $t . "set \$cors_origin \"\";\n";
        $config_contents .= $t . "set \$expires_value off;\n";
        $config_contents .= $t . "set \$cache_control_value \"\";\n";
        $config_contents .= $t . "set \$is_image \"\";\n";
        $config_contents .= $t . "set \$google_noarchive \"\";\n";
        $config_contents .= $t . "set \$hsts \"\";\n";
        if (!empty($sconfigs['cors_origins'])) {
            if (in_array('*', $sconfigs['cors_origins'], true)) {
                $origin_domains = '.*';
            } else {
                $origin_domains = self::contentsImplode($sconfigs['cors_origins']);
            }
            $config_contents .= $t . "if (\$http_origin ~ \"^https?://(www\.)?(" . $origin_domains . ")\$\") {\n";
            $config_contents .= $t . $t . "set \$cors_origin \$http_origin;\n";
            $config_contents .= $t . "}\n";
        }
        foreach ($ftypes as $type) {
            if (!empty($sconfigs[$type]['extslist'])) {
                $config_contents .= $t . "if (\$request_uri ~ \"\.(" . $sconfigs[$type]['extslist'] . ")(\$|\?)\") {\n";
                if (!empty($sconfigs[$type]['expires'])) {
                    $config_contents .= $t . $t . 'set $expires_value ' . $sconfigs[$type]['expires'] . ";\n";
                }
                if (!empty($sconfigs[$type]['cache_control'])) {
                    $config_contents .= $t . $t . 'set $cache_control_value "' . $sconfigs[$type]['cache_control'] . "\";\n";
                }
                if ($type == 'image_files') {
                    $config_contents .= $t . $t . "set \$is_image \"1\";\n";
                }
                $config_contents .= $t . "}\n";
            }
        }
        if ($sconfigs['not_cache_and_snippet']) {
            $config_contents .= $t . "if (\$request_uri ~ \"\.(doc|pdf|swf)(\$|\?)\") {\n";
            $config_contents .= $t . $t . "set \$google_noarchive \"noarchive, nosnippet\";\n";
            $config_contents .= $t . "}\n";
        }
        if (!empty($sconfigs['strict_transport_security'])) {
            $config_contents .= $t . "if (\$scheme = \"https\") {\n";
            $config_contents .= $t . $t . 'set $hsts "' . $sconfigs['strict_transport_security'] . "\";\n";
            $config_contents .= $t . "}\n";
        }
        $config_contents .= "\n";

        $config_contents .= $t . "expires \$expires_value;\n";
        $config_contents .= $t . "add_header Strict-Transport-Security \$hsts;\n";
        $config_contents .= $t . "add_header Cache-Control \$cache_control_value;\n";
        $config_contents .= $t . "add_header Access-Control-Allow-Origin \$cors_origin;\n";
        if (!empty($sconfigs['referrer_policy'])) {
            $config_contents .= $t . 'add_header Referrer-Policy "' . $sconfigs['referrer_policy'] . "\" always;\n";
        }
        $config_contents .= $t . "add_header X-Robots-Tag \$google_noarchive;\n";
        if (!empty($sconfigs['x_content_type_options'])) {
            $config_contents .= $t . 'add_header X-Content-Type-Options "' . $sconfigs['x_content_type_options'] . "\" always;\n";
        }
        if (!empty($sconfigs['x_frame_options'])) {
            $config_contents .= $t . 'add_header X-Frame-Options "' . $sconfigs['x_frame_options'] . "\" always;\n";
        }
        if (!empty($sconfigs['x_xss_protection'])) {
            $config_contents .= $t . 'add_header X-XSS-Protection "' . $sconfigs['x_xss_protection'] . "\" always;\n";
        }
        $config_contents .= "\n";

        if ($sconfigs['image_files']['prevent_image_hot_linking']) {
            $config_contents .= $t . "#Prevent Image Hotlinking\n";
            $config_contents .= $t . "valid_referers none blocked server_names ~^(.*\.)?(" . $this->my_domains . ");\n";
            $config_contents .= $t . "set \$hotlink_denied \$invalid_referer\$is_image;\n";
            $config_contents .= $t . "if (\$hotlink_denied = \"11\") {\n";
            $config_contents .= $t . $t . "return 404;\n";
            $config_contents .= $t . "}\n";
            $config_contents .= "\n";
        }

        $config_contents .= $t . "#Only allow access to api.php via POST method\n";
        $config_contents .= $t . "location /api.php {\n";
        $config_contents .= $t . $t . "if (\$request_method !~ \"^POST\") {\n";
        $config_contents .= $t . $t . $t . "return 404;\n";
        $config_contents .= $t . $t . "}\n";
        $config_contents .= $t . $t . "try_files /does_not_exists @php;\n";
        $config_contents .= $t . "}\n";
        $config_contents .= "\n";

        $config_contents .= $t . "location / {\n";
        $config_contents .= $t . $t . "#........................................\n";
        $config_contents .= "\n";

        if (!empty($sconfigs['deny_access'])) {
            $config_contents .= $t . $t . "#Block access to certain folders and files\n";
            foreach ($sconfigs['deny_access'] as $key => $vals) {
                if (!empty($vals)) {
                    $end = '';
                    if ($key == 'dir') {
                        $end = '/.*';
                    } elseif ($key == 'include_dirs') {
                        $end = '/.*/.*';
                    } elseif ($key == 'file') {
                        $end = '.*';
                    } elseif ($key == 'include_exec_files') {
                        $exec_files = self::contentsImplode($this->server_configs['exec_files']);
                        $end = "/.*\.(" . $exec_files . ').*';
                    }
                    if (!empty($end)) {
                        $vals = self::contentsImplode($vals);
                        $config_contents .= $t . $t . 'if ($request_uri ~ "^/(' . $vals . ')' . $end . "\$\") {\n";
                        $config_contents .= $t . $t . $t . 'return ' . $sconfigs['deny_access_code'];
                        if ($sconfigs['deny_access_code'] == '301') {
                            $config_contents .= ' ' . NV_BASE_SITEURL;
                        }
                        $config_contents .= ";\n";
                        $config_contents .= $t . $t . "}\n";
                    }
                }
            }
            $config_contents .= "\n";
        }

        $config_contents .= $t . $t . "#Creating Rewrite Rules\n";
        $config_contents .= $t . $t . "rewrite ^/robots\.txt\$ /robots.php last;\n";
        $config_contents .= $t . $t . "rewrite ^/sitemap\.xml\$ /index.php?nv=SitemapIndex last;\n";
        $config_contents .= $t . $t . "rewrite ^/sitemap\-([a-z]+)\.xml\$ /index.php?language=\$1&nv=SitemapIndex last;\n";
        $config_contents .= $t . $t . "rewrite ^/sitemap\-([a-z]+)\.([a-zA-Z0-9\-]+)\.xml\$ /index.php?language=\$1&nv=\$2&op=sitemap last;\n";
        $config_contents .= $t . $t . "rewrite ^/sitemap\-([a-z]+)\.([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-]+)\.xml\$ /index.php?language=\$1&nv=\$2&op=sitemap/\$3 last;\n";
        $config_contents .= $t . $t . "if (!-e \$request_filename) {\n";
        $config_contents .= $t . $t . $t . 'rewrite ^/(.*)(' . $this->rewrite_exts . ")\$ /index.php;\n";
        $config_contents .= $t . $t . "}\n";
        $config_contents .= $t . $t . "rewrite ^/(.*)tag\/([^?]+)\$ /index.php;\n";
        $config_contents .= $t . $t . "rewrite ^/([a-zA-Z0-9\-\/]+)\/([a-zA-Z0-9\-]+)\$ /\$1/\$2/ redirect;\n";
        $config_contents .= $t . $t . "rewrite ^/([a-zA-Z0-9\-]+)\$ /\$1/ redirect;\n";
        $config_contents .= "\n";

        $config_contents .= $t . $t . "#Redirecting php files to location @php\n";
        $config_contents .= $t . $t . "location ~ [^/]\.php\$ {\n";
        $config_contents .= $t . $t . $t . "try_files /does_not_exists @php;\n";
        $config_contents .= $t . $t . "}\n";
        $config_contents .= "\n";

        $config_contents .= $t . "}\n";
        $config_contents .= "\n";

        $config_contents .= $t . "#Location @php - where to specifically handle php files\n";
        $config_contents .= $t . "location @php {\n";
        $config_contents .= $t . $t . "#........................................\n";
        $config_contents .= "\n";
        $config_contents .= $t . $t . "fastcgi_hide_header X-Powered-By;# If you use NGINX with FastCGI\n";
        $config_contents .= $t . $t . "# proxy_hide_header X-Powered-By;# If you use Nginx Reverse Proxy\n";
        $config_contents .= $t . $t . "add_header Strict-Transport-Security \$hsts;\n";
        $config_contents .= $t . "}\n";

        $config_contents .= "}\n";

        return $config_contents;
    }

    /**
     * setIisContents()
     * Tạo nội dung khuyến cáo của file cấu hình máy chủ Iis7
     *
     * @return false|string
     * @throws DOMException
     */
    public function setIisContents()
    {
        $config_contents = $this->setIisConfigs();
        $rewrite_contents = $this->setIisRewrite(true);
        $outboundRules = $this->setIisOutboundRules();

        $doc = new \DOMDocument();
        $doc->preserveWhiteSpace = false;
        if ($doc->loadXML('<configuration><system.webServer/></configuration>') === false) {
            return false;
        }

        $xpath = new \DOMXPath($doc);
        $xmlnodes = $xpath->query('/configuration/system.webServer');
        $system_webServer_node = $xmlnodes->item(0);

        if (!empty($config_contents)) {
            $config_fragment = $doc->createDocumentFragment();
            $config_fragment->appendXML($config_contents);
            $system_webServer_node->appendChild($config_fragment);
        }

        if (!empty($rewrite_contents) or !empty($outboundRules)) {
            $rewrite_node = $doc->createElement('rewrite');

            $rules_node = $doc->createElement('rules');
            $rule_fragment = $doc->createDocumentFragment();
            $rule_fragment->appendXML($rewrite_contents);
            $rules_node->appendChild($rule_fragment);
            $rewrite_node->appendChild($rules_node);

            $outboundRules_node = $doc->createElement('outboundRules');
            $rule_fragment2 = $doc->createDocumentFragment();
            $rule_fragment2->appendXML($outboundRules);
            $outboundRules_node->appendChild($rule_fragment2);
            $rewrite_node->appendChild($outboundRules_node);

            $system_webServer_node->appendChild($rewrite_node);
        }

        $doc->formatOutput = true;

        return $doc->saveXML();
    }

    /**
     * setApacheContents()
     * Tạo nội dung khuyến cáo của file cấu hình máy chủ APACHE
     *
     * @return string
     */
    public function setApacheContents()
    {
        $contents = "#nukeviet\n\n";
        $contents .= $this->setApacheConfigs() . "\n";
        $contents .= $this->setApacheRewrite();

        return $contents;
    }

    /**
     * iisRewriteRule()
     * Chuyển phần Rewrite trong file cấu hình (web.config) về mặc định
     *
     * @return false|string
     * @throws DOMException
     */
    public function iisRewriteRule()
    {
        if (!Site::class_exists('DOMDocument')) {
            return false;
        }

        $rewrite_rule = $this->setIisRewrite();

        $filename = NV_ROOTDIR . '/web.config';
        $webconfig = file_exists($filename) ? @file_get_contents($filename) : '';
        $partten = "/[\n\s]*\<\!\-\-\s*NUKEVIET\_REWRITE\_START\s*\-\-\>(.*)\<\!\-\-\s*NUKEVIET\_REWRITE\_END\s*\-\-\>[\n\s]*/si";
        if (preg_match($partten, $webconfig)) {
            $webconfig = preg_replace($partten, $rewrite_rule, $webconfig, 1);
            $doc = new \DOMDocument();
            $doc->preserveWhiteSpace = false;
            if ($doc->loadXML($webconfig) === false) {
                return false;
            }
            $doc->formatOutput = true;

            return $doc->saveXML();
        }
        empty($webconfig) && $webconfig = '<configuration><system.webServer/></configuration>';
        $doc = new \DOMDocument();
        $doc->preserveWhiteSpace = false;
        if ($doc->loadXML($webconfig) === false) {
            return false;
        }

        if (!empty($rewrite_rule)) {
            $rule_fragment = $doc->createDocumentFragment();
            $rule_fragment->appendXML($rewrite_rule);

            $xpath = new \DOMXPath($doc);

            $xmlnodes = $xpath->query('/configuration/system.webServer/rewrite/rules');
            if ($xmlnodes->length > 0) {
                $rules_node = $xmlnodes->item(0);
                $rules_node->appendChild($rule_fragment);
            } else {
                $rules_node = $doc->createElement('rules');
                $rules_node->appendChild($rule_fragment);

                $xmlnodes = $xpath->query('/configuration/system.webServer/rewrite');
                if ($xmlnodes->length > 0) {
                    $rewrite_node = $xmlnodes->item(0);
                    $rewrite_node->appendChild($rules_node);
                } else {
                    $rewrite_node = $doc->createElement('rewrite');
                    $rewrite_node->appendChild($rules_node);

                    $xmlnodes = $xpath->query('/configuration/system.webServer');
                    if ($xmlnodes->length > 0) {
                        $system_webServer_node = $xmlnodes->item(0);
                        $system_webServer_node->appendChild($rewrite_node);
                    } else {
                        $system_webServer_node = $doc->createElement('system.webServer');
                        $system_webServer_node->appendChild($rewrite_node);

                        $xmlnodes = $xpath->query('/configuration');
                        if ($xmlnodes->length > 0) {
                            $config_node = $xmlnodes->item(0);
                            $config_node->appendChild($system_webServer_node);
                        } else {
                            $config_node = $doc->createElement('configuration');
                            $doc->appendChild($config_node);
                            $config_node->appendChild($system_webServer_node);
                        }
                    }
                }
            }
        }

        $doc->formatOutput = true;

        return $doc->saveXML();
    }

    /**
     * apacheRewriteRule()
     * Chuyển phần Rewrite trong file cấu hình (.htaccess) về mặc định
     *
     * @return false|string
     */
    public function apacheRewriteRule()
    {
        $rewrite_rule = $this->setApacheRewrite();
        $rewrite_rule = str_replace('$', '\$', $rewrite_rule);

        $filename = NV_ROOTDIR . '/.htaccess';

        $htaccess = '';
        $config_rule_exists = false;
        if (file_exists($filename)) {
            $htaccess = @file_get_contents($filename);
            if (!empty($htaccess)) {
                $partten = "/[\n\s]*[\#]+[\n\s]+\#NUKEVIET\_REWRITE\_START(.*)\#NUKEVIET\_REWRITE\_END[\n\s]+[\#]+[\n\s]*/si";
                if (preg_match($partten, $htaccess)) {
                    $htaccess = preg_replace($partten, "\n\n" . $rewrite_rule . "\n", $htaccess);
                    $config_rule_exists = true;
                }
                $htaccess = trim($htaccess);
            }
        }
        if (!$config_rule_exists) {
            $htaccess .= "\n\n" . $rewrite_rule;
        }

        return $htaccess;
    }

    /**
     * setIisRewrite()
     * Tạo phần Rewrite khuyến cáo cho file cấu hình (web.config)
     *
     * @param bool $full
     * @return string
     */
    public function setIisRewrite($full = false)
    {
        $rulename = 0;
        $rewrite_rule = '';

        if ($full) {
            foreach ($this->server_configs['deny_access'] as $key => $vals) {
                if (!empty($vals)) {
                    $vals = self::contentsImplode($vals);

                    if ($this->server_configs['deny_access_code'] == 301) {
                        $action = '<action type="Redirect" redirectType="Permanent" url="' . NV_BASE_SITEURL . '" />';
                    } elseif ($this->server_configs['deny_access_code'] == 404) {
                        $action = '<action type="CustomResponse" statusCode="404" statusReason="Not Found" statusDescription="Not Found" />';
                    } else {
                        $action = '<action type="CustomResponse" statusCode="403" statusReason="Forbidden" statusDescription="Forbidden" />';
                    }

                    if ($key == 'file') {
                        $rewrite_rule .= '<rule name="blockAccessToFiles" stopProcessing="true"><match url="^(' . $vals . ')" /><conditions><add input="{DOCUMENT_ROOT}\{R:1}" matchType="IsFile" /></conditions>' . $action . '</rule>';
                    } elseif ($key == 'dir') {
                        $rewrite_rule .= '<rule name="blockAccessToDirs" stopProcessing="true"><match url="^(' . $vals . ')" /><conditions><add input="{DOCUMENT_ROOT}\{R:1}" matchType="IsDirectory" /></conditions>' . $action . '</rule>';
                    } elseif ($key == 'include_dirs') {
                        $rewrite_rule .= '<rule name="blockAccessToSubDirs" stopProcessing="true"><match url="^((' . $vals . ')/.+)" /><conditions><add input="{DOCUMENT_ROOT}\{R:1}" matchType="IsDirectory" /></conditions>' . $action . '</rule>';
                    } elseif ($key == 'include_exec_files') {
                        if (!empty($this->server_configs['exec_files'])) {
                            $exec_files = self::contentsImplode($this->server_configs['exec_files']);
                            $rewrite_rule .= '<rule name="blockAccessToSubExecFiles" stopProcessing="true"><match url="^((' . $vals . ')/.*\.(' . $exec_files . '))" /><conditions><add input="{DOCUMENT_ROOT}\{R:1}" matchType="IsFile" /></conditions>' . $action . '</rule>';
                        }
                    }
                }
            }

            if (!empty($this->server_configs['image_files']['mime_types']) and $this->server_configs['image_files']['prevent_image_hot_linking']) {
                $els = [];
                foreach ($this->server_configs['image_files']['mime_types'] as $mime) {
                    foreach ($this->server_configs['site_mimetypes'][$mime] as $ext) {
                        if (!in_array($ext, $els, true)) {
                            $els[] = $ext;
                        }
                    }
                }
                sort($els);
                $els = array_map('preg_quote', $els);
                $els = implode('|', $els);

                $rewrite_rule .= '<rule name="Prevent image hotlinking"><match url=".*\.(' . $els . ')$" ignoreCase="false" /><conditions><add input="{HTTP_REFERER}" pattern="^$" ignoreCase="false" negate="true" /><add input="{HTTP_REFERER}" pattern="^http(s)?://(www\.)?(' . $this->my_domains . ')" ignoreCase="false" negate="true" /></conditions><action type="CustomResponse" statusCode="403" statusReason="Forbidden" statusDescription="Forbidden" /></rule>';
            }
        }

        $rewrite_rule .= '<!-- NUKEVIET_REWRITE_START -->';
        $rewrite_rule .= '<!-- Please do not change the contents from the next line to the line "nukeviet_rewrite_end" -->';
        $rewrite_rule .= '<rule name="nv_rule_' . ++$rulename . '">';
        $rewrite_rule .= "<match url=\"^api\.php(.*?)$\" ignoreCase=\"false\" />";
        $rewrite_rule .= '<conditions logicalGrouping="MatchAll">';
        $rewrite_rule .= '<add input="{REQUEST_METHOD}" pattern="^POST$" ignoreCase="false" negate="true" />';
        $rewrite_rule .= '</conditions>';
        $rewrite_rule .= '<action type="CustomResponse" statusCode="403" statusReason="Forbidden" statusDescription="Forbidden" />';
        $rewrite_rule .= '</rule>';

        $rewrite_rule .= '<rule name="nv_rule_' . ++$rulename . '">';
        $rewrite_rule .= '<match url="^" ignoreCase="false" />';
        $rewrite_rule .= '<conditions>';
        $rewrite_rule .= '<add input="{REQUEST_FILENAME}" pattern="/robots.txt$" />';
        $rewrite_rule .= '</conditions>';
        $rewrite_rule .= '<action type="Rewrite" url="robots.php" appendQueryString="false" />';
        $rewrite_rule .= '</rule>';

        $rewrite_rule .= '<rule name="nv_rule_' . ++$rulename . '">';
        $rewrite_rule .= "<match url=\"^(.*?)sitemap\.xml$\" ignoreCase=\"false\" />";
        $rewrite_rule .= '<action type="Rewrite" url="index.php?' . NV_NAME_VARIABLE . '=SitemapIndex" appendQueryString="false" />';
        $rewrite_rule .= '</rule>';

        $rewrite_rule .= '<rule name="nv_rule_' . ++$rulename . '">';
        $rewrite_rule .= "<match url=\"^(.*?)sitemap\-([a-z]{2})\.xml$\" ignoreCase=\"false\" />";
        $rewrite_rule .= '<action type="Rewrite" url="index.php?' . NV_LANG_VARIABLE . '={R:2}&amp;' . NV_NAME_VARIABLE . '=SitemapIndex" appendQueryString="false" />';
        $rewrite_rule .= '</rule>';

        $rewrite_rule .= '<rule name="nv_rule_' . ++$rulename . '">';
        $rewrite_rule .= "<match url=\"^(.*?)sitemap\-([a-z]{2})\.([a-zA-Z0-9-]+)\.xml$\" ignoreCase=\"false\" />";
        $rewrite_rule .= '<action type="Rewrite" url="index.php?' . NV_LANG_VARIABLE . '={R:2}&amp;' . NV_NAME_VARIABLE . '={R:3}&amp;' . NV_OP_VARIABLE . '=sitemap" appendQueryString="false" />';
        $rewrite_rule .= '</rule>';

        $rewrite_rule .= '<rule name="nv_rule_' . ++$rulename . '">';
        $rewrite_rule .= "<match url=\"^(.*?)sitemap\-([a-z]{2})\.([a-zA-Z0-9-]+)\.([a-zA-Z0-9-]+)\.xml$\" ignoreCase=\"false\" />";
        $rewrite_rule .= '<action type="Rewrite" url="index.php?' . NV_LANG_VARIABLE . '={R:2}&amp;' . NV_NAME_VARIABLE . '={R:3}&amp;' . NV_OP_VARIABLE . '=sitemap/{R:4}" appendQueryString="false" />';
        $rewrite_rule .= '</rule>';

        $rewrite_rule .= '<rule name="nv_rule_' . ++$rulename . '">';
        $rewrite_rule .= '<match url="(.*)(' . $this->rewrite_exts . ')$" ignoreCase="false" />';
        $rewrite_rule .= '<conditions logicalGrouping="MatchAll">';
        $rewrite_rule .= '<add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />';
        $rewrite_rule .= '<add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />';
        $rewrite_rule .= '</conditions>';
        $rewrite_rule .= '<action type="Rewrite" url="index.php" />';
        $rewrite_rule .= '</rule>';

        $rewrite_rule .= '<rule name="nv_rule_' . ++$rulename . '">';
        $rewrite_rule .= "<match url=\"(.*)tag\/([^?]+)$\" ignoreCase=\"false\" />";
        $rewrite_rule .= '<action type="Rewrite" url="index.php" />';
        $rewrite_rule .= '</rule>';

        $rewrite_rule .= '<rule name="nv_rule_' . ++$rulename . '" stopProcessing="true">';
        $rewrite_rule .= "<match url=\"^([a-zA-Z0-9-\/]+)\/([a-zA-Z0-9-]+)$\" ignoreCase=\"false\" />";
        $rewrite_rule .= '<action type="Redirect" redirectType="Permanent" url="' . NV_BASE_SITEURL . '{R:1}/{R:2}/" />';
        $rewrite_rule .= '</rule>';

        $rewrite_rule .= '<rule name="nv_rule_' . ++$rulename . '" stopProcessing="true">';
        $rewrite_rule .= '<match url="^([a-zA-Z0-9-]+)$" ignoreCase="false" />';
        $rewrite_rule .= '<action type="Redirect" redirectType="Permanent" url="' . NV_BASE_SITEURL . '{R:1}/" />';
        $rewrite_rule .= '</rule>';
        $rewrite_rule .= '<!-- NUKEVIET_REWRITE_END -->';

        return $rewrite_rule;
    }

    /**
     * setApacheRewrite()
     * Tạo phần Rewrite khuyến cáo cho file cấu hình (.htaccess)
     *
     * @return string
     */
    public function setApacheRewrite()
    {
        $rewrite_rule = "##################################################################################\n";
        $rewrite_rule .= "#NUKEVIET_REWRITE_START //Please do not change the contents of the following lines\n";
        $rewrite_rule .= "##################################################################################\n\n";
        $rewrite_rule .= "#Options +FollowSymLinks\n\n";
        $rewrite_rule .= "<IfModule mod_rewrite.c>\n";
        $rewrite_rule .= "  RewriteEngine On\n";
        $rewrite_rule .= '  RewriteBase ' . NV_BASE_SITEURL . "\n";
        $rewrite_rule .= "\n";
        $rewrite_rule .= "  RewriteCond %{REQUEST_FILENAME} /api.php$ [NC]\n";
        $rewrite_rule .= "  RewriteCond %{REQUEST_METHOD} !^(POST) [NC]\n";
        $rewrite_rule .= "  RewriteRule ^.* - [F]\n";
        $rewrite_rule .= "\n";

        $rewrite_rule .= "  RewriteCond %{REQUEST_FILENAME} /robots.txt$ [NC]\n";
        $rewrite_rule .= "  RewriteRule ^.* robots.php [L]\n";
        $rewrite_rule .= "\n";

        $rewrite_rule .= "  RewriteRule ^(.*?)sitemap\.xml$ index.php?" . NV_NAME_VARIABLE . "=SitemapIndex [L]\n";
        $rewrite_rule .= "  RewriteRule ^(.*?)sitemap\-([a-z]{2})\.xml$ index.php?" . NV_LANG_VARIABLE . '=$2&' . NV_NAME_VARIABLE . "=SitemapIndex [L]\n";
        $rewrite_rule .= "  RewriteRule ^(.*?)sitemap\-([a-z]{2})\.([a-zA-Z0-9-]+)\.xml$ index.php?" . NV_LANG_VARIABLE . '=$2&' . NV_NAME_VARIABLE . '=$3&' . NV_OP_VARIABLE . "=sitemap [L]\n";
        $rewrite_rule .= "  RewriteRule ^(.*?)sitemap\-([a-z]{2})\.([a-zA-Z0-9-]+)\.([a-zA-Z0-9-]+)\.xml$ index.php?" . NV_LANG_VARIABLE . '=$2&' . NV_NAME_VARIABLE . '=$3&' . NV_OP_VARIABLE . "=sitemap/$4 [L]\n";
        $rewrite_rule .= "\n";

        // Rewrite for other module's rule
        $rewrite_rule .= "  RewriteCond %{REQUEST_FILENAME} !-f\n";
        $rewrite_rule .= "  RewriteCond %{REQUEST_FILENAME} !-d\n";
        $rewrite_rule .= '  RewriteRule (.*)(' . $this->rewrite_exts . ")\$ index.php [L]\n";
        $rewrite_rule .= "\n";

        $rewrite_rule .= "  RewriteRule (.*)tag\/([^?]+)$ index.php [L]\n";

        $rewrite_rule .= "  RewriteRule ^([a-zA-Z0-9-\/]+)\/([a-zA-Z0-9-]+)$ " . NV_BASE_SITEURL . "$1/$2/ [L,R=301]\n";
        $rewrite_rule .= '  RewriteRule ^([a-zA-Z0-9-]+)$ ' . NV_BASE_SITEURL . "$1/ [L,R=301]\n";
        $rewrite_rule .= "</IfModule>\n\n";
        $rewrite_rule .= "#NUKEVIET_REWRITE_END\n";
        $rewrite_rule .= "##################################################################################\n";

        return $rewrite_rule;
    }

    /**
     * apacheConfigs()
     * Chuyển phần Cấu hình chung trong file cấu hình (.htaccess) về mặc định
     *
     * @return false|string
     */
    public function apacheConfigs()
    {
        $config_contents = $this->setApacheConfigs();
        $config_contents = str_replace('$', '\$', $config_contents);

        $filename = NV_ROOTDIR . '/.htaccess';

        $htaccess = '';
        $config_exists = false;
        if (file_exists($filename)) {
            $htaccess = @file_get_contents($filename);
            if (!empty($htaccess)) {
                $partten = "/[\n\s]*[\#]+[\n\s]+\#NUKEVIET\_CONFIG\_START(.*)\#NUKEVIET\_CONFIG\_END[\n\s]+[\#]+[\n\s]*/si";
                if (preg_match($partten, $htaccess)) {
                    $htaccess = preg_replace($partten, "\n\n" . $config_contents . "\n", $htaccess);
                    $config_exists = true;
                }
                $htaccess = trim($htaccess);
            }
        }
        if (!$config_exists) {
            $htaccess .= "\n\n" . $config_contents;
        }

        return $htaccess;
    }

    /**
     * setIisConfigs()
     * Tạo phần Cấu hình khuyến cáo cho file cấu hình (web.config)
     *
     * @return string
     */
    public function setIisConfigs()
    {
        $config_contents = '';
        $config_contents .= '<directoryBrowse enabled="false" />';
        $config_contents .= '<defaultDocument><files><clear /><add value="index.php" /><add value="index.html" /></files></defaultDocument>';
        if (!empty($this->server_configs['error_document'])) {
            $config_contents .= '<httpErrors>';
            $remove = '';
            $error = '';
            foreach ($this->server_configs['error_document'] as $code => $val) {
                $remove .= '<remove statusCode="' . $code . '" subStatusCode="-1" />';
                $error .= '<error statusCode="' . $code . '" prefixLanguageFilePath="" path="' . NV_BASE_SITEURL . $val . '" responseMode="ExecuteURL" />';
            }
            $config_contents .= $remove . $error . '</httpErrors>';
        }

        if (!empty($this->server_configs['compress_types'])) {
            $scheme = '';
            foreach ($this->server_configs['compress_types'] as $type => $path) {
                $scheme .= '<scheme name="' . $type . '" dll="' . $path . '" />';
            }
            $httpCompression = '<clear /><add mimeType="text/*" enabled="true" />';
            if (!empty($this->server_configs['compress_file_exts'])) {
                foreach ($this->server_configs['compress_file_exts'] as $mime) {
                    $httpCompression .= '<add mimeType="' . $mime . '" enabled="true" />';
                }
            }
            $httpCompression .= '<add mimeType="*/*" enabled="false" />';

            $config_contents .= '<httpCompression minFileSizeForComp="256">' . $scheme;
            $config_contents .= '<dynamicTypes>' . $httpCompression . '</dynamicTypes><staticTypes>' . $httpCompression . '</staticTypes>';
            $config_contents .= '</httpCompression><urlCompression doStaticCompression="true" doDynamicCompression="true" dynamicCompressionBeforeCache="true" />';
        }

        $mime_types = $this->server_configs['site_mimetypes'];
        ksort($mime_types);
        $remove = '';
        $mimeMap = '';
        foreach ($mime_types as $mime => $exts) {
            $exts = array_map(function ($ext) {
                return '.' . $ext;
            }, $exts);
            foreach ($exts as $ext) {
                $mimetype = $mime;
                if (!empty($this->server_configs['charset_types']) and in_array($mime, $this->server_configs['charset_types'], true)) {
                    $mimetype .= '; charset=UTF-8';
                }
                $remove .= '<remove fileExtension="' . $ext . '" />';
                $mimeMap .= '<mimeMap fileExtension="' . $ext . '" mimeType="' . $mimetype . '" />';
            }
        }
        $staticContent = $remove . $mimeMap;

        $customHeaders = '';
        if ($this->server_configs['remove_x_powered_by']) {
            $customHeaders .= '<remove name="X-Powered-By" />';
        }
        if ($this->server_configs['remove_etag']) {
            $customHeaders .= '<remove name="ETag" />';
        }

        if (!empty($this->server_configs['strict_transport_security'])) {
            $customHeaders .= '<add name="Strict-Transport-Security" value="' . $this->server_configs['strict_transport_security'] . '" />';
        }

        if (!empty($this->server_configs['x_content_type_options'])) {
            $customHeaders .= '<add name="X-Content-Type-Options" value="' . $this->server_configs['x_content_type_options'] . '" />';
        }

        if (!empty($this->server_configs['x_frame_options'])) {
            $customHeaders .= '<add name="X-Frame-Options" value="' . $this->server_configs['x_frame_options'] . '" />';
        }

        if (!empty($this->server_configs['x_xss_protection'])) {
            $customHeaders .= '<add name="X-XSS-Protection" value="' . $this->server_configs['x_xss_protection'] . '" />';
        }

        if (!empty($this->server_configs['referrer_policy'])) {
            $customHeaders .= '<add name="Referrer-Policy" value="' . $this->server_configs['referrer_policy'] . '" />';
        }

        if (!empty($staticContent) or !empty($customHeaders)) {
            $config_contents .= '<httpProtocol>';
            !empty($staticContent) && $config_contents .= '<staticContent>' . $staticContent . '</staticContent>';
            !empty($customHeaders) && $config_contents .= '<customHeaders>' . $customHeaders . '</customHeaders>';
            $config_contents .= '</httpProtocol>';
        }

        return $config_contents;
    }

    /**
     * setApacheConfigs()
     * Tạo phần Cấu hình khuyến cáo cho file cấu hình (.htaccess)
     *
     * @return string
     */
    public function setApacheConfigs()
    {
        $config_contents = "##################################################################################\n";
        $config_contents .= "#NUKEVIET_CONFIG_START //Please do not change the contents of the following lines\n";
        $config_contents .= "##################################################################################\n\n";
        $config_contents .= "AddDefaultCharset UTF-8\n";
        $config_contents .= "DirectoryIndex index.php index.html\n";
        if ($this->server_configs['disable_server_signature']) {
            $config_contents .= "ServerSignature Off\n";
        }
        if ($this->server_configs['remove_etag']) {
            $config_contents .= "FileETag None\n";
        }
        $config_contents .= "\n";

        if (!empty($this->server_configs['deny_access'])) {
            $deny_access_contents = '';
            foreach ($this->server_configs['deny_access'] as $key => $vals) {
                if (!empty($vals)) {
                    $start = '';
                    $end = '';
                    if ($key == 'dir') {
                        $start = 'RewriteCond %{REQUEST_URI} ^/';
                        $end = '/.*';
                    } elseif ($key == 'include_dirs') {
                        $start = 'RewriteCond %{REQUEST_URI} ^/';
                        $end = '/.*/.*$';
                    } elseif ($key == 'file') {
                        $start = 'RewriteCond %{REQUEST_FILENAME} /';
                        $end = '$';
                    } elseif ($key == 'include_exec_files') {
                        $start = 'RewriteCond %{REQUEST_URI} ^/';
                        $exec_files = self::contentsImplode($this->server_configs['exec_files']);
                        $end = "/.*\.(" . $exec_files . ')($|\?|\/)';
                    }
                    if (!empty($start)) {
                        $vals = self::contentsImplode($vals);
                        $redirect = $this->server_configs['deny_access_code'] == '301' ? NV_BASE_SITEURL : '-';
                        $deny_access_contents .= '  ' . $start . '(' . $vals . ')' . $end . " [NC]\n";
                        $deny_access_contents .= '  RewriteRule ^.* ' . $redirect . ' [L,R=' . $this->server_configs['deny_access_code'] . "]\n";
                        //$deny_access_contents .= 'RedirectMatch ' . $this->server_configs['deny_access_code'] . ' ^/(' . $vals . ')' . $end . '$' . $redirect . "\n";
                    }
                }
            }
            if (!empty($deny_access_contents)) {
                $config_contents .= "<IfModule mod_rewrite.c>\n";
                $config_contents .= "  <IfModule mod_env.c>\n";
                $config_contents .= "    SetEnv HTTP_SUPPORT_REWRITE on\n";
                $config_contents .= "  </IfModule>\n\n";
                $config_contents .= "  RewriteEngine On\n";
                $config_contents .= '  RewriteBase ' . NV_BASE_SITEURL . "\n";
                $config_contents .= $deny_access_contents;
                $config_contents .= "</IfModule>\n\n";
            }
        }

        if (!empty($this->server_configs['error_document'])) {
            $error_document_contents = '';
            foreach ($this->server_configs['error_document'] as $code => $val) {
                $error_document_contents .= 'ErrorDocument ' . $code . ' ' . NV_BASE_SITEURL . $val . "\n";
            }
            if (!empty($error_document_contents)) {
                $config_contents .= $error_document_contents . "\n";
            }
        }

        $config_contents .= "<IfModule mod_mime.c>\n";

        $mime_types = $this->server_configs['site_mimetypes'];
        ksort($mime_types);
        foreach ($mime_types as $mime => $exts) {
            $exts = array_map(function ($ext) {
                return '.' . $ext;
            }, $exts);
            $exts = implode(' ', $exts);
            $config_contents .= '  AddType ' . $mime . ' ' . $exts . "\n";
        }
        $config_contents .= "\n";

        if (!empty($this->server_configs['charset_types'])) {
            $charset_types = $this->extsList($this->server_configs['charset_types'], true);
            $charset_types = array_map(function ($a) {
                return '.' . $a;
            }, $charset_types);
            $charset_types = implode(' ', $charset_types);
            $config_contents .= '  AddCharset UTF-8 ' . $charset_types . "\n";
        }
        $config_contents .= "</IfModule>\n";
        $config_contents .= "\n";

        $config_contents .= "<IfModule mod_autoindex.c>\n";
        $config_contents .= "  Options -Indexes\n";
        $config_contents .= "</IfModule>\n";
        $config_contents .= "\n";

        $mod_headers_contents = '';
        if ($this->server_configs['remove_x_powered_by']) {
            $mod_headers_contents .= "  Header unset X-Powered-By\n";
            $mod_headers_contents .= "  Header always unset X-Powered-By\n";
        }
        if ($this->server_configs['remove_etag']) {
            $mod_headers_contents .= "  Header unset ETag\n";
        }

        if (!empty($this->server_configs['strict_transport_security'])) {
            $mod_headers_contents .= '  Header set Strict-Transport-Security "' . $this->server_configs['strict_transport_security'] . "\" env=HTTPS\n";
        }

        if (!empty($this->server_configs['x_content_type_options'])) {
            $mod_headers_contents .= '  Header set X-Content-Type-Options "' . $this->server_configs['x_content_type_options'] . "\"\n";
        }

        if (!empty($this->server_configs['x_frame_options'])) {
            $mod_headers_contents .= '  Header set X-Frame-Options "' . $this->server_configs['x_frame_options'] . "\"\n";
        }

        if (!empty($this->server_configs['x_xss_protection'])) {
            $mod_headers_contents .= '  Header set X-XSS-Protection "' . $this->server_configs['x_xss_protection'] . "\"\n";
        }

        if (!empty($this->server_configs['referrer_policy'])) {
            $mod_headers_contents .= '  Header set Referrer-Policy "' . $this->server_configs['referrer_policy'] . "\"\n";
        }

        if (!empty($this->server_configs['cors_origins'])) {
            $mod_headers_contents .= !empty($mod_headers_contents) ? "\n" : '';
            $mod_headers_contents .= "  <IfModule mod_setenvif.c>\n";

            if (in_array('*', $this->server_configs['cors_origins'], true)) {
                $origin_domains = '.*';
            } else {
                $origin_domains = self::contentsImplode($this->server_configs['cors_origins']);
            }

            $mod_headers_contents .= "    SetEnvIf Origin \"https?://(www\.)?(" . $origin_domains . ")$\" cors_origins=\$0\n";
            $mod_headers_contents .= "    Header add Access-Control-Allow-Origin %{cors_origins}e env=cors_origins\n";
            $mod_headers_contents .= "    Header merge Vary Origin\n";
            $mod_headers_contents .= "  </IfModule>\n";
        }

        if (!empty($mod_headers_contents)) {
            $config_contents .= "<IfModule mod_headers.c>\n";
            $config_contents .= $mod_headers_contents;
            $config_contents .= "</IfModule>\n";
            $config_contents .= "\n";
        }

        $config_contents .= "<IfModule mod_expires.c>\n";
        $config_contents .= "  ExpiresActive on\n";
        $config_contents .= "</IfModule>\n";
        $config_contents .= "\n";

        if (!empty($this->server_configs['compress_file_exts'])) {
            $els = $this->extsList($this->server_configs['compress_file_exts']);

            $config_contents .= "<FilesMatch \"\.(" . $els . ")($|\?)\">\n";
            $config_contents .= "  <IfModule mod_brotli.c>\n";
            $config_contents .= "    SetOutputFilter BROTLI_COMPRESS;DEFLATE\n";
            $config_contents .= "  </IfModule>\n";
            $config_contents .= "\n";
            $config_contents .= "  <IfModule !mod_brotli.c>\n";
            $config_contents .= "    <IfModule mod_deflate.c>\n";
            $config_contents .= "      SetOutputFilter DEFLATE\n";
            $config_contents .= "    </IfModule>\n";
            $config_contents .= "  </IfModule>\n";
            $config_contents .= "\n";
            $config_contents .= "  <IfModule mod_headers.c>\n";
            $config_contents .= "    Header append Vary Accept-Encoding\n";
            $config_contents .= "  </IfModule>\n";
            $config_contents .= "</FilesMatch>\n";
            $config_contents .= "\n";
        }

        $this->ApacheConfigsByTypes('js_css_files', $config_contents);
        $this->ApacheConfigsByTypes('image_files', $config_contents);
        $this->ApacheConfigsByTypes('font_files', $config_contents);

        if ($this->server_configs['not_cache_and_snippet']) {
            $config_contents .= "<FilesMatch \"\.(doc|pdf|swf)$\">\n";
            $config_contents .= "  <IfModule mod_headers.c>\n";
            $config_contents .= "    Header set X-Robots-Tag \"noarchive, nosnippet\"\n";
            $config_contents .= "  </IfModule>\n";
            $config_contents .= "</FilesMatch>\n";
            $config_contents .= "\n";
        }

        $config_contents .= "#NUKEVIET_CONFIG_END\n";
        $config_contents .= "##################################################################################\n";

        return $config_contents;
    }

    /**
     * ApacheConfigsByTypes()
     * Hàm bổ trợ cho việc tạo phần Cấu hình khuyến cáo cho file cấu hình (.htaccess)
     *
     * @param mixed $type
     * @param mixed $config_contents
     */
    private function ApacheConfigsByTypes($type, &$config_contents)
    {
        if (!empty($this->server_configs[$type]['mime_types'])) {
            $els = $this->extsList($this->server_configs[$type]['mime_types']);

            $config_contents .= "<FilesMatch \"\.(" . $els . ")($|\?)\">\n";
            if ($type == 'image_files' and $this->server_configs[$type]['prevent_image_hot_linking']) {
                $config_contents .= "  <IfModule mod_setenvif.c>\n";
                $config_contents .= "    SetEnvIfNoCase Referer \"^https?://(www\.)?(" . $this->my_domains . ")\" noban=yes\n";
                $config_contents .= "    SetEnvIfNoCase Referer \"^$\" noban=yes\n";
                $config_contents .= "\n";
                $config_contents .= "    Order Allow,Deny\n";
                $config_contents .= "    Allow from env=noban\n";
                $config_contents .= "  </IfModule>\n";
                $config_contents .= "\n";
            }

            $mod_headers_contents = '';
            $this->apacheCacheControl($type, $mod_headers_contents);
            if (!empty($mod_headers_contents)) {
                $config_contents .= "  <IfModule mod_headers.c>\n";
                $config_contents .= $mod_headers_contents;
                $config_contents .= "  </IfModule>\n";
            }

            $this->apacheExpires($type, $config_contents);
            $config_contents .= "</FilesMatch>\n";
            $config_contents .= "\n";
        }
    }

    /**
     * apacheCacheControl()
     * Hàm bổ trợ cho việc tạo phần Cấu hình khuyến cáo cho file cấu hình (.htaccess)
     *
     * @param mixed $type
     * @param mixed $config_contents
     */
    private function apacheCacheControl($type, &$config_contents)
    {
        if (!empty($this->server_configs[$type]['cache_control'])) {
            $cache_control = $this->server_configs[$type]['cache_control'];
            if (str_contains($cache_control, 'public')) {
                $coefficient = 0;
                unset($matches);
                if (preg_match('/^([1-9][0-9]*)(y|M|w|d|h|m|s)$/', $this->server_configs[$type]['expires'], $matches)) {
                    switch ($matches[2]) {
                        case 'y':
                            $coefficient = (int) $matches[1] * (365 * 24 * 60 * 60);
                            break;
                        case 'M':
                            $coefficient = (int) $matches[1] * (30 * 24 * 60 * 60);
                            break;
                        case 'w':
                            $coefficient = (int) $matches[1] * (7 * 24 * 60 * 60);
                            break;
                        case 'd':
                            $coefficient = (int) $matches[1] * (24 * 60 * 60);
                            break;
                        case 'h':
                            $coefficient = (int) $matches[1] * (60 * 60);
                            break;
                        case 'm':
                            $coefficient = (int) $matches[1] * 60;
                            break;
                        default:
                            $coefficient = (int) $matches[1];
                    }
                } elseif ($this->server_configs[$type]['expires'] == 'max') {
                    $coefficient = 290304000;
                }

                if (!empty($coefficient)) {
                    if (str_contains($cache_control, 'max-age')) {
                        $cache_control = preg_replace('/max\-age\=[0-9]+/', 'max-age=' . $coefficient, $cache_control);
                    } else {
                        $cache_control = 'max-age=' . $coefficient . ', ' . $cache_control;
                    }
                }
            }
            $config_contents .= '    Header set Cache-Control "' . $cache_control . "\"\n";
        }
    }

    /**
     * apacheExpires()
     * Hàm bổ trợ cho việc tạo phần Cấu hình khuyến cáo cho file cấu hình (.htaccess)
     *
     * @param mixed $type
     * @param mixed $config_contents
     */
    private function apacheExpires($type, &$config_contents)
    {
        $coefficient = '';
        unset($matches);
        if (preg_match('/^([1-9][0-9]*)(y|M|w|d|h|m|s)$/', $this->server_configs[$type]['expires'], $matches)) {
            $num = (int) $matches[1];
            $coefficient = 'access plus ' . $num . ' ';
            switch ($matches[2]) {
                case 'y':
                    $coefficient .= $num == 1 ? 'year' : 'years';
                    break;
                case 'M':
                    $coefficient .= $num == 1 ? 'month' : 'months';
                    break;
                case 'w':
                    $coefficient .= $num == 1 ? 'week' : 'weeks';
                    break;
                case 'd':
                    $coefficient .= $num == 1 ? 'day' : 'days';
                    break;
                case 'h':
                    $coefficient .= $num == 1 ? 'hour' : 'hours';
                    break;
                case 'm':
                    $coefficient .= $num == 1 ? 'minute' : 'minutes';
                    break;
                default:
                    $coefficient .= $num == 1 ? 'second' : 'seconds';
            }
        } elseif ($this->server_configs[$type]['expires'] == 'max') {
            $coefficient = 'access plus 10 years';
        }
        if (!empty($coefficient)) {
            $config_contents .= "  <IfModule mod_expires.c>\n";
            $config_contents .= '    ExpiresDefault "' . $coefficient . "\"\n";
            $config_contents .= "  </IfModule>\n";
        }
    }

    public static function contentsImplode($contents)
    {
        $contents = array_map(function ($val) {
            unset($matches);
            if (preg_match('/^\[(.*)\]$/', $val, $matches)) {
                $matches[1] = preg_replace_callback('/\{([A-Z0-9\_]+)\}/', function ($v) {
                    if (defined($v[1])) {
                        return preg_quote(constant($v[1]));
                    }

                    return $v[0];
                }, $matches[1]);

                return $matches[1];
            }
            $val = preg_replace_callback('/\{([A-Z0-9\_]+)\}/', function ($v) {
                if (defined($v[1])) {
                    return constant($v[1]);
                }

                return $v[0];
            }, $val);

            return preg_quote($val);
        }, $contents);

        return implode('|', $contents);
    }

    private function extsList($mimeList, $is_array = false)
    {
        if (empty($mimeList)) {
            return '';
        }

        $els = [];
        foreach ($mimeList as $mime) {
            foreach ($this->server_configs['site_mimetypes'][$mime] as $ext) {
                if (!in_array($ext, $els, true)) {
                    $els[] = $ext;
                }
            }
        }
        sort($els);

        if ($is_array) {
            return $els;
        }

        $els = array_map('preg_quote', $els);

        return implode('|', $els);
    }

    private function setIisOutboundRules()
    {
        $AdjustCache = '';
        $AddCrossDomainHeader = '';
        $not_cache_and_snippet = '';
        $file_types = ['js_css_files', 'image_files', 'font_files'];
        foreach ($file_types as $type) {
            if (!empty($this->server_configs[$type]['mime_types']) and !empty($this->server_configs[$type]['cache_control'])) {
                $els = $this->extsList($this->server_configs[$type]['mime_types']);
                $mm = array_map('preg_quote', $this->server_configs[$type]['mime_types']);
                sort($mm);
                $mm = implode('|', $mm);

                $coefficient = 0;
                unset($matches);
                if (preg_match('/^([1-9][0-9]*)(y|M|w|d|h|m|s)$/', $this->server_configs[$type]['expires'], $matches)) {
                    switch ($matches[2]) {
                        case 'y':
                            $coefficient = (int) $matches[1] * (365 * 24 * 60 * 60);
                            break;
                        case 'M':
                            $coefficient = (int) $matches[1] * (30 * 24 * 60 * 60);
                            break;
                        case 'w':
                            $coefficient = (int) $matches[1] * (7 * 24 * 60 * 60);
                            break;
                        case 'd':
                            $coefficient = (int) $matches[1] * (24 * 60 * 60);
                            break;
                        case 'h':
                            $coefficient = (int) $matches[1] * (60 * 60);
                            break;
                        case 'm':
                            $coefficient = (int) $matches[1] * 60;
                            break;
                        default:
                            $coefficient = (int) $matches[1];
                    }
                } elseif ($this->server_configs[$type]['expires'] == 'max') {
                    $coefficient = 290304000;
                }
                if (!empty($coefficient)) {
                    if (str_contains($this->server_configs[$type]['cache_control'], 'max-age')) {
                        $cache_control = preg_replace('/max\-age\=[0-9]+/', 'max-age=' . $coefficient, $this->server_configs[$type]['cache_control']);
                    } else {
                        $cache_control = 'max-age=' . $coefficient . ', ' . $this->server_configs[$type]['cache_control'];
                    }
                }
                $AdjustCache .= '<rule name="AdjustCacheFor_' . $type . '"><match serverVariable="RESPONSE_Cache-Control" pattern=".*" /><conditions><add input="{REQUEST_FILENAME}" pattern="\.(' . $els . ')$" /><add input="{RESPONSE_CONTENT-TYPE}" pattern="^(' . $mm . ')" /></conditions><action type="Rewrite" value="' . $cache_control . '" /></rule>';
            }
        }

        if (!empty($this->server_configs['cors_origins'])) {
            if (in_array('*', $this->server_configs['cors_origins'], true)) {
                $origin_domains = '.*';
            } else {
                $origin_domains = self::contentsImplode($this->server_configs['cors_origins']);
            }

            $AddCrossDomainHeader .= '<rule name="AddCrossDomainHeader"><match serverVariable="RESPONSE_Access_Control_Allow_Origin" pattern=".*" /><conditions><add input="{HTTP_ORIGIN}" pattern="https?://(www\.)?(' . $origin_domains . ')$" /></conditions><action type="Rewrite" value="{C:0}" /></rule>';
        }

        if ($this->server_configs['not_cache_and_snippet']) {
            $not_cache_and_snippet = '<rule name="AddX_Robots_TagFor_doc"><match serverVariable="RESPONSE_X_Robots_Tag" pattern=".*" /><conditions><add input="{REQUEST_FILENAME}" pattern="\.(doc|pdf|swf)$" /></conditions><action type="Rewrite" value="noarchive, nosnippet"/></rule>';
        }

        return $AdjustCache . $AddCrossDomainHeader . $not_cache_and_snippet;
    }
}
