<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Language Tiếng Việt
 * @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)
 * @Createdate Mar 04, 2010, 03:22:00 PM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2012 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['errorsave'] = 'Une erreur inconnue menant à des données ne peut pas être enregistrée';
$lang_module['add_template'] = 'Ajouter un modèle de courrier électronique';
$lang_module['edit_template'] = 'Modifier le modèle de courrier électronique';
$lang_module['categories'] = 'Catégories de modèles de courrier électronique';
$lang_module['categories_other'] = 'Autres';
$lang_module['categories_list'] = 'Liste de catégories';
$lang_module['categories_add'] = 'Ajouter une catégorie';
$lang_module['categories_edit'] = 'Modifier la catégorie';
$lang_module['categories_title'] = 'Nom de catégorie';
$lang_module['categories_error_title'] = 'Nom de la catégorie non déjà entré';
$lang_module['categories_error_exists'] = 'Ce nom de catégorie a déjà été utilisé, veuillez choisir un autre nom';
$lang_module['tpl_send_name'] = 'Expéditeur &amp; Email envoyé';
$lang_module['tpl_send_cc'] = 'CC';
$lang_module['tpl_send_bcc'] = 'BCC';
$lang_module['tpl_is_plaintext'] = 'Texte brut';
$lang_module['tpl_is_plaintext_help'] = 'Supprimer le formatage du contenu du courrier électronique sortant';
$lang_module['tpl_is_disabled'] = 'Annuler l\'envoi de courrier';
$lang_module['tpl_is_disabled_help'] = 'Sélectionnez cette option et le système suspendra le courrier électronique de ce modèle.';
$lang_module['list_email_help'] = 'Plusieurs e-mails peuvent être entrés, séparés par des virgules';
$lang_module['tpl_send_name_help'] = 'S\'il n\'est pas entré ici, le système prendra à partir du nom du site Web et de l\'adresse électronique du site.';
$lang_module['tpl_basic_info'] = 'Informations de base';
$lang_module['tpl_attachments'] = 'Pièces jointes';
$lang_module['tpl_error_default_subject'] = 'Erreur: l\'objet du courrier électronique est vide';
$lang_module['tpl_error_default_content'] = 'Erreur: le contenu du courrier électronique est vide';
$lang_module['tpl_error_title'] = 'Erreur: nom du modèle de courrier électronique vide';
$lang_module['tpl_error_exists'] = 'Erreur: le nom du modèle de courrier électronique a déjà été utilisé. Veuillez choisir un autre nom pour éviter toute confusion';
$lang_module['tpl_title'] = 'Nom du modèle de courrier électronique';
$lang_module['tpl_subject'] = 'Sujet du courriel';
$lang_module['tpl_incat'] = 'Catégorie';
$lang_module['default_content'] = 'Contenu du courrier électronique par défaut';
$lang_module['default_content_info'] = 'S\'applique à toutes les langues si la langue n\'est pas définie ci-dessous';
$lang_module['lang_content'] = 'Contenu du courrier électronique par langue';
$lang_module['lang_content_info'] = 'Poser sa candidature <strong>%s</strong> seulement';
$lang_module['tpl_list'] = 'Liste des modèles d\'email';
$lang_module['tpl_is_active'] = 'Recevoir un email';
$lang_module['tpl_is_disabled'] = 'Arrêtez d\'envoyer des emails';
$lang_module['tpl_is_disabled_label'] = 'Arrêtez';
$lang_module['tpl_custom_label'] = 'Douane';
$lang_module['tpl_plugin'] = 'Plugin';
$lang_module['tpl_plugin_help'] = 'Choisissez le plugin qui gère les champs de fusion dans le contenu du courrier électronique';
$lang_module['tpl_pluginsys'] = 'Plugin système';
$lang_module['tpl_pluginsys_help'] = 'Ces plugins sont fixés sur le modèle de courrier électronique du système et ne peuvent pas être modifiés. Si vous souhaitez ajouter plus, sélectionnez ci-dessous';
$lang_module['merge_field'] = 'Merge fields';
$lang_module['merge_field_help'] = 'Ces champs sont automatiquement remplacés par la valeur correspondante lors de l\'exportation du contenu de l\'e-mail. Cliquez sur la description des variables pour remplir l\'éditeur';
$lang_module['merge_field_guild1'] = 'Affichage conditionnel';
$lang_module['merge_field_guild2'] = 'Afficher le contenu en fonction de la condition d\'une variable. Par exemple:';
$lang_module['merge_field_guild3'] = 'Pour plus de détails, voir <a href="https://www.smarty.net/docs/en/language.function.if.tpl" target="_blank">ici</a>';
$lang_module['merge_field_guild4'] = 'Sortie en boucle';
$lang_module['merge_field_guild5'] = 'Boucle tableau pour générer des éléments dans ce tableau. Par exemple:';
$lang_module['merge_field_guild6'] = 'Pour plus de détails, voir <a href="https://www.smarty.net/docs/en/language.function.foreach.tpl" target="_blank">ici</a>';
