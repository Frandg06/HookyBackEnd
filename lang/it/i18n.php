<?php

declare(strict_types=1);

return [

    'register_user_ko' => "Si è verificato un errore durante la registrazione dell'utente.",
    'register_company_ok' => "L'utente è stato registrato con successo.",
    'user_exists' => 'Esiste già un utente registrato con questa email.',
    'company_not_exists' => "L'organizzatore non esiste. Scansiona nuovamente il codice QR.",
    'event_not_active' => 'Attualmente non ci sono eventi attivi.',
    'credentials_ko' => "L'email o la password sono errati.",
    'login_ok' => 'Accesso effettuato con successo.',
    'login_ko' => "Si è verificato un errore durante l'accesso.",
    'update_user_ko' => "Si è verificato un errore durante l'aggiornamento dell'utente.",
    'actual_password_ko' => 'La password attuale non è corretta.',
    'update_password_ko' => "Si è verificato un errore durante l'aggiornamento della password.",
    'register_company_ko' => "Si è verificato un errore durante la registrazione dell'azienda.",
    'update_user_interest_ko' => "Si è verificato un errore durante l'aggiornamento degli interessi dell'utente.",
    'get_notifications_ko' => 'Si è verificato un errore nel recupero delle notifiche.',
    'images_extension_ko' => 'I formati di immagine consentiti sono JPG, PNG e WEBP.',
    'images_size_ko' => "Le dimensioni dell'immagine sono troppo grandi. Il massimo consentito è 10 MB.",
    'images_store_ko' => "Si è verificato un errore durante il salvataggio dell'immagine.",
    'image_not_found' => "L'immagine non esiste o non è stata trovata.",
    'image_delete_ko' => "Si è verificato un errore durante l'eliminazione dell'immagine.",
    'get_users_ko' => 'Si è verificato un errore nel recupero degli utenti.',
    'set_interaction_ko' => "Si è verificato un errore durante la registrazione dell'interazione.",
    'ws_chat_ko' => 'Si è verificato un errore durante la creazione della chat.',
    'user_not_found' => "L'utente non esiste.",
    'password_reset_email' => "È stata inviata un'email con il link per reimpostare la password.",
    'password_reset_subject' => 'Reimposta la password',
    'email_reset_password' => [
        'subject' => 'Reimposta la password',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
      Ciao :name,<br /><br />
      Hai richiesto di reimpostare la tua password su <strong>Hooky!</strong>.  
      Fai clic sul link seguente per creare una nuova password:<br /><br />
      
      <em><strong>Questo link scadrà tra 15 minuti.</strong></em>
      Se non hai richiesto questa modifica, puoi ignorare questo messaggio. 
      <br /><br /><strong>Riferimento: </strong>:uid 
    </p>',
        'button' => 'Reimposta la password',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
      <strong>Hooky!</strong> - Connettiti con le persone all interno della discoteca e fai nuove amicizie mentre ti godi la serata.<br />
      Se hai domande o hai bisogno di aiuto, contatta il nostro team di supporto a <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
      Questo è un messaggio automatico, si prega di non rispondere a questa email.<br />
      <small>&copy; 2025 Hooky. Tutti i diritti riservati.</small>
    </p>',
    ],
    'logged_out' => 'Hai effettuato il logout con successo.',
    'token_not_found' => 'Non esiste nessun utente con questo token.',
    'token_expired' => 'Il token è scaduto.',
    'unexpected_error' => 'Si è verificato un errore imprevisto. Riprova più tardi.',
    'event_same_day' => "C'è già un evento nella data scelta, scegli un altro giorno.",
    'event_limit_reached' => 'Non puoi creare altri eventi, hai raggiunto il limite.',
    'start_date_past' => 'La data di inizio deve essere successiva alla data attuale.',
    'end_date_before_start' => 'La data di fine deve essere successiva alla data di inizio.',
    'event_duration_exceeded' => 'La durata massima di un evento è di 12 ore.',
    'event_duration_too_short' => 'La durata minima di un evento è di 2 ore.',
    'create_event_ko' => "Si è verificato un errore durante la creazione dell'evento.",
    'tickets_not_numeric' => 'Il numero di biglietti deve essere un numero.',
    'tickets_minimum' => 'Il numero di biglietti deve essere maggiore di 1.',
    'tickets_maximum' => 'Il numero di biglietti non può essere superiore a 1000.',
    'ticket_invalid' => 'Il codice è già stato riscattato o non esiste.',
    'timezone_not_found' => 'Il fuso orario selezionato non esiste.',
    'update_company_ko' => "Si è verificato un errore nell'aggiornamento dei dati.",
    'invalid_image_count' => "L'utente può caricare solo 3 immagini.",
    'update_user_interest_ko' => "Si è verificato un errore durante l'aggiornamento degli interessi dell'utente.",
    'password_changed' => 'La password è stata cambiata con successo.',
    'user_images_limit' => "L'utente ha già 3 immagini.",
    'image_delete_ko' => "Si è verificato un errore durante l'eliminazione dell'immagine.",
    'delete_image_unexpected_error' => "Errore imprevisto durante l'eliminazione dell'immagine.",
    'store_image_unexpected_error' => 'Errore imprevisto durante il salvataggio della nuova immagine.',
    'read_notifications_ko' => 'Si è verificato un errore nella lettura delle notifiche.',
    'notification_ko' => "Si è verificato un errore durante l'invio della notifica.",
    'email_required' => "L'email è obbligatoria.",
    'not_aviable_user' => 'Non hai i permessi per visualizzare questo utente.',
    'event_not_active_or_next' => 'Non c\'è nessun evento attivo o imminente.',
    'limit_users_reached' => 'L\'evento ha raggiunto il limite di utenti.',
    'authenticated_user' => 'Utente autenticato con successo.',
    'password_reset_success' => 'La password è stata reimpostata con successo.',
    'user_not_login' => 'Devi accedere per continuare.',
    'events_retrieved_successfully' => 'Eventi recuperati con successo.',
    'cities_retrieved_successfully' => 'Città recuperate con successo.',
    'image_order_updated' => "Ordine dell'immagine aggiornato con successo.",
    'image_order_limit' => "L'immagine è già al limite dell'ordine.",
    'payment_not_completed' => 'Il pagamento non è stato completato.',
    'payment_intent_created' => 'Pagamento elaborato con successo.',
    'user_already_premium' => "L'utente è già premium.",
    'notification_scheduled' => 'Tutto pronto! Ti avviseremo quando l’evento starà per iniziare.',
    'event_attached_by_company' => 'Ti sei unito con successo all’evento dell’azienda.',
    'link_not_valid' => 'Il link da cui hai effettuato l\'accesso non è valido.',
    'notification_already_scheduled' => 'Hai già una notifica programmata per questo evento.',
    'email_event_starting' => [
        'subject' => ':eventname inizia tra 5 minuti!',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
        Ciao :name,<br /><br />
        Preparati! L\'evento <strong>:eventname</strong> sta per iniziare.<br /><br />
        Ti aspettiamo a<strong> :location </strong>tra soli 5 minuti. 
        Assicurati di essere pronto per entrare e goderti la serata.<br /><br />
        Ci vediamo lì!
      </p>',
        'button' => 'Vedi dettagli evento',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
        <strong>Hooky!</strong> - Connettiti con le persone in discoteca e fai nuove amicizie mentre ti godi la serata.<br />
        Se hai domande o hai bisogno di aiuto, non esitare a contattare il nostro team di supporto su <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
        Questo è un messaggio automatico, per favore non rispondere a questa email.<br />
        <small>&copy; 2025 Hooky. Tutti i diritti riservati.</small>
      </p>',
    ],
];
