<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';
$lang_translator['createdate'] = '17/11/2022, 11:00';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main_title'] = 'Gestion des API';
$lang_module['role_management'] = 'Gestion des rôles API';
$lang_module['add_role'] = 'Ajouter un rôle d\'API';
$lang_module['edit_role'] = 'Modifier le rôle d\'API';
$lang_module['api_addtime'] = 'Créé à';
$lang_module['api_edittime'] = 'Mis à jour à';
$lang_module['api_roles_list'] = 'Liste des rôles de l\'API';
$lang_module['api_roles_empty'] = 'Les rôles d\'API demandés sont introuvables';
$lang_module['api_roles_empty2'] = 'Aucun rôle d\'API n\'est créé. Veuillez d\'abord créer une API de rôle. Le système bascule automatiquement sur la page de création de l\'API de rôle dans un instant';
$lang_module['api_roles_title'] = 'Nom de rôle de l\'API';
$lang_module['api_roles_description'] = 'Rôle de description d\'API';
$lang_module['api_roles_allowed'] = 'APIs';
$lang_module['api_roles_error_title'] = 'Erreur: le nom du rôle de l\'API n\'a pas été entré';
$lang_module['api_roles_error_exists'] = 'Erreur: Ce nom de rôle d\'API existe déjà. Veuillez entrer un nom différent pour éviter toute confusion.';
$lang_module['api_roles_error_role'] = 'Erreur: Aucune API sélectionnée';
$lang_module['api_roles_api_doesnt_exist'] = 'Les API ne sont pas reconnues';
$lang_module['api_roles_checkall'] = 'Sélectionner tout';
$lang_module['api_roles_uncheckall'] = 'Décocher tout';
$lang_module['api_roles_detail'] = 'Liste API de';
$lang_module['api_role_notice'] = 'Remarque: Selon le niveau du compte d\'administrateur sous licence, les API utilisées dans chaque API de rôle seront redéfinies.';
$lang_module['api_role_notice_lang'] = 'Les API système sont valides pour toutes les langues. Les API du module ne sont valides que pour la langue actuelle.';
$lang_module['api_of_system'] = 'Système';
$lang_module['api_role_credential'] = 'Accès aux rôles API';
$lang_module['api_role_credential_empty'] = 'Aucun objet n\'a encore accès à ce rôle d\'API';
$lang_module['api_role_select'] = 'Sélectionnez le rôle de l\'API';
$lang_module['api_role'] = 'Rôle API';
$lang_module['api_role_credential_add'] = 'Ajouter un accès';
$lang_module['api_role_credential_edit'] = 'Modifier les informations d\'accès';
$lang_module['api_role_credential_search'] = 'Recherche d\'objet';
$lang_module['api_role_credential_error'] = 'Veuillez déclarer l\'objet affecté à cet accès API-role';
$lang_module['api_role_credential_addtime'] = 'Démarrage';
$lang_module['api_role_credential_access_count'] = 'Nombre d\'appels<br/>de rôle API';
$lang_module['api_role_credential_last_access'] = 'Dernier appel<br/>au rôle API';
$lang_module['api_role_credential_userid'] = 'ID';
$lang_module['api_role_credential_username'] = 'Login';
$lang_module['api_role_credential_fullname'] = 'Nom et prénom';
$lang_module['status'] = 'Statut';
$lang_module['active'] = 'Actif';
$lang_module['inactive'] = 'Inactif';
$lang_module['activated'] = 'Activé';
$lang_module['not_activated'] = 'Non activé';
$lang_module['suspended'] = 'Suspendu';
$lang_module['activate'] = 'Activer';
$lang_module['deactivate'] = 'Désactiver';
$lang_module['api_role_status'] = 'Statut<br/>du rôle d\'API';
$lang_module['api_role_credential_status'] = 'Statut<br/>de l\'utilisateur';
$lang_module['api_role_credential_unknown'] = 'Objet inconnu';
$lang_module['api_role_credential_count'] = 'Nombre d\'accès';
$lang_module['api_role_type'] = 'Type de rôle API';
$lang_module['api_role_type_private'] = 'Privé';
$lang_module['api_role_type_public'] = 'Public';
$lang_module['api_role_type_private2'] = 'Rôles API rien que pour vous';
$lang_module['api_role_type_public2'] = 'Rôles API publics';
$lang_module['api_role_object'] = 'Objet de rôle API';
$lang_module['api_role_object_admin'] = 'Administrateur';
$lang_module['api_role_object_user'] = 'Utilisateur';
$lang_module['api_role_type_private_note'] = 'Un rôle d\'API privé est un groupe d\'API qu\'un objet ne peut pas s\'enregistrer lui-même pour utiliser. Seul l\'administrateur général est autorisé à attribuer un rôle d\'API privé à certains objets';
$lang_module['api_role_type_public_note'] = 'Le rôle d\'API publique est un groupe d\'API que n\'importe quel objet peut s\'inscrire pour utiliser';
$lang_module['api_role_type_private_error'] = 'Le rôle d\'API n\'autorise pas l\'activation arbitraire de l\'utilisation';
$lang_module['all'] = 'Toute';
$lang_module['authentication'] = 'Authentication';
$lang_module['not_access_authentication'] = 'Vous n\'avez pas créé d\'authentification d\'accès par rôle d\'API';
$lang_module['recreate_access_authentication_info'] = 'Si vous avez oublié le code secret, recréez l\'authentification';
$lang_module['create_access_authentication'] = 'Créer une authentification';
$lang_module['recreate_access_authentication'] = 'Recréer l\'authentification';
$lang_module['api_credential_ident'] = 'Touches d\'accès';
$lang_module['api_credential_secret'] = 'Code secret';
$lang_module['auth_method'] = 'Méthode';
$lang_module['auth_method_select'] = 'Veuillez choisir une méthode d\'authentification';
$lang_module['auth_method_password_verify'] = 'password_verify (recommander)';
$lang_module['auth_method_md5_verify'] = 'md5_verify';
$lang_module['auth_method_none'] = 'Aucun, pour le développement';
$lang_module['value_copied'] = 'La valeur a été copiée';
$lang_module['api_ips'] = 'IP d\'accès';
$lang_module['api_ips_help'] = 'Les IP sont séparées par des virgules. L\'accès au rôle d\'API se fait uniquement à partir de ces IP. Le laisser vide signifie ne pas vérifier l\'IP';
$lang_module['api_ips_update'] = 'Mettre à jour l\'IP d\'accès';
$lang_module['deprivation'] = 'Privation';
$lang_module['deprivation_confirm'] = 'Voulez-vous vraiment priver cet utilisateur d\'autorisations?';
$lang_module['config'] = 'Réglages';
$lang_module['remote_api_access'] = 'Activer l\'API à distance';
$lang_module['remote_api_access_help'] = 'La désactivation de tous les accès API de l\'extérieur sera bloquée. Les API internes sont toujours utilisées normalement';
$lang_module['api_remote_off'] = 'L\'API distante <strong>est désactivée</strong>, les appels d\'API ne seront donc pas possibles. Pour prendre en charge les appels d\'API, <strong><a href="%s">activez l\'API distante ici</a></strong>';
$lang_module['api_remote_off2'] = 'L\'API distante <strong>est désactivée</strong>, les appels d\'API ne seront donc pas possibles.';
$lang_module['cat_api_list'] = 'Liste des API sous catégorie';
$lang_module['flood_blocker'] = 'Restriction de requête';
$lang_module['flood_blocker_note'] = 'Si vous laissez ces champs vides, le nombre de requêtes est illimité';
$lang_module['flood_limit'] = 'Requêtes maximales';
$lang_module['flood_interval'] = 'Dans les';
$lang_module['minutes'] = 'minutes';
$lang_module['hours'] = 'heures';
$lang_module['log_period'] = 'Période de conservation des journaux';
$lang_module['log_period_note'] = 'Le laisser vide signifie qu\'il n\'y a pas de journaux';
$lang_module['flood_interval_error'] = 'La période de validité d\'une règle de restriction des demandes ne peut pas dépasser la période de conservation des journaux';
$lang_module['logs'] = 'Demander des journaux';
$lang_module['log_time'] = 'Heure de la demande';
$lang_module['log_ip'] = 'Depuis IP';
$lang_module['log_del_confirm'] = 'Voulez-vous vraiment supprimer?';
$lang_module['del_selected'] = 'Supprimer ceux sélectionnés';
$lang_module['del_all'] = 'Supprimer tout';
$lang_module['api_select'] = 'Veuillez sélectionner l\'API';
$lang_module['fromdate'] = 'Demande du';
$lang_module['todate'] = 'Demande au';
$lang_module['filter_logs'] = 'Filtrer les journaux';
$lang_module['endtime'] = 'Heure de fin';
$lang_module['quota'] = 'Quota';
$lang_module['indefinitely'] = 'Indéfiniment';
$lang_module['no_quota'] = 'Pas de quota';
$lang_module['addtime_note'] = 'Si laissé vide, cela sera interprété comme l\'heure actuelle';
$lang_module['endtime_note'] = 'Si le champ est laissé vide, il sera interprété comme indéfiniment';
$lang_module['quota_note'] = 'Si le champ est laissé vide, il sera interprété comme illimité';
