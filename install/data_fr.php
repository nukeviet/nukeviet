<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$install_lang['modules'] = array();
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

$install_lang['modfuncs'] = array();
$install_lang['modfuncs']['users'] = array();
$install_lang['modfuncs']['users']['login'] = 'Se connecter';
$install_lang['modfuncs']['users']['register'] = 'S&#039;inscrire';
$install_lang['modfuncs']['users']['lostpass'] = 'Mot de passe oublié?';
$install_lang['modfuncs']['users']['active'] = 'Active';
$install_lang['modfuncs']['users']['editinfo'] = 'Edit User Info';
$install_lang['modfuncs']['users']['memberlist'] = 'Liste des membres';
$install_lang['modfuncs']['users']['logout'] = 'Logout';
$install_lang['modfuncs']['users']['groups'] = 'Groups';

$install_lang['modfuncs']['statistics'] = array();
$install_lang['modfuncs']['statistics']['allreferers'] = 'Par Site';
$install_lang['modfuncs']['statistics']['allcountries'] = 'Par Pays';
$install_lang['modfuncs']['statistics']['allbrowsers'] = 'Par Navigateur';
$install_lang['modfuncs']['statistics']['allos'] = 'Par Système d&#039;exploitation';
$install_lang['modfuncs']['statistics']['allbots'] = 'Par Moteur de recherche';
$install_lang['modfuncs']['statistics']['referer'] = 'Par Site';

$install_lang['blocks_groups'] = array();
$install_lang['blocks_groups']['news'] = array();
$install_lang['blocks_groups']['news']['module.block_newscenter'] = 'Articles récents';
$install_lang['blocks_groups']['news']['global.block_category'] = 'Catégorie';
$install_lang['blocks_groups']['news']['global.block_tophits'] = 'Article Clics';
$install_lang['blocks_groups']['banners'] = array();
$install_lang['blocks_groups']['banners']['global.banners1'] = 'Publicité du centre';
$install_lang['blocks_groups']['banners']['global.banners2'] = 'Publicité à côté';
$install_lang['blocks_groups']['banners']['global.banners3'] = 'Publicité à côté2';
$install_lang['blocks_groups']['statistics'] = array();
$install_lang['blocks_groups']['statistics']['global.counter'] = 'Statistics';
$install_lang['blocks_groups']['about'] = array();
$install_lang['blocks_groups']['about']['global.about'] = 'À propos';
$install_lang['blocks_groups']['voting'] = array();
$install_lang['blocks_groups']['voting']['global.voting_random'] = 'Sondage';
$install_lang['blocks_groups']['users'] = array();
$install_lang['blocks_groups']['users']['global.user_button'] = 'Se connecter';
$install_lang['blocks_groups']['theme'] = array();
$install_lang['blocks_groups']['theme']['global.company_info'] = 'Management Company';
$install_lang['blocks_groups']['theme']['global.menu_footer'] = 'Menu';
$install_lang['blocks_groups']['freecontent'] = array();
$install_lang['blocks_groups']['freecontent']['global.free_content'] = 'Produits';

$install_lang['cron'] = array();
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

$menu_rows_lev1['users'] = array();
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