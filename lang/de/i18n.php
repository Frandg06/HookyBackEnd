<?php

declare(strict_types=1);

return [

    'user_data_completed' => 'Deine Daten wurden erfolgreich vervollständigt.',
    'event_too_soon' => 'Es verbleiben weniger als 5 Minuten bis zum Event, du kannst die Benachrichtigung nicht mehr planen.',
    'register_user_ko' => 'Ein Fehler ist bei der Registrierung des Benutzers aufgetreten.',
    'register_company_ok' => 'Der Benutzer wurde erfolgreich registriert.',
    'user_exists' => 'Es existiert bereits ein Benutzer mit dieser E-Mail-Adresse.',
    'company_not_exists' => 'Der Veranstalter existiert nicht. Bitte scanne den QR-Code erneut.',
    'event_not_active' => 'Derzeit gibt es keine aktiven Events.',
    'credentials_ko' => 'E-Mail oder Passwort sind falsch.',
    'login_ok' => 'Erfolgreich eingeloggt.',
    'login_ko' => 'Ein Fehler ist beim Einloggen aufgetreten.',
    'update_user_ko' => 'Ein Fehler ist bei der Aktualisierung des Benutzers aufgetreten.',
    'actual_password_ko' => 'Das aktuelle Passwort ist falsch.',
    'update_password_ko' => 'Ein Fehler ist bei der Aktualisierung des Passworts aufgetreten.',
    'register_company_ko' => 'Ein Fehler ist bei der Registrierung des Unternehmens aufgetreten.',
    'update_user_interest_ko' => 'Ein Fehler ist bei der Aktualisierung der Benutzerinteressen aufgetreten.',
    'get_notifications_ko' => 'Ein Fehler ist beim Abrufen der Benachrichtigungen aufgetreten.',
    'images_extension_ko' => 'Erlaubte Bildformate sind JPG, PNG und WEBP.',
    'images_size_ko' => 'Die Bildgröße ist zu groß. Das Maximum beträgt 10 MB.',
    'images_store_ko' => 'Ein Fehler ist beim Speichern des Bildes aufgetreten.',
    'image_not_found' => 'Das Bild existiert nicht oder wurde nicht gefunden.',
    'image_delete_ko' => 'Ein Fehler ist beim Löschen des Bildes aufgetreten.',
    'get_users_ko' => 'Ein Fehler ist beim Abrufen der Benutzer aufgetreten.',
    'set_interaction_ko' => 'Ein Fehler ist beim Speichern der Interaktion aufgetreten.',
    'ws_chat_ko' => 'Ein Fehler ist beim Erstellen des Chats aufgetreten.',
    'user_not_found' => 'Der Benutzer existiert nicht.',
    'password_reset_email' => 'Eine E-Mail mit dem Link zum Zurücksetzen des Passworts wurde gesendet.',
    'password_reset_subject' => 'Passwort zurücksetzen',
    'email_reset_password' => [
        'subject' => 'Passwort zurücksetzen',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
      Hallo :name,<br /><br />
      Du hast eine Zurücksetzung deines Passworts auf <strong>Hooky!</strong> angefordert.  
      Klicke auf den folgenden Link, um ein neues Passwort zu erstellen:<br /><br />
      
      <em><strong>Dieser Link läuft in 15 Minuten ab.</strong></em>
      Wenn du diese Änderung nicht angefordert hast, kannst du diese Nachricht ignorieren. 
      <br /><br /><strong>Referenz: </strong>:uid 
    </p>',
        'button' => 'Passwort zurücksetzen',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
      <strong>Hooky!</strong> - Vernetze dich mit Leuten im Club und schließe neue Freundschaften, während du die Nacht genießt.<br />
      Wenn du Fragen hast oder Hilfe benötigst, zögere nicht, unser Support-Team unter <a href="mailto:support@hooky.com">support@hooky.com</a> zu kontaktieren.<br /><br />
      Dies ist eine automatische Nachricht, bitte antworte nicht auf diese E-Mail.<br />
      <small>&copy; 2025 Hooky. Alle Rechte vorbehalten.</small>
    </p>',
    ],
    'logged_out' => 'Du hast dich erfolgreich ausgeloggt.',
    'token_not_found' => 'Es existiert kein Benutzer mit diesem Token.',
    'token_expired' => 'Der Token ist abgelaufen.',
    'unexpected_error' => 'Ein unerwarteter Fehler ist aufgetreten. Bitte versuche es später erneut.',
    'event_same_day' => 'Es gibt bereits ein Event am gewählten Datum, wähle einen anderen Tag.',
    'event_limit_reached' => 'Du kannst keine weiteren Events erstellen, du hast das Limit erreicht.',
    'start_date_past' => 'Das Startdatum darf nicht in der Vergangenheit liegen.',
    'end_date_before_start' => 'Das Enddatum muss nach dem Startdatum liegen.',
    'event_duration_exceeded' => 'Die maximale Dauer eines Events beträgt 12 Stunden.',
    'event_duration_too_short' => 'Die minimale Dauer eines Events beträgt 2 Stunden.',
    'create_event_ko' => 'Ein Fehler ist beim Erstellen des Events aufgetreten.',
    'tickets_not_numeric' => 'Die Anzahl der Tickets muss eine Zahl sein.',
    'tickets_minimum' => 'Die Anzahl der Tickets muss größer als 1 sein.',
    'tickets_maximum' => 'Es können maximal 3000 Tickets auf einmal generiert werden.',
    'ticket_invalid' => 'Der Code wurde bereits eingelöst oder existiert nicht.',
    'timezone_not_found' => 'Die ausgewählte Zeitzone existiert nicht.',
    'update_company_ko' => 'Ein Fehler ist bei der Aktualisierung der Daten aufgetreten.',
    'invalid_image_count' => 'Der Benutzer kann nur 3 Bilder hochladen.',
    'update_user_interest_ko' => 'Ein Fehler ist bei der Aktualisierung der Benutzerinteressen aufgetreten.',
    'password_changed' => 'Das Passwort wurde erfolgreich geändert.',
    'user_images_limit' => 'Der Benutzer hat bereits 3 Bilder.',
    'image_delete_ko' => 'Ein Fehler ist beim Löschen des Bildes aufgetreten.',
    'delete_image_unexpected_error' => 'Unerwarteter Fehler beim Löschen des Bildes.',
    'store_image_unexpected_error' => 'Unerwarteter Fehler beim Speichern des neuen Bildes.',
    'read_notifications_ko' => 'Ein Fehler ist beim Lesen der Benachrichtigungen aufgetreten.',
    'notification_ko' => 'Ein Fehler ist beim Senden der Benachrichtigung aufgetreten.',
    'email_required' => 'Die E-Mail-Adresse ist erforderlich.',
    'not_aviable_user' => 'Du hast keine Berechtigung, diesen Benutzer anzusehen.',
    'event_not_active_or_next' => 'Es gibt kein aktives oder bevorstehendes Event.',
    'limit_users_reached' => 'Das Event hat das Benutzerlimit erreicht.',
    'event_at_same_time' => 'Es kann nicht mehr als ein Event zur gleichen Zeit geben.',
    'notify' => [
        'base' => 'Du hast eine :interaction erhalten.',
        'hook' => 'Du hast einen Hook gemacht.',
    ],
    'message_notify' => 'Die Nachricht wurde erfolgreich gesendet.',
    'event_not_found' => 'Das Event ist nicht aktiv.',
    'event_not_active' => 'Das Event ist nicht aktiv.',
    'event_is_past_delete' => 'Ein bereits beendetes Event kann nicht gelöscht werden.',
    'event_is_past' => 'Das Event ist bereits beendet.',
    'event_not_start' => 'Das Event hat noch nicht begonnen, bitte versuche es später erneut.',
    'ticket_limit_reached' => 'Du hast das Ticketlimit für dieses Event erreicht.',
    'ticket_not_found' => 'Das Ticket existiert nicht oder ist nicht aktiv.',
    'user_not_login' => 'Du musst dich anmelden, um fortfahren zu können.',
    'authenticated_user' => 'Benutzer erfolgreich authentifiziert.',
    'password_reset_success' => 'Das Passwort wurde erfolgreich zurückgesetzt.',
    'events_retrieved_successfully' => 'Events erfolgreich abgerufen.',
    'cities_retrieved_successfully' => 'Städte erfolgreich abgerufen.',
    'image_order_updated' => 'Bildreihenfolge erfolgreich aktualisiert.',
    'image_order_limit' => 'Das Bild befindet sich bereits am Limit der Reihenfolge.',
    'payment_not_completed' => 'Die Zahlung wurde nicht abgeschlossen.',
    'payment_intent_created' => 'Zahlung erfolgreich verarbeitet.',
    'user_already_premium' => 'Der Benutzer ist bereits Premium.',
    'notifications_disabled' => 'Benachrichtigungen sind in deinen Einstellungen deaktiviert.',
    'notification_scheduled' => 'Alles bereit! Wir benachrichtigen dich, wenn das Event kurz vor dem Beginn steht.',
    'event_attached_by_company' => 'Du bist dem Firmenevent erfolgreich beigetreten.',
    'link_not_valid' => 'Der Link, über den du zugegriffen hast, ist ungültig.',
    'notification_already_scheduled' => 'Du hast bereits eine Benachrichtigung für dieses Event geplant.',
    'email_event_starting' => [
        'subject' => ':eventname startet in 5 Minuten!',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
        Hallo :name,<br /><br />
        Mach dich bereit! Das Event <strong>:eventname</strong> beginnt gleich.<br /><br />
        Wir erwarten dich in <strong> :location </strong> in nur 5 Minuten. 
        Stelle sicher, dass du bereit bist, einzutreten und die Nacht zu genießen.<br /><br />
        Wir sehen uns dort!
      </p>',
        'button' => 'Event-Details ansehen',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
        <strong>Hooky!</strong> - Vernetze dich mit Leuten im Club und schließe neue Freundschaften, während du die Nacht genießt.<br />
        Wenn du Fragen hast oder Hilfe benötigst, zögere nicht, unser Support-Team unter <a href="mailto:support@hooky.com">support@hooky.com</a> zu kontaktieren.<br /><br />
        Dies ist eine automatische Nachricht, bitte antworte nicht auf diese E-Mail.<br />
        <small>&copy; 2025 Hooky. Alle Rechte vorbehalten.</small>
      </p>',
    ],
    'heterosexual' => 'Heterosexuell',
    'gay' => 'Schwul',
    'lesbian' => 'Lesbisch',
    'bisexual' => 'Bisexuell',
    'not_specified' => 'Nicht angegeben',
    'payment_successful' => 'Zahlung erfolgreich',
    'payment_success_description' => 'Deine Transaktion wurde erfolgreich abgeschlossen.',
    'premium_access_granted' => 'Du hast jetzt Zugriff auf die Premium-Funktionen von Hooky.',
    'payment_details' => 'Zahlungsdetails',
    'total_amount' => 'Gesamtbetrag',
    'payment_method' => 'Zahlungsmethode',
    'date' => 'Datum',
    'transaction_id' => 'Transaktions-ID',
    'go_to_app' => 'Zur App gehen',
    'all_rights_reserved' => 'Alle Rechte vorbehalten',
];
