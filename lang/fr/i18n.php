<?php

declare(strict_types=1);

return [

    'user_data_completed' => 'Vos données ont été complétées avec succès.',
    'event_too_soon' => 'Il reste moins de 5 minutes avant l\'événement, vous ne pouvez plus programmer l\'alerte.',
    'register_user_ko' => 'Une erreur est survenue lors de l\'enregistrement de l\'utilisateur.',
    'register_company_ok' => 'L\'utilisateur a été enregistré avec succès.',
    'user_exists' => 'Il existe déjà un utilisateur enregistré avec cette adresse e-mail.',
    'company_not_exists' => 'L\'organisateur n\'existe pas. Veuillez scanner à nouveau le QR code.',
    'event_not_active' => 'Il n\'y a actuellement aucun événement actif.',
    'credentials_ko' => 'L\'adresse e-mail ou le mot de passe est incorrect.',
    'login_ok' => 'Connexion réussie.',
    'login_ko' => 'Une erreur est survenue lors de la connexion.',
    'update_user_ko' => 'Une erreur est survenue lors de la mise à jour de l\'utilisateur.',
    'actual_password_ko' => 'Le mot de passe actuel est incorrect.',
    'update_password_ko' => 'Une erreur est survenue lors de la mise à jour du mot de passe.',
    'register_company_ko' => 'Une erreur est survenue lors de l\'enregistrement de l\'entreprise.',
    'update_user_interest_ko' => 'Une erreur est survenue lors de la mise à jour des intérêts de l\'utilisateur.',
    'get_notifications_ko' => 'Une erreur est survenue lors de la récupération des notifications.',
    'images_extension_ko' => 'Les formats d\'image autorisés sont JPG, PNG et WEBP.',
    'images_size_ko' => 'La taille de l\'image est trop grande. Le maximum autorisé est de 10 Mo.',
    'images_store_ko' => 'Une erreur est survenue lors de l\'enregistrement de l\'image.',
    'image_not_found' => 'L\'image n\'existe pas ou n\'a pas été trouvée.',
    'image_delete_ko' => 'Une erreur est survenue lors de la suppression de l\'image.',
    'get_users_ko' => 'Une erreur est survenue lors de la récupération des utilisateurs.',
    'set_interaction_ko' => 'Une erreur est survenue lors de l\'enregistrement de l\'interaction.',
    'ws_chat_ko' => 'Une erreur est survenue lors de la création du chat.',
    'user_not_found' => 'L\'utilisateur n\'existe pas.',
    'password_reset_email' => 'Un e-mail contenant le lien pour réinitialiser le mot de passe a été envoyé.',
    'password_reset_subject' => 'Réinitialiser le mot de passe',
    'email_reset_password' => [
        'subject' => 'Réinitialiser le mot de passe',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
      Bonjour :name,<br /><br />
      Vous avez demandé la réinitialisation de votre mot de passe sur <strong>Hooky!</strong>.  
      Cliquez sur le lien suivant pour créer un nouveau mot de passe :<br /><br />
      
      <em><strong>Ce lien expirera dans 15 minutes.</strong></em>
      Si vous n\'avez pas demandé ce changement, vous pouvez ignorer ce message. 
      <br /><br /><strong>Référence : </strong>:uid 
    </p>',
        'button' => 'Réinitialiser le mot de passe',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
      <strong>Hooky!</strong> - Connectez-vous avec des gens dans la discothèque et faites de nouvelles amitiés tout en profitant de la soirée.<br />
      Si vous avez des questions ou avez besoin d\'aide, n\'hésitez pas à contacter notre équipe de support à <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
      Ceci est un message automatisé, veuillez ne pas répondre à cet e-mail.<br />
      <small>&copy; 2025 Hooky. Tous droits réservés.</small>
    </p>',
    ],
    'logged_out' => 'Vous vous êtes déconnecté avec succès.',
    'token_not_found' => 'Aucun utilisateur n\'existe avec ce jeton.',
    'token_expired' => 'Le jeton a expiré.',
    'unexpected_error' => 'Une erreur inattendue est survenue. Veuillez réessayer plus tard.',
    'event_same_day' => 'Il y a déjà un événement à la date choisie, choisissez un autre jour.',
    'event_limit_reached' => 'Vous ne pouvez plus créer d\'événements, vous avez atteint la limite.',
    'start_date_past' => 'La date de début ne peut pas être antérieure à la date actuelle.',
    'end_date_before_start' => 'La date de fin doit être postérieure à la date de début.',
    'event_duration_exceeded' => 'La durée maximale d\'un événement est de 12 heures.',
    'event_duration_too_short' => 'La durée minimale d\'un événement est de 2 heures.',
    'create_event_ko' => 'Une erreur est survenue lors de la création de l\'événement.',
    'tickets_not_numeric' => 'Le nombre de tickets doit être un nombre.',
    'tickets_minimum' => 'Le nombre de tickets doit être supérieur à 1.',
    'tickets_maximum' => 'Vous ne pouvez générer qu\'un maximum de 3000 tickets à la fois.',
    'ticket_invalid' => 'Le code a déjà été utilisé ou n\'existe pas.',
    'timezone_not_found' => 'Le fuseau horaire sélectionné n\'existe pas.',
    'update_company_ko' => 'Une erreur est survenue lors de la mise à jour des données.',
    'invalid_image_count' => 'L\'utilisateur ne peut télécharger que 3 images.',
    'update_user_interest_ko' => 'Une erreur est survenue lors de la mise à jour des intérêts de l\'utilisateur.',
    'password_changed' => 'Le mot de passe a été modifié avec succès.',
    'user_images_limit' => 'L\'utilisateur a déjà 3 images.',
    'image_delete_ko' => 'Une erreur est survenue lors de la suppression de l\'image.',
    'delete_image_unexpected_error' => 'Erreur inattendue lors de la suppression de l\'image.',
    'store_image_unexpected_error' => 'Erreur inattendue lors de l\'enregistrement de la nouvelle image.',
    'read_notifications_ko' => 'Une erreur est survenue lors de la lecture des notifications.',
    'notification_ko' => 'Une erreur est survenue lors de l\'envoi de la notification.',
    'email_required' => 'L\'e-mail est requis.',
    'not_aviable_user' => 'Vous n\'avez pas la permission de voir cet utilisateur.',
    'event_not_active_or_next' => 'Il n\'y a aucun événement actif ou à venir.',
    'limit_users_reached' => 'L\'événement a atteint la limite d\'utilisateurs.',
    'event_at_same_time' => 'Il ne peut pas y avoir plus d\'un événement à la même heure.',
    'notify' => [
        'base' => 'Vous avez reçu une :interaction.',
        'hook' => 'Vous avez fait un Hook.',
    ],
    'message_notify' => 'Le message a été envoyé avec succès.',
    'event_not_found' => 'L\'événement n\'est pas actif.',
    'event_not_active' => 'L\'événement n\'est pas actif.',
    'event_is_past_delete' => 'Impossible de supprimer un événement qui est déjà terminé.',
    'event_is_past' => 'L\'événement est déjà terminé.',
    'event_not_start' => 'L\'événement n\'a pas encore commencé, veuillez réessayer plus tard.',
    'ticket_limit_reached' => 'Vous avez atteint la limite de tickets pour cet événement.',
    'ticket_not_found' => 'Le ticket n\'existe pas ou n\'est pas actif.',
    'user_not_login' => 'Vous devez vous connecter pour pouvoir continuer.',
    'authenticated_user' => 'Utilisateur authentifié avec succès.',
    'password_reset_success' => 'Le mot de passe a été réinitialisé avec succès.',
    'events_retrieved_successfully' => 'Événements récupérés avec succès.',
    'cities_retrieved_successfully' => 'Villes récupérées avec succès.',
    'image_order_updated' => 'Ordre des images mis à jour avec succès.',
    'image_order_limit' => 'L\'image est déjà à la limite de l\'ordre.',
    'payment_not_completed' => 'Le paiement n\'a pas été finalisé.',
    'payment_intent_created' => 'Paiement traité avec succès.',
    'user_already_premium' => 'L\'utilisateur est déjà premium.',
    'notifications_disabled' => 'Les notifications sont désactivées dans vos paramètres.',
    'notification_scheduled' => 'C\'est tout bon ! Nous vous préviendrons lorsque l\'événement sera sur le point de commencer.',
    'event_attached_by_company' => 'Vous avez rejoint l\'événement de l\'entreprise avec succès.',
    'link_not_valid' => 'Le lien depuis lequel vous avez accédé n\'est pas valide.',
    'notification_already_scheduled' => 'Vous avez déjà une notification programmée pour cet événement.',
    'email_event_starting' => [
        'subject' => '¡:eventname commence dans 5 minutes !',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
        Bonjour :name,<br /><br />
        Préparez-vous ! L\'événement <strong>:eventname</strong> est sur le point de commencer.<br /><br />
        Nous vous attendons à <strong> :location </strong> dans seulement 5 minutes. 
        Assurez-vous d\'être prêt à entrer et à profiter de la soirée.<br /><br />
        On se voit là-bas !
      </p>',
        'button' => 'Voir les détails de l\'événement',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
        <strong>Hooky!</strong> - Connectez-vous avec des gens dans la discothèque et faites de nouvelles amitiés tout en profitant de la soirée.<br />
        Si vous avez des questions ou avez besoin d\'aide, n\'hésitez pas à contacter notre équipe de support à <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
        Ceci est un message automatisé, veuillez ne pas répondre à cet e-mail.<br />
        <small>&copy; 2025 Hooky. Tous droits réservés.</small>
      </p>',
    ],
    'heterosexual' => 'Hétérosexuel',
    'gay' => 'Gay',
    'lesbian' => 'Lesbienne',
    'bisexual' => 'Bisexuel',
    'not_specified' => 'Non spécifié',
    'payment_successful' => 'Paiement réussi',
    'payment_success_description' => 'Votre transaction a été effectuée avec succès.',
    'premium_access_granted' => 'Vous avez maintenant accès aux fonctionnalités Premium de Hooky.',
    'payment_details' => 'Détails du paiement',
    'total_amount' => 'Montant total',
    'payment_method' => 'Moyen de paiement',
    'date' => 'Date',
    'transaction_id' => 'ID de transaction',
    'go_to_app' => 'Aller à l\'application',
    'all_rights_reserved' => 'Tous droits réservés',
];
