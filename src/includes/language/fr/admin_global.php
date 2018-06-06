<?php

/**
* @Project NUKEVIET 4.x
* @Author VINADES.,JSC <contact@vinades.vn>
* @Copyright (C) 2017 VINADES.,JSC. All rights reserved
* @Language Français
* @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)
* @Createdate Jun 21, 2010, 12:30:00 PM
*/

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$lang_translator['author'] = 'Phạm Chí Quang';
$lang_translator['createdate'] = '21/6/2010, 19:30';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. Tous droits réservés.';
$lang_translator['info'] = 'Langue française pour NukeViet 4';
$lang_translator['langtype'] = 'lang_global';

$lang_global['mod_authors'] = 'Administrateurs';
$lang_global['mod_groups'] = 'Groupe';
$lang_global['mod_database'] = 'Base de données';
$lang_global['mod_settings'] = 'Configuration';
$lang_global['mod_cronjobs'] = 'Procès automatiques';
$lang_global['mod_modules'] = 'Gestion des Modules';
$lang_global['mod_themes'] = 'Gestion de l\'inteface';
$lang_global['mod_siteinfo'] = 'Info';
$lang_global['mod_language'] = 'Langues';
$lang_global['mod_upload'] = 'Médias';
$lang_global['mod_webtools'] = 'Utilitaire Web';
$lang_global['mod_seotools'] = 'Outils SEO';
$lang_global['mod_subsite'] = 'Gestion du site fils';
$lang_global['mod_extensions'] = 'Extension';
$lang_global['go_clientsector'] = 'Page d\'Accueil';
$lang_global['go_clientmod'] = 'Prévisualiser';
$lang_global['go_instrucion'] = 'Document de guide';
$lang_global['please_select'] = 'Sélectionnez';
$lang_global['admin_password_empty'] = 'Manque de Mot de passe';
$lang_global['adminpassincorrect'] = 'Mot de passe &ldquo;<strong>%s</strong>&rdquo; incorrect. Essayez de nouveau';
$lang_global['admin_password'] = 'Votre mot de passe';
$lang_global['admin_no_allow_func'] = 'Vous n\'êtes pas authorisé d\'accéder à cette fonction';
$lang_global['admin_suspend'] = 'Est suspendu';
$lang_global['block_modules'] = 'Blocks de modules';
$lang_global['hello_admin1'] = 'Bonjour %1$s ! Votre dernière session était à %2$s';
$lang_global['hello_admin2'] = 'Compte: %1$s ! Votre session est ouverte depuis %2$s';
$lang_global['hello_admin3'] = 'Bonjour %1$s. C\'est votre première session d\'administration';
$lang_global['ftp_error_account'] = 'Erreur: Impossible de se connecter au serveur FTP, merci de vérifier la configuration de FTP';
$lang_global['ftp_error_path'] = 'Erreur: Chemin d\'accès incorrect';
$lang_global['login_error_account'] = 'Erreur: Compte d\'Administrateur manquant ou invalide (pas moins de %1$s caractères, ni plus de  %2$s caractères. Utilisez uniquement les lettres latines, chiffres et tiret)';
$lang_global['login_error_password'] = 'Erreur: Mot de passe manquant ou invalide! (pas moins de %1$s caractères, ni plus de %2$s caractères combinés de lettres latines et chiffres)';
$lang_global['login_error_security'] = 'Erreur: Code de sécurité manquant ou invalide! (il faut %1$s caractères combinés de lettres latines et chiffres)';
$lang_global['error_zlib_support'] = 'Erreur: votre serveur ne supporte pas l\'extension zlib, veuillez demander votre hébergeur de l\'activer pour utiliser cette fonction.';
$lang_global['error_zip_extension'] = 'Erreur: votre serveur ne supporte pas l\'extension ZIP, veuillez demander votre hébergeur de l\'activer pour utiliser cette fonction.';
$lang_global['length_characters'] = 'Nombre de caractères';
$lang_global['length_suggest_max'] = 'Nombre de caractères à saisir';
$lang_global['error_code_1'] = 'Adresse donnée n\'est pas bonne, veuillez la vérifier.';
$lang_global['error_code_2'] = 'Protocole HTTP est interdit dans ce cas';
$lang_global['error_code_3'] = 'Dossier contient des fichier(s) qui va être sauvegardé ne peut pas être écrit';
$lang_global['error_code_4'] = 'Aucun outil ne soutien le protocole HTTP';
$lang_global['error_code_5'] = 'Trop de ré-orientations qui se passent.';
$lang_global['error_code_6'] = 'Certificat SSL ne peut pas être vérifié.';
$lang_global['error_code_7'] = 'Demande HTTP échoué';
$lang_global['error_code_8'] = 'N\'arrive pas à écrire dans les fichiers temporaires';
$lang_global['error_code_9'] = 'Fonction fopen() est échoué en applicant sur ces fichiers';
$lang_global['error_code_10'] = 'Demande HTTP par Curl échoué';
$lang_global['error_code_11'] = 'Un erreur non-déterminé a eu lieu';
$lang_global['error_valid_response'] = 'Les données retournées n\'est pas en bonne forme';
$lang_global['phone_note_title'] = 'Règle de déclarer le numéro de téléphone';
$lang_global['phone_note_content'] = 'Le numéro de téléphone est divisé en 2 parties. La première partie est obligée et est utilisée pour l\'affichage sur le site, la deuxième est facultative et est utilisée pour faire les appels un fois qu\'on clique au dessus.La première partie est écrite librement sans utiliser le crochet. La deuxième partie est mise entre les crochets juste après la première partie et ne contient que les caractères suivants: chiffre, étoile, dièse, virgule, point, point-virgule et plus ([0-9\*\#\.\,\;\+]).Par exemple, si vous utiliser <strong>0438211725 (ext 601)</strong>, alors le numéro <strong>0438211725 (ext 601)</strong> sera affiché simplement sur le site. Si vous déclarez <strong>0438211725 (ext 601)[+84438211725,601]</strong>, alors le système va afficher <strong>0438211725 (ext 601)</strong> sur le site et l\'url quand vous cliquer sur ce dernier sera <strong>tel:+84438211725,601</strong>Vous pouvez déclarer plusieurs numéros selon la règle au dessus. Il sont séparé par |.';
$lang_global['multi_note'] = 'Pouvez déclarer plus qu\'une valeur. Les valeurs sont séparées par les point-virgule';
$lang_global['multi_email_note'] = 'Pouvez déclarer plus qu\'une valeur. Les valeurs sont séparées par les point-virgule. La première adresse email est considéré comme la principale et qui sera utilisée pour envoyer et recevoir des messages';
$lang_global['view_all'] = 'voir tous les';
$lang_global['email'] = 'E-mail';
$lang_global['phonenumber'] = 'Téléphone';