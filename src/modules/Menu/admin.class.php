<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

class nv_menu
{
    private $module_data;
    private $module_name;
    private $admin_info;

    /**
     * nv_menu::__construct()
     *
     * @param mixed $module_data
     * @param mixed $module_name
     * @param mixed $admin_info
     * @return void
     */
    public function __construct($module_data, $module_name, $admin_info)
    {
        $this->module_data = $module_data;
        $this->module_name = $module_name;
        $this->admin_info = $admin_info;
    }

    /**
     * nv_menu::delRow()
     *
     * @param mixed $id
     * @param mixed $parentid
     * @return
     */
    function delRow($id, $parentid)
    {
        global $db, $admin_info;

        $sql = 'SELECT title, subitem FROM ' . NV_PREFIXLANG . '_' . $this->module_data . '_rows WHERE id=' . $id . ' AND parentid=' . $parentid;
        $row = $db->query($sql)->fetch();

        if (empty($row)) {
            return false;
        }

        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $this->module_data . '_rows WHERE id=' . $id;
        if ($db->exec($sql)) {
            // Cap nhat cho menu cha
            if ($parentid > 0) {
                $sql = 'SELECT subitem FROM ' . NV_PREFIXLANG . '_' . $this->module_data . '_rows WHERE id=' . $parentid;
                $subitem = $db->query($sql)->fetch();
                if (! empty($subitem)) {
                    $subitem = implode(',', array_diff(array_filter(array_unique(explode(',', $subitem['subitem']))), array( $id )));

                    $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $this->module_data . '_rows SET subitem= :subitem WHERE id=' . $parentid);
                    $stmt->bindParam(':subitem', $subitem, PDO::PARAM_STR, strlen($subitem));
                    $stmt->execute();
                }
            }

            $subitem = (! empty($row['subitem'])) ? explode(',', $row['subitem']) : array();
            foreach ($subitem as $id) {
                $sql = 'SELECT parentid FROM ' . NV_PREFIXLANG . '_' . $this->module_data . '_rows WHERE id=' . $id;

                list($parentid) = $db->query($sql)->fetch(3);
                $this->delRow($id, $parentid);
                nv_insert_logs(NV_LANG_DATA, $this->module_name, 'Delete menu item', 'Item ID ' . $id, $admin_info['userid']);
            }
        }
        return true;
    }

    /**
     * nv_menu::fixMenuOrder()
     *
     * @param mixed $mid
     * @param integer $parentid
     * @param integer $order
     * @param integer $lev
     * @return
     */
    function fixMenuOrder($mid, $parentid = 0, $order = 0, $lev = 0)
    {
        global $db;

        $sql = 'SELECT id, parentid FROM ' . NV_PREFIXLANG . '_' . $this->module_data . '_rows WHERE parentid=' . $parentid . ' AND mid= ' . $mid . ' ORDER BY weight ASC';
        $result = $db->query($sql);

        $array_cat_order = array();
        while ($row = $result->fetch()) {
            $array_cat_order[] = $row['id'];
        }
        $result->closeCursor();

        $weight = 0;
        if ($parentid > 0) {
            ++$lev;
        } else {
            $lev = 0;
        }

        foreach ($array_cat_order as $catid_i) {
            ++$order;
            ++$weight;
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $this->module_data . "_rows SET weight=" . $weight . ", sort=" . $order . ", lev='" . $lev . "' WHERE id=" . intval($catid_i);
            $db->query($sql);
            $order = $this->fixMenuOrder($mid, $catid_i, $order, $lev);
        }

        return $order;
    }
}
