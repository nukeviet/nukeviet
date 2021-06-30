<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '31/07/2015, 16:30';
$lang_translator['copyright'] = '@Copyright (C) 2009-2021 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['confirm_password'] = 'Saisissez la confirmation de mot de passe pour continuer';
$lang_module['confirm_password_info'] = 'Pour implémenter cette fonctionnalité, vous devez confirmer votre mot de passe, entrez votre mot de passe dans la case ci-dessous et cliquez sur Confirmer';
$lang_module['confirm'] = 'Confirmer';
$lang_module['secretkey'] = 'Secret code';
$lang_module['wrong_confirm'] = 'Code de confirmation est incorrecte, s\'il vous plaît ré-entrer';
$lang_module['cfg_step1'] = 'Étape 1: Scannez le code QR';
$lang_module['cfg_step1_manual'] = 'Scannez QR-code ci-dessus avec le logiciel authentification à deux facteurs (par exemple Google Authenticator) sur votre téléphone. Si vous ne pouvez pas utiliser l\'appareil photo, s\'il vous plaît';
$lang_module['cfg_step1_manual1'] = 'entrez ce code';
$lang_module['cfg_step1_manual2'] = 'artisanat';
$lang_module['cfg_step1_note'] = 'Remarque code secret';
$lang_module['cfg_step2_info'] = 'Après avoir scanné le code ou entrer manuellement avec succès, l\'application affichera une chaîne à 6 chiffres, que l\'entrée de chaîne ci-dessous pour confirmer';
$lang_module['cfg_step2_info2'] = 'Vérification affichage à 6 chiffres sur l\'écran de l\'application sur votre téléphone';
$lang_module['cfg_step2'] = 'Étape 2: Entrez le code de l\'application';
$lang_module['title_2step'] = 'L\'authentification à deux étapes';
$lang_module['status_2step'] = 'L\'authentification à deux étapes est';
$lang_module['active_2step'] = 'ON';
$lang_module['deactive_2step'] = 'RÉSUMÉ';
$lang_module['backupcode_2step'] = 'Vous avez <strong>%d</strong> dans les codes de sauvegarde inutilisés';
$lang_module['backupcode_2step_view'] = 'Voir les codes de sauvegarde';
$lang_module['backupcode_2step_note'] = 'Remarque: Enregistrer un code de sauvegarde avec soin pour éviter la perte du téléphone, vous pouvez utiliser ce code pour accéder au compte. Si vous l\'oubliez et vous perdez votre téléphone, vous ne pouvez pas vous connecter à votre compte';
$lang_module['turnoff2step'] = 'Désactivez l\'authentification en deux étapes';
$lang_module['turnon2step'] = 'Configuration de l\'authentification en deux étapes';
$lang_module['creat_other_code'] = 'Créer des codes de sauvegarde';
$lang_module['email_subject'] = 'Privacy notice';
$lang_module['email_2step_on'] = 'Votre <strong>%4$s</strong> compte sur <a href="%5$s"><strong>%6$s</strong></a> vient d\'activer Two-Factor Authentication. Information:<br /><br />- Temps: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Navigateur: <strong>%3$s</strong><br /><br />Si c\'est vous, ignorez cet email. Si ce n\'est pas vous, votre compte est très probablement volé. Veuillez contacter l\'administrateur du site pour obtenir de l\'aide';
$lang_module['email_2step_off'] = 'Votre <strong>%5$s</strong> compte sur <a href="%6$s"><strong>%7$s</strong></a> vient d\'activer Two-Factor Authentication. Information:<br /><br />- Temps: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Navigateur: <strong>%3$s</strong><br /><br />Si c\'est vous, ignorez cet email. Si ce n\'est pas vous, veuillez vérifier vos informations personnelles à l\'adresse <a href="%4$s">%4$s</a>';
$lang_module['email_code_renew'] = 'Votre <strong>%5$s</strong> compte sur <a href="%6$s"><strong>%7$s</strong></a> vient de recréer le code de sauvegarde. Information:<br /><br />- Temps: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Navigateur: <strong>%3$s</strong><br /><br />Si c\'est vous, ignorez cet email. Si ce n\'est pas vous, veuillez vérifier vos informations personnelles à l\'adresse <a href="%4$s">%4$s</a>';

$lang_module['change_2step_notvalid'] = 'Votre compte n\'a pas de mot de passe, donc l\'authentification en deux étapes ne peut pas être modifiée. Veuillez créer un mot de passe, puis revenir à cette page. Veuillez <a class="btn btn-primary btn-xs" href="%s">cliquer ici</a> pour créer un mot de passe';
