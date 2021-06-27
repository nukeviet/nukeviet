<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
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
    public function delRow($id, $parentid)
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
                if (!empty($subitem)) {
                    $subitem = implode(',', array_diff(array_filter(array_unique(explode(',', $subitem['subitem']))), [$id]));

                    $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $this->module_data . '_rows SET subitem= :subitem WHERE id=' . $parentid);
                    $stmt->bindParam(':subitem', $subitem, PDO::PARAM_STR, strlen($subitem));
                    $stmt->execute();
                }
            }

            $subitem = (!empty($row['subitem'])) ? explode(',', $row['subitem']) : [];
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
     * @param int   $parentid
     * @param int   $order
     * @param int   $lev
     * @return
     */
    public function fixMenuOrder($mid, $parentid = 0, $order = 0, $lev = 0)
    {
        global $db;

        $sql = 'SELECT id, parentid FROM ' . NV_PREFIXLANG . '_' . $this->module_data . '_rows WHERE parentid=' . $parentid . ' AND mid= ' . $mid . ' ORDER BY weight ASC';
        $result = $db->query($sql);

        $array_cat_order = [];
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
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $this->module_data . '_rows SET weight=' . $weight . ', sort=' . $order . ", lev='" . $lev . "' WHERE id=" . (int) $catid_i;
            $db->query($sql);
            $order = $this->fixMenuOrder($mid, $catid_i, $order, $lev);
        }

        return $order;
    }
}
