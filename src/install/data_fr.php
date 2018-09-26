<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$install_lang['modules'] = [];
$install_lang['modules']['about'] = 'À propos';
$install_lang['modules']['about_for_acp'] = '';
$install_lang['modules']['news'] = 'News';
$install_lang['modules']['news_for_acp'] = '';
$install_lang['modules']['users'] = 'Compte d&#039;utilisateur';
$install_lang['modules']['users_for_acp'] = '';
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
$install_lang['modules']['siteterms'] = 'Conditions d\'utilisation';
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
$install_lang['modfuncs']['users']['active'] = 'Active';
$install_lang['modfuncs']['users']['editinfo'] = 'Edit User Info';
$install_lang['modfuncs']['users']['memberlist'] = 'Liste des membres';
$install_lang['modfuncs']['users']['logout'] = 'Logout';
$install_lang['modfuncs']['users']['groups'] = 'Groups';

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
$install_lang['cron']['cron_auto_sendmail_error_log'] = 'Envoyer à l\'administrateur l\'e-mail des notifications d\'erreurs';
$install_lang['cron']['cron_ref_expired_del'] = 'Supprimer les referers expirés';
$install_lang['cron']['cron_auto_check_version'] = 'Vérifier la version NukeViet';
$install_lang['cron']['cron_notification_autodel'] = 'Supprimer vieille notification';

$install_lang['groups']['NukeViet-Fans'] = 'NukeViet-Fans';
$install_lang['groups']['NukeViet-Admins'] = 'NukeViet-Admins';
$install_lang['groups']['NukeViet-Programmers'] = 'NukeViet-Programmeurs';

