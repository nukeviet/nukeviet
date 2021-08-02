<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Xml;

use DOMDocument;

/**
 * NukeViet\Xml\Array2XML
 *
 * @package NukeViet\Xml
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Array2XML
{
    private $rootname_default = 'root';
    private $itemname_default = 'item';
    private $xml;

    /**
     * __construct()
     */
    public function __construct()
    {
    }

    /**
     * checkArray()
     *
     * @param mixed $array
     * @return bool
     */
    private function checkArray($array)
    {
        return (is_array($array) and !empty($array)) ? true : false;
    }

    /**
     * setRootName()
     *
     * @param mixed $array
     * @param mixed $rootname
     * @return mixed
     */
    private function setRootName($array, $rootname)
    {
        if (empty($rootname)) {
            $rootname = $this->rootname_default;
        }

        if (sizeof($array) > 1) {
            return $rootname;
        }
        $key = key($array);
        if (preg_match('/^[0-9](.*)$/', $key)) {
            return $rootname;
        }

        return $key;
    }

    /**
     * addArray()
     *
     * @param mixed $array
     * @param mixed $root
     * @param mixed $lastname
     */
    private function addArray($array, &$root, $lastname)
    {
        foreach ($array as $key => $val) {
            if (preg_match('/^[0-9](.*)$/', $key)) {
                $newKey = $lastname . '_' . $this->itemname_default;
            } else {
                $newKey = $key;
            }

            $node = $this->xml->createElement($newKey);

            if (is_array($val)) {
                $this->addArray($array[$key], $node, $newKey);
            } else {
                $nodeText = $this->xml->createTextNode($val);
                $node->appendChild($nodeText);
            }
            $root->appendChild($node);
        }
    }

    /**
     * createXML()
     *
     * @param mixed  $array
     * @param mixed  $rootname
     * @param string $encoding
     * @param bool   $is_save
     * @param string $file
     * @return bool|string
     */
    private function createXML($array, $rootname, $encoding = 'utf-8', $is_save = false, $file = '')
    {
        if (!$this->checkArray($array)) {
            return false;
        }

        $rootname = $this->setRootName($array, $rootname);
        $this->xml = new DOMDocument('1.0', $encoding);
        $this->xml->formatOutput = true;
        $root = $this->xml->createElement($rootname);
        $root = $this->xml->appendchild($root);

        if (sizeof($array) > 1) {
            $this->addArray($array, $root, $rootname);
        } else {
            $key = key($array);
            $this->addArray($array[$key], $root, $rootname);
        }

        if ($is_save) {
            if ($this->xml->save($file) == 0) {
                return false;
            }

            return true;
        }

        return $this->xml->saveXML();
    }

    /**
     * saveXML()
     *
     * @param mixed  $array
     * @param mixed  $rootname
     * @param mixed  $file
     * @param string $encoding
     * @return bool|string
     */
    public function saveXML($array, $rootname, $file, $encoding = '')
    {
        return $this->createXML($array, $rootname, $encoding, true, $file);
    }

    /**
     * showXML()
     *
     * @param mixed  $array
     * @param mixed  $rootname
     * @param string $encoding
     * @return bool|string
     */
    public function showXML($array, $rootname, $encoding = '')
    {
        $content = $this->createXML($array, $rootname, $encoding);

        if ($content == false) {
            return $content;
        }

        @header('Last-Modified: ' . gmdate('D, d M Y H:i:s', strtotime('-1 day')) . ' GMT');
        @header('Content-Type: text/xml; charset=' . $encoding);
        @header('Cache-Control: no-store, max-age=0');
        @header('Expires: 0');
        @header('Pragma: no-cache');
        header('Content-Encoding: none');
        exit($content);
    }
}
