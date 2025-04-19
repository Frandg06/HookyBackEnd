<?php

return [

    'register_user_ko' => 'Beim Registrieren des Benutzers ist ein Fehler aufgetreten.',
    'register_company_ok' => 'Der Benutzer wurde erfolgreich registriert.',
    'user_exists' => 'Ein Benutzer mit dieser E-Mail-Adresse existiert bereits.',
    'company_not_exists' => 'Der Veranstalter existiert nicht. Bitte scannen Sie den QR-Code erneut.',
    'event_not_active' => 'Derzeit gibt es keine aktiven Veranstaltungen.',
    'credentials_ko' => 'Die E-Mail-Adresse oder das Passwort ist falsch.',
    'login_ok' => 'Erfolgreich eingeloggt.',
    'login_ko' => 'Beim Einloggen ist ein Fehler aufgetreten.',
    'update_user_ko' => 'Beim Aktualisieren des Benutzers ist ein Fehler aufgetreten.',
    'actual_password_ko' => 'Das aktuelle Passwort ist falsch.',
    'update_password_ko' => 'Beim Aktualisieren des Passworts ist ein Fehler aufgetreten.',
    'register_company_ko' => 'Beim Registrieren des Unternehmens ist ein Fehler aufgetreten.',
    'update_user_interest_ko' => 'Beim Aktualisieren der Benutzerinteressen ist ein Fehler aufgetreten.',
    'get_notifications_ko' => 'Beim Abrufen der Benachrichtigungen ist ein Fehler aufgetreten.',
    'images_extension_ko' => 'Erlaubte Bildformate sind JPG, PNG und WEBP.',
    'images_size_ko' => 'Die Bildgröße ist zu groß. Das Maximum beträgt 10 MB.',
    'images_store_ko' => 'Beim Speichern des Bildes ist ein Fehler aufgetreten.',
    'image_not_found' => 'Das Bild existiert nicht oder wurde nicht gefunden.',
    'image_delete_ko' => 'Beim Löschen des Bildes ist ein Fehler aufgetreten.',
    'get_users_ko' => 'Beim Abrufen der Benutzer ist ein Fehler aufgetreten.',
    'set_interaction_ko' => 'Beim Registrieren der Interaktion ist ein Fehler aufgetreten.',
    'ws_chat_ko' => 'Beim Erstellen des Chats ist ein Fehler aufgetreten.',
    'user_not_found' => 'Der Benutzer existiert nicht.',
    'password_reset_email' => 'Eine E-Mail mit einem Link zum Zurücksetzen des Passworts wurde gesendet.',
    'password_reset_subject' => 'Passwort zurücksetzen',
    'email_reset_password' => [
        'subject' => 'Passwort zurücksetzen',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
      Hallo :name,<br /><br />
      Sie haben eine Anfrage zum Zurücksetzen Ihres Passworts auf <strong>Hooky!</strong> gestellt.  
      Klicken Sie auf den folgenden Link, um ein neues Passwort zu erstellen:<br /><br />
      
      <em><strong>Dieser Link läuft in 15 Minuten ab.</strong></em>
      Falls Sie diese Änderung nicht angefordert haben, können Sie diese Nachricht ignorieren. 
      <br /><br /><strong>Referenz: </strong>:uid 
    </p>',
        'button' => 'Passwort zurücksetzen',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
      <strong>Hooky!</strong> - Verbinden Sie sich mit Menschen in der Diskothek und schließen Sie neue Freundschaften, während Sie die Nacht genießen.<br />
      Wenn Sie Fragen haben oder Hilfe benötigen, wenden Sie sich bitte an unser Support-Team unter <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
      Dies ist eine automatische Nachricht, bitte antworten Sie nicht auf diese E-Mail.<br />
      <small>&copy; 2025 Hooky. Alle Rechte vorbehalten.</small>
    </p>',
    ],
    'logged_out' => 'Sie haben sich erfolgreich abgemeldet.',
    'token_not_found' => 'Es gibt keinen Benutzer mit diesem Token.',
    'token_expired' => 'Der Token ist abgelaufen.',
    'unexpected_error' => 'Ein unerwarteter Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.',
    'event_same_day' => 'Für das gewählte Datum gibt es bereits eine Veranstaltung. Bitte wählen Sie einen anderen Tag.',
    'event_limit_reached' => 'Sie können keine weiteren Veranstaltungen erstellen, da Sie das Limit erreicht haben.',
    'start_date_past' => 'Das Startdatum muss in der Zukunft liegen.',
    'end_date_before_start' => 'Das Enddatum muss nach dem Startdatum liegen.',
    'event_duration_exceeded' => 'Die maximale Dauer einer Veranstaltung beträgt 12 Stunden.',
    'event_duration_too_short' => 'Die Mindestdauer einer Veranstaltung beträgt 2 Stunden.',
    'create_event_ko' => 'Beim Erstellen der Veranstaltung ist ein Fehler aufgetreten.',
    'tickets_not_numeric' => 'Die Anzahl der Tickets muss eine Zahl sein.',
    'tickets_minimum' => 'Die Anzahl der Tickets muss größer als 1 sein.',
    'tickets_maximum' => 'Die Anzahl der Tickets darf 1000 nicht überschreiten.',
    'ticket_invalid' => 'Der Code wurde bereits eingelöst oder existiert nicht.',
    'timezone_not_found' => 'Die ausgewählte Zeitzone existiert nicht.',
    'update_company_ko' => 'Beim Aktualisieren der Daten ist ein Fehler aufgetreten.',
    'invalid_image_count' => 'Der Benutzer kann nur 3 Bilder hochladen.',
    'update_user_interest_ko' => 'Beim Aktualisieren der Benutzerinteressen ist ein Fehler aufgetreten.',
    'password_changed' => 'Das Passwort wurde erfolgreich geändert.',
    'user_images_limit' => 'Der Benutzer hat bereits 3 Bilder.',
    'image_delete_ko' => 'Beim Löschen des Bildes ist ein Fehler aufgetreten.',
    'delete_image_unexpected_error' => 'Unerwarteter Fehler beim Löschen des Bildes.',
    'store_image_unexpected_error' => 'Unerwarteter Fehler beim Speichern des neuen Bildes.',
    'read_notifications_ko' => 'Beim Lesen der Benachrichtigungen ist ein Fehler aufgetreten.',
    'notification_ko' => 'Beim Senden der Benachrichtigung ist ein Fehler aufgetreten.',
    'email_required' => 'Die E-Mail-Adresse ist erforderlich.',
    'not_aviable_user' => 'Sie haben keine Berechtigung, diesen Benutzer zu sehen.',
    'event_not_active_or_next' => 'Es gibt kein aktives oder bevorstehendes Event.',
    'limit_users_reached' => 'Das Event hat die maximale Anzahl an Teilnehmern erreicht.',

];
