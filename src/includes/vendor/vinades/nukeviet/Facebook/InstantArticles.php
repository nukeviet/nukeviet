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
    const FB_ALLOW_HTML_TAGS_IN_LIST = 'a,b,i,em,u,strong,li,span';
    
    private $lang = array();
    private $langPrefix = '';
    
    private $article = '';
    
    private $allowed_tags = array();
    private $disabledattributes = array('action', 'background', 'codebase', 'dynsrc', 'lowsrc');
    private $disablecomannds = array('base64_decode', 'cmd', 'passthru', 'eval', 'exec', 'system', 'fopen', 'fsockopen', 'file', 'file_get_contents', 'readfile', 'unlink');
    private $allowedattributes = array('href', 'src', 'class', 'data-mode', 'data-feedback', 'type');
    
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
    
            if (!in_array($attrSubSet[0], $this->allowedattributes)) {
                continue;
            }
    
            if (!empty($attrSubSet[1])) {
                $attrSubSet[1] = preg_replace('/[ ]+/', ' ', $attrSubSet[1]);
                $attrSubSet[1] = preg_replace("/^\"(.*)\"$/", "\\1", $attrSubSet[1]);
                $attrSubSet[1] = preg_replace("/^\'(.*)\'$/", "\\1", $attrSubSet[1]);
                $attrSubSet[1] = str_replace(array('"', '&quot;'), "'", $attrSubSet[1]);                
                if (($attrSubSet[0] == 'href' or $attrSubSet[0] == 'src') and preg_match("/^" . preg_quote(NV_BASE_SITEURL, "/") . "/", $attrSubSet[1])) {
                    $attrSubSet[1] = NV_MY_DOMAIN . $attrSubSet[1];
                }
            } elseif ($attrSubSet[1] !== '0') {
                $attrSubSet[1] = $attrSubSet[0];
            }
            $newSet[] = $attrSubSet[0] . '=[@{' . $attrSubSet[1] . '}@]';
        }
        return $newSet;
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
        
        if (!empty($not_allowed_tags)) {
            return $this->getError(self::ERROR_HTML_ELEMENTS, htmlspecialchars('<' . implode('> <', $not_allowed_tags) . '>'));
        }// elseif ($is_attr_exists) {
        //    return $this->getError(self::ERROR_HTML_ELEMENTS_ATTR);
        //}
        
        return true;
    }
    
    private function procces($hard = false)
    {
        $preTag = null;
        $postTag = $this->article;
        $tagOpen_start = strpos($this->article, '<');

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
        
            if ((!preg_match('/^[a-z][a-z0-9]*$/i', $tagName)) or (!in_array($tagName, $this->allowed_tags) and $hard)) {
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
                    $attrSet = $this->filterAttr($attrSet);
                    $preTag .= $attrSet ? ' ' . implode(' ', $attrSet) : '';
                }
        
                $preTag .= (strpos($fromTagOpen, '</' . $tagName)) ? ']@}' : ' /]@}';
            } else {
                $preTag .= '{@[/' . $tagName . ']@}';
            }
        
            $postTag = substr($postTag, ($tagLength + 2));
            $tagOpen_start = strpos($postTag, '<');
        }
        
        $preTag .= $postTag;
        
        $preTag = str_replace(array("'", '"', '<', '>'), array("&#039;", "&quot;", "&lt;", "&gt;"), $preTag);
        $preTag = trim(str_replace(array("[@{", "}@]", "{@[", "]@}"), array('"', '"', "<", '>'), $preTag));
        $preTag = preg_replace('/\t/', '', $preTag);
        $preTag = preg_replace("/[\r\n]+/", "\n", $preTag);
        $preTag = preg_replace("/[\n]+/", "\n", $preTag);
        
        if ($hard) {
            // Remove all tags in list
            $preTag = $this->tagListProcces($preTag, 1);
            $preTag = $this->tagListProcces($preTag, 2);
        }
        
        return $preTag;
    }
    
    private function tagListProcces($html, $type)
    {
        $chr = ($type == 1 ? 'ul' : 'ol');
        $leftHTML = '';
        $rightHTML = $html;
        
        while (1) {
            $start = strpos($rightHTML, '<' . $chr);
            
            // Không có List nữa thì kết thúc
            if ($start === false) {
                break;
            }
            
            // Dồn nội dung cho LEFT nếu chưa bắt đầu LIST
            $leftHTML .= substr($rightHTML, 0, $start);
            
            // Đánh dấu vị trí bắt đầu LIST
            $listStart = $start;
            $listEnd = $start;
            
            // Cắt bớt
            $rightHTML = substr($rightHTML, $start);
            
            $close = strpos($rightHTML, '>');
            if ($close === false) {
                break;
            }
            $listEnd += ($close + 1);
            $rightHTML = substr($rightHTML, $close + 1);
            
            $listStartClose = strpos($rightHTML, '</' . $chr . '>');
            if ($listStartClose === false) {
                break;
            }
            
            $midleHTML = substr($rightHTML, 0, $listStartClose);
            $numSubList = substr_count($midleHTML, '<' . $chr); // Số tag con
            $listEnd += $listStartClose + 5;
            
            if ($numSubList > 0) {
                $rightHTML = substr($rightHTML, $listStartClose + 5);
                $numSubList1 = $numSubList;
                $step = 0;
                while ($numSubList1 > 0) {
                    if ($step++ == 999) {
                        break;
                    }
                    $numSubList1--;
                    $close = strpos($rightHTML, '</' . $chr . '>');
                    if ($close === false) {
                        break;
                    }
                    $listEnd += $close + 5;
                    $rightHTML = substr($rightHTML, $close + 5);
                    if ($numSubList1 <= 0) {
                        $test = substr($html, $listStart, ($listEnd - $listStart));
                        if (substr_count($test, '<' . $chr) > substr_count($test, '</' . $chr)) {
                            $numSubList1++;
                        }
                    }
                }
            }
            
            $midleHTML = substr($html, $listStart, ($listEnd - $listStart));
            $midleHTML = preg_replace("/^\<" . $chr . "[^\>]*\>(.*?)\<\/" . $chr . "\>$/is", "\\1", trim($midleHTML));
            $midleHTML = preg_replace("/\<(ul|ol)[^\>]*\>/is", "</li>", $midleHTML);
            $midleHTML = preg_replace("/\<\/(ul|ol)\>[\s\n\t\r]*\<\/li\>/is", "", $midleHTML);
            $midleHTML = '<' . $chr . '>' . trim($midleHTML) . '</' . $chr . '>';

            $leftHTML .= strip_tags($midleHTML, '<' . $chr . '><' . str_replace(',', '><', self::FB_ALLOW_HTML_TAGS_IN_LIST) . '>');

            $html = substr($html, $listEnd);
            $rightHTML = $html;            
        }
        
        return $leftHTML . $html;
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