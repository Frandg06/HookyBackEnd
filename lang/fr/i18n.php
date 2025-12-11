<?php

declare(strict_types=1);

return [

    'register_user_ko' => "Une erreur s'est produite lors de l'enregistrement de l'utilisateur.",
    'register_company_ok' => "L'utilisateur a été enregistré avec succès.",
    'user_exists' => 'Un utilisateur est déjà enregistré avec cet e-mail.',
    'company_not_exists' => "L'organisateur n'existe pas. Veuillez scanner à nouveau le code QR.",
    'event_not_active' => "Il n'y a actuellement aucun événement actif.",
    'credentials_ko' => "L'e-mail ou le mot de passe est incorrect.",
    'login_ok' => 'Connexion réussie.',
    'login_ko' => "Une erreur s'est produite lors de la connexion.",
    'update_user_ko' => "Une erreur s'est produite lors de la mise à jour de l'utilisateur.",
    'actual_password_ko' => 'Le mot de passe actuel est incorrect.',
    'update_password_ko' => "Une erreur s'est produite lors de la mise à jour du mot de passe.",
    'register_company_ko' => "Une erreur s'est produite lors de l'enregistrement de l'entreprise.",
    'update_user_interest_ko' => "Une erreur s'est produite lors de la mise à jour des centres d'intérêt de l'utilisateur.",
    'get_notifications_ko' => "Une erreur s'est produite lors de la récupération des notifications.",
    'images_extension_ko' => "Les formats d'image autorisés sont JPG, PNG et WEBP.",
    'images_size_ko' => "La taille de l'image est trop grande. La limite maximale est de 10 Mo.",
    'images_store_ko' => "Une erreur s'est produite lors de l'enregistrement de l'image.",
    'image_not_found' => "L'image n'existe pas ou est introuvable.",
    'image_delete_ko' => "Une erreur s'est produite lors de la suppression de l'image.",
    'get_users_ko' => "Une erreur s'est produite lors de la récupération des utilisateurs.",
    'set_interaction_ko' => "Une erreur s'est produite lors de l'enregistrement de l'interaction.",
    'ws_chat_ko' => "Une erreur s'est produite lors de la création du chat.",
    'user_not_found' => "L'utilisateur n'existe pas.",
    'password_reset_email' => 'Un e-mail contenant le lien pour réinitialiser votre mot de passe a été envoyé.',
    'password_reset_subject' => 'Réinitialisation du mot de passe',
    'email_reset_password' => [
        'subject' => 'Réinitialisation du mot de passe',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
      Bonjour :name,<br /><br />
      Vous avez demandé à réinitialiser votre mot de passe sur <strong>Hooky!</strong>.  
      Cliquez sur le lien ci-dessous pour créer un nouveau mot de passe :<br /><br />
      
      <em><strong>Ce lien expirera dans 15 minutes.</strong></em>
      Si vous n\'avez pas demandé cette modification, ignorez simplement ce message. 
      <br /><br /><strong>Référence : </strong>:uid 
    </p>',
        'button' => 'Réinitialiser le mot de passe',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
      <strong>Hooky!</strong> - Connectez-vous avec des personnes dans la discothèque et faites de nouvelles rencontres en profitant de la soirée.<br />
      Si vous avez des questions ou besoin d\'aide, contactez notre équipe de support à <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
      Ceci est un message automatique, merci de ne pas y répondre.<br />
      <small>&copy; 2025 Hooky. Tous droits réservés.</small>
    </p>',
    ],
    'logged_out' => 'Vous vous êtes déconnecté avec succès.',
    'token_not_found' => "Aucun utilisateur n'est associé à ce jeton.",
    'token_expired' => 'Le jeton a expiré.',
    'unexpected_error' => "Une erreur inattendue s'est produite. Veuillez réessayer plus tard.",
    'event_same_day' => 'Un événement est déjà programmé à cette date, veuillez en choisir une autre.',
    'event_limit_reached' => "Vous ne pouvez pas créer plus d'événements, vous avez atteint la limite.",
    'start_date_past' => 'La date de début doit être postérieure à la date actuelle.',
    'end_date_before_start' => 'La date de fin doit être postérieure à la date de début.',
    'event_duration_exceeded' => "La durée maximale d'un événement est de 12 heures.",
    'event_duration_too_short' => "La durée minimale d'un événement est de 2 heures.",
    'create_event_ko' => "Une erreur s'est produite lors de la création de l'événement.",
    'tickets_not_numeric' => 'Le nombre de billets doit être un nombre.',
    'tickets_minimum' => 'Le nombre de billets doit être supérieur à 1.',
    'tickets_maximum' => 'Le nombre de billets ne peut pas dépasser 1000.',
    'ticket_invalid' => "Le code a déjà été utilisé ou n'existe pas.",
    'timezone_not_found' => "Le fuseau horaire sélectionné n'existe pas.",
    'update_company_ko' => "Une erreur s'est produite lors de la mise à jour des informations.",
    'invalid_image_count' => "L'utilisateur ne peut télécharger que 3 images.",
    'update_user_interest_ko' => "Une erreur s'est produite lors de la mise à jour des centres d'intérêt de l'utilisateur.",
    'password_changed' => 'Le mot de passe a été changé avec succès.',
    'user_images_limit' => "L'utilisateur a déjà 3 images.",
    'image_delete_ko' => "Une erreur s'est produite lors de la suppression de l'image.",
    'delete_image_unexpected_error' => "Erreur inattendue lors de la suppression de l'image.",
    'store_image_unexpected_error' => "Erreur inattendue lors de l'enregistrement de la nouvelle image.",
    'read_notifications_ko' => "Une erreur s'est produite lors de la lecture des notifications.",
    'notification_ko' => "Une erreur s'est produite lors de l'envoi de la notification.",
    'email_required' => "L'e-mail est requis.",
    'not_aviable_user' => "Vous n'avez pas la permission de voir cet utilisateur.",
    'event_not_active_or_next' => 'Il n’y a aucun événement actif ou à venir.',
    'limit_users_reached' => 'L’événement a atteint la limite d’utilisateurs.',
    'authenticated_user' => 'Utilisateur authentifié avec succès.',
];
