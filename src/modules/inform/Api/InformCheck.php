<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\inform\Api;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;
use PDO;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * NukeViet\Module\inform\Api\InformCheck
 * API dùng để lấy số thông báo chưa đọc của một user
 *
 * @package NukeViet\Module\inform\Api
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class InformCheck implements IApi
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
        return 'Get';
    }

    /**
     * {@inheritdoc}
     * @see \NukeViet\Api\IApi::setResultHander()
     */
    public function setResultHander(ApiResult $result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     * @see \NukeViet\Api\IApi::execute()
     */
    public function execute()
    {
        global $db, $nv_Request, $nv_Lang;

        $userid = $nv_Request->get_absint('userid', 'post', 0);
        if (empty($userid)) {
            return $this->result->setError()
                ->setCode('5016')
                ->setMessage($nv_Lang->getModule('please_enter_user'))
                ->getResult();
        }

        $sth = $db->prepare('SELECT group_id, in_groups FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=:userid AND active=1');
        $sth->bindValue(':userid', $userid, PDO::PARAM_INT);
        $sth->execute();
        $user = $sth->fetch();
        $sth->closeCursor();

        if (empty($user)) {
            return $this->result->setError()
                ->setCode('5017')
                ->setMessage($nv_Lang->getModule('user_not_exist'))
                ->getResult();
        }

        $array_groups = explode(',', $user['in_groups']);
        $array_groups[] = $user['group_id'];
        $array_groups = array_unique(array_filter(array_map(function ($gr) {
            return $gr >= 10 ? (int) $gr : 0;
        }, $array_groups)));

        $where = [];
        $where[] = "(mtb.receiver_grs = '' AND mtb.receiver_ids = '')";

        if (!empty($array_groups)) {
            $where[] = "(mtb.receiver_grs != '' AND (CONCAT(',', mtb.receiver_grs, ',') REGEXP :array_groups_regex))";
        }

        $where[] = "(mtb.receiver_ids != '' AND FIND_IN_SET(:userid, mtb.receiver_ids))";
        $where = '(' . implode(' OR ', $where) . ') AND (mtb.add_time <= :current_time1) AND (mtb.exp_time = 0 OR mtb.exp_time > :current_time2)';
        if (!empty($array_groups)) {
            $where .= " AND (mtb.sender_role != 'group' OR (mtb.sender_role = 'group' AND mtb.sender_group IN (" . implode(',', $array_groups) . ')))';
        } else {
            $where .= " AND (mtb.sender_role != 'group')";
        }

        $where .= ' AND mtb.id NOT IN (SELECT exc.pid FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE (exc.pid = mtb.id AND exc.userid = :userid) AND (exc.shown_time != 0 OR exc.hidden_time != 0))';
        $sql = 'SELECT COUNT(mtb.id) FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb WHERE ' . $where;
        $sth = $db->prepare($sql);
        $sth->bindValue(':userid', $userid, PDO::PARAM_INT);
        $sth->bindValue(':current_time1', NV_CURRENTTIME, PDO::PARAM_INT);
        $sth->bindValue(':current_time2', NV_CURRENTTIME, PDO::PARAM_INT);
        if (!empty($array_groups)) {
            $sth->bindValue(':array_groups_regex', ',(' . implode('|', $array_groups) . '),', PDO::PARAM_STR);
        }
        $sth->execute();
        $count = (int) $sth->fetchColumn();

        $this->result->set('count', $count);
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
