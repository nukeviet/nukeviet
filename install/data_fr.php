<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$my_lang['modules'] = array();
$my_lang['modules']['about'] = 'À propos';
$my_lang['modules']['about_for_acp'] = '';
$my_lang['modules']['news'] = 'News';
$my_lang['modules']['news_for_acp'] = '';
$my_lang['modules']['users'] = 'Compte d&#039;utilisateur';
$my_lang['modules']['users_for_acp'] = '';
$my_lang['modules']['contact'] = 'Contact';
$my_lang['modules']['contact_for_acp'] = '';
$my_lang['modules']['statistics'] = 'Statistiques';
$my_lang['modules']['statistics_for_acp'] = '';
$my_lang['modules']['voting'] = 'Sondage';
$my_lang['modules']['voting_for_acp'] = '';
$my_lang['modules']['banners'] = 'Publicité';
$my_lang['modules']['banners_for_acp'] = '';
$my_lang['modules']['seek'] = 'Recherche';
$my_lang['modules']['seek_for_acp'] = '';
$my_lang['modules']['menu'] = 'Barre de navigation';
$my_lang['modules']['menu_for_acp'] = '';
$my_lang['modules']['comment'] = 'Comment';
$my_lang['modules']['comment_for_acp'] = '';
$my_lang['modules']['siteterms'] = 'Conditions d\'utilisation';
$my_lang['modules']['siteterms_for_acp'] = '';
$my_lang['modules']['feeds'] = 'Rss Feeds';
$my_lang['modules']['Page'] = 'Page';
$my_lang['modules']['Page_for_acp'] = '';
$my_lang['modules']['freecontent'] = 'Introduction';
$my_lang['modules']['freecontent_for_acp'] = '';

$my_lang['modfuncs'] = array();
$my_lang['modfuncs']['users'] = array();
$my_lang['modfuncs']['users']['login'] = 'Se connecter';
$my_lang['modfuncs']['users']['register'] = 'S&#039;inscrire';
$my_lang['modfuncs']['users']['lostpass'] = 'Mot de passe oublié?';
$my_lang['modfuncs']['users']['active'] = 'Active';
$my_lang['modfuncs']['users']['editinfo'] = 'Edit User Info';
$my_lang['modfuncs']['users']['memberlist'] = 'Liste des membres';
$my_lang['modfuncs']['users']['logout'] = 'Logout';
$my_lang['modfuncs']['users']['groups'] = 'Groups';

$my_lang['modfuncs']['statistics'] = array();
$my_lang['modfuncs']['statistics']['allreferers'] = 'Par Site';
$my_lang['modfuncs']['statistics']['allcountries'] = 'Par Pays';
$my_lang['modfuncs']['statistics']['allbrowsers'] = 'Par Navigateur';
$my_lang['modfuncs']['statistics']['allos'] = 'Par Système d&#039;exploitation';
$my_lang['modfuncs']['statistics']['allbots'] = 'Par Moteur de recherche';
$my_lang['modfuncs']['statistics']['referer'] = 'Par Site';

$my_lang['blocks_groups'] = array();
$my_lang['blocks_groups']['news'] = array();
$my_lang['blocks_groups']['news']['module.block_newscenter'] = 'Articles récents';
$my_lang['blocks_groups']['news']['global.block_category'] = 'Catégorie';
$my_lang['blocks_groups']['news']['global.block_tophits'] = 'Article Clics';
$my_lang['blocks_groups']['banners'] = array();
$my_lang['blocks_groups']['banners']['global.banners1'] = 'Publicité du centre';
$my_lang['blocks_groups']['banners']['global.banners2'] = 'Publicité à côté';
$my_lang['blocks_groups']['banners']['global.banners3'] = 'Publicité à côté2';
$my_lang['blocks_groups']['statistics'] = array();
$my_lang['blocks_groups']['statistics']['global.counter'] = 'Statistics';
$my_lang['blocks_groups']['about'] = array();
$my_lang['blocks_groups']['about']['global.about'] = 'À propos';
$my_lang['blocks_groups']['voting'] = array();
$my_lang['blocks_groups']['voting']['global.voting_random'] = 'Sondage';
$my_lang['blocks_groups']['users'] = array();
$my_lang['blocks_groups']['users']['global.user_button'] = 'Se connecter';
$my_lang['blocks_groups']['theme'] = array();
$my_lang['blocks_groups']['theme']['global.company_info'] = 'Management Company';
$my_lang['blocks_groups']['theme']['global.menu_footer'] = 'Menu';
$my_lang['blocks_groups']['freecontent'] = array();
$my_lang['blocks_groups']['freecontent']['global.free_content'] = 'Produits';

$my_lang['cron'] = array();
$my_lang['cron']['cron_online_expired_del'] = 'Supprimer les anciens registres du status en ligne dans la base de données';
$my_lang['cron']['cron_dump_autobackup'] = 'Sauvegarder automatique la base de données';
$my_lang['cron']['cron_auto_del_temp_download'] = 'Supprimer les fichiers temporaires du répertoire tmp';
$my_lang['cron']['cron_del_ip_logs'] = 'Supprimer les fichiers ip_logs expirés';
$my_lang['cron']['cron_auto_del_error_log'] = 'Supprimer les fichiers error_log expirés';
$my_lang['cron']['cron_auto_sendmail_error_log'] = 'Envoyer à l\'administrateur l\'e-mail des notifications d\'erreurs';
$my_lang['cron']['cron_ref_expired_del'] = 'Supprimer les referers expirés';
$my_lang['cron']['cron_siteDiagnostic_update'] = 'Mise à jour du site de diagnostic';
$my_lang['cron']['cron_auto_check_version'] = 'Vérifier la version NukeViet';
$my_lang['cron']['cron_notification_autodel'] = 'Supprimer vieille notification';

$my_lang['groups']['NukeViet-Fans'] = 'NukeViet-Fans';
$my_lang['groups']['NukeViet-Admins'] = 'NukeViet-Admins';
$my_lang['groups']['NukeViet-Programmers'] = 'NukeViet-Programmeurs';

$my_lang['vinades_fullname'] = "Vietnam Open Source Development Joint Stock Company";
$my_lang['vinades_address'] = "Номер 2004/CT2, здание Nang Huong, д. 583, ул. Nguyen Trai, г. Ханой, Вьетнам";
$my_lang['nukeviet_description'] = 'Partager le succès, connectez passions';
$my_lang['disable_site_content'] = 'Notre site est fermé temporairement pour la maintenance. Veuillez revenir plus tard. Merci!';

$menu_rows_lev0['about'] = array(
    'title' => $my_lang['modules']['about'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=about",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['news'] = array(
    'title' => $my_lang['modules']['news'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['users'] = array(
    'title' => $my_lang['modules']['users'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['voting'] = array(
    'title' => $my_lang['modules']['voting'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=voting",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['contact'] = array(
    'title' => $my_lang['modules']['contact'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=contact",
    'groups_view' => '6',
    'op' => ''
);

$menu_rows_lev1['users'] = array();
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['login'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=login",
    'groups_view' => '5',
    'op' => 'login'
);
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['register'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=register",
    'groups_view' => '5',
    'op' => 'register'
);
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['lostpass'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=lostpass",
    'groups_view' => '5',
    'op' => 'lostpass'
);
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['editinfo'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=editinfo",
    'groups_view' => '4,7',
    'op' => 'editinfo'
);
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['memberlist'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=memberlist",
    'groups_view' => '4,7',
    'op' => 'memberlist'
);
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['logout'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=logout",
    'groups_view' => '4,7',
    'op' => 'logout'
);
