<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 27/11/2010, 22:45
 */

namespace NukeViet\Core;

class Optimizer
{
    private $_content;
    private $_conditon = array();
    private $_condCount = 0;
    private $_meta = array();
    private $_title = '<title></title>';
    private $_style = array();
    private $_links = array();
    private $_cssLinks = array();
    private $_jsMatches = array();
    private $_htmlforFooter = "";
    private $_jsCount = 0;
    private $base_siteurl;
    private $eol = "\r\n";

    /**
     *
     * @param string $content
     * @param boolean $opt_css_file
     * @param string $base_siteurl
     */
    public function __construct($content, $base_siteurl)
    {
        $this->_content = $content;
        $this->base_siteurl = $base_siteurl;
    }

    /**
     * optimizer::process()
     *
     * @return
     *
     */
    public function process($jquery = true)
    {
        $conditionRegex = "/<\!--\[if([^\]]+)\].*?\[endif\]-->/is";
        $this->_content = preg_replace_callback($conditionRegex, array($this, 'conditionCallback'), $this->_content);

        $_jsSrc = array();
        // Xác định biến này để chỉ xuất cứng jquery nếu như Buffer là toàn trang, đảm bảo không lỗi khi load ajax lại xuất tiếp jquery ra.
        $_isFullBuffer = preg_match('/\<\/body\>/', $this->_content);
        if ($_isFullBuffer and $jquery) {
            $_jsAfter = '<script src="' . $this->base_siteurl . NV_ASSETS_DIR . '/js/jquery/jquery.min.js"></script>' . $this->eol;
            $_jsSrcPreload = '<link rel="preload" as="script" href="' . $this->base_siteurl . NV_ASSETS_DIR . '/js/jquery/jquery.min.js">' . $this->eol;
        } else {
            $_jsAfter = $_jsSrcPreload = '';
        }

        if (preg_match("/<script[^>]+src\s*=\s*[\"|']([^\"']+jquery.min.js)[\"|'][^>]*>[\s\r\n\t]*<\/script>/is", $this->_content, $matches)) {
            $this->_content = preg_replace("/<script[^>]+src\s*=\s*[\"|']([^\"']+jquery.min.js)[\"|'][^>]*>[\s\r\n\t]*<\/script>/is", "", $this->_content);
            if ($jquery and $_isFullBuffer) {
                $_jsAfter = $matches[0] . $this->eol;
                $_jsSrcPreload = '<link rel="preload" as="script" href="' . $matches[1] . '">' . $this->eol;
            }
        }

        $jsRegex = "/<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>/is";
        $this->_content = preg_replace_callback($jsRegex, array($this, 'jsCallback'), $this->_content);

        $htmlRegex = "/<\!--\s*START\s+FORFOOTER\s*-->(.*?)<\!--\s*END\s+FORFOOTER\s*-->/is";
        if (preg_match_all($htmlRegex, $this->_content, $htmlMatches)) {
            $this->_htmlforFooter = implode($this->eol, $htmlMatches[1]);
            $this->_content = preg_replace($htmlRegex, "", $this->_content);
        }

        $this->_meta['http-equiv'] = $this->_meta['name'] = $this->_meta['other'] = array();
        $this->_meta['charset'] = '';

        $regex = "!<meta[^>]+>|<title>[^<]+<\/title>|<link[^>]+>|<style[^>]*>[^\<]*</style>!is";
        if (preg_match_all($regex, $this->_content, $matches)) {
            foreach ($matches[0] as $tag) {
                if (preg_match('/^<meta/', $tag)) {
                    preg_match_all("/([a-zA-Z\-\_]+)\s*=\s*[\"|']([^\"']+)/is", $tag, $matches2);
                    if (! empty($matches2)) {
                        $combine = array_combine($matches2[1], $matches2[2]);
                        if (array_key_exists('http-equiv', $combine)) {
                            $this->_meta['http-equiv'][$combine['http-equiv']] = $combine['content'];
                        } elseif (array_key_exists('name', $combine)) {
                            $this->_meta['name'][$combine['name']] = $combine['content'];
                        } elseif (array_key_exists('charset', $combine)) {
                            $this->_meta['charset'] = $combine['charset'];
                        } else {
                            $this->_meta['other'][] = array($matches2[1], $matches2[2]);
                        }
                    }
                } elseif (preg_match("/^<title>[^<]+<\/title>/is", $tag)) {
                    $this->_title = $tag;
                } elseif (preg_match("/^<style[^>]*>([^<]*)<\/style>/is", $tag, $matches2)) {
                    $this->_style[] = $matches2[1];
                } elseif (preg_match('/^<link/', $tag)) {
                    preg_match_all("/([a-zA-Z]+)\s*=\s*[\"|']([^\"']+)/is", $tag, $matches2);
                    $combine = array_combine($matches2[1], $matches2[2]);
                    if (isset($combine['rel']) and preg_match("/stylesheet/is", $combine['rel'])) {
                        $this->_cssLinks[] = $tag;
                    } else {
                        $this->_links[] = $tag;
                    }
                }
            }

            $this->_content = preg_replace($regex, '', $this->_content);
        }

        if (! empty($this->_conditon)) {
            foreach ($this->_conditon as $key => $value) {
                $this->_content = preg_replace("/\{\|condition\_" . $key . "\|\}/", $value, $this->_content);
            }
        }

        $meta = array();
        if (! empty($this->_meta['name'])) {
            foreach ($this->_meta['name'] as $value => $content) {
                $meta[] = '<meta name="' . $value . '" content="' . $content . '">';
            }
        }

        if (! empty($this->_meta['charset'])) {
            $meta[] = '<meta charset="' . $this->_meta['charset'] . '">';
        }
        if (! empty($this->_meta['http-equiv'])) {
            foreach ($this->_meta['http-equiv'] as $value => $content) {
                $meta[] = '<meta http-equiv="' . $value . '" content="' . $content . '">';
            }
        }
        if (! empty($this->_meta['other'])) {
            foreach ($this->_meta['other'] as $row) {
                $meta[] = '<meta ' . $row[0][0] . '="' . $row[1][0] . '" ' . $row[0][1] . '="' . $row[1][1] . '">';
            }
        }

        if (! empty($this->_jsMatches)) {
            foreach ($this->_jsMatches as $key => $value) {
                if (preg_match("/(<\s*\bscript\b[^>]+)src\s*=\s*([\"|'])([^\"']+)([\"|'][^>]*>[\s\r\n\t]*<\s*\/\s*script\s*>)/is", $value, $matches2)) {
                    // Chi cho phep ket noi 1 lan doi voi 1 file JS
                    $external = trim($matches2[3]);
                    if (! empty($external)) {
                        if (! in_array($external, $_jsSrc)) {
                            $_jsSrc[] = $external;
                            $_jsSrcPreload .= '<link rel="preload" as="script" href="' . $external . '">' . $this->eol;
                            $_jsAfter .= $value . $this->eol;
                            $value = '';
                        } else {
                            $value = '';
                        }
                    } else {
                        $value = '';
                    }
                } elseif (preg_match("/<\s*\bscript\b([^>]*)>(.*?)<\s*\/\s*script\s*>/is", $value, $matches2)) {
                    $internal = trim($matches2[2]);
                    if (! empty($internal) and (empty($matches2[1]) or ! preg_match("/^([^\W]*)$/is", $matches2[1]))) {
                        $_jsAfter .= $value . $this->eol;
                        $value = '';
                    } else {
                        $value = '';
                    }
                } else {
                    $value = '';
                }

                $this->_content = preg_replace("/\{\|js\_" . $key . "\|\}/", $this->eol . $value, $this->_content);
                if (! empty($this->_htmlforFooter)) {
                    $this->_htmlforFooter = preg_replace("/\{\|js\_" . $key . "\|\}/", $this->eol . $value, $this->_htmlforFooter);
                }
            }
        }

        $head = "";

        if (! empty($meta)) {
            $head .= implode($this->eol, $meta) . $this->eol;
        }
        if (! empty($this->_links)) {
            $head .= implode($this->eol, $this->_links) . $this->eol;
        }
        if (! empty($_jsSrcPreload)) {
            $head .= $_jsSrcPreload;
        }
        if (! empty($this->_cssLinks)) {
            $head .= implode($this->eol, array_unique($this->_cssLinks)) . $this->eol;
        }
        if (! empty($this->_style)) {
            $head .= '<style>' . implode($this->eol, $this->_style) . '</style>' . $this->eol;
        }

        if (preg_match('/\<head\>/', $this->_content)) {
            $head = '<head>' . $this->eol . $this->_title . $this->eol . $head;
            $this->_content = trim(preg_replace('/<head>/i', $head, $this->_content, 1));
        } else {
            $this->_content = $head . $this->_content;
        }

        if ($_isFullBuffer) {
            if (! empty($this->_htmlforFooter)) {
                $this->_content = preg_replace('/\s*<\/body>/', $this->eol . $this->_htmlforFooter . $this->eol . '</body>', $this->_content, 1);
            }
            $this->_content = preg_replace('/\s*<\/body>/', $this->eol . $_jsAfter . '</body>', $this->_content, 1);
        } else {
            if (! empty($this->_htmlforFooter)) {
                $this->_content .= $this->eol . $this->_htmlforFooter;
            }
            $this->_content = $this->_content . $this->eol . $_jsAfter;
        }
        $this->_content = str_replace("\r\n", "\n", $this->_content);
        return preg_replace("/\n([\t\n\s]+)\n/", "\n", $this->_content);
    }

    /**
     * optimizer::conditionCallback()
     *
     * @param mixed $matches
     * @return
     *
     */
    private function conditionCallback($matches)
    {
        $this->_conditon[] = $matches[0];
        $num = $this->_condCount;
        ++$this->_condCount;
        return '{|condition_' . $num . '|}';
    }

    /**
     * optimizer::jsCallback()
     *
     * @param mixed $matches
     * @return
     *
     */
    private function jsCallback($matches)
    {
        if (preg_match('/<\s*\bscript\b[^>]*data\-show\=["|\']inline["|\'][^>]*>/is', $matches[0])) {
            return $matches[0];
        } else {
            $this->_jsMatches[] = $matches[0];
            $num = $this->_jsCount;
            ++$this->_jsCount;
            return '{|js_' . $num . '|}';
        }
    }
}
