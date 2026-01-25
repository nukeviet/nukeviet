<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Template\Email\Cat;
use NukeViet\Template\Email\Tpl2Step;

$module_emails[Tpl2Step::ACTIVE_2STEP] = [
    'sys_pids' => '5',
    'is_system' => 1,
    'catid' => Cat::CAT_USER,
    't' => 'Avis pour activer l\'authentification en deux étapes pour les comptes membres',
    's' => 'Avis de confidentialité',
    'c' => '{$greeting_user}<br /><br />Votre compte sur <a href="{$Home}"><strong>{$site_name}</strong></a> vient d\'activer Two-Factor Authentication. Information:<br /><br />- Temps: <strong>{$time}</strong><br />- IP: <strong>{$ip}</strong><br />- Navigateur: <strong>{$browser}</strong><br /><br />Si c\'est vous, ignorez cet email. Si ce n\'est pas vous, votre compte est très probablement volé. Veuillez contacter l\'administrateur du site pour obtenir de l\'aide'
];
$module_emails[Tpl2Step::DEACTIVATE_2STEP] = [
    'sys_pids' => '5',
    'is_system' => 1,
    'catid' => Cat::CAT_USER,
    't' => 'Avis de désactivation de l\'authentification en deux étapes pour les comptes membres',
    's' => 'Avis de confidentialité',
    'c' => '{$greeting_user}<br /><br />Votre compte sur <a href="{$Home}"><strong>{$site_name}</strong></a> vient d\'activer Two-Factor Authentication. Information:<br /><br />- Temps: <strong>{$time}</strong><br />- IP: <strong>{$ip}</strong><br />- Navigateur: <strong>{$browser}</strong><br /><br />Si c\'est vous, ignorez cet email. Si ce n\'est pas vous, veuillez vérifier vos informations personnelles à l\'adresse <a href="{$link}">{$link}</a>'
];
$module_emails[Tpl2Step::RENEW_BACKUPCODE] = [
    'sys_pids' => '5',
    'is_system' => 1,
    'catid' => Cat::CAT_USER,
    't' => 'Avis de régénération des codes de sauvegarde d\'authentification en deux étapes pour les comptes membres',
    's' => 'Avis de confidentialité',
    'c' => '{$greeting_user}<br /><br />Votre compte sur <a href="{$Home}"><strong>{$site_name}</strong></a> vient de recréer le code de sauvegarde. Information:<br /><br />- Temps: <strong>{$time}</strong><br />- IP: <strong>{$ip}</strong><br />- Navigateur: <strong>{$browser}</strong><br /><br />Si c\'est vous, ignorez cet email. Si ce n\'est pas vous, veuillez vérifier vos informations personnelles à l\'adresse <a href="{$link}">{$link}</a>'
];
