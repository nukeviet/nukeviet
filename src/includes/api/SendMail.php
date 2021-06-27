<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Api;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class SendMail implements IApi
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
        return 'System';
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
        global $nv_Request;

        $from_name = $nv_Request->get_title('from_name', 'post', '');
        $from_email = $nv_Request->get_title('from_email', 'post', '');
        $to_email = $nv_Request->get_title('to_email', 'post', '');
        $email_subject = $nv_Request->get_title('email_subject', 'post', '');
        $email_message = $nv_Request->get_title('email_message', 'post', '');

        if (($check = nv_check_valid_email($from_email)) != '') {
            $this->result->setMessage($check);
        } elseif (($check2 = nv_check_valid_email($to_email)) != '') {
            $this->result->setMessage($check2);
        } elseif (empty($email_subject)) {
            $this->result->setMessage('No email subject');
        } elseif (empty($email_subject)) {
            $this->result->setMessage('No message');
        } else {
            if (empty($from_name)) {
                $from = $from_email;
            } else {
                $from = [$from_name, $from_email];
            }
            $check_send = nv_sendmail($from, $to_email, $email_subject, $email_message);
            if ($check_send) {
                $this->result->setSuccess();
            } else {
                $this->result->setMessage('Can\'t send email');
            }
        }

        return $this->result->getResult();
    }
}
