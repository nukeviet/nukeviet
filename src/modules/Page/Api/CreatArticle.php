<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jun 20, 2010 8:59:32 PM
 */

namespace NukeViet\Module\Page\Api;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;
use PDO;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

class CreatArticle implements IApi
{
    private $result;

    /**
     * @return number
     */
    public static function getAdminLev()
    {
        return Api::ADMIN_LEV_MOD;
    }

    /**
     * @return string
     */
    public static function getCat()
    {
        return '';
    }

    /**
     * {@inheritDoc}
     * @see \NukeViet\Api\IApi::setResultHander()
     */
    public function setResultHander(ApiResult $result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritDoc}
     * @see \NukeViet\Api\IApi::execute()
     */
    public function execute()
    {
        global $nv_Lang, $nv_Request, $db, $nv_Cache;

        $module_name = Api::getModuleName();
        $module_info = Api::getModuleInfo();
        $module_data = $module_info['module_data'];
        $admin_id = Api::getAdminId();

        // Get Config Module
        $page_config = [];
        $sql = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            $page_config[$row['config_name']] = $row['config_value'];
        }

        $row = [];
        $row['title'] = nv_substr($nv_Request->get_title('title', 'post', ''), 0, 250);
        $row['alias'] = nv_substr($nv_Request->get_title('alias', 'post', ''), 0, 250);
        $row['description'] = $nv_Request->get_textarea('description', '', 'br', 1);
        $row['bodytext'] = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
        $row['keywords'] = nv_strtolower($nv_Request->get_title('keywords', 'post', '', 0));
        $row['socialbutton'] = intval($nv_Request->get_bool('socialbutton', 'post', false));

        $row['alias'] = empty($row['alias']) ? change_alias($row['title']) : change_alias($row['alias']);
        $row['alias'] = $page_config['alias_lower'] ? strtolower($row['alias']) : $row['alias'];

        if (empty($row['title'])) {
            $this->result->setMessage($nv_Lang->getModule('empty_title'));
        } elseif ($row['bodytext'] == '') {
            $this->result->setMessage($nv_Lang->getModule('empty_bodytext'));
        } else {
            $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE alias = :alias');
            $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->fetchColumn()) {
                $weight = $db->query('SELECT MAX(id) FROM ' . NV_PREFIXLANG . '_' . $module_data)->fetchColumn();
                $weight = intval($weight) + 1;
                $row['alias'] = $row['alias'] . '-' . $weight;
            }

            if ($page_config['news_first']) {
                $weight = 1;
            } else {
                $weight = $db->query("SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data)->fetchColumn();
                $weight = intval($weight) + 1;
            }

            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . " (
                title, alias, image, imagealt, imageposition, description, bodytext, keywords, socialbutton, activecomm,
                layout_func, gid, weight,admin_id, add_time, edit_time, status, hot_post
            ) VALUES (
                :title, :alias, '', '', 0, :description, :bodytext, :keywords, :socialbutton, '',
                '', 0, " . $weight . ", " . $admin_id . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 1, 0
            )";
            $sth = $db->prepare($sql);
            $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
            $sth->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
            $sth->bindParam(':description', $row['description'], PDO::PARAM_STR);
            $sth->bindParam(':bodytext', $row['bodytext'], PDO::PARAM_STR, strlen($row['bodytext']));
            $sth->bindParam(':keywords', $row['keywords'], PDO::PARAM_STR);
            $sth->bindParam(':socialbutton', $row['socialbutton'], PDO::PARAM_INT);
            $sth->execute();
            if ($sth->rowCount()) {
                if ($page_config['news_first']) {
                    $id = $db->lastInsertId();
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=weight+1 WHERE id!=' . $id);
                }
                $nv_Cache->delMod($module_name);
                $this->result->setSuccess();
            } else {
                $this->result->setMessage($nv_Lang->getModule('errorsave'));
            }
        }

        return $this->result->getResult();
    }
}
