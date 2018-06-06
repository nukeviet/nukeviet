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
$lang_translator['langtype'] = 'lang_module';

$lang_module['main'] = 'Infos générales';
$lang_module['database_info'] = 'Infos générales de Base de données &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['tables_info'] = 'Tables de base de données &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['table_caption'] = 'Infos générales de table &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['table_row_caption'] = 'Infos générales des champs de table &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['db_host_info'] = 'Ordinateur maître';
$lang_module['db_sql_version'] = 'Version de l\'ordinateur maître';
$lang_module['db_proto_info'] = 'Version du protocole';
$lang_module['server'] = 'Nom de l\'ordinateur maître';
$lang_module['db_dbname'] = 'Nom de Base de données';
$lang_module['db_uname'] = 'Nom d\'utilisateur';
$lang_module['db_charset'] = 'Table de code CSDL';
$lang_module['db_collation'] = 'Code de comparaison CSDL';
$lang_module['db_time_zone'] = 'Fuseau horaire de base de données';
$lang_module['table_name'] = 'Nom de Table';
$lang_module['table_size'] = 'Taille';
$lang_module['table_max_size'] = 'Maximum';
$lang_module['table_datafree'] = 'Excédentaire';
$lang_module['table_numrow'] = 'Ligne';
$lang_module['table_charset'] = 'Collation';
$lang_module['table_type'] = 'Type';
$lang_module['row_format'] = 'Format des données';
$lang_module['table_auto_increment'] = 'Autonum';
$lang_module['table_create_time'] = 'Date de création';
$lang_module['table_update_time'] = 'Mise à jour';
$lang_module['table_check_time'] = 'Vérifier';
$lang_module['optimize'] = 'Optimiser';
$lang_module['savefile'] = 'Stocker sur le serveur';
$lang_module['download'] = 'Télécharger';
$lang_module['download_now'] = 'Télécharger des données actuelles';
$lang_module['download_all'] = 'Structure et données';
$lang_module['download_str'] = 'Structure';
$lang_module['ext_sql'] = 'Fichier SQL';
$lang_module['ext_gz'] = 'Fichier Gzip';
$lang_module['submit'] = 'Exécuter';
$lang_module['third'] = 'Totale: %1$d; Taille: %2$s; Données excédentaires: %3$s';
$lang_module['optimize_result'] = 'Le système a optimisé les tables:%1$s et libéré %2$s données excédentaires';
$lang_module['nv_show_tab'] = 'Infos des tables &ldquo;%s&rdquo;';
$lang_module['field_name'] = 'Champs';
$lang_module['field_type'] = 'Type';
$lang_module['field_null'] = 'Obligatoire';
$lang_module['field_key'] = 'Mot clé';
$lang_module['field_default'] = 'Par défaut';
$lang_module['field_extra'] = 'Complémentaire';
$lang_module['php_code'] = 'Code PHP';
$lang_module['sql_code'] = 'Code PHP';
$lang_module['save_data'] = 'Sauvegarder';
$lang_module['save_error'] = 'Erreur: il est impossible de sauvegarder le fichier <br /> Merci de vérifier les permissions du répertoire: %1$s, ce répertoire doit être obligatoirement modifiable.';
$lang_module['save_ok'] = 'Sauvegarder avec succès';
$lang_module['save_download'] = 'Cliquez ici pour télécharger le fichier.';
$lang_module['dump_autobackup'] = 'Activer le sauvegarde automatique de données';
$lang_module['dump_backup_ext'] = 'Type de fichier de données';
$lang_module['dump_interval'] = 'Répéter les travails suivants';
$lang_module['dump_backup_day'] = 'Temps de sauvegarder les fichiers backup CSDL';
$lang_module['file_backup'] = 'Sauvegarder les données';
$lang_module['file_nb'] = 'Ordre';
$lang_module['file_name'] = 'Nom du fichier';
$lang_module['file_time'] = 'Horaire';
$lang_module['file_size'] = 'Taille';
$lang_module['sampledata'] = 'Exporter des données d\'exemple';
$lang_module['sampledata_note'] = 'C\'est ainsi que vous pouvez exporter la base de données complète du site Web actuel vers un fichier modèle afin d\'emballer l\'ensemble du site Web. Une fois installé, le système restaurera les anciennes données d\'emballage au lieu d\'installer les données d\'exemple dans le programme d\'installation. Remplissez les champs requis ci-dessous puis cliquez sur le bouton Exécuter pour commencer le processus';
$lang_module['sampledata_creat'] = 'Créer un nouvel exemple de paquet de données';
$lang_module['sampledata_list'] = 'Une liste des paquets de données formatés';
$lang_module['sampledata_empty'] = 'Aucun échantillon de paquets pour le moment';
$lang_module['sampledata_start'] = 'Commencez à créer';
$lang_module['sampledata_dat_init'] = 'Le processus démarre, veuillez ne pas éteindre le navigateur avant d\'avoir terminé le message ou le message d\'erreur. Le système vérifie les informations';
$lang_module['sampledata_name'] = 'Exemple de nom de package';
$lang_module['sampledata_name_rule'] = 'Entrez uniquement les caractères de a-z et 0-9';
$lang_module['sampledata_error_sys'] = 'Erreur du serveur, veuillez recharger la page et réessayer';
$lang_module['sampledata_error_name'] = 'Veuillez entrer un exemple de nom de package';
$lang_module['sampledata_error_namerule'] = 'Veuillez entrer seulement les caractères de a-z et 0-9';
$lang_module['sampledata_error_exists'] = 'Cet exemple de package de données existe déjà. En cliquant à nouveau sur le bouton <strong /> Lancer la création , le système écrase les données de modèle existantes. Si vous ne voulez pas remplacer, entrez un autre nom';
$lang_module['sampledata_error_writetmp'] = 'Erreur: Le système ne peut pas écrire de données, donner l\'autorisation d\'écriture à% s, puis exécuter à nouveau';
$lang_module['sampledata_success_1'] = 'L\'exportation de données réussie! Le système a écrit des données dans le fichier. Vous pouvez maintenant nettoyer le système pour supprimer les fichiers obsolètes, puis supprimer le fichier de configuration et encapsuler le code à partager.';
$lang_module['sampledata_success_2'] = 'Les données sont exportées avec succès mais le système ne peut pas écrire dans le fichier. Vous pouvez télécharger un paquet <a href="%s"> <strong /> ici ! </a>';