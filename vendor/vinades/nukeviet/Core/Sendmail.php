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

    private $configs = [];

    private $myFrom = [];

    private $myReply = [];

    private $logo = false;

    /**
     *
     * @param array $config
     * @param string $lang_interface
     */
    public function __construct($config, $lang_interface)
    {
        parent::__construct();
        $this->SetLanguage($lang_interface);
        $this->CharSet = $config['site_charset'];
        $this->configs = $config;

        $mailer_mode = strtolower($this->configs['mailer_mode']);

        if ($mailer_mode == 'smtp') {
            $this->isSMTP();
            $this->SMTPAuth = true;
            $this->Port = $this->configs['smtp_port'];
            $this->Host = $this->configs['smtp_host'];
            $this->Username = $this->configs['smtp_username'];
            $this->Password = $this->configs['smtp_password'];

            $SMTPSecure = intval($this->configs['smtp_ssl']);
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
            $this->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => ($this->configs['verify_peer_ssl'] == 1) ? true : false,
                    'verify_peer_name' => ($this->configs['verify_peer_name_ssl'] == 1) ? true : false,
                    'allow_self_signed' => true
                ]
            ];
        } elseif ($mailer_mode == 'sendmail') {
            $this->IsSendmail();
        } elseif ($mailer_mode == 'mail') {
            // disable_functions
            $disable_functions = (($disable_functions = ini_get('disable_functions')) != '' and $disable_functions != false) ? array_map('trim', preg_split("/[\s,]+/", $disable_functions)) : array();

            if (extension_loaded('suhosin')) {
                $disable_functions = array_merge($disable_functions, array_map('trim', preg_split("/[\s,]+/", ini_get('suhosin.executor.func.blacklist'))));
            }
            if (!in_array('mail', $disable_functions)) {
                $this->IsMail();
            } else {
                $this->Mailer = 'no';
            }
        } else {
            $this->Mailer = 'no';
        }

        $this->_setFrom();
    }

    /**
     *
     * @param string $file
     */
    public function addFile($file)
    {
        $this->addAttachment($file);
        return true;
    }

    /**
     */
    public function addLogo()
    {
        $this->logo = true;
        return true;
    }

    /**
     *
     * @param string $address
     * @param string $name
     */
    public function addFrom($address, $name = '')
    {
        $this->myFrom[$address] = $name;
        return true;
    }

    /**
     *
     * @param string $address
     * @param string $name
     */
    public function addReply($address, $name = '')
    {
        $this->myReply[$address] = $name;
    }

    /**
     *
     * @param string $address
     * @param string $name
     */
    public function addTo($address, $name = '')
    {
        return $this->addAddress($address, nv_unhtmlspecialchars($name));
    }

    /**
     *
     * @param string $address
     * @param string $name
     */
    public function addCC($address, $name = '')
    {
        return $this->addOrEnqueueAnAddress('cc', $address, nv_unhtmlspecialchars($name));
    }

    /**
     *
     * @param string $address
     * @param string $name
     */
    public function addBCC($address, $name = '')
    {
        return $this->addOrEnqueueAnAddress('bcc', $address, nv_unhtmlspecialchars($name));
    }

    /**
     *
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->Subject = $subject;
    }

    /**
     *
     * @param string $message
     */
    public function setContent($message)
    {
        $this->Body = $message;
    }

    private static function _formatBody($Body)
    {
        $Body = nv_url_rewrite($Body);
        $optimizer = new Optimizer($Body, NV_BASE_SITEURL);
        $Body = $optimizer->process(false);
        return nv_unhtmlspecialchars($Body);
    }

    /**
     */
    private function _setFrom()
    {
        $sender_mail = !empty($this->configs['sender_mail']) ? $this->configs['sender_mail'] : '';
        $sender_name = !empty($this->configs['sender_name']) ? $this->configs['sender_name'] : '';

        $force_sender = !empty($this->configs['force_sender']) && !empty($this->configs['sender_mail']);

        if (!$force_sender) {
            if ($this->Mailer == 'smtp') {
                if (empty($sender_mail)) {
                    if (filter_var($this->configs['smtp_username'], FILTER_VALIDATE_EMAIL)) {
                        $sender_mail = $this->configs['smtp_username'];
                    }
                }
            } elseif ($this->Mailer == 'sendmail') {
                if (empty($sender_mail)) {
                    if (isset($_SERVER['SERVER_ADMIN']) and !empty($_SERVER['SERVER_ADMIN']) and filter_var($_SERVER['SERVER_ADMIN'], FILTER_VALIDATE_EMAIL)) {
                        $sender_mail = $_SERVER['SERVER_ADMIN'];
                    } elseif (checkdnsrr($_SERVER['SERVER_NAME'], "MX") || checkdnsrr($_SERVER['SERVER_NAME'], "A")) {
                        $sender_mail = "webmaster@" . $_SERVER['SERVER_NAME'];
                    }
                }
            } elseif ($this->Mailer == 'mail') {
                if (empty($sender_mail)) {
                    if (($php_email = @ini_get("sendmail_from")) != "" and filter_var($php_email, FILTER_VALIDATE_EMAIL)) {
                        $sender_mail = $php_email;
                    } elseif (preg_match("/([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+/", ini_get("sendmail_path"), $matches) and filter_var($matches[0], FILTER_VALIDATE_EMAIL)) {
                        $sender_mail = $matches[0];
                    } elseif (checkdnsrr($_SERVER['SERVER_NAME'], "MX") || checkdnsrr($_SERVER['SERVER_NAME'], "A")) {
                        $sender_mail = "webmaster@" . $_SERVER['SERVER_NAME'];
                    }
                }
            }
        }

        empty($sender_mail) && $sender_mail = $this->configs['site_email'];
        empty($sender_name) && $sender_name = $this->configs['site_name'];

        $this->From = $sender_mail;
        $this->FromName = nv_unhtmlspecialchars($sender_name);
    }

    /**
     */
    private function _setReply()
    {
        $reply = [];
        if (!empty($this->configs['reply_email'])) {
            $reply[$this->configs['reply_email']] = !empty($this->configs['reply_name']) ? $this->configs['reply_name'] : '';
        }

        $force_reply = !empty($this->configs['force_reply']) and !empty($this->configs['reply_email']);

        if (!$force_reply) {
            $reply = $reply + $this->myFrom + $this->myReply;
        }

        if (!empty($reply)) {
            foreach ($reply as $reply_email => $reply_name) {
                $this->addReplyTo($reply_email, nv_unhtmlspecialchars($reply_name));
            }
        }
    }

    /**
     *
     * @return boolean
     */
    public function send()
    {
        if ($this->Mailer == 'no') {
            return false;
        } else {
            $this->_setReply();

            $this->WordWrap = 120;
            $this->IsHTML(true);

            $this->AltBody = strip_tags($this->Body);

            if (function_exists("nv_mailHTML")) {
                $this->Body = nv_mailHTML($this->Subject, $this->Body);
                $this->logo = true;
            }

            $this->Subject = nv_unhtmlspecialchars($this->Subject);
            $this->Body = self::_formatBody($this->Body);

            if ($this->logo) {
                $this->AddEmbeddedImage(NV_ROOTDIR . '/' . $this->configs['site_logo'], 'sitelogo', basename(NV_ROOTDIR . '/' . $this->configs['site_logo']));
            }

            try {
                if (!$this->preSend()) {
                    return false;
                }

                return $this->postSend();
            } catch (Exception $exc) {
                $this->mailHeader = '';
                $this->setError($exc->getMessage());
                if ($this->exceptions) {
                    throw $exc;
                }

                return false;
            }
        }
    }
}
