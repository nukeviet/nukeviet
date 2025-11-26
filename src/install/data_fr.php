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

$install_lang['modules'] = [];
$install_lang['modules']['about'] = 'À propos';
$install_lang['modules']['about_for_acp'] = '';
$install_lang['modules']['news'] = 'News';
$install_lang['modules']['news_for_acp'] = '';
$install_lang['modules']['users'] = 'Compte d&#039;utilisateur';
$install_lang['modules']['users_for_acp'] = '';
$install_lang['modules']['myapi'] = 'Mes API';
$install_lang['modules']['myapi_for_acp'] = '';
$install_lang['modules']['inform'] = 'Notification';
$install_lang['modules']['inform_for_acp'] = '';
$install_lang['modules']['contact'] = 'Contact';
$install_lang['modules']['contact_for_acp'] = '';
$install_lang['modules']['statistics'] = 'Statistiques';
$install_lang['modules']['statistics_for_acp'] = '';
$install_lang['modules']['voting'] = 'Sondage';
$install_lang['modules']['voting_for_acp'] = '';
$install_lang['modules']['banners'] = 'Publicité';
$install_lang['modules']['banners_for_acp'] = '';
$install_lang['modules']['seek'] = 'Recherche';
$install_lang['modules']['seek_for_acp'] = '';
$install_lang['modules']['menu'] = 'Barre de navigation';
$install_lang['modules']['menu_for_acp'] = '';
$install_lang['modules']['comment'] = 'Comment';
$install_lang['modules']['comment_for_acp'] = '';
$install_lang['modules']['siteterms'] = 'Conditions d’utilisation';
$install_lang['modules']['siteterms_for_acp'] = '';
$install_lang['modules']['feeds'] = 'Rss Feeds';
$install_lang['modules']['Page'] = 'Page';
$install_lang['modules']['Page_for_acp'] = '';
$install_lang['modules']['freecontent'] = 'Introduction';
$install_lang['modules']['freecontent_for_acp'] = '';
$install_lang['modules']['two_step_verification'] = '2-Step Vérification';
$install_lang['modules']['two_step_verification_for_acp'] = '';

$install_lang['modfuncs'] = [];
$install_lang['modfuncs']['users'] = [];
$install_lang['modfuncs']['users']['login'] = 'Se connecter';
$install_lang['modfuncs']['users']['register'] = 'S&#039;inscrire';
$install_lang['modfuncs']['users']['lostpass'] = 'Mot de passe oublié?';
$install_lang['modfuncs']['users']['lostactivelink'] = 'Récupérer le lien d\'activation';
$install_lang['modfuncs']['users']['r2s'] = 'Désactiver la vérification en deux étapes';
$install_lang['modfuncs']['users']['active'] = 'Active';
$install_lang['modfuncs']['users']['editinfo'] = 'Edit User Info';
$install_lang['modfuncs']['users']['memberlist'] = 'Liste des membres';
$install_lang['modfuncs']['users']['logout'] = 'Logout';
$install_lang['modfuncs']['users']['groups'] = 'Groups';
$install_lang['modfuncs']['users']['datadeletion'] = 'Supprimer les données personnelles';
$install_lang['modfuncs']['users']['main'] = 'Page d\'accueil du compte';
$install_lang['modfuncs']['users']['avatar'] = 'Changer l\'avatar';
$install_lang['modfuncs']['users']['security-privacy'] = 'Sécurité et Confidentialité';
$install_lang['modfuncs']['users']['verify-password'] = 'Vérifier le mot de passe';

$install_lang['modfuncs']['statistics'] = [];
$install_lang['modfuncs']['statistics']['allreferers'] = 'Par Site';
$install_lang['modfuncs']['statistics']['allcountries'] = 'Par Pays';
$install_lang['modfuncs']['statistics']['allbrowsers'] = 'Par Navigateur';
$install_lang['modfuncs']['statistics']['allos'] = 'Par Système d&#039;exploitation';
$install_lang['modfuncs']['statistics']['allbots'] = 'Par Moteur de recherche';
$install_lang['modfuncs']['statistics']['referer'] = 'Par Site';

