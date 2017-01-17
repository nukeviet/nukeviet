<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1/9/2010, 3:21
 */

namespace NukeViet\Facebook;

/**
 * InstantArticles
 * 
 * @package NukeViet Facebook
 * @author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @version 4.0
 * @access public
 */
class InstantArticles
{
    const ERROR_HTML_ELEMENTS = 100;
    const ERROR_ARTICLE_NO_CONTENT = 101;
    const ERROR_HTML_ELEMENTS_ATTR = 102;
    
    const FB_ALLOW_HTML_TAGS = 'figure,figcaption,h1,h2,video,audio,source,img,iframe,ul,ol,li,aside,em,i,a,b,strong,cite,br,p,u,span,blockquote';
    
    private $lang = array();
    private $langPrefix = '';
    
    private $article = '';
    
    private $allowed_tags = array();
    private $disabledattributes = array('action', 'background', 'codebase', 'dynsrc', 'lowsrc');
    private $disablecomannds = array('base64_decode', 'cmd', 'passthru', 'eval', 'exec', 'system', 'fopen', 'fsockopen', 'file', 'file_get_contents', 'readfile', 'unlink');
    
    public function __construct($lang = array(), $langPrefix = 'fbinsartmgs_')
    {
        if (!empty($lang)) {
            $this->lang = $lang;
        }
        $this->langPrefix = $langPrefix;
        $this->allowed_tags = explode(',', self::FB_ALLOW_HTML_TAGS);
    }
    
    private function getError()
    {
        $args = func_get_args();
        if (empty($args)) {
            return null;
        }
        
        $errorNO = intval($args[0]);
        
        if (!empty($this->lang[$this->langPrefix . $errorNO])) {
            $errorMGS = $this->lang[$this->langPrefix . $errorNO];
            if (sizeof($args) > 1) {
                $args[0] = $errorMGS;
                return call_user_func_array("sprintf", $args);
            }
            return $errorMGS;
        }
        return $errorNO;
    }
    
    private function filterAttr($attrSet)
    {
        $newSet = array();
    
        for ($i = 0, $count = sizeof($attrSet); $i < $count; ++$i) {
            if (!$attrSet[$i]) {
                continue;
            }
            $attrSubSet = array_map('trim', explode('=', trim($attrSet[$i]), 2));
            $attrSubSet[0] = strtolower($attrSubSet[0]);
    
            if (!preg_match('/[a-z]+/i', $attrSubSet[0]) or in_array($attrSubSet[0], $this->disabledattributes) or preg_match('/^on/i', $attrSubSet[0])) {
                continue;
            }
    
            if (!empty($attrSubSet[1])) {
                $attrSubSet[1] = preg_replace('/[ ]+/', ' ', $attrSubSet[1]);
                $attrSubSet[1] = preg_replace("/^\"(.*)\"$/", "\\1", $attrSubSet[1]);
                $attrSubSet[1] = preg_replace("/^\'(.*)\'$/", "\\1", $attrSubSet[1]);
                $attrSubSet[1] = str_replace(array('"', '&quot;'), "'", $attrSubSet[1]);
    
                if (preg_match("/(expression|javascript|behaviour|vbscript|mocha|livescript)(\:*)/", $attrSubSet[1])) {
                    continue;
                }
    
                if (!empty($this->disablecomannds) and preg_match('#(' . implode('|', $this->disablecomannds) . ')(\s*)\((.*?)\)#si', $attrSubSet[1])) {
                    continue;
                }
    
                $value = $this->unhtmlentities($attrSubSet[1]);
                $search = array(
                    'javascript' => '/j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t/si',
                    'vbscript' => '/v\s*b\s*s\s*c\s*r\s*i\s*p\s*t/si',
                    'script' => '/s\s*c\s*r\s*i\s*p\s*t/si',
                    'applet' => '/a\s*p\s*p\s*l\s*e\s*t/si',
                    'alert' => '/a\s*l\s*e\s*r\s*t/si',
                    'document' => '/d\s*o\s*c\s*u\s*m\s*e\s*n\s*t/si',
                    'write' => '/w\s*r\s*i\s*t\s*e/si',
                    'cookie' => '/c\s*o\s*o\s*k\s*i\s*e/si',
                    'window' => '/w\s*i\s*n\s*d\s*o\s*w/si');
                $value = preg_replace(array_values($search), array_keys($search), $value);
    
                if (preg_match("/(expression|javascript|behaviour|vbscript|mocha|livescript)(\:*)/", $value)) {
                    continue;
                }
    
                if (!empty($this->disablecomannds) and preg_match('#(' . implode('|', $this->disablecomannds) . ')(\s*)\((.*?)\)#si', $value)) {
                    continue;
                }
    
                $attrSubSet[1] = preg_replace_callback('/\#([0-9ABCDEFabcdef]{3,6})[\;]*/', array($this, 'color_hex2rgb_callback'), $attrSubSet[1]);
            } elseif ($attrSubSet[1] !== '0') {
                $attrSubSet[1] = $attrSubSet[0];
            }
            $newSet[] = $attrSubSet[0] . '=[@{' . $attrSubSet[1] . '}@]';
        }
        return $newSet;
    }
    
