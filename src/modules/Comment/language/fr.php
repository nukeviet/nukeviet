<?php

/**
* @Project NUKEVIET 4.x
* @Author VINADES.,JSC <contact@vinades.vn>
* @Copyright (C) 2018 VINADES.,JSC. All rights reserved
* @Language Français
* @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)
* @Createdate Jul 31, 2015, 09:30:00 AM
*/

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$lang_translator['author'] = 'Nguyễn Phú Thành';
$lang_translator['createdate'] = '31/07/2015, 16:30';
$lang_translator['copyright'] = 'phuthanh.nguyen215@gmail.com';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['comment'] = 'Commentaires des lecteurs';
$lang_module['comment_login'] = 'Vous devez vous connecter en tant que <strong>%s</strong> pour pouvoir commenter';
$lang_module['comment_register_groups'] = 'Vous devez faire partie du groupe <strong>%1$s</strong> pour pouvoir commenter. Cliquer sur <a href="%2$s" title="Inscription dans un groupe">đây</a> pour vous inscrire dans un groupe!';
$lang_module['comment_success'] = 'Envoi du Commentaire réussi';
$lang_module['comment_success_queue'] = 'Envoie réussite. Votre commentaire va apparaître après être contrôlé';
$lang_module['comment_unsuccess'] = 'Erreur dans le processus de traiter les données ou les données sont insuffisantes';
$lang_module['comment_title'] = 'Envoyer vos commentaires';
$lang_module['comment_view'] = 'Consulter les commentaires';
$lang_module['comment_hide_show'] = 'Cacher/Presenter la commentaire';
$lang_module['comment_name'] = 'Votre nom';
$lang_module['comment_email'] = 'E-mail';
$lang_module['comment_content'] = 'Contenu';
$lang_module['comment_seccode'] = 'Code de sécurité';
$lang_module['comment_submit'] = 'Envoyer';
$lang_module['comment_timeout'] = 'Vous avez voter, veuillez attendre %s pour envoyer les commentaires';
$lang_module['sortcomm'] = 'Mettre en ordre des commentaires';
$lang_module['sortcomm_0'] = 'Les nouveaux avant';
$lang_module['sortcomm_1'] = 'Les anciens avant';
$lang_module['sortcomm_2'] = 'Aimé le plus';
$lang_module['feedback'] = 'Répondre';
$lang_module['like'] = 'J\'aime';
$lang_module['dislike'] = 'Je n\'aime pas';
$lang_module['delete'] = 'Supprimer';
$lang_module['like_unsuccess'] = 'Vous avez commenter ce commentaire avant';
$lang_module['attach'] = 'Joindre un fichier';
$lang_module['attachdownload'] = 'Télécharger les pièces jointes';
$lang_module['user'] = 'Membre';
$lang_module['main'] = 'Page centrale';
$lang_module['config'] = 'Configuration';
$lang_module['save'] = 'Sauvegarde';
$lang_module['comment_manager'] = 'Commentaires';
$lang_module['edit'] = 'Éditer';
$lang_module['search'] = 'Rechercher';
$lang_module['search_type'] = 'Recherche par';
$lang_module['search_status'] = 'État';
$lang_module['search_id'] = 'ID';
$lang_module['search_key'] = 'Mot clé';
$lang_module['search_module'] = 'Recherche dans les modules';
$lang_module['search_module_all'] = 'Tous les modules';
$lang_module['search_content'] = 'Contenu du commentaire';
$lang_module['search_content_id'] = 'ID de l\'article';
$lang_module['search_post_name'] = 'Celui qui a mis en ligne';
$lang_module['search_post_email'] = 'Email';
$lang_module['search_per_page'] = 'Nombre d\'articles affichés';
$lang_module['from_date'] = 'Depuis';
$lang_module['to_date'] = 'à';
$lang_module['search_note'] = 'Mot clé doit être plus de 2 et moins de 64 caractères, code html interdit';
$lang_module['edit_title'] = 'Modifier le commentaire';
$lang_module['edit_active'] = 'Activer';
$lang_module['edit_delete'] = 'Supprimer le commentaire';
$lang_module['funcs'] = 'Fonction';
$lang_module['email'] = 'Expéditeur';
$lang_module['content'] = 'Description';
$lang_module['status'] = 'Etat';
$lang_module['delete_title'] = 'Supprimer le commentaire';
$lang_module['delete_confirm'] = 'Voulez-vous vraiment supprimer ce commentaire?';
$lang_module['delete_yes'] = 'Oui';
$lang_module['delete_no'] = 'Non';
$lang_module['delete_accept'] = 'Enregistre le changement';
$lang_module['delete_unsuccess'] = 'Erreur dans le processus de suppression des données';
$lang_module['delete_success'] = 'Suppression des données réussite';
$lang_module['enable'] = 'Activé';
$lang_module['disable'] = 'Désactivé';
$lang_module['checkall'] = 'Sélectionner tout';
$lang_module['uncheckall'] = 'Désélectionner tout';
$lang_module['nocheck'] = 'Faut choisir au moins un commentaire pour le faire';
$lang_module['update_success'] = 'Mise a jour réussite!';
$lang_module['mod_name'] = 'Nom du module';
$lang_module['weight'] = 'Numero';
$lang_module['config_mod_name'] = 'Configuration du module de commentaire: %s';
$lang_module['activecomm'] = 'Utilisation du fonctionnement de commentaire';
$lang_module['emailcomm'] = 'Presenter l\'email de commentateur';
$lang_module['setcomm'] = 'Commenter par défaut quand vous créez un nouveau commentaire';
$lang_module['auto_postcomm'] = 'Contrôler les commentaires';
$lang_module['auto_postcomm_0'] = 'Contrôler tous';
$lang_module['auto_postcomm_1'] = 'Ne peut pas controller';
$lang_module['auto_postcomm_2'] = 'Contrôler si vous n\'êtes pas membres';
$lang_module['perpagecomm'] = 'Nombre de commentaires affichés sur une page';
$lang_module['perpagecomm_note'] = 'Entrez au moins 1 et pas plus de 100';
$lang_module['timeoutcomm'] = 'Temps (s) minimum entre chaque poste commentaire';
$lang_module['timeoutcomm_note'] = 'Entrez 0 pour un nombre illimité. Remarque devrait permettre captcha si choisir cette valeur est 0, la valeur est pas applicable pour l\'administrateur';
$lang_module['allowattachcomm'] = 'Active les pièces jointes';
$lang_module['alloweditorcomm'] = 'Autorise l\'éditeur';
$lang_module['allowautolink'] = 'Détecter automatiquement les liens dans le contenu';
$lang_module['adminscomm'] = 'Admin gère les commentaires';
$lang_module['view_comm'] = 'Ceux qui ont le droit de regarder les commentaires';
$lang_module['allowed_comm'] = 'Ceux qui ont le droit d\'écrire les commentaires';
$lang_module['allowed_comm_item'] = 'Selon la configuration des commentaires';
$lang_module['adminscomm_note'] = 'Le "Admin peut gérer les commentaires" applique uniquement au module de gestion admin, vous devez ajouter un module de gestion avant la décentralisation';
$lang_module['captcha'] = 'Configuration de captcha';
$lang_module['captcha_0'] = 'Masqué';
$lang_module['captcha_1'] = 'Si vous n\'êtes pas membres';
$lang_module['captcha_2'] = 'On n\'est pas le responsable des commentaires';
$lang_module['captcha_3'] = 'Présente dans tous les cas';
$lang_module['siteinfo_queue_comments'] = 'Le nombre de commentaire en liste d\'attente d\'être vérifié';
$lang_module['notification_comment_queue'] = 'Commentaire messages de modération par %s<br /><em>%s</em>';
$lang_module['attach_choose'] = 'Sélectionnez';
$lang_module['attach_view'] = 'Accès';
$lang_module['attach_download'] = 'Télécharger les pièces jointes';
$lang_module['width'] = 'Width';
$lang_module['numpost'] = 'Num posts';
$lang_module['scheme'] = 'Color Scheme';
$lang_module['facebookappid'] = 'Facebook Appid';