$install_lang['blocks_groups'] = [];
$install_lang['blocks_groups']['news'] = [];
$install_lang['blocks_groups']['news']['module.block_newscenter'] = 'Articles récents';
$install_lang['blocks_groups']['news']['global.block_category'] = 'Catégorie';
$install_lang['blocks_groups']['news']['global.block_tophits'] = 'Article Clics';
$install_lang['blocks_groups']['banners'] = [];
$install_lang['blocks_groups']['banners']['global.banners1'] = 'Publicité du centre';
$install_lang['blocks_groups']['banners']['global.banners2'] = 'Publicité à côté';
$install_lang['blocks_groups']['banners']['global.banners3'] = 'Publicité à côté2';
$install_lang['blocks_groups']['statistics'] = [];
$install_lang['blocks_groups']['statistics']['global.counter'] = 'Statistics';
$install_lang['blocks_groups']['about'] = [];
$install_lang['blocks_groups']['about']['global.about'] = 'À propos';
$install_lang['blocks_groups']['voting'] = [];
$install_lang['blocks_groups']['voting']['global.voting_random'] = 'Sondage';
$install_lang['blocks_groups']['inform'] = [];
$install_lang['blocks_groups']['inform']['global.inform'] = 'Notification';
$install_lang['blocks_groups']['users'] = [];
$install_lang['blocks_groups']['users']['global.user_button'] = 'Se connecter';
$install_lang['blocks_groups']['theme'] = [];
$install_lang['blocks_groups']['theme']['global.company_info'] = 'Management Company';
$install_lang['blocks_groups']['theme']['global.menu_footer'] = 'Menu';
$install_lang['blocks_groups']['freecontent'] = [];
$install_lang['blocks_groups']['freecontent']['global.free_content'] = 'Produits';

$install_lang['cron'] = [];
$install_lang['cron']['cron_online_expired_del'] = 'Supprimer les anciens registres du status en ligne dans la base de données';
$install_lang['cron']['cron_dump_autobackup'] = 'Sauvegarder automatique la base de données';
$install_lang['cron']['cron_auto_del_temp_download'] = 'Supprimer les fichiers temporaires du répertoire tmp';
$install_lang['cron']['cron_del_ip_logs'] = 'Supprimer les fichiers ip_logs expirés';
$install_lang['cron']['cron_auto_del_error_log'] = 'Supprimer les fichiers error_log expirés';
$install_lang['cron']['cron_auto_sendmail_error_log'] = 'Envoyer à l’administrateur l’e-mail des notifications d’erreurs';
$install_lang['cron']['cron_ref_expired_del'] = 'Supprimer les referers expirés';
$install_lang['cron']['cron_auto_check_version'] = 'Vérifier la version NukeViet';
$install_lang['cron']['cron_notification_autodel'] = 'Supprimer vieille notification';
$install_lang['cron']['cron_remove_expired_inform'] = 'Supprimer les notifications expirées';
$install_lang['cron']['cron_apilogs_autodel'] = 'Supprimer les journaux d’API expirés';
$install_lang['cron']['cron_expadmin_handling'] = 'Gestion des administrateurs expirés';
$install_lang['cron']['cron_user_datadeletion_handling'] = 'Gestion de la suppression des données utilisateur programmée';

$install_lang['groups']['NukeViet-Fans'] = 'Fans de NukeViet';
$install_lang['groups']['NukeViet-Admins'] = 'Admins de NukeViet';
$install_lang['groups']['NukeViet-Programmers'] = 'Programmeurs de NukeViet';

$install_lang['groups']['NukeViet-Fans-desc'] = 'Groupe de ventilateurs du système NukeViet';
$install_lang['groups']['NukeViet-Admins-desc'] = 'Groupe d’administrateurs pour les sites créés par le système NukeViet';
$install_lang['groups']['NukeViet-Programmers-desc'] = 'Groupe de programmeurs de systèmes NukeViet';

$install_lang['vinades_fullname'] = 'Vietnam Open Source Development Joint Stock Company';
$install_lang['vinades_address'] = '6ème étage, bâtiment Song Da, rue Tran Phu n° 131, quartier Van Quan, district de Ha Dong, ville de Hanoï, Vietnam';
$install_lang['nukeviet_description'] = 'Partager le succès, connectez passions';
$install_lang['disable_site_content'] = 'Notre site est fermé temporairement pour la maintenance. Veuillez revenir plus tard. Merci!';

// Ngôn ngữ dữ liệu cho phần mẫu email
use NukeViet\Template\Email\Cat as EmailCat;
use NukeViet\Template\Email\Tpl as EmailTpl;

$install_lang['emailtemplates'] = [];
$install_lang['emailtemplates']['cats'] = [];
$install_lang['emailtemplates']['cats'][EmailCat::CAT_SYSTEM] = 'Messages système';
$install_lang['emailtemplates']['cats'][EmailCat::CAT_USER] = 'Messages utilisateur';
$install_lang['emailtemplates']['cats'][EmailCat::CAT_AUTHOR] = 'Messages Admin';
$install_lang['emailtemplates']['cats'][EmailCat::CAT_MODULE] = 'Messages du module';

$install_lang['emailtemplates']['emails'] = [];

$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTO_ERROR_REPORT] = [
    'pids' => '5',
    'catid' => EmailCat::CAT_SYSTEM,
    't' => 'Erreur de notification automatique par courrier électronique',
    's' => 'Notification du site {$site_name}',
    'c' => 'Le système a reçu des notifications. Veuillez étudier le fichier attaché pour les détails'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_EMAIL_CONFIG_TEST] = [
    'pids' => '5',
    'catid' => EmailCat::CAT_SYSTEM,
    't' => 'Envoyer un e-mail de test pour tester la configuration de l\'envoi d\'e-mails',
    's' => 'Tester l\'e-mail',
    'c' => 'Ceci est un courrier électronique de test pour vérifier la configuration du courrier. Supprimez-le simplement!'
];