    private function color_hex2rgb_callback($hex)
    {
        if (preg_match('/[^0-9ABCDEFabcdef]/', $hex[1])) {
            return $hex[0];
        }
        $color = $hex[1];
        $l = strlen($color);
        if ($l != 3 and $l != 6) {
            return $hex[0];
        }
        $l = $l / 3;
        return 'rgb(' . (hexdec(substr($color, 0, 1 * $l))) . ', ' . (hexdec(substr($color, 1 * $l, 1 * $l))) . ', ' . (hexdec(substr($color, 2 * $l, 1 * $l))) . ');';
    }
    
    private function chr_hexdec_callback($m)
    {
        return chr(hexdec($m[1]));
    }
    
    private function chr_callback($m)
    {
        return chr($m[1]);
    }
    
    private function unhtmlentities($value)
    {
        $value = preg_replace("/%3A%2F%2F/", '', $value);
        $value = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $value);
        $value = preg_replace("/%u0([a-z0-9]{3})/i", "&#x\\1;", $value);
        $value = preg_replace("/%([a-z0-9]{2})/i", "&#x\\1;", $value);
        $value = str_ireplace(array( '&#x53;&#x43;&#x52;&#x49;&#x50;&#x54;', '&#x26;&#x23;&#x78;&#x36;&#x41;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x31;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x36;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x31;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x33;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x33;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x32;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x39;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x30;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x34;&#x3B;', '/*', '*/', '<!--', '-->', '<!-- -->', '&#x0A;', '&#x0D;', '&#x09;', '' ), '', $value);
        $search = '/&#[xX]0{0,8}(21|22|23|24|25|26|27|28|29|2a|2b|2d|2f|30|31|32|33|34|35|36|37|38|39|3a|3b|3d|3f|40|41|42|43|44|45|46|47|48|49|4a|4b|4c|4d|4e|4f|50|51|52|53|54|55|56|57|58|59|5a|5b|5c|5d|5e|5f|60|61|62|63|64|65|66|67|68|69|6a|6b|6c|6d|6e|6f|70|71|72|73|74|75|76|77|78|79|7a|7b|7c|7d|7e);?/i';
        $value = preg_replace_callback($search, array( $this, 'chr_hexdec_callback' ), $value);
        $search = '/&#0{0,8}(33|34|35|36|37|38|39|40|41|42|43|45|47|48|49|50|51|52|53|54|55|56|57|58|59|61|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99|100|101|102|103|104|105|106|107|108|109|110|111|112|113|114|115|116|117|118|119|120|121|122|123|124|125|126);?/i';
        $value = preg_replace_callback($search, array( $this, 'chr_callback' ), $value);
        $search = array( '&#60', '&#060', '&#0060', '&#00060', '&#000060', '&#0000060', '&#60;', '&#060;', '&#0060;', '&#00060;', '&#000060;', '&#0000060;', '&#x3c', '&#x03c', '&#x003c', '&#x0003c', '&#x00003c', '&#x000003c', '&#x3c;', '&#x03c;', '&#x003c;', '&#x0003c;', '&#x00003c;', '&#x000003c;', '&#X3c', '&#X03c', '&#X003c', '&#X0003c', '&#X00003c', '&#X000003c', '&#X3c;', '&#X03c;', '&#X003c;', '&#X0003c;', '&#X00003c;', '&#X000003c;', '&#x3C', '&#x03C', '&#x003C', '&#x0003C', '&#x00003C', '&#x000003C', '&#x3C;', '&#x03C;', '&#x003C;', '&#x0003C;', '&#x00003C;', '&#x000003C;', '&#X3C', '&#X03C', '&#X003C', '&#X0003C', '&#X00003C', '&#X000003C', '&#X3C;', '&#X03C;', '&#X003C;', '&#X0003C;', '&#X00003C;', '&#X000003C;', '\x3c', '\x3C', '\u003c', '\u003C' );
        $value = str_ireplace($search, '<', $value);
        return $value;
    }
        
    public function setArticle($html)
    {
        $this->article = $html;
    }
    
    public function clearArticle()
    {
        $this->article = '';
    }    
    
    public function checkArticle($html = '')
    {
        if (!empty($html)) {
            $this->article = $html;
        }
        
        if (empty($this->article)) {
            return $this->getError(self::ERROR_ARTICLE_NO_CONTENT);
        }
        
        $preTag = null;
        $postTag = $this->article;
        $tagOpen_start = strpos($this->article, '<');
        
        $not_allowed_tags = array();
        $is_attr_exists = false;
        
        while ($tagOpen_start !== false) {
            $preTag .= substr($postTag, 0, $tagOpen_start); // Đoạn bài viết bên trước không có TAG
            $postTag = substr($postTag, $tagOpen_start); // Đoạn bài viết từ khi bắt đầu tag
            $fromTagOpen = substr($postTag, 1); // Đoạn bài viết bắt đầu có tag, bỏ đi ký tự mở tag <
            $tagOpen_end = strpos($fromTagOpen, '>'); // Vị trí tag kết thúc trong $fromTagOpen

            if ($tagOpen_end === false) {
                break;
            }
        
            $tagOpen_nested = strpos($fromTagOpen, '<');
     
            if (($tagOpen_nested !== false) and ($tagOpen_nested < $tagOpen_end)) {
                $preTag .= substr($postTag, 0, ($tagOpen_nested + 1));
                $postTag = substr($postTag, ($tagOpen_nested + 1));
                $tagOpen_start = strpos($postTag, '<');
                continue;
            }
        
            $tagOpen_nested = (strpos($fromTagOpen, '<') + $tagOpen_start + 1);
            $currentTag = substr($fromTagOpen, 0, $tagOpen_end);
            $tagLength = strlen($currentTag);
        
            if (!$tagOpen_end) {
                $preTag .= $postTag;
                $tagOpen_start = strpos($postTag, '<');
            }
        
            $tagLeft = $currentTag;
            $attrSet = array();
            $currentSpace = strpos($tagLeft, ' ');
        
            if (substr($currentTag, 0, 1) == '/') {
                $isCloseTag = true;
                list($tagName) = explode(' ', $currentTag);
                $tagName = strtolower(substr($tagName, 1));
            } else {
                $isCloseTag = false;
                list($tagName) = explode(' ', $currentTag);
                $tagName = strtolower($tagName);
            }
        
            if ((!preg_match('/^[a-z][a-z0-9]*$/i', $tagName)) or !in_array($tagName, $this->allowed_tags)) {
                $not_allowed_tags[] = $tagName;
                $postTag = substr($postTag, ($tagLength + 2));
                $tagOpen_start = strpos($postTag, '<');
                continue;
            }
        
            while ($currentSpace !== false) {
                $fromSpace = substr($tagLeft, ($currentSpace + 1));
                $nextSpace = strpos($fromSpace, ' ');
                $openQuotes = strpos($fromSpace, '"');
                $closeQuotes = strpos(substr($fromSpace, ($openQuotes + 1)), '"') + $openQuotes + 1;
        
                if (strpos($fromSpace, '=') !== false) {
                    if (($openQuotes !== false) and (strpos(substr($fromSpace, ($openQuotes + 1)), '"') !== false)) {
                        $attr = substr($fromSpace, 0, ($closeQuotes + 1));
                    } else {
                        $attr = substr($fromSpace, 0, $nextSpace);
                    }
                } else {
                    $attr = substr($fromSpace, 0, $nextSpace);
                }
        
                if (!$attr) {
                    $attr = $fromSpace;
                }
        
                $attrSet[] = $attr;
                $tagLeft = substr($fromSpace, strlen($attr));
                $currentSpace = strpos($tagLeft, ' ');
            }
        
            if (!$isCloseTag) {
                $preTag .= '{@[' . $tagName;
        
                if (!empty($attrSet)) {
                    foreach ($attrSet as $_attrSet1) {
                        $_attrSet1 = trim($_attrSet1);
                        $_attrSet1 = trim($_attrSet1, "/");
                        if (!empty($_attrSet1) and !preg_match("/^class[\s]*\=/i", $_attrSet1)) {
                            $is_attr_exists = true;
                        }
                    }
                    
                    $attrSet = $this->filterAttr($attrSet);
                    $preTag .= ' ' . implode(' ', $attrSet);
                }
        
                $preTag .= (strpos($fromTagOpen, '</' . $tagName)) ? ']@}' : ' /]@}';
            } else {
                $preTag .= '{@[/' . $tagName . ']@}';
            }
        
            $postTag = substr($postTag, ($tagLength + 2));
            $tagOpen_start = strpos($postTag, '<');
        }
        
        $not_allowed_tags = array_unique(array_filter($not_allowed_tags));
        
        //$preTag .= $postTag;
        //$preTag = str_replace(array("'", '"', '<', '>'), array("&#039;", "&quot;", "&lt;", "&gt;"), $preTag);
        //return trim(str_replace(array("[@{", "}@]", "{@[", "]@}"), array('"', '"', "<", '>'), $preTag));
        
        if (!empty($not_allowed_tags)) {
            return $this->getError(self::ERROR_HTML_ELEMENTS, htmlspecialchars('<' . implode('> <', $not_allowed_tags) . '>'));
        } elseif ($is_attr_exists) {
            return $this->getError(self::ERROR_HTML_ELEMENTS_ATTR);
        }
        
        return true;
    }
    
    private function procces($hard = false)
    {
        return $this->article;
    }
    
    public function preProcces()
    {
        return $this->procces(false);
    }
    
    public function hardProcces()
    {
        return $this->procces(true);
    }
}