$install_lang['vinades_fullname'] = "Vietnam Open Source Development Joint Stock Company";
$install_lang['vinades_address'] = "Номер 1706/CT2, здание Nang Huong, д. 583, ул. Nguyen Trai, г. Ханой, Вьетнам";
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
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_EMAIL_ACTIVE] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Activation du compte par email',
    's' => 'Infos pour l\'activation du compte',
    'c' => 'Hi {$user_full_name},<br /><br />Your account at website {$site_name} waitting to activate. To activate, please click link follow:<br /><br />URL: <a href="{$active_link}">{$active_link}</a><br /><br />Account information:<br /><br />Account: {$user_username}<br />Email: {$user_email}<br /><br />Activate expired on {$active_deadline}<br /><br />This is email automatic sending from website {$site_name}.<br /><br />Site administrator'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_DELETE] = [
    'pids' => '8',
    'catid' => EmailCat::CAT_USER,
    't' => 'Notification de suppression du compte',
    's' => 'Notification de suppression du compte',
    'c' => 'Bonjour {$user_full_name} ({$user_username}),<br /><br />Nous sommes désolé de vous informer la suppression de votre compte sur le site {$site_name}.'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_NEW_2STEP_CODE] = [
    'pids' => '9',
    'catid' => EmailCat::CAT_USER,
    't' => 'Codes de sauvegarde',
    's' => 'Codes de sauvegarde',
    'c' => 'Bonjour {$user_full_name},<br /><br /> code de sauvegarde sur votre compte sur le site {$site_name} a été changé. Voici le nouveau code de sauvegarde:<br /><br />{foreach from=$new_code item=code}{$code}<br />{/foreach}<br /><br /> Vous gardez les codes de sauvegarde sécurisés. Si vous perdez votre téléphone et prenez les deux codes de sauvegarde que vous ne serez pas en mesure d\'accéder à votre compte. <br /> <br /> C\'est un message automatique envoyé à votre boîte de réception e-mail à partir du site {$site_name}. Si vous ne comprenez rien au sujet du contenu de cette lettre, supprimer tout simplement. <br /> <br /> site d\'administration'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_NEW_INFO] = [
    'pids' => '10',
    'catid' => EmailCat::CAT_USER,
    't' => 'Notification de compte créée/activée',
    's' => 'Votre compte a été créé',
    'c' => 'Bonjour {$user_full_name},<br /><br />Your account at website {$site_name} activated. Your login information:<br /><br />URL: <a href="{$login_link}">{$login_link}</a><br /><br />Account: {$user_username}<br /><br />This is email automatic sending from website {$site_name}.<br /><br />Site administrator'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_ADMIN_ADDED] = [
    'pids' => '11',
    'catid' => EmailCat::CAT_USER,
    't' => 'Le compte administratif signalé est initialisé',
    's' => 'Votre compte a été créé',
    'c' => 'Bonjour {$user_full_name},<br /><br /> Votre compte sur le site {$site_name} a été créé. Voici vos informations de connexion: <br /><br />URL: <a href="{$login_link}">{$login_link}</a><br /> Nom: {$user_username}<br /> mot de passe: {$user_password}<br /><br /> Nous de vous recommandons de changer votre mot de passe avant d\'utiliser le compte <br /><br /> Ceci est un message automatique envoyé. votre boîte de réception e-mail à partir du site web {$site_name}. Si vous ne comprenez rien au sujet du contenu de cette lettre, supprimer tout simplement. <br /><br /> site d\'administration'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_SAFE_KEY] = [
    'pids' => '12',
    'catid' => EmailCat::CAT_USER,
    't' => 'Le message de vérification active le mode sécurisé',
    's' => 'Code de certifier en mode sans échec',
    'c' => 'Bonjour {$user_full_name},<br /><br />. Vous avez demandé l\'utilisation du mode sans échec sur le site {$site_name}. En dessous est le code de certifier pour l\'activer ou le désactiver:<br /><br /><strong>{$code}</strong><br /><br />. Ce code ne peut être utilisé qu\'une seule fois. Apres la désactivation de ce mode, ce code est inutilisable.<br /><br /> C\'est un courier automatique qui est envoyé à votre email à partir du site {$site_name}.<br /><br /><br /><br />Administration du site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_SELF_EDIT] = [
    'pids' => '13',
    'catid' => EmailCat::CAT_USER,
    't' => 'La newsletter a mis à jour le compte',
    's' => 'La mise à jour les infos du compte réussite',
    'c' => 'Bonjour {$user_full_name},<br /><br /> Votre compte sur le site Web {$site_name} est mise à jour avec nouveau {$edit_label} qui est <strong>{$new_value}</strong>.<br /><br /> C\'est un courier automatique qui est envoyé à votre email à partir du site {$site_name}.<br /><br /><br /><br />Administration du site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_ADMIN_EDIT] = [
    'pids' => '14',
    'catid' => EmailCat::CAT_USER,
    't' => 'Message de gestion modifier le compte membre',
    's' => 'Votre compte a été mis à jour',
    'c' => 'Bonjour {$user_full_name},<br /><br /> Votre compte sur le site {$site_name} a été mis à jour. Voici les nouvelles informations de connexion: <br /><br />URL: <a href="{$login_url}">{$login_url}</a><br /> Nom: {$user_username}{if $send_password}<br />Mot de passe: {$user_password}{/if}<br /><br />Ceci est un message automatique envoyé à votre boîte de réception e-mail à partir du site {$site_name}. Si vous ne comprenez rien au sujet du contenu de cette lettre, supprimer tout simplement. <br /> <br /> site d\'administration'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_VERIFY_EMAIL] = [
    'pids' => '15',
    'catid' => EmailCat::CAT_USER,
    't' => 'Confirmation de la modification du compte de messagerie électronique',
    's' => 'Infos d\'activation de changement d\'email',
    'c' => 'Bonjour {$user_full_name},<br /><br />. Vous avez demandé l\'utilisation du mode sans échec sur le site {$site_name}. Pour valider le changement, vous devez declarer votre nouvel email en saisissant le code de certifier en dessous dans le zone Modification des infos<br /><br /> <br /><br />Code de certifier: <strong>{$code}</strong><br /><br />Ce code est utilisable jusqu\'à {$timeout}.<br /><br />C\'est un courier automatique qui est envoyé à votre email à partir du site {$site_name}. SI vous ne comprenez pas le contenu de ce courrier vous pouvez le supprimer simplement. <br /><br /><br /><br />Administration du site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_GROUP_JOIN] = [
    'pids' => '16',
    'catid' => EmailCat::CAT_USER,
    't' => 'Les notifications sont obligatoires pour rejoindre le groupe',
    's' => 'Demander à joindre le groupe',
    'c' => 'Bonjour chef <strong>{$leader_name}</strong>,<br /><br /><strong>{$user_full_name}</strong> a envoyé la demande à rejoindre le groupe <strong>{$group_name}</strong> parce que vous gérez. Vous devez approuver cette demande!<br /><br />S\'il vous plaît visitez <a href="{$link}">ce lien</a> d\'approuver l\'adhésion.'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_LOST_ACTIVE] = [
    'pids' => '17',
    'catid' => EmailCat::CAT_USER,
    't' => 'Récupérer le lien d\'activation du compte',
    's' => 'Infos pour l\'activation du compte',
    'c' => 'Hi {$user_full_name},<br /><br />Your account at website {$site_name} waitting to activate. To activate, please click link follow:<br /><br />URL: <a href="{$active_link}">{$active_link}</a><br /><br />Account information:<br /><br />Account: {$user_username}<br />Email: {$user_email}<br />Password: {$user_password}<br /><br />Activate expired on {$timeout}<br /><br />This is email automatic sending from website {$site_name}.<br /><br />Site administrator'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_LOST_PASS] = [
    'pids' => '18',
    'catid' => EmailCat::CAT_USER,
    't' => 'Récupérer le mot de passe de membre',
    's' => 'Guide de rechercher le mot de passe du site {$site_name}',
    'c' => 'Hello {$user_full_name},<br /><br />You propose to change my login password at the website {$site_name}. To change your password, you will need to enter the verification code below in the corresponding box at the password change area.<br /><br />Verification code: <strong>{$code}</strong><br /><br />This code is only used once and before the deadline of {$timeout}<br />This letter is automatically sent to your email inbox from site {$site_name}. If you do not know anything about the contents of this letter, just delete it.<br /><br />Administrator'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_DELETE] = [
    'pids' => '5',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Supprimer le compte administrateur',
    's' => 'Notification du site {$site_name}',
    'c' => 'L\'administrateur du site {$site_name} informe:<br />Votre compte d\'administration sur le site {$site_name} est supprimé au {$delete_time}{if not empty($delete_reason)} en raison de: {$delete_reason}{/if}.<br />Toute proposition, question... merci d\'envoyer à l\'adresse {$contact_link}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_SUSPEND] = [
    'pids' => '6',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Suspendre/réactiver l\'administrateur du site',
    's' => 'Notification du site {$site_name}',
    'c' => '{if $is_suspend}L\'administrateur du site {$site_name} informe:<br />Votre compte d\'administration sur le site {$site_name} est suspendu au {$suspend_time} en raison: {$suspend_reason}.<br />Toute proposition, question... merci d\'envoyer à l\'adresse {$contact_link}{else}L\'administration du site {$site_name} informe:<br />Votre compte d\'administration sur le site {$site_name} est rétabli au {$suspend_time}.<br />Ce compte avait été suspendu en raison: {$suspend_reason}{/if}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTO_ERROR_REPORT] = [
    'pids' => '7',
    'catid' => EmailCat::CAT_SYSTEM,
    't' => 'Erreur de notification automatique par courrier électronique',
    's' => 'Notification du site {$site_name}',
    'c' => 'Le système a reçu des notifications. Veuillez étudier le fichier attaché pour les détails'
];

$menu_rows_lev0['about'] = array(
    'title' => $install_lang['modules']['about'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=about",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['news'] = array(
    'title' => $install_lang['modules']['news'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['users'] = array(
    'title' => $install_lang['modules']['users'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['voting'] = array(
    'title' => $install_lang['modules']['voting'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=voting",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['contact'] = array(
    'title' => $install_lang['modules']['contact'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=contact",
    'groups_view' => '6',
    'op' => ''
);

$menu_rows_lev1['users'] = [];
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['login'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=login",
    'groups_view' => '5',
    'op' => 'login'
);
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['register'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=register",
    'groups_view' => '5',
    'op' => 'register'
);
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['lostpass'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=lostpass",
    'groups_view' => '5',
    'op' => 'lostpass'
);
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['editinfo'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=editinfo",
    'groups_view' => '4,7',
    'op' => 'editinfo'
);
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['memberlist'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=memberlist",
    'groups_view' => '4,7',
    'op' => 'memberlist'
);
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['logout'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=logout",
    'groups_view' => '4,7',
    'op' => 'logout'
);
