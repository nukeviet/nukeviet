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

$lang_translator['author'] = 'Nguyễn Phú Thành <phuthanh.nguyen215@gmail.com>';
$lang_translator['createdate'] = '31/07/2015, 16:30';
$lang_translator['copyright'] = '@Copyright (C) 2009-2021 VINADES.,JSC. Tous droits réservés';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['pagetitle'] = 'Configuration la balise "title"';
$lang_module['metaTagsConfig'] = 'Configuration de Meta-Tags';
$lang_module['linkTagsConfig'] = 'Configuration de Link-Tags';
$lang_module['sitemapPing'] = 'Ping Plan du site';
$lang_module['searchEngine'] = 'Moteur de recherche';
$lang_module['searchEngineConfig'] = 'Gérer les moteurs de recherches';
$lang_module['searchEngineName'] = 'Moteur de recherche';
$lang_module['searchEngineActive'] = 'Activer';
$lang_module['searchEngineSelect'] = 'Sélectionner la moteur de recherche';
$lang_module['sitemapModule'] = 'Sélectionner module';
$lang_module['sitemapView'] = 'Voir sitemap';
$lang_module['sitemapSend'] = 'Envoyer';
$lang_module['PingNotSupported'] = 'Ping n\'est pas supporté';
$lang_module['pleasePingAgain'] = 'Vous venez d\'envoyer. Attendez un moment';
$lang_module['searchEngineValue'] = 'lien';
$lang_module['searchEngineFailed'] = 'Erreur de lien';
$lang_module['pingOK'] = 'Sitemap est envoyé avec succès. Cette opération peut être refait dans 60 minutes';
$lang_module['submit'] = 'Soumettre';
$lang_module['weight'] = 'Numéro';
$lang_module['robots'] = 'Config. de robots.txt';
$lang_module['robots_number'] = 'Ordre';
$lang_module['robots_filename'] = 'Nom du fichier';
$lang_module['robots_type'] = 'Mode';
$lang_module['robots_type_0'] = 'Accès interdit';
$lang_module['robots_type_1'] = 'Ne pas afficher dans le fichier robots.txt';
$lang_module['robots_type_2'] = 'Autoriser l\'accès';
$lang_module['robots_error_writable'] = 'Erreur: impossible d\'enregistrer le fichier robots.txt, veuillez créer un fichier robots.txt avec le contenu ci-dessous et mettre dans la répertoire parente du site';
$lang_module['pagetitle2'] = 'Méthode d\'affichage de tag "title"';
$lang_module['pagetitleNote'] = '<strong>Variables acceptés:</strong><br /><br />- <strong>pagetitle</strong>: Titre de page pour des cas définis,<br />- <strong>funcname</strong>: Nom de la function,<br />- <strong>modulename</strong>: Nom de module,<br />- <strong>sitename</strong>: Nom du site';
$lang_module['metaTagsGroupName'] = 'Type du groupe';
$lang_module['metaTagsGroupValue'] = 'Nom du groupe';
$lang_module['metaTagsNote'] = 'Les Meta-Tags: "%s" sont déterminés automatiquement';
$lang_module['metaTagsVar'] = 'Accepter les variables';
$lang_module['metaTagsContent'] = 'Contenu';
$lang_module['metaTagsOgp'] = 'Activer le protocole meta-Tag Open Graph';
$lang_module['metaTagsOgpNote'] = 'Protocole Open Graph: est un critère de données pour partager les données au Facebook, regarde les détails à <a href="http://ogp.me" target="_blank">http://ogp.me</a>';
$lang_module['description_length'] = 'Nombre de caractères dans la balise meta tag description';
$lang_module['description_note'] = '= 0 ne limite pas le nombre de caractères';
$lang_module['module'] = 'Module';
$lang_module['custom_title'] = 'Titre';
$lang_module['rpc'] = 'Service PING';
$lang_module['rpc_setting'] = 'Configuration du service PING';
$lang_module['rpc_error_timeout'] = 'Veuillez attendre dans %s pour continuer PING';
$lang_module['rpc_error_titleEmpty'] = 'Veuillez donner le nom  d\'URL à Ping';
$lang_module['rpc_error_urlEmpty'] = 'Veuillez donner le bon nom d\'URL à Ping';
$lang_module['rpc_error_rsschannelEmpty'] = 'Veuillez donner le bon canal RSS de cet URL';
$lang_module['rpc_error_serviceEmpty'] = 'Service ne peut pas être utilisé. Veuillez l\'informer au responsable du site Web';
$lang_module['rpc_error_unknown'] = 'Erreur non-déterminé';
$lang_module['rpc_flerror0'] = 'PING réussi';
$lang_module['rpc_flerror1'] = 'Erreur';
$lang_module['rpc_ftitle'] = 'PING est un outils gratuit qui vous aide de créer les articles rapidement pour les pages de votre site Web sur les grands ordinateurs central de recherche';
$lang_module['rpc_webtitle'] = 'Nom de la page';
$lang_module['rpc_weblink'] = 'URL de la page';
$lang_module['rpc_rsslink'] = 'Canal RSS de la page';
$lang_module['rpc_submit'] = 'PING !';
$lang_module['rpc_linkname'] = 'Ordinateur central';
$lang_module['rpc_reruslt'] = 'Résultat';
$lang_module['rpc_message'] = 'Information';
$lang_module['rpc_ping'] = 'PING en misant à jour des données';
$lang_module['rpc_ping_page'] = 'PING article';
$lang_module['rpc_finish'] = 'Processus de PING réussit, voulez-vous revenir à la page de gestion des articles?';
$lang_module['private_site'] = 'Décourager les moteurs de recherche d\'indexer ce site';
$lang_module['ogp_image'] = 'Image par défaut pour les balises Open Graph<br/>(meilleure taille: 1080px x 1080px)';

$lang_module['linkTags_attribute'] = 'Attribut';
$lang_module['linkTags_value'] = 'Valeur';
$lang_module['linkTags_add_attribute'] = 'Ajouter un attribut';
$lang_module['linkTags_rel_val_required'] = 'Vous devez déclarer la valeur de l\'attribut rel';
$lang_module['linkTags_add'] = 'Ajouter une nouvelle balise de lien';
$lang_module['linkTags_acceptVars'] = 'Variables acceptées dans la valeur d\'attribut';
$lang_module['linkTags_del_confirm'] = 'Voulez-vous vraiment supprimer?';
