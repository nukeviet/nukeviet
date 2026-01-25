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

use NukeViet\Module\news\Shared\Emails;
use NukeViet\Template\Email\Emf;

$module_emails[Emails::SENDMAIL] = [
    'pids' => Emf::P_ALL,
    't' => 'Envoyer un e-mail présentant l\'article à un ami dans le module d\'actualités',
    's' => 'Message de {$from_name}',
    'c' => 'Bonjour!<br />Votre ami {$from_name} aimerait vous présenter l\'article “{$post_name}” sur le site {$site_name}{if not empty($message)} avec le message:<br />{$message}{/if}.<br/>----------<br/><strong>{$post_name}</strong><br/>{$hometext}<br/><br/>Vous pouvez consulter l\'intégralité de l\'article en cliquant sur le lien ci-dessous:<br /><a href="{$link}" title="{$post_name}">{$link}</a>'
];
$module_emails[Emails::REPORT_THANKS] = [
    'pids' => Emf::P_ALL,
    't' => 'Email remerciant la personne qui a signalé l\'erreur sur les actualités du module',
    's' => 'Merci d\'avoir soumis un rapport d\'erreur',
    'c' => 'Bonjour!<br />L\'administration du site Web de {$site_name} vous remercie beaucoup d\'avoir soumis un rapport d\'erreur dans le contenu de l\'article de notre site Web. Nous avons corrigé l\'erreur que vous avez signalée.<br />Nous espérons recevoir votre prochaine aide à l\'avenir. Je vous souhaite toujours la santé, le bonheur et le succès!'
];
