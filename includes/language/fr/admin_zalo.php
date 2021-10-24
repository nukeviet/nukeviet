<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2009-2021 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main'] = 'Zalo';
$lang_module['settings'] = 'Paramètres';
$lang_module['zalo_official_account_id'] = 'Zalo Official Account ID';
$lang_module['oa_create_note'] = '<ul><li>Si vous n\'avez pas de Zalo Official Account, veuillez <a href="%s" target="_blank">cliquer ici</a> pour le créer.</li><li>Puis <a href="%s" target="_blank">visitez ici</a> pour obtenir OAID</li></ul>';
$lang_module['app_id'] = 'ID de l\'application';
$lang_module['app_secret_key'] = 'Clé secrète de l\'application';
$lang_module['app_note'] = '<ul><li>Si vous n\'avez pas d\'application, vous pouvez la <a href="%s" target="_blank">créer ici</a>.</li><li>Accédez à la page <a href="%s" target="_blank">Gérer les applications</a>, cliquez sur l\'application souhaitée pour accéder à la page des paramètres, copiez l\'ID de l\'application et la clé secrète de l\'application dans les 2 cases correspondantes sur le côté (Remarque : si l\'application est dans un état inactif, vous devez cliquer sur le commutateur dans le coin supérieur droit pour basculer l\'application dans un état actif).</li><li>Cliquez sur &ldquo;Official Account&rdquo; => &ldquo;Manage Official Account&rdquo; dans la barre de menu de gauche pour créer un lien vers le "Official Account" que vous avez spécifié ci-dessus.</li><li>Cliquez sur &ldquo;Official Account&rdquo; => &ldquo;General settings&rdquo; dans la barre de menu de gauche, recherchez la case &ldquo;Official Account Callback Url&rdquo; et cliquez sur le bouton &ldquo;Update&rdquo; pour saisir la valeur suivante:<code>%s</code>, puis cliquez sur le bouton &ldquo;Save&rdquo; à côté. Continuez à &ldquo;Select permissions to request from OA&rdquo;, sélectionnez tout et cliquez sur le bouton &ldquo;Save&rdquo;</li><li>Cliquez sur &laquo;Login&raquo;, cliquez sur &laquo;Web&raquo; sur la page qui apparaît, saisissez dans la case &ldquo;Home URL&rdquo; la valeur: <code>%s</code>, puis ajoutez les 2 valeurs suivantes dans la case &ldquo;Callback URL&rdquo;: <code>%s</code> et <code>%s</code>, cliquez sur &ldquo;Save change&rdquo;.</li></ul>';
$lang_module['access_token'] = 'Jeton d\'accès';
$lang_module['refresh_token'] = 'Jeton d\'actualisation';
$lang_module['submit'] = 'Soumettre';
$lang_module['access_token_create'] = 'Créer un jeton d\'accès';
$lang_module['oa_id_empty'] = 'Erreur: Official Account ID non spécifié';
$lang_module['redirect_uri_empty'] = 'Erreur: L\'URL de rappel n\'a pas été déclarée';
$lang_module['app_id_empty'] = 'Erreur : L\'ID d\'application n\'a pas été déclaré';
$lang_module['app_seckey_empty'] = 'Erreur: La clé secrète de l\'application n\'a pas été déclarée';
$lang_module['refresh_token_empty'] = 'Erreur: le jeton d\'actualisation n\'est pas défini';
$lang_module['not_response'] = 'Erreur: aucune donnée de retour';
$lang_module['oa_id_incorrect'] = 'Erreur: OAID renvoyé ne correspond pas à OAID que vous avez déclaré';
$lang_module['refresh_token_expired'] = 'Erreur: le jeton d\'actualisation a expiré';