$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_DELETE] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Notez que le compte administrateur a été supprimé',
    's' => 'Notification du site {$site_name}',
    'c' => 'L\'administrateur du site {$site_name} informe:<br />Votre compte d\'administration sur le site {$site_name} est supprimé au {$time}{if not empty($note)} en raison de: {$note}{/if}.<br />Toute proposition, question... veuillez envoyer un e-mail à <a href="mailto:{$email}">{$email}</a>{if not empty($username)}<br/><br/>{$sig}<br/><br/>{$username}<br/>{$position}<br/>{$email}{/if}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_SUSPEND] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Avis de suspension de l\'administration du site',
    's' => 'Notification du site {$site_name}',
    'c' => 'L\'administrateur du site {$site_name} informe:<br />Votre compte d\'administration sur le site {$site_name} est suspendu au {$time} en raison: {$note}.<br />Toute proposition, question... veuillez envoyer un e-mail à <a href="mailto:{$email}">{$email}</a>{if not empty($username)}<br/><br/>{$sig}<br/><br/>{$username}<br/>{$position}<br/>{$email}{/if}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_REACTIVE] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Avis de réactivation de l\'administration du site',
    's' => 'Notification du site {$site_name}',
    'c' => 'L\'administrateur du site {$site_name} informe:<br />Votre compte d\'administration sur le site {$site_name} est rétabli au {$time}.<br />Ce compte avait été suspendu en raison: {$note}{if not empty($username)}<br/><br/>{$sig}<br/><br/>{$username}<br/>{$position}<br/>{$email}{/if}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_2STEP_ADD] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Notez qu\'une nouvelle méthode d\'authentification en deux étapes a été ajoutée au compte administrateur',
    's' => 'Configurer la vérification en 2 étapes à l\'aide d\'Oauth terminé',
    'c' => '{$greeting_user}<br /><br />L\'administration du site {$site_name} tient à informer:<br />L\'authentification en deux étapes utilisant Oauth dans le panneau d\'administration a été installée avec succès. Vous pouvez utiliser le compte <strong>{$oauth_id}</strong> du fournisseur <strong>{$oauth_name}</strong> pour l\'authentification lorsque vous vous connectez à la zone d\'administration du site.'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_2STEP_TRUNCATE] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Notification indiquant que toutes les méthodes d\'authentification en deux étapes ont été supprimées du compte administrateur',
    's' => 'La configuration de l\'authentification en deux étapes avec Oauth a été annulée',
    'c' => '{$greeting_user}<br /><br />L\'administration du site {$site_name} tient à informer:<br />À votre demande, la validation en deux étapes à l\'aide d\'Oauth a été annulée avec succès. Désormais, vous ne pouvez plus utiliser le fournisseur de comptes <strong>{$oauth_id}</strong> pour vous authentifier dans la zone d\'administration du site.'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_2STEP_DEL] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Notez que l\'authentification en deux étapes a été supprimée du compte administrateur',
    's' => 'La configuration de l\'authentification en deux étapes avec Oauth a été annulée',
    'c' => '{$greeting_user}<br /><br />L\'administration du site {$site_name} tient à informer:<br />À votre demande, la validation en deux étapes à l\'aide d\Oauth a été annulée avec succès. Désormais, vous ne pouvez plus utiliser le compte <strong>{$oauth_id}</strong> du fournisseur <strong>{$oauth_name}</strong> pour vous authentifier dans la zone d\'administration du site.'
];

$menu_rows_lev0['about'] = [
    'title' => $install_lang['modules']['about'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=about',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['news'] = [
    'title' => $install_lang['modules']['news'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=news',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['users'] = [
    'title' => $install_lang['modules']['users'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['voting'] = [
    'title' => $install_lang['modules']['voting'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=voting',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['contact'] = [
    'title' => $install_lang['modules']['contact'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=contact',
    'groups_view' => '6',
    'op' => ''
];

$menu_rows_lev1['users'] = [];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['login'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=login',
    'groups_view' => '5',
    'op' => 'login'
];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['register'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=register',
    'groups_view' => '5',
    'op' => 'register'
];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['lostpass'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=lostpass',
    'groups_view' => '5',
    'op' => 'lostpass'
];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['editinfo'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=editinfo',
    'groups_view' => '4,7',
    'op' => 'editinfo'
];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['memberlist'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=memberlist',
    'groups_view' => '4,7',
    'op' => 'memberlist'
];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['logout'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=logout',
    'groups_view' => '4,7',
    'op' => 'logout'
];
