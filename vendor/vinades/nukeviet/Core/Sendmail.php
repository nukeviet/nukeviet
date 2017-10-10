<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 12 Sep 2013 04:07:53 GMT
 */

namespace NukeViet\Core;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * extends for PHPMailer
 */
class Sendmail extends PHPMailer
{

    public function __construct($config, $lang_interface)
    {
        parent::__construct();
        $this->SetLanguage($lang_interface);
        $this->CharSet = $config['site_charset'];

        $mailer_mode = strtolower($config['mailer_mode']);

        if ($mailer_mode == 'smtp') {
            $this->isSMTP();
            $this->SMTPAuth = true;
            $this->Port = $config['smtp_port'];
            $this->Host = $config['smtp_host'];
            $this->Username = $config['smtp_username'];
            $this->Password = $config['smtp_password'];

            $SMTPSecure = intval($config['smtp_ssl']);
            switch ($SMTPSecure) {
                case 1:
                    $this->SMTPSecure = 'ssl';
                    break;
                case 2:
                    $this->SMTPSecure = 'tls';
                    break;
                default:
                    $this->SMTPSecure = '';
            }
            $this->SMTPOptions = array(
            		'ssl' => array(
            				'verify_peer' => ($config['verify_peer_ssl'] == 1) ? true : false,
            				'verify_peer_name' => ($config['verify_peer_name_ssl'] == 1) ? true : false,
            				'allow_self_signed' => true
            		)
            );
        } elseif ($mailer_mode == 'sendmail') {
            $this->IsSendmail();
        } else {
            //disable_functions
            $disable_functions = (($disable_functions = ini_get('disable_functions')) != '' and $disable_functions != false) ? array_map('trim', preg_split("/[\s,]+/", $disable_functions)) : array();

            if (extension_loaded('suhosin')) {
                $disable_functions = array_merge($disable_functions, array_map('trim', preg_split("/[\s,]+/", ini_get('suhosin.executor.func.blacklist'))));
            }
            if (!in_array('mail', $disable_functions)) {
                $this->IsMail();
            } else {
                return false;
            }
        }

        $this->From = $config['site_email'];
        $this->FromName = nv_unhtmlspecialchars($config['site_name']);
    }

    /**
     * @param string $address
     * @param string $name
     */
    public function From($address, $name = '')
    {
        $this->addReplyTo($address, nv_unhtmlspecialchars($name));
    }

    /**
     * @param string $address
     * @param string $name
     */
    public function To($address, $name = '')
    {
        $this->addAddress($address, nv_unhtmlspecialchars($name));
    }

    /**
     * @param string $address
     * @param string $name
     */
    public function CC($address, $name = '')
    {
        $this->addCC($address, nv_unhtmlspecialchars($name));
    }

    /**
     * @param string $address
     * @param string $name
     */
    public function BCC($address, $name = '')
    {
        $this->addBCC($address, nv_unhtmlspecialchars($name));
    }

    /**
     * @param string $subject
     */
    public function Subject($subject)
    {
        $this->Subject = nv_unhtmlspecialchars($subject);
    }

    /**
     * @param string $message
     */
    public function Content($message)
    {
        $this->WordWrap = 120;
        $this->IsHTML(true);

        $message = nv_url_rewrite($message);
        $message = nv_change_buffer($message);
        $message = nv_unhtmlspecialchars($message);

        $this->Body = $message;
        $this->AltBody = strip_tags($message);
    }

}