<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

/**
 * NukeViet\Core\Optimizer
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Optimizer
{
    private $_content;
    private $_meta = [];
    private $_title = '<title></title>';
    private $_style = [];
    private $_other_style = [];
    private $_links = [];
    private $_cssLinks = [];
    private $_jsMatches = [];
    private $_htmlforFooter = '';
    private $_inlineContents = [];
    private $_inlineContentsCount = 0;
    private $base_siteurl;
    private $eol = "\r\n";
    private $is_http2 = false;
    private $resource_preload = 0;
    private $headerPreloadItems = [];

    /**
     * __construct()
     *
     * @param mixed $content
     * @param mixed $base_siteurl
     * @param bool  $is_http2
     * @param int   $resource_preload
     */
    public function __construct($content, $base_siteurl, $is_http2 = false, $resource_preload = 0)
    {
        $this->_content = $content;
        $this->base_siteurl = $base_siteurl;
        $this->is_http2 = (bool) $is_http2;
        $this->resource_preload = ($resource_preload == 1 or $resource_preload == 2) ? (int) $resource_preload : 0;
        if ($this->resource_preload === 1 and !$this->is_http2) {
            $this->resource_preload = 2;
        }
    }

    /**
     * process()
     *
     * @param bool  $jquery
     * @param array $custom_preloads
     * @return string
     */
    public function process($jquery = true, $custom_preloads = [])
    {
        $_jsSrc = [];
        $_linkHref = [];
        $_preload = '';
        $_jsAfter = '';

        // Tài nguyên tải trước
        if (!empty($custom_preloads) and $this->resource_preload) {
            if ($this->resource_preload === 1) {
                foreach ($custom_preloads as $custom_preload) {
                    if (!empty($custom_preload['as']) and !empty($custom_preload['href'])) {
                        $this->headerPreloadItems[$custom_preload['href']] = '<' . $custom_preload['href'] . '>; rel=preload; as=' . $custom_preload['as'] . (!empty($custom_preload['type']) ? '; type=' . $custom_preload['type'] : '') . (!empty($custom_preload['crossorigin']) ? '; crossorigin' : '');
                    }
                }
            } elseif ($this->resource_preload === 2) {
                foreach ($custom_preloads as $custom_preload) {
                    if (!empty($custom_preload['as']) and !empty($custom_preload['href'])) {
                        $_preload .= '<link rel="preload" as="' . $custom_preload['as'] . '" href="' . $custom_preload['href'] . '"' . (!empty($custom_preload['type']) ? ' type="' . $custom_preload['type'] . '"' : '') . (!empty($custom_preload['crossorigin']) ? ' crossorigin' : '') . '>' . $this->eol;
                    }
                }
            }
        }

        // Load Jquery-script đầu tiên nếu buffer là toàn trang
        $_isFullBuffer = str_contains($this->_content, '</body>');
        if (preg_match("/<script[^>]+src\s*=\s*[\"|']([^\"']+jquery.min.js)[\"|'][^>]*>[\s\r\n\t]*<\/script>/is", $this->_content, $matches)) {
            $this->_content = preg_replace("/<script[^>]+src\s*=\s*[\"|']([^\"']+jquery.min.js)[\"|'][^>]*>[\s\r\n\t]*<\/script>/is", '', $this->_content);
            ($_isFullBuffer and $jquery) && $this->_jsMatches[] = $matches[0];
        } else {
            ($_isFullBuffer and $jquery) && $this->_jsMatches[] = '<script src="' . ASSETS_STATIC_URL . '/js/jquery/jquery.min.js"></script>';
        }

        // Thay thế tạm thời HTML-conductions [if...]...[endif], noscript, js-inline
        $this->_content = preg_replace_callback([
            "/<\!--\[(if)([^\]]+)\].*?\[endif\]-->/is",
            '/<(noscript)([^>]*)>((?:(?!<\/noscript>).)*?)<\s*\/\s*noscript\s*>/smix',
            '/<(script)([^>]*)data\-show\=["|\']inline["|\']([^>]*)>((?:(?!<\/script>).)*?)<\s*\/\s*script\s*>/smix'
        ], [$this, 'inlineCallback'], $this->_content);

        $this->_meta['http-equiv'] = $this->_meta['name'] = $this->_meta['other'] = [];
        $this->_meta['charset'] = '';

        $regex = "/<(meta)\s([^>]*)\s*>|<(title)>\s*([^<]*)\s*<\/title>|<(link)\s([^>]*)\s*>|<(style)([^>]*)>\s*([^\<]*)\s*<\/style>|<\s*\b(script)\b[^>]*>(.*?)<\s*\/\s*script\s*>/is";
        $matches = $matches2 = [];
        if (preg_match_all($regex, $this->_content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $ele) {
                // Xác định các meta-tags
                if ($ele[1] == 'meta' and !empty($ele[2])) {
                    preg_match_all("/([a-zA-Z\-\_]+)\s*=\s*[\"|']([^\"']+)/is", $ele[2], $matches2);
                    if (!empty($matches2)) {
                        $combine = array_combine($matches2[1], $matches2[2]);
                        if (array_key_exists('http-equiv', $combine)) {
                            $this->_meta['http-equiv'][$combine['http-equiv']] = $combine['content'];
                        } elseif (array_key_exists('name', $combine)) {
                            $this->_meta['name'][$combine['name']] = $combine['content'];
                        } elseif (array_key_exists('charset', $combine)) {
                            $this->_meta['charset'] = $combine['charset'];
                        } else {
                            $this->_meta['other'][] = [$matches2[1], $matches2[2]];
                        }
                    }
                }
                // Xác định tag title
                elseif ($ele[3] == 'title' and !empty($ele[4])) {
                    $this->_title = '<title>' . $ele[4] . '</title>';
                }
                // Xác định tag link
                elseif ($ele[5] == 'link' and !empty($ele[6])) {
                    preg_match_all("/([a-zA-Z]+)\s*=\s*[\"|']([^\"']+)/is", $ele[6], $matches2);
                    $combine = array_combine($matches2[1], $matches2[2]);
                    if (isset($combine['rel']) and preg_match('/stylesheet/is', $combine['rel'])) {
                        $this->_cssLinks[] = $ele[0];
                    } else {
                        $this->_links[] = $ele[0];
                    }
                }
                // Xác định css-inline
                elseif ($ele[7] == 'style' and !empty($ele[9])) {
                    if (empty($ele[8])) {
                        $this->_style[] = $ele[9];
                    } else {
                        $this->_other_style[] = $ele[0];
                    }
                }
                // Xác định mã js
                else {
                    $this->_jsMatches[] = $ele[0];
                }
            }

            $this->_content = preg_replace($regex, '', $this->_content);
        }

        // Đưa block HTML được đánh dấu bằng <!-- START FORFOOTER -->...<!-- END FORFOOTER --> xuống dưới trang
        $htmlRegex = "/<\!--\s*START\s+FORFOOTER\s*-->(.*?)<\!--\s*END\s+FORFOOTER\s*-->/is";
        if (preg_match_all($htmlRegex, $this->_content, $htmlMatches)) {
            $this->_htmlforFooter = implode($this->eol, $htmlMatches[1]);
            $this->_content = preg_replace($htmlRegex, '', $this->_content);
        }

        // Trả về nội dung của các js-inline hoặc HTML-conductions [if...]...[endif], <noscript>...</noscript>
        if (!empty($this->_inlineContents)) {
            $this->_content = preg_replace(array_keys($this->_inlineContents), array_values($this->_inlineContents), $this->_content);
        }

        $meta = [];
        if (!empty($this->_meta['name'])) {
            foreach ($this->_meta['name'] as $value => $content) {
                $meta[] = '<meta name="' . $value . '" content="' . $content . '">';
            }
        }

        if (!empty($this->_meta['charset'])) {
            $meta[] = '<meta charset="' . $this->_meta['charset'] . '">';
        }
        if (!empty($this->_meta['http-equiv'])) {
            foreach ($this->_meta['http-equiv'] as $value => $content) {
                $meta[] = '<meta http-equiv="' . $value . '" content="' . $content . '">';
            }
        }
        if (!empty($this->_meta['other'])) {
            foreach ($this->_meta['other'] as $row) {
                $meta[] = '<meta ' . $row[0][0] . '="' . $row[1][0] . '" ' . $row[0][1] . '="' . $row[1][1] . '">';
            }
        }

        if (!empty($this->_jsMatches)) {
            foreach ($this->_jsMatches as $value) {
                if (preg_match("/<\s*\bscript\b[^>]+src\s*=\s*[\"|']\s*([^\"']+)\s*[\"|'][^>]*>[\s\r\n\t]*<\s*\/\s*script\s*>/is", $value, $matches2)) {
                    // Chi cho phep ket noi 1 lan doi voi 1 file JS
                    if (!in_array($matches2[1], $_jsSrc, true)) {
                        $_jsSrc[] = $matches2[1];
                        $matches3 = $matches4 = [];
                        $crossorigin = preg_match("/crossorigin\s*=\s*[\"|']([^\"']+)[\"|']/is", $value, $matches3) ? $matches3[1] : '';
                        $integrity = preg_match("/integrity\s*=\s*[\"|']([^\"']+)[\"|']/is", $value, $matches4) ? $matches4[1] : '';
                        if ($this->resource_preload === 1) {
                            $this->headerPreloadItems[$matches2[1]] = '<' . $matches2[1] . '>; rel=preload; as=script' . (!empty($crossorigin) ? '; crossorigin=' . $crossorigin : '') . (!empty($integrity) ? '; integrity=' . $integrity : '');
                        } elseif ($this->resource_preload === 2) {
                            $_preload .= '<link rel="preload" as="script" href="' . $matches2[1] . '" type="text/javascript"' . (!empty($crossorigin) ? ' crossorigin="' . $crossorigin . '"' : '') . (!empty($integrity) ? ' integrity="' . $integrity . '"' : '') . '>' . $this->eol;
                        }
                        $_jsAfter .= $value . $this->eol;
                    }
                } elseif (preg_match("/<\s*\bscript\b([^>]*)>[\s\r\n\t]*(.+)[\s\r\n\t]*<\s*\/\s*script\s*>/is", $value, $matches2)) {
                    if (empty($matches2[1]) or !preg_match("/^([^\W]*)$/is", $matches2[1])) {
                        $_jsAfter .= $value . $this->eol;
                    }
                }
            }
        }

        if (!empty($this->_cssLinks)) {
            foreach ($this->_cssLinks as $value) {
                if (preg_match("/(<\s*\blink\b[^>]+)href\s*=\s*([\"|'])\s*([^\"']+)\s*([\"|'][^>]*>)/is", $value, $matches2)) {
                    // Chi cho phep ket noi 1 lan doi voi 1 file CSS
                    if (!in_array($matches2[3], $_linkHref, true)) {
                        $_linkHref[] = $matches2[3];
                        $matches3 = $matches4 = [];
                        $crossorigin = preg_match("/crossorigin\s*=\s*[\"|']([^\"']+)[\"|']/is", $value, $matches3) ? $matches3[1] : '';
                        $integrity = preg_match("/integrity\s*=\s*[\"|']([^\"']+)[\"|']/is", $value, $matches4) ? $matches4[1] : '';
                        if ($this->resource_preload === 1) {
                            $this->headerPreloadItems[$matches2[3]] = '<' . $matches2[3] . '>; rel=preload; as=style' . (!empty($crossorigin) ? '; crossorigin=' . $crossorigin : '') . (!empty($integrity) ? '; integrity=' . $integrity : '');
                        } elseif ($this->resource_preload === 2) {
                            $_preload .= '<link rel="preload" as="style" href="' . $matches2[3] . '" type="text/css"' . (!empty($crossorigin) ? ' crossorigin="' . $crossorigin . '"' : '') . (!empty($integrity) ? ' integrity="' . $integrity . '"' : '') . '>' . $this->eol;
                        }
                    }
                }
            }
        }

        $head = '';

        if (!empty($meta)) {
            $head .= implode($this->eol, $meta) . $this->eol;
        }
        if (!empty($this->_links)) {
            $head .= implode($this->eol, $this->_links) . $this->eol;
        }

        if (!empty($_preload)) {
            $head .= $_preload;
        }

        if (!empty($this->_cssLinks)) {
            $head .= implode($this->eol, array_unique($this->_cssLinks)) . $this->eol;
        }
        if (!empty($this->_style)) {
            $head .= '<style>' . implode($this->eol, $this->_style) . '</style>' . $this->eol;
        }
        if (!empty($this->_other_style)) {
            $head .= implode($this->eol, $this->_other_style) . $this->eol;
        }

        if (str_contains($this->_content, '<head>')) {
            $head = '<head>' . $this->eol . $this->_title . $this->eol . $head;
            $this->_content = trim(preg_replace('/<head>/i', $head, $this->_content, 1));
        } else {
            $this->_content = $head . $this->_content;
        }

        if ($_isFullBuffer) {
            if (!empty($this->_htmlforFooter)) {
                $this->_content = preg_replace('/\s*<\/body>/', $this->eol . $this->_htmlforFooter . $this->eol . '</body>', $this->_content, 1);
            }
            $this->_content = preg_replace('/\s*<\/body>/', $this->eol . $_jsAfter . '</body>', $this->_content, 1);
        } else {
            if (!empty($this->_htmlforFooter)) {
                $this->_content .= $this->eol . $this->_htmlforFooter;
            }
            $this->_content = $this->_content . $this->eol . $_jsAfter;
        }

        return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $this->_content);
    }

    /**
     * headerPreload()
     *
     * @param array $headers
     */
    public function headerPreload(&$headers)
    {
        if (!empty($this->headerPreloadItems)) {
            $headers['link'] = implode(', ', $this->headerPreloadItems);
        }
    }

    /**
     * inlineCallback()
     *
     * @param array $matches
     * @return string
     */
    private function inlineCallback($matches)
    {
        $num = $this->_inlineContentsCount;
        if ($matches[1] == 'script') {
            $this->_inlineContents['/\{\|inline\_' . $num . '\|\}/'] = '<script' . rtrim($matches[2]) . $matches[3] . '>' . $matches[4] . '</script>';
        } else {
            $this->_inlineContents['/\{\|inline\_' . $num . '\|\}/'] = $matches[0];
        }
        ++$this->_inlineContentsCount;

        return '{|inline_' . $num . '|}';
    }
}
