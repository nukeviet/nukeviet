<?php

/**
* @Project NUKEVIET 3.x
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2012 VINADES.,JSC. All rights reserved
* @Language Français
* @Createdate Jun 21, 2010, 10:30:00 AM
*/

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$lang_translator['author'] = 'Phạm Chí Quang';
$lang_translator['createdate'] = '21/6/2010, 17:30';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. Tous droits réservés.';
$lang_translator['info'] = 'Langue française pour NukeViet 3';
$lang_translator['langtype'] = 'lang_module';

$lang_module['modules'] = 'Installation';
$lang_module['blocks'] = 'Configuration des blocks';
$lang_module['language'] = 'Installation de langues';
$lang_module['setup'] = 'Installer';
$lang_module['main'] = 'Liste des modules';
$lang_module['edit'] = 'Éditer le module &ldquo;%s&rdquo;';
$lang_module['caption_actmod'] = 'Liste des modules actifs';
$lang_module['caption_deactmod'] = 'Liste de modules inactifs';
$lang_module['caption_badmod'] = 'Liste des modules inactifs par erreurs';
$lang_module['caption_newmod'] = 'Liste des modules pas encore installés';
$lang_module['module_name'] = 'Module';
$lang_module['custom_title'] = 'Titre';
$lang_module['weight'] = 'Ordre';
$lang_module['in_menu'] = 'Menu en haut';
$lang_module['submenu'] = 'Menu secondaire';
$lang_module['version'] = 'Version';
$lang_module['settime'] = 'Date d\'installation';
$lang_module['author'] = 'Auteur';
$lang_module['theme'] = 'Thème';
$lang_module['theme_default'] = 'Défaut';
$lang_module['keywords'] = 'Mots clés';
$lang_module['keywords_info'] = 'Séparer par virgule';
$lang_module['funcs_list'] = 'Liste des fonctions de module &ldquo;%s&rdquo;';
$lang_module['funcs_title'] = 'Fonction';
$lang_module['funcs_custom_title'] = 'Titre';
$lang_module['funcs_layout'] = 'Layout utilisé';
$lang_module['funcs_in_submenu'] = 'Menu';
$lang_module['funcs_subweight'] = 'Ordre';
$lang_module['activate_rss'] = 'Activer la fonction RSS';
$lang_module['module_sys'] = 'Les Modules du système';
$lang_module['vmodule'] = 'Modules virtuels';
$lang_module['vmodule_add'] = 'Ajout de mod. virtuel';
$lang_module['vmodule_name'] = 'Nom du nouveau module';
$lang_module['vmodule_file'] = 'Module original';
$lang_module['vmodule_note'] = 'Description';
$lang_module['vmodule_select'] = 'Choisir un module';
$lang_module['vmodule_blockquote'] = 'Note: Nom du nouveau module se combine de lettres latines, chiffres et/ou tiret.';
$lang_module['autoinstall'] = 'Auto-Installation';
$lang_module['autoinstall_method'] = 'Sélectionner le procès';
$lang_module['autoinstall_method_none'] = 'Sélectionnez:';
$lang_module['autoinstall_method_module'] = 'Installer Module + Block';
$lang_module['autoinstall_method_block'] = 'Installer Block';
$lang_module['autoinstall_method_packet'] = 'Paqueter Module';
$lang_module['autoinstall_continue'] = 'Suivant';
$lang_module['autoinstall_back'] = 'Précédant';
$lang_module['autoinstall_error_nomethod'] = 'Sélectionnez un type d\'installation !';
$lang_module['autoinstall_module_install'] = 'Installer';
$lang_module['autoinstall_module_select_file'] = 'Sélectionner le fichier d\'installation:';
$lang_module['autoinstall_module_error_filetype'] = 'Erreur: Format zip ou gz obligatoire';
$lang_module['autoinstall_module_error_nofile'] = 'Erreur: Il faut sélectionner un fichier d\'installation';
$lang_module['autoinstall_module_nomethod'] = 'Il faut se1lectionner le type';
$lang_module['autoinstall_module_uploadedfile'] = 'Le système a transféré les fichiers:';
$lang_module['autoinstall_module_uploadedfilesize'] = 'Taille:';
$lang_module['autoinstall_module_uploaded_filenum'] = 'Total fichiers + répertoires:';
$lang_module['autoinstall_module_error_uploadfile'] = 'Erreur: il est impossible de transférer les fichiers vers le serveur. Vérifier les permissions (chmod 777) du répertoire tmp';
$lang_module['autoinstall_module_error_createfile'] = 'Erreur: il est impossible d\'enregistrer les fichiers. Vérifier les permissions (chmod 777) du répertoire tmp';
$lang_module['autoinstall_module_error_invalidfile'] = 'Erreur: Fichier zip incompatible';
$lang_module['autoinstall_module_error_invalidfile_back'] = 'Précédant';
$lang_module['autoinstall_module_error_warning_overwrite'] = 'Notification: structure de ce module n\'est pas conforme, êtes-vous sur de vouloir continuer?';
$lang_module['autoinstall_module_overwrite'] = 'Continuer';
$lang_module['autoinstall_module_error_warning_fileexist'] = 'Liste des fichiers existants sur le serveur:';
$lang_module['autoinstall_module_error_warning_invalidfolder'] = 'Structure du répertoire imcompatible:';
$lang_module['autoinstall_module_error_warning_permission_folder'] = 'Il est impossible à créer le répertoire à cause de safe mod on';
$lang_module['autoinstall_module_checkfile_notice'] = 'Pour continuer l\'installation, cliquez sur VÉRIFIER, le système vous aidera a vérifier la compatibilité';
$lang_module['autoinstall_module_checkfile'] = 'VÉRIFIER !';
$lang_module['autoinstall_module_installdone'] = 'INSTALLER...';
$lang_module['autoinstall_module_cantunzip'] = 'Impossible de décompresser. Vérifiez les permissions (Chmod) des répertoires.';
$lang_module['autoinstall_module_unzip_success'] = 'Installation est terminée avec succès. Vous serez amené à la page d\'activation.';
$lang_module['autoinstall_module_unzip_setuppage'] = 'À la gestion des modules.';
$lang_module['autoinstall_module_unzip_filelist'] = 'Liste des fichiers décompressés';
$lang_module['autoinstall_module_error_movefile'] = 'Installation automatique impossible. Votre hôte ne supporte pas le déplacement des fichiers après décompresser';
$lang_module['autoinstall_package_select'] = 'Choisir le module à paqueter';
$lang_module['autoinstall_package_noselect'] = 'Il faut sé1ectionner un module pour paqueter';
$lang_module['autoinstall_package_processing'] = 'Veuillez patienter quelques instants...';
$lang_module['mobile'] = 'Theme mobile';
$lang_module['delete_module_info1'] = 'Ce module existe sur la langue <strong>%s</strong>, il faut tout d\'abord supprimer ce module sur cette langue';
$lang_module['delete_module_info2'] = 'Il y a %d modules virtuels basés sur ce module, il faut les supprimer d\'abord';
$lang_module['admin_title'] = 'Titre de la section d\'administration';
$lang_module['change_func_name'] = 'Changer le nom de la fonction "%s" de module "%s"';
$lang_module['edit_error_update_theme'] = 'La mise a jour a détecté que le thème  %s est invalide ou erroné, merci de vérifier';
$lang_module['description'] = 'Description';

?>