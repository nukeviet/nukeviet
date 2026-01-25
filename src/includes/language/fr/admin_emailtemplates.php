<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2012 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['copy'] = 'Copie';
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
$lang_module['categories_show'] = 'Afficher l\'e-mail dans la liste';
$lang_module['tpl_send_name'] = 'Expéditeur &amp; Email envoyé';
$lang_module['tpl_send_cc'] = 'CC';
$lang_module['tpl_send_bcc'] = 'BCC';
$lang_module['tpl_is_plaintext'] = 'Texte brut';
$lang_module['tpl_is_plaintext_help'] = 'Supprimer le formatage du contenu du courrier électronique sortant';
$lang_module['tpl_is_disabled'] = 'Annuler l\'envoi de courrier';
$lang_module['tpl_is_disabled_help'] = 'Sélectionnez cette option et le système suspendra le courrier électronique de ce modèle.';
$lang_module['tpl_is_selftemplate'] = 'Modèle d\'individu';
$lang_module['tpl_is_selftemplate_help'] = 'Sélectionnez cette option si vous ne souhaitez pas appliquer de modèles d\'e-mail génériques lors de l\'envoi d\'e-mails';
$lang_module['list_email_help'] = 'Plusieurs e-mails peuvent être entrés, séparés par des virgules';
$lang_module['tpl_send_name_help'] = 'S\'il n\'est pas entré ici, le système prendra à partir du nom du site Web et de l\'adresse électronique du site.';
$lang_module['tpl_basic_info'] = 'Informations de base';
$lang_module['tpl_attachments'] = 'Pièces jointes';
$lang_module['tpl_error_default_subject'] = 'L\'objet du courrier électronique est vide';
$lang_module['tpl_error_default_content'] = 'Le contenu du courrier électronique est vide';
$lang_module['tpl_error_title'] = 'Le nom du modèle d\'e-mail %s est vide';
$lang_module['tpl_error_exists'] = 'Ce nom %s est déjà utilisé, veuillez en choisir un autre pour éviter toute confusion';
$lang_module['tpl_error_smarty_subject'] = 'Pour des raisons de sécurité, vous n\'êtes pas autorisé à utiliser la variable $smarty dans l\'objet par défaut de l\'e-mail';
$lang_module['tpl_error_smarty_subject1'] = 'Pour des raisons de sécurité, vous n\'êtes pas autorisé à utiliser la variable $smarty dans le sujet de l\'e-mail %s';
$lang_module['tpl_error_smarty_content'] = 'Pour des raisons de sécurité, vous n\'êtes pas autorisé à utiliser la variable $smarty dans le contenu des emails par défaut';
$lang_module['tpl_error_smarty_content1'] = 'Pour des raisons de sécurité, vous n\'êtes pas autorisé à utiliser la variable $smarty dans le contenu de l\'e-mail %s';
$lang_module['tpl_title'] = 'Nom du modèle de courrier électronique';
$lang_module['tpl_subject'] = 'Sujet du courriel';
$lang_module['tpl_content'] = 'Corps de l\'e-mail';
$lang_module['tpl_incat'] = 'Catégorie';
$lang_module['default_content'] = 'Contenu du courrier électronique par défaut';
$lang_module['default_content_info'] = 'S\'applique à toutes les langues si la langue n\'est pas définie ci-dessous';
$lang_module['lang_content'] = 'Contenu du courrier électronique par langue';
$lang_module['order'] = 'Commande';
$lang_module['adv_info'] = 'Paramètres avancés';
$lang_module['tpl_list'] = 'Liste des modèles d\'email';
$lang_module['tpl_is_active'] = 'Recevoir un email';
$lang_module['tpl_is_disabled'] = 'Arrêtez d\'envoyer des emails';
$lang_module['tpl_is_disabled_label'] = 'Arrêtez';
$lang_module['tpl_custom_label'] = 'Douane';
$lang_module['tpl_plugin'] = 'Plugin';
$lang_module['tpl_plugin_help'] = 'Choisissez le plugin qui gère les champs de fusion dans le contenu du courrier électronique';
$lang_module['tpl_pluginsys'] = 'Plugin système';
$lang_module['tpl_pluginsys_help'] = 'Ces plugins sont fixés sur le modèle de courrier électronique du système et ne peuvent pas être modifiés. Si vous souhaitez ajouter plus, sélectionnez ci-dessous';
$lang_module['function'] = 'Fonction';
$lang_module['rollback_message'] = 'Si lors du processus d’édition, vous avez modifié le titre et le contenu de l’e-mail sans vous souvenir du prototype original. Actuellement, cet e-mail ne peut pas être envoyé normalement. Veuillez cliquer sur le bouton ci-dessous pour restaurer l\'e-mail d\'origine. Le nom, l\'objet, le contenu des emails dans toutes les langues disponibles reviendront à leur état d\'origine.';
$lang_module['update_for'] = 'Effectuez cette action pour';
$lang_module['update_for1'] = 'Ce modèle est disponible dans toutes les langues';
$lang_module['update_for2'] = 'Ce modèle est en %s';
$lang_module['update_for3'] = 'Ce modèle est basé sur des modules du même nom %s';
$lang_module['update_for4'] = 'Ce modèle uniquement';
$lang_module['merge_field'] = 'Merge fields';
$lang_module['merge_field_help'] = 'Ces champs sont automatiquement remplacés par la valeur correspondante lors de l\'exportation du contenu de l\'e-mail. Cliquez sur la description des variables pour remplir l\'éditeur';
$lang_module['merge_field_guild1'] = 'Affichage conditionnel';
$lang_module['merge_field_guild2'] = 'Afficher le contenu en fonction de la condition d\'une variable. Par exemple:';
$lang_module['merge_field_guild3'] = 'Pour plus de détails, voir <a href="https://www.smarty.net/docs/en/language.function.if.tpl" target="_blank">ici</a>';
$lang_module['merge_field_guild4'] = 'Sortie en boucle';
$lang_module['merge_field_guild5'] = 'Boucle tableau pour générer des éléments dans ce tableau. Par exemple:';
$lang_module['merge_field_guild6'] = 'Pour plus de détails, voir <a href="https://www.smarty.net/docs/en/language.function.foreach.tpl" target="_blank">ici</a>';
$lang_module['test'] = 'Envoyer un e-mail de test';
$lang_module['test_tomail'] = 'E-mail reçu';
$lang_module['test_error_tomail'] = 'E-mail reçu vide';
$lang_module['test_error_template'] = 'Ce modèle d\'e-mail n\'existe pas';
$lang_module['test_tomail_note'] = 'Entrez un e-mail par ligne, généralement jusqu\'à 50 e-mails';
$lang_module['test_value_fields'] = 'Les champs personnalisés';
$lang_module['test_success'] = 'Test e-mail envoyé avec succès, veuillez vérifier votre boîte de réception (boîte de spam si elle n\'est pas dans votre boîte de réception) pour voir l\'e-mail reçu';
$lang_module['test_note1'] = 'La fonctionnalité d\'envoi de test par e-mail prend en charge des variables uniques avec des données de chaîne ou numériques telles que <code>$site_name</code>, <code>$username</code> et des données de tableau unidimensionnelles de chaînes ou par exemple <code>$user.full_name</code>';
$lang_module['test_note2'] = 'Si votre variable est un tableau, écrivez <code>$user.full_name</code> au lieu de <code>$user[\'full_name\']</code>';
$lang_module['test_note3'] = 'Lorsque vous rédigez un modèle d\'e-mail avec des conditions et des variables complexes qui dépassent la portée de la prise en charge ci-dessus, testez l\'envoi d\'e-mails à l\'aide de la fonction <code>nv_sendmail_from_template</code> ou de la fonction <code>nv_sendmail_template_async</code> via la programmation';
$lang_module['from'] = 'Depuis';
$lang_module['to'] = 'À';
$lang_module['keywords'] = 'Mot-clé';
$lang_module['all'] = 'Tous';
$lang_module['add_edit'] = 'Ajouter/modifier';
$lang_module['default'] = 'Défaut';
$lang_module['tpl_mailtpl'] = 'Modèle fixe';
