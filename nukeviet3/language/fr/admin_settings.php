<?php

/**
* @Project NUKEVIET 3.0
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2010 VINADES.,JSC. All rights reserved
* @Language Français
* @Createdate Aug 23, 2010, 09:00:49 PM
*/

 if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')){
 die('Stop!!!');
}

$lang_translator['author'] ="Phạm Chí Quang";
$lang_translator['createdate'] ="21/6/2010, 19:30";
$lang_translator['copyright'] ="@Copyright (C) 2010 VINADES.,JSC. Tous droits réservés.";
$lang_translator['info'] ="Langue française pour NukeViet 3";
$lang_translator['langtype'] ="lang_module";

$lang_module['global_config'] = "Config. générale";
$lang_module['lang_site_config'] = "Config. selon langue";
$lang_module['bots_config'] = "Moteurs de recherche";
$lang_module['checkupdate'] = "Vérif. de version";
$lang_module['sitename'] = "Nom du site";
$lang_module['theme'] = "Thème par défaut";
$lang_module['themeadmin'] = "Thème de l'Administration";
$lang_module['default_module'] = "Module par défaut à l'Accueil";
$lang_module['description'] = "Description du site";
$lang_module['rewrite'] = "Activer rewrite";
$lang_module['rewrite_optional'] = "Au cas d'activer rewrite, filtrer les accents sur url";
$lang_module['site_disable'] = "Site en maintenance";
$lang_module['disable_content'] = "Notification";
$lang_module['submit'] = "Sauver";
$lang_module['err_writable'] = "Erreur: impossible d'entregister le fichier: %s merci de vérifier les permissions (chmod) de ce fichier.";
$lang_module['err_supports_rewrite'] = "Erreur: le serveur ne supporte pas le module rewrite";
$lang_module['captcha'] = "Configuration de captcha";
$lang_module['captcha_0'] = "Masqué";
$lang_module['captcha_1'] = "Lors de l'identification de l'admin";
$lang_module['captcha_2'] = "Lors de l'identification de membre";
$lang_module['captcha_3'] = "Lors de l'inscription de membre";
$lang_module['captcha_4'] = "Lors de l'identification de membre ou à l'inscription";
$lang_module['captcha_5'] = "Lors de l'identification de l'admin ou de membre";
$lang_module['captcha_6'] = "Lors de l'identification de l'admin ou l'inscription du membre";
$lang_module['captcha_7'] = "Toujours";
$lang_module['ftp_config'] = "Config. de FTP";
$lang_module['smtp_config'] = "Config. de SMTP";
$lang_module['server'] = "Serveur ou Lien";
$lang_module['port'] = "Porte";
$lang_module['username'] = "Identifiant";
$lang_module['password'] = "Mot de passe";
$lang_module['ftp_path'] = "Chemin d'accès";
$lang_module['mail_config'] = "Choisir le méthode";
$lang_module['type_smtp'] = "SMTP";
$lang_module['type_linux'] = "Linux Mail";
$lang_module['type_phpmail'] = "PHPmail";
$lang_module['smtp_server'] = "Infos du serveur";
$lang_module['incoming_ssl'] = "Ce serveur demande une connexion sécurisée (SSL)";
$lang_module['outgoing'] = "Outgoing mail server (SMTP)";
$lang_module['outgoing_port'] = "Outgoing port(SMTP)";
$lang_module['smtp_username'] = "Infos du compte";
$lang_module['smtp_login'] = "Nom d'utilisateur";
$lang_module['smtp_pass'] = "Mot de passe";
$lang_module['update_error'] = "Erreur: Impossible de vérifier les informations, merci de vérifier plus tard";
$lang_module['version_latest'] = "Félicitation, Vous avez la dernière version";
$lang_module['version_no_latest'] = "Vous n'avez pas la dernière version";
$lang_module['version_info'] = "Infos de la nouvelle version";
$lang_module['version_name'] = "Nom du système:";
$lang_module['version_number'] = "Version:";
$lang_module['version_date'] = "Date de publication:";
$lang_module['version_note'] = "Notes de la nouvelle version";
$lang_module['version_download'] = "Vous pouvez télécharger la nouvelle version";
$lang_module['version_updatenew'] = "Mettre à jour";
$lang_module['bot_name'] = "Moteurs de recherche";
$lang_module['bot_agent'] = "Agent du serveur";
$lang_module['bot_ips'] = "IP du serveur";
$lang_module['bot_allowed'] = "Autorisé";
$lang_module['site_keywords'] = "Mots clés pour les moteurs de recherche";
$lang_module['site_logo'] = "Nom du fichier logo du site";
$lang_module['site_email'] = "E-mail du site";
$lang_module['error_send_email'] = "E-mail recevant les notifications d'erreurs";
$lang_module['site_phone'] = "Téléphone du site";
$lang_module['lang_multi'] = "Activer le multi-langage";
$lang_module['site_lang'] = "Langue par défaut";
$lang_module['site_timezone'] = "Fuseau horaire";
$lang_module['date_pattern'] = "Type d'affichage: Date Mois An";
$lang_module['time_pattern'] = "Type d'affichage: Heure Minute";
$lang_module['online_upd'] = "Activer le compteur de visiteurs en ligne";
$lang_module['gzip_method'] = "Activer gzip";
$lang_module['statistic'] = "Activer  les statistiques";
$lang_module['proxy_blocker'] = "Contrôler et bloquer les ordiateurs utilisant le proxy";
$lang_module['proxy_blocker_0'] = "Sans contrôle";
$lang_module['proxy_blocker_1'] = "Contrôle léger";
$lang_module['proxy_blocker_2'] = "Contrôle moyen";
$lang_module['proxy_blocker_3'] = "Contrôle strict";
$lang_module['str_referer_blocker'] = "Activer le contrôle des liens vers le site depuis l'exterieur";
$lang_module['my_domains'] = "Les domaines du site";
$lang_module['cookie_prefix'] = "Préfixe de cookie";
$lang_module['session_prefix'] = "Préfixe de session";
$lang_module['is_user_forum'] = "Confier la gestion de membres au forum";
$lang_module['banip'] = "IPs interdits";
$lang_module['banip_ip'] = "IP";
$lang_module['banip_timeban'] = "Commencer";
$lang_module['banip_timeendban'] = "Terminer";
$lang_module['banip_funcs'] = "Fonctions";
$lang_module['banip_checkall'] = "Sélectionner tout";
$lang_module['banip_uncheckall'] = "Desélectionner tout";
$lang_module['banip_add'] = "Ajouter";
$lang_module['banip_address'] = "Adresse IP";
$lang_module['banip_begintime'] = "Commencer";
$lang_module['banip_endtime'] = "Terminer";
$lang_module['banip_notice'] = "Note";
$lang_module['banip_confirm'] = "Confirmer";
$lang_module['banip_mask_select'] = "Sélectionnez";
$lang_module['banip_area'] = "Zone interdite";
$lang_module['banip_nolimit'] = "Perpétuel";
$lang_module['banip_area_select'] = "Séletionnez la zone";
$lang_module['banip_noarea'] = "pas encore déterminé";
$lang_module['banip_del_success'] = "Suppression avec succès!";
$lang_module['banip_area_front'] = "Site";
$lang_module['banip_area_admin'] = "Administration";
$lang_module['banip_area_both'] = "Site et Administration";
$lang_module['banip_delete_confirm'] = "Êtes-vous sûr de vouloir supprimer cette IP de la liste des IPs interdits?";
$lang_module['banip_mask'] = "Masque IP";
$lang_module['banip_edit'] = "Éditer";
$lang_module['banip_delete'] = "Supprimer";
$lang_module['banip_error_ip'] = "Saisissez IP à interdire";
$lang_module['banip_error_area'] = "Merci de sélectionner la zone";
$lang_module['banip_error_validip'] = "Erreur: Adresse IP invalide";
$lang_module['uploadconfig'] = "Configurer le Transfert";
$lang_module['uploadconfig_ban_ext'] = "Types de fichiers interdits";
$lang_module['uploadconfig_ban_mime'] = "Types de Mime interdits";
$lang_module['uploadconfig_types'] = "Types de fichier autorisés";
$lang_module['sys_max_size'] = "Taille maximum du fichier transféré autorisé par votre serveur";
$lang_module['nv_max_size'] = "Taille maximum du fichier transféré";

?>
