<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Module\users\Shared\Emails;
use NukeViet\Template\Email\Cat;

if ($module_name == 'users') {
    $catid = Cat::CAT_USER;
    $pids = '3';
    $is_system = 1;
    $pfile = '';
} else {
    $catid = Cat::CAT_MODULE;
    $pids = '';
    $is_system = 0;
    $pfile = 'emf_code_user.php';
}

$module_emails[Emails::REGISTER_ACTIVE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Activation du compte par email',
    's' => 'Infos pour l\'activation du compte',
    'c' => '{$greeting_user}<br /><br />Votre compte sur le site Web {$site_name} attend d\'être activé. Pour l\'activer, veuillez cliquer sur le lien suivant:<br /><br />URL: <a href="{$link}">{$link}</a><br /><br />Informations sur le compte:<br /><br />Nom d\'utilisateur: {$username}<br />E-mail: {$email}<br /><br />Activation expirée le {$active_deadline}<br /><br />Ceci est un envoi automatique d\'e-mail du site Web {$site_name}.'
];
$module_emails[Emails::USER_DELETE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification de suppression du compte',
    's' => 'Notification de suppression du compte',
    'c' => '{$greeting_user}<br /><br />Nous sommes désolé de vous informer la suppression de votre compte sur le site {$site_name}.'
];
$module_emails[Emails::NEW_2STEP_CODE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Codes de sauvegarde',
    's' => 'Codes de sauvegarde',
    'c' => '{$greeting_user}<br /><br />Code de sauvegarde sur votre compte sur le site {$site_name} a été changé. Voici le nouveau code de sauvegarde:<br /><br />{foreach from=$new_code item=code}{$code}<br />{/foreach}<br />Vous gardez les codes de sauvegarde sécurisés. Si vous perdez votre téléphone et prenez les deux codes de sauvegarde que vous ne serez pas en mesure d\'accéder à votre compte. <br /> <br /> C\'est un message automatique envoyé à votre boîte de réception e-mail à partir du site {$site_name}. Si vous ne comprenez rien au sujet du contenu de cette lettre, supprimer tout simplement.'
];
$module_emails[Emails::NEW_INFO] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification que le compte a été créé lorsque le membre s\'inscrit avec succès dans le formulaire',
    's' => 'Votre compte a été créé',
    'c' => '{$greeting_user}<br /><br />Votre compte sur le site Web {$site_name} a été activé. Ci-dessous les informations du compte:<br /><br />Nom d\'utilisateur: {$username}<br />Email: {$email}<br /><br />Veuillez cliquer sur le lien ci-dessous pour vous connecter:<br />URL: <a href="{$link}">{$link}</a><br /><br />Il s\'agit d\'un message automatique envoyé à votre adresse e-mail depuis le site Web {$site_name}.'
];
$module_emails[Emails::NEW_INFO_OAUTH] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification indiquant que le compte a été créé lorsque le membre s\'inscrit avec succès via Oauth',
    's' => 'Votre compte a été créé',
    'c' => '{$greeting_user}<br /><br />Votre compte sur le site Web {$site_name} est activé. Pour vous connecter à votre compte, veuillez visiter la page: <a href="{$link}">{$link}</a> et appuyez sur le bouton: Connectez-vous avec {$oauth_name}.<br /><br />Cela est un message automatique qui était envoyé à votre boîte mail à partir du site {$site_name}. Si vous ne comprenez pas le contenu de ce mail, vous pouvez simplement le supprimer.'
];
$module_emails[Emails::ADDED_BY_LEADER] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification de compte créé par le responsable d\'équipe',
    's' => 'Votre compte a été créé',
    'c' => '{$greeting_user}<br /><br />Votre compte Site de {$site_name} été activé. Voici vos informations de connexion:<br /><br />URL: <a href="{$link}">{$link}</a><br />Nom: {$username}<br />Email: {$email}<br /><br />Ceci est un message automatique envoyé à votre boîte de réception e-mail à partir du site {$site_name}. Si vous ne comprenez pas quelque chose sur le contenu de cette lettre, il suffit de le supprimer.'
];
$module_emails[Emails::ADDED_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification de compte créé par l\'administrateur',
    's' => 'Votre compte a été créé',
    'c' => '{$greeting_user}<br /><br />Votre compte sur le site {$site_name} a été créé. Voici vos informations de connexion:<br /><br />URL: <a href="{$link}">{$link}</a><br />Nom: {$username}<br />Mot de passe: {$password}<br />{if $pass_reset gt 0 or $email_reset gt 0}<br />Remarque:<br />{if $pass_reset eq 2}- Nous vous recommandons de changer votre mot de passe avant d\'utiliser le compte.<br />{elseif $pass_reset eq 1}- Vous devez changer votre mot de passe avant d\'utiliser le compte.<br />{/if}{if $email_reset eq 2}- Nous vous recommandons de changer votre email avant d\'utiliser le compte.<br />{elseif $email_reset eq 1}- Vous devez changer votre email avant d\'utiliser le compte.<br />{/if}{/if}<br />Ceci est un message automatique envoyé. votre boîte de réception e-mail à partir du site web {$site_name}. Si vous ne comprenez rien au sujet du contenu de cette lettre, supprimer tout simplement.'
];
$module_emails[Emails::SAFE_KEY] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Envoyer le code de vérification lorsque l\'utilisateur active/désactive le mode sans échec',
    's' => 'Code de certifier en mode sans échec',
    'c' => '{$greeting_user}<br /><br />Vous avez demandé l\'utilisation du mode sans échec sur le site {$site_name}. En dessous est le code de certifier pour l\'activer ou le désactiver:<br /><br /><strong>{$code}</strong><br /><br />Ce code ne peut être utilisé qu\'une seule fois. Apres la désactivation de ce mode, ce code est inutilisable.<br /><br /> C\'est un courier automatique qui est envoyé à votre email à partir du site {$site_name}.'
];
$module_emails[Emails::SELF_EDIT] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notifier les modifications de compte que l\'utilisateur vient d\'effectuer',
    's' => 'La mise à jour les infos du compte réussite',
    'c' => '{$greeting_user}<br /><br />Votre compte sur le site Web {$site_name} {if $send_newvalue}mis à jour avec le nouveau {$label} <strong>{$newvalue}</strong>{else}le nouveau {$label} a été mis à jour{/if}.<br /><br />C\'est un courier automatique qui est envoyé à votre email à partir du site {$site_name}.'
];
$module_emails[Emails::EDIT_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notifier les modifications de compte qui viennent d\'être apportées par l\'administrateur',
    's' => 'Votre compte a été mis à jour',
    'c' => '{$greeting_user}<br /><br />Votre compte sur le site {$site_name} a été mis à jour. Voici vos informations de connexion :<br /><br />URL : <a href="{$link}">{$link}</a><br />Alias : {$username}<br />Email : {$email}{if not empty($password)}<br />Mot de passe : {$password}{/if}<br />{if $pass_reset gt 0 or $email_reset gt 0}<br />Remarque :<br />{if $pass_reset eq 2}- Nous vous recommandons de changer votre mot de passe avant d\'utiliser le compte.<br />{elseif $pass_reset eq 1}- Vous devez changer votre mot de passe avant d\'utiliser le compte.<br />{/if}{if $email_reset eq 2}- Nous vous recommandons de changer votre email avant d\'utiliser le compte.<br />{elseif $email_reset eq 1}- Vous devez changer votre email avant d\'utiliser le compte.<br />{/if}{/if}<br />Ceci est un email automatique envoyé à votre boîte de réception depuis le site {$site_name}. Si vous ne comprenez pas le contenu de cet email, supprimez-le simplement.'
];
$module_emails[Emails::VERIFY_EMAIL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'E-mail de confirmation pour changer l\'e-mail du compte',
    's' => 'Infos d\'activation de changement d\'email',
    'c' => '{$greeting_user}<br /><br />Vous avez demandé l\'utilisation du mode sans échec sur le site {$site_name}. Pour valider le changement, vous devez declarer votre nouvel email en saisissant le code de certifier en dessous dans le zone Modification des infos:<br /><br />Code de certifier: <strong>{$code}</strong><br /><br />Ce code est utilisable jusqu\'à {$deadline}.<br /><br />C\'est un courier automatique qui est envoyé à votre email à partir du site {$site_name}. SI vous ne comprenez pas le contenu de ce courrier vous pouvez le supprimer simplement.'
];
$module_emails[Emails::GROUP_JOIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Avis demandant de rejoindre le groupe',
    's' => 'Demander à joindre le groupe',
    'c' => 'Bonjour chef <strong>{$group_name}</strong>,<br /><br /><strong>{$full_name}</strong> a envoyé la demande à rejoindre le groupe <strong>{$group_name}</strong> parce que vous gérez. Vous devez approuver cette demande!<br /><br />S\'il vous plaît visitez <a href="{$link}">ce lien</a> d\'approuver l\'adhésion.'
];
$module_emails[Emails::LOST_ACTIVE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Renvoyer les informations d\'activation du compte',
    's' => 'Activer le compte',
    'c' => '{$greeting_user}<br /><br />Votre compte dans le site Web {$site_name} est en attendant d\'être activé. Pour l\'activer, vous cliquez sur le lien au dessous:<br /><br />URL: <a href="{$link}">{$link}</a><br />Les informations nécessaires:<br />Compte: {$username}<br />Email: {$email}<br />Mot de passe: {$password}<br /><br />L\'activation du compte n\'est disponible que jusqu\'à {$active_deadline}<br /><br />Ce mail vous est envoyé automatiquement à partir du site Web {$site_name}. Si vous ne comprenez pas le contenu de ce mail, vous pouvez le supprimer simplement.'
];
$module_emails[Emails::LOST_PASS] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Instructions pour récupérer le mot de passe du membre',
    's' => 'Guide de rechercher le mot de passe',
    'c' => '{$greeting_user}<br /><br />Vous proposez de changer mon mot de passe de connexion sur le site {$site_name}. Pour changer votre mot de passe, vous devrez saisir le code de vérification ci-dessous dans la case correspondante dans la zone de changement de mot de passe.<br /><br />Le code de vérification: <strong>{$code}</strong><br /><br />Ce code n\'est utilisé qu\'une seule fois et avant la date limite de: {$deadline}.<br /><br />Cette lettre est automatiquement envoyée dans votre boîte de réception e-mail depuis le site {$site_name}. Si vous ne comprenez rien au contenu de cette lettre, supprimez-la simplement. Si vous ne comprenez rien au contenu de cette lettre, supprimez-la simplement.'
];
$module_emails[Emails::R2S] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notez que l\'authentification en deux étapes a été supprimée avec succès',
    's' => 'La vérification en deux étapes est désactivée',
    'c' => '{$greeting_user}<br /><br />À votre demande, nous avons désactivé la vérification en deux étapes pour votre compte sur le site Web {$site_name}.<br /><br />Il s\'agit d\'un envoi automatique d\'e-mail depuis le site Web {$site_name}.'
];
$module_emails[Emails::R2S_REQUEST] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Instructions pour désactiver l\'authentification en deux étapes lorsque vous oubliez votre code',
    's' => 'Informations sur la désactivation de la vérification en deux étapes',
    'c' => '{$greeting_user}<br /><br />Nous avons reçu une demande de suppression de la vérification en deux étapes pour votre compte sur le site Web {$site_name}. Si vous avez envoyé cette demande vous-même, veuillez utiliser le code de vérification ci-dessous pour procéder à la suppression:<br /><br />Code de vérification: <strong>{$code}</strong><br /><br />Il s\'agit d\'un envoi automatique d\'e-mail depuis le site Web {$site_name}.'
];
$module_emails[Emails::OAUTH_LEADER_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'La notification oauth est ajoutée au compte par le chef d\'équipe',
    's' => 'Avis de confidentialité',
    'c' => '{$greeting_user}<br /><br />Nous vous informons qu\'un compte tiers <strong>{$oauth_name}</strong> vient d\'être connecté à votre compte <strong>{$username}</strong> par le chef d\'équipe.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Gérer les comptes tiers</a>'
];
$module_emails[Emails::OAUTH_SELF_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'La notification oauth est ajoutée au compte par l\'utilisateur lui-même',
    's' => 'Avis de confidentialité',
    'c' => '{$greeting_user}<br /><br />Le compte tiers <strong>{$oauth_name}</strong> vient d\'être connecté à votre compte <strong>{$username}</strong>. Si ce n\'était pas votre intention, veuillez le supprimer rapidement de votre compte en vous rendant dans la zone de gestion des comptes tiers.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Gérer les comptes tiers</a>'
];
$module_emails[Emails::OAUTH_LEADER_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'La notification oauth est supprimée du compte par le chef d\'équipe',
    's' => 'Avis de confidentialité',
    'c' => '{$greeting_user}<br /><br />Nous vous informons que le compte tiers <strong>{$oauth_name}</strong> vient d\'être déconnecté de votre compte <strong>{$username}</strong> par le chef d\'équipe.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Gérer les comptes tiers</a>'
];
$module_emails[Emails::OAUTH_SELF_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'La notification oauth est supprimée du compte par l\'utilisateur lui-même',
    's' => 'Avis de confidentialité',
    'c' => '{$greeting_user}<br /><br />Le compte tiers <strong>{$oauth_name}</strong> vient d\'être déconnecté de votre compte <strong>{$username}</strong>. Si ce n\'est pas votre intention, veuillez contacter rapidement l\'administrateur du site pour obtenir de l\'aide.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Gérer les comptes tiers</a>'
];
$module_emails[Emails::OAUTH_VERIFY_EMAIL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Envoyez un code de vérification par e-mail lors de la connexion via Oauth et l\'e-mail correspond à votre compte existant',
    's' => 'Nouvelle vérification par e-mail',
    'c' => 'Bonjour!<br /><br />Vous avez envoyé une demande de vérification de votre adresse e-mail: {$email}. Copiez le code ci-dessous et collez-le dans la case Code de vérification sur le site.<br /><br />Code de vérification: <strong>{$code}</strong><br /><br />Ceci est un e-mail envoyé automatiquement depuis site Web {$site_name}.'
];
$module_emails[Emails::ACTIVE_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Un e-mail avertit les utilisateurs lorsque l\'administrateur active le compte',
    's' => 'Votre compte a été créé',
    'c' => '{$greeting_user}<br /><br />Votre compte sur le site Web {$site_name} est activé. {if empty($oauth_name)}Les informations de connexion est au dessous:<br /><br />URL: <a href="{$link}">{$link}</a><br />Nom de compte: {$username}<br />{if not empty($password)}Mot de passe: {$password}{/if}{else}Pour vous connecter à votre compte, veuillez visiter la page: <a href="{$link}">{$link}</a> et appuyez sur le bouton: <strong>Connectez-vous avec {$oauth_name}</strong>.{if not empty($password)}<br /><br />Vous pouvez également vous connecter en utilisant la méthode habituelle avec les informations suivantes:<br />Nom de compte: {$username}<br />Mot de passe: {$password}{/if}{/if}{if $pass_reset gt 0 or $email_reset gt 0}<br />Remarque:<br />{if $pass_reset eq 2}- Nous vous recommandons de changer votre mot de passe avant d\'utiliser le compte.<br />{elseif $pass_reset eq 1}- Vous devez changer votre mot de passe avant d\'utiliser le compte.<br />{/if}{if $email_reset eq 2}- Nous vous recommandons de changer votre email avant d\'utiliser le compte.<br />{elseif $email_reset eq 1}- Vous devez changer votre email avant d\'utiliser le compte.<br />{/if}{/if}<br />Cela est un message automatique qui était envoyé à votre boîte mail à partir du site {$site_name}. Si vous ne comprenez pas le contenu de ce mail, vous pouvez simplement le supprimer.'
];
$module_emails[Emails::REQUEST_RESET_PASS] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'E-mail demandant à l\'utilisateur de modifier son mot de passe',
    's' => '{if $pass_reset eq 2}Changement de mot de passe de compte recommandé{else}Besoin de changer le mot de passe du compte{/if}',
    'c' => '{$greeting_user}<br /><br />L\'administration du site {$site_name} informe: Pour des raisons de sécurité, {if $pass_reset eq 2}nous vous recommandons de modifier{else}vous devez changer{/if} le mot de passe de votre compte dès que possible. Pour modifier votre mot de passe, vous devez d\'abord vous rendre sur la page <a href="{$link}">Gestion du compte personnel</a>, sélectionner le bouton Paramètres du compte, puis le bouton Mot de passe, et suivre les instructions.'
];
$module_emails[Emails::OFF2S_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification aux utilisateurs indiquant que l\'authentification en deux étapes a été désactivée par l\'administrateur',
    's' => 'Avis de confidentialité',
    'c' => '{$greeting_user}<br /><br />Votre compte vient de voir l\'authentification en deux étapes désactivée par votre administrateur. Nous vous envoyons cet e-mail pour vous informer.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Gérer l\'authentification en deux étapes</a>'
];
$module_emails[Emails::OAUTH_ADMIN_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Avertir les utilisateurs lorsque les administrateurs suppriment leur compte tiers',
    's' => 'Avis de confidentialité',
    'c' => '{$greeting_user}<br /><br />Nous vous informons que le compte tiers <strong>{$oauth_name}</strong> vient d\'être déconnecté de votre compte par un administrateur.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Gérer les comptes tiers</a>'
];
$module_emails[Emails::OAUTH_TRUNCATE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Avertir les utilisateurs lorsque les administrateurs suppriment tous leurs comptes tiers',
    's' => 'Avis de confidentialité',
    'c' => '{$greeting_user}<br /><br />Nous vous informons que tous les comptes tiers ont été déconnectés de votre compte par un administrateur.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Gérer les comptes tiers</a>'
];
$module_emails[Emails::REQUEST_RESET_EMAIL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'E-mail demandant à l\'utilisateur de modifier son e-mail',
    's' => '{if $email_reset eq 2}Changement d\'e-mail de compte recommandé{else}Besoin de changer l\'e-mail du compte{/if}',
    'c' => '{$greeting_user}<br /><br />L\'administration du site {$site_name} informe: Pour des raisons de sécurité, {if $email_reset eq 2}nous vous recommandons de modifier{else}vous devez changer{/if} l\'e-mail de votre compte dès que possible. Pour modifier votre e-mail, vous devez d\'abord vous rendre sur la page <a href="{$link}">Gestion du compte personnel</a>, sélectionner le bouton Paramètres du compte, puis le bouton E-mail, et suivre les instructions.'
];
$module_emails[Emails::SECURITY_KEY_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email ajout de clé de sécurité',
    's' => 'Une clé de sécurité a été ajoutée à votre compte',
    'c' => '{$greeting_user}<br /><br />Une clé de sécurité nommée &quot;{$security_key}&quot; vient d\'être ajoutée à votre compte sur le site {$site_name}. Cette action provient de:
<ul>
    <li>Navigateur: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Heure de l\'action: <strong>{$action_time}</strong></li>
</ul>
<p>Nous envoyons cette notification obligatoire à votre email pour nous assurer que c\'est bien vous qui avez effectué cette action. Si ce n\'est pas le cas, veuillez rapidement accéder à <a href="{$tstep_link}">la page de gestion de l\'authentification à deux facteurs</a> pour vérifier les clés de sécurité. En même temps, veuillez <a href="{$pass_link}">changer immédiatement votre mot de passe</a> pour garantir la sécurité.</p>
Rappel: Avez-vous sauvegardé vos codes de secours? Si ce n\'est pas le cas, veuillez prendre un moment pour les télécharger et les stocker en toute sécurité, car ils constituent la dernière méthode pour accéder à votre compte en cas de perte des dispositifs d\'authentification à deux facteurs. Vous pouvez <a href="{$code_link}">les télécharger ici</a>.'
];
$module_emails[Emails::PASSKEY_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email ajout de clé de passe',
    's' => 'Une clé de passe a été ajoutée à votre compte',
    'c' => '{$greeting_user}<br /><br />Une clé de passe nommée &quot;{$passkey}&quot; vient d\'être ajoutée à votre compte sur le site {$site_name}. Cette action provient de:
<ul>
    <li>Navigateur: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Heure de l\'action: <strong>{$action_time}</strong></li>
</ul>
<p>Nous envoyons cette notification obligatoire à votre email pour nous assurer que c\'est bien vous qui avez effectué cette action. Si ce n\'est pas le cas, veuillez rapidement accéder à <a href="{$passkey_link}">la page de gestion des clés de passe</a> pour vérifier les clés de passe. En même temps, veuillez <a href="{$pass_link}">changer immédiatement votre mot de passe</a> pour garantir la sécurité.</p>'
];
$module_emails[Emails::SECURITY_KEY_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email suppression de clé de sécurité',
    's' => 'La clé de sécurité a été supprimée de votre compte',
    'c' => '{$greeting_user}<br /><br />La clé de sécurité nommée &quot;{$security_key}&quot; vient d\'être supprimée de votre compte sur le site {$site_name}. Cette action provient de:
<ul>
    <li>Navigateur: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Heure de l\'action: <strong>{$action_time}</strong></li>
</ul>
<p>Nous envoyons cette notification obligatoire à votre email pour nous assurer que c\'est bien vous qui avez effectué cette action. Si ce n\'est pas le cas, veuillez rapidement accéder à <a href="{$tstep_link}">la page de gestion de l\'authentification à deux facteurs</a> pour vérifier les clés de sécurité. En même temps, veuillez <a href="{$pass_link}">changer immédiatement votre mot de passe</a> pour garantir la sécurité.</p>
Rappel: Avez-vous sauvegardé vos codes de secours? Si ce n\'est pas le cas, veuillez prendre un moment pour les télécharger et les stocker en toute sécurité, car ils constituent la dernière méthode pour accéder à votre compte en cas de perte des dispositifs d\'authentification à deux facteurs. Vous pouvez <a href="{$code_link}">les télécharger ici</a>.'
];
$module_emails[Emails::PASSKEY_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email suppression de clé de passe',
    's' => 'La clé de passe a été supprimée de votre compte',
    'c' => '{$greeting_user}<br /><br />La clé de passe nommée &quot;{$passkey}&quot; vient d\'être supprimée de votre compte sur le site {$site_name}. Cette action provient de:
<ul>
    <li>Navigateur: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Heure de l\'action: <strong>{$action_time}</strong></li>
</ul>
<p>Nous envoyons cette notification obligatoire à votre email pour nous assurer que c\'est bien vous qui avez effectué cette action. Si ce n\'est pas le cas, veuillez rapidement accéder à <a href="{$passkey_link}">la page de gestion des clés de passe</a> pour vérifier les clés de passe. En même temps, veuillez <a href="{$pass_link}">changer immédiatement votre mot de passe</a> pour garantir la sécurité.</p>'
];
$module_emails[Emails::DELETE_ACCOUNT_SECCODE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Code de confirmation pour supprimer le compte',
    's' => 'Code de confirmation pour votre demande de suppression de compte',
    'c' => '<p>{$greeting_user}</p> <p>Nous avons reçu votre demande de suppression de compte. Pour continuer, veuillez utiliser le code de confirmation ci-dessous.</p> <div style="text-align: center; margin: 32px 0;"> <p style="font-size: 14px; color: #4b5563; margin-bottom: 8px;">Votre code de confirmation est :</p> <div style="display: inline-block; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; padding: 12px 24px;"> <span style="font-size: 24px; font-weight: bold; letter-spacing: 0.2em; color: #111827; font-family: monospace;">{$code}</span> </div> </div> <p>Ce code est valide pendant 10 minutes. Veuillez ne pas partager ce code avec qui que ce soit.</p> <p>Si vous n\'avez pas demandé cette action, veuillez ignorer cet e-mail et contacter immédiatement notre équipe de support pour protéger votre compte.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> Il s\'agit d\'une notification obligatoire concernant l\'activité de votre compte. Veuillez ne pas répondre et ignorer cet e-mail si vous ne comprenez pas le contenu. </p>'
];
$module_emails[Emails::DELETE_ACCOUNT_PENDING] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification de planification de suppression de compte',
    's' => 'Votre compte a été programmé pour suppression',
    'c' => '<p>{$greeting_user}</p> <p>Votre demande de suppression de compte a été confirmée. Votre compte sera supprimé définitivement vers <strong style="color: #111827;">{$action_time}</strong>.</p> <p>Après suppression, toutes les données et services associés ne pourront pas être récupérés.</p> <div style="text-align: center; margin: 32px 0;"> <p style="color: #4b5563; margin-bottom: 16px;">Si vous changez d\'avis, vous pouvez annuler cette demande à tout moment avant l\'échéance en cliquant sur le bouton ci-dessous et en vous reconnectant.</p> <a href="{$link}" style="display: inline-block; padding: 12px 32px; font-weight: bold; color: white; background-color: #2563eb; text-decoration: none; border-radius: 6px;">Annuler la demande de suppression</a> </div> <p>Si vous n\'avez pas fait cette demande, veuillez cliquer sur le lien ci-dessus pour annuler la demande ou contacter immédiatement notre équipe de support.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> Il s\'agit d\'une notification obligatoire concernant l\'activité de votre compte. Veuillez ne pas répondre et ignorer cet e-mail si vous ne comprenez pas le contenu. </p>'
];
$module_emails[Emails::DELETE_ACCOUNT_CANCEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Annulation de demande de suppression de compte',
    's' => 'Votre demande de suppression de compte a été annulée',
    'c' => '<p>{$greeting_user}</p> <p>Nous confirmons que votre demande de suppression de compte a été annulée avec succès selon votre demande.</p> <p>Votre compte reste <strong>sécurisé</strong> et fonctionne normalement. Vous pouvez continuer à utiliser tous nos services sans interruption.</p> <p>Si vous n\'êtes pas la personne qui a effectué cette action, veuillez <a href="{$pass_link}" style="color: #2563eb; font-weight: 600; text-decoration: none;">changer immédiatement votre mot de passe</a> et consulter <a href="{$link}" style="color: #2563eb; font-weight: 600; text-decoration: none;">les sessions de connexion récentes</a> pour protéger votre compte.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> Il s\'agit d\'une notification obligatoire concernant l\'activité de votre compte. Veuillez ne pas répondre et ignorer cet e-mail si vous ne comprenez pas le contenu. </p>'
];
$module_emails[Emails::DELETE_ACCOUNT_COMPLETED] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Suppression de compte terminée avec succès',
    's' => 'Votre compte a été supprimé définitivement',
    'c' => '<p>{$greeting_user}</p> <p>Comme demandé, votre compte a été <strong>supprimé définitivement</strong> de notre système. Cette action ne peut pas être annulée.</p> <p>Toutes les données personnelles, l\'historique des activités et les services liés à ce compte ont été supprimés de manière irrécupérable.</p> <p>{$newvalue}.</p> <p>Nous regrettons de devoir vous dire au revoir et espérons avoir l\'occasion de vous servir à nouveau dans le futur.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> Il s\'agit d\'une notification obligatoire concernant l\'activité de votre compte. Veuillez ne pas répondre et ignorer cet e-mail si vous ne comprenez pas le contenu. </p>'
];
