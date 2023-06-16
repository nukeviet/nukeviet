<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'Phạm Chí Quang';
$lang_translator['createdate'] = '21/6/2010, 19:30';
$lang_translator['copyright'] = '@Copyright (C) 2009-2021 VINADES.,JSC. Tous droits réservés';
$lang_translator['info'] = 'Langue française pour NukeViet 4';
$lang_translator['langtype'] = 'lang_module';

$lang_module['order'] = 'Ordre';
$lang_module['nv_lang_data'] = 'Langue de données';
$lang_module['site_lang'] = 'Langue par défaut';
$lang_module['nv_lang_interface'] = 'Langue d\'interface';
$lang_module['nv_lang_setting'] = 'Configuration';
$lang_module['nv_admin_read'] = 'Importer du fichier dans la base de données';
$lang_module['nv_admin_read_all'] = 'Importer des fichiers dans la base de données';
$lang_module['nv_admin_submit'] = 'Exécuter';
$lang_module['nv_lang_module'] = 'Module';
$lang_module['nv_admin_write'] = 'Exporter';
$lang_module['nv_admin_download'] = 'Télécharger';
$lang_module['nv_admin_delete'] = 'Supprimer de la base de données';
$lang_module['nv_admin_write_all'] = 'Exporter toutes les données vers fichiers';
$lang_module['nv_admin_edit'] = 'Éditer';
$lang_module['nv_admin_edit_save'] = 'Sauver';
$lang_module['nv_lang_nb'] = 'Ordre';
$lang_module['nv_lang_area'] = 'Section';
$lang_module['nv_lang_site'] = 'Secteur client';
$lang_module['nv_lang_admin'] = 'Panneau d\'administration';
$lang_module['nv_lang_whole_site'] = 'Tout le site';
$lang_module['nv_lang_func'] = 'Opération';
$lang_module['nv_lang_key'] = 'Signe';
$lang_module['nv_lang_value'] = 'Valeur';
$lang_module['nv_lang_note_edit'] = 'Remarque: Utilisez uniquement les balises HTML suivantes pour les champs de valeur';
$lang_module['nv_lang_author'] = 'Auteur';
$lang_module['nv_lang_createdate'] = 'Date de création';
$lang_module['nv_setting_read'] = 'Méthode d\'enregistrement de données';
$lang_module['nv_setting_type_0'] = 'Enregistrer toutes les valeurs';
$lang_module['nv_setting_type_1'] = 'Ne sauvegarder que les valeurs qui n\'ont pas eu lang_key';
$lang_module['nv_setting_type_2'] = 'Enregistrer uniquement les valeurs avec lang_key';
$lang_module['nv_setting_save'] = 'Mise à jour réussie de la configuration';
$lang_module['nv_lang_show'] = 'Gestion de l\'affichage de langues';
$lang_module['nv_lang_name'] = 'Nom de Langue';
$lang_module['nv_lang_slsite'] = 'Afficher au site';
$lang_module['nv_lang_native_name'] = 'Langue de la region';
$lang_module['nv_lang_sl'] = 'Sélection possible';
$lang_module['nv_lang_error_exit'] = 'Pour vérifier la langue, au moins 2 langues doivent être stockées dans la base de données, dont au moins une langue de base est le vietnamien ou l\'anglais. <a href="%s">Cliquez ici</a> pour écrire les données de langue dans la base de données.';
$lang_module['nv_lang_empty'] = 'No interface languages have been read into the database yet. <a href="%s">Click here</a> to read the language into the database.';
$lang_module['nv_data_note'] = 'Pour télécharger la nouvelle langue, visitez le site Web <a title="Site NukeViet Language Translation 4">NukeViet 4 Language</ a>';
$lang_module['nv_data_note2'] = 'Pour ajouter un nouveau langage de données, vous devez <a title="Activer la fonctionnalité multi-langues: Configuration -&gt; Configuration générale" href="%s">activation multilingue</a>.';
$lang_module['nv_setup'] = 'Déjà installé';
$lang_module['nv_setup_new'] = 'Installer';
$lang_module['nv_setup_delete'] = 'Supprimer les données';
$lang_module['nv_data_setup'] = 'Données de cette langue a été installées';
$lang_module['nv_data_setup_ok'] = 'Installation réussie ! Vous serez redirigé vers la configuration du site dans la nouvelle langue.';
$lang_module['nv_lang_readok'] = 'Lecture réussie de langue d\'interface, aller à la liste des fichiers. Cliquez ici si vous attendez trop longtemps';
$lang_module['nv_lang_deleteok'] = 'Suppression de la langue de la base de données terminée.';
$lang_module['nv_lang_wite_ok'] = 'Données de langue exportées vers les fichiers suivants';
$lang_module['nv_lang_delete'] = 'Supprimer la langue de l\'interface de la base de données';
$lang_module['nv_lang_delete_error'] = 'Erreur de la suppression des fichiers de langue d\'interface, vérifiez les permissions des fichiers.';
$lang_module['nv_error_write_file'] = 'Erreur de création du fichier';
$lang_module['nv_error_write_module'] = 'Erreur: fichier inmodifiable';
$lang_module['nv_error_exit_module'] = 'Erreur: manque de langue de module';
$lang_module['nv_lang_check'] = 'Vérification de langue';
$lang_module['nv_lang_data_source'] = 'Comparer avec la langue';
$lang_module['nv_lang_checkallarea'] = 'Toutes les sections';
$lang_module['nv_lang_check_no_data'] = 'Aucun résultat';
$lang_module['nv_check_type'] = 'Condition de vérification';
$lang_module['nv_check_type_0'] = 'Vérifier langue non-traduite';
$lang_module['nv_check_type_1'] = 'Vérifier les mots pareils';
$lang_module['nv_check_type_2'] = 'Vérifier tout';
$lang_module['nv_lang_check_title'] = 'Vérifier les lignes pas encore traduites';
$lang_module['countries'] = 'Langue selon pays';
$lang_module['countries_name'] = 'Pays';
$lang_module['lang_installed'] = 'Langue a installé';
$lang_module['lang_can_install'] = 'Langue est pas installé';
$lang_module['key_is_duplicate'] = 'Cette clé est en double';
$lang_module['field_is_required'] = 'Ce champ est obligatoire pour être déclaré';
$lang_module['language_to_check'] = 'Langue à vérifier';
$lang_module['read_files'] = 'Les fichiers suivants ont été lus dans la base de données';
