<?php

declare(strict_types=1);

return [

    'user_data_completed' => 'I tuoi dati sono stati completati correttamente.',
    'event_too_soon' => 'Mancano meno di 5 minuti all\'evento, non puoi più programmare l\'avviso.',
    'register_user_ko' => 'Si è verificato un errore durante la registrazione dell\'utente.',
    'register_company_ok' => 'L\'utente è stato registrato con successo.',
    'user_exists' => 'Esiste già un utente registrato con questa email.',
    'company_not_exists' => 'L\'organizzatore non esiste. Per favore, scansiona nuovamente il codice QR.',
    'event_not_active' => 'Attualmente non ci sono eventi attivi.',
    'credentials_ko' => 'L\'email o la password non sono corrette.',
    'login_ok' => 'Login effettuato con successo.',
    'login_ko' => 'Si è verificato un errore durante il login.',
    'update_user_ko' => 'Si è verificato un errore durante l\'aggiornamento dell\'utente.',
    'actual_password_ko' => 'La password attuale non è corretta.',
    'update_password_ko' => 'Si è verificato un errore durante l\'aggiornamento della password.',
    'register_company_ko' => 'Si è verificato un errore durante la registrazione dell\'azienda.',
    'update_user_interest_ko' => 'Si è verificato un errore durante l\'aggiornamento degli interessi dell\'utente.',
    'get_notifications_ko' => 'Si è verificato un errore durante il recupero delle notifiche.',
    'images_extension_ko' => 'I formati di immagine consentiti sono JPG, PNG e WEBP.',
    'images_size_ko' => 'La dimensione dell\'immagine è troppo grande. Il massimo consentito è 10 MB.',
    'images_store_ko' => 'Si è verificato un errore durante il salvataggio dell\'immagine.',
    'image_not_found' => 'L\'immagine non esiste o non è stata trovata.',
    'image_delete_ko' => 'Si è verificato un errore durante l\'eliminazione dell\'immagine.',
    'get_users_ko' => 'Si è verificato un errore durante il recupero degli utenti.',
    'set_interaction_ko' => 'Si è verificato un errore durante la registrazione dell\'interazione.',
    'ws_chat_ko' => 'Si è verificato un errore durante la creazione della chat.',
    'user_not_found' => 'L\'utente non esiste.',
    'password_reset_email' => 'È stata inviata un\'email con il link per reimpostare la password.',
    'password_reset_subject' => 'Reimposta password',
    'email_reset_password' => [
        'subject' => 'Reimposta password',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
      Ciao :name,<br /><br />
      Hai richiesto di reimpostare la tua password su <strong>Hooky!</strong>.  
      Clicca sul seguente link per creare una nuova password:<br /><br />
      
      <em><strong>Questo link scadrà tra 15 minuti.</strong></em>
      Se non hai richiesto questa modifica, puoi ignorare questo messaggio. 
      <br /><br /><strong>Riferimento: </strong>:uid 
    </p>',
        'button' => 'Reimposta password',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
      <strong>Hooky!</strong> - Connettiti con le persone all\'interno della discoteca e fai nuove amicizie mentre ti godi la serata.<br />
      Se hai domande o hai bisogno di aiuto, non esitare a contattare il nostro team di supporto a <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
      Questo è un messaggio automatico, per favore non rispondere a questa email.<br />
      <small>&copy; 2025 Hooky. Tutti i diritti riservati.</small>
    </p>',
    ],
    'logged_out' => 'Ti sei disconnesso correttamente.',
    'token_not_found' => 'Non esiste alcun utente con quel token.',
    'token_expired' => 'Il token è scaduto.',
    'unexpected_error' => 'Si è verificato un errore imprevisto. Riprova più tardi.',
    'event_same_day' => 'C\'è già un evento nella data scelta, scegli un altro giorno.',
    'event_limit_reached' => 'Non puoi creare più eventi, hai raggiunto il limite.',
    'start_date_past' => 'La data di inizio non può essere precedente a quella attuale.',
    'end_date_before_start' => 'La data di fine deve essere successiva alla data di inizio.',
    'event_duration_exceeded' => 'La durata massima di un evento è di 12 ore.',
    'event_duration_too_short' => 'La durata minima di un evento è di 2 ore.',
    'create_event_ko' => 'Si è verificato un errore durante la creazione dell\'evento.',
    'tickets_not_numeric' => 'Il numero di biglietti deve essere un numero.',
    'tickets_minimum' => 'Il numero di biglietti deve essere maggiore di 1.',
    'tickets_maximum' => 'È possibile generare un massimo di 3000 biglietti alla volta.',
    'ticket_invalid' => 'Il codice è già stato riscattato o non esiste.',
    'timezone_not_found' => 'Il fuso orario selezionato non esiste.',
    'update_company_ko' => 'Si è verificato un errore durante l\'aggiornamento dei dati.',
    'invalid_image_count' => 'L\'utente può caricare solo 3 immagini.',
    'update_user_interest_ko' => 'Si è verificato un errore durante l\'aggiornamento degli interessi dell\'utente.',
    'password_changed' => 'La password è stata cambiata correttamente.',
    'user_images_limit' => 'L\'utente ha già 3 immagini.',
    'image_delete_ko' => 'Si è verificato un errore durante l\'eliminazione dell\'immagine.',
    'delete_image_unexpected_error' => 'Errore imprevisto durante l\'eliminazione dell\'immagine.',
    'store_image_unexpected_error' => 'Errore imprevisto durante il salvataggio della nuova immagine.',
    'read_notifications_ko' => 'Si è verificato un errore durante la lettura delle notifiche.',
    'notification_ko' => 'Si è verificato un errore durante l\'invio della notifica.',
    'email_required' => 'L\'email è obbligatoria.',
    'not_aviable_user' => 'Non hai i permessi per vedere questo utente.',
    'event_not_active_or_next' => 'Non ci sono eventi attivi o imminenti.',
    'limit_users_reached' => 'L\'evento ha raggiunto il limite di utenti.',
    'event_at_same_time' => 'Non può esserci più di un evento alla stessa ora.',
    'notify' => [
        'base' => 'Hai ricevuto un :interaction.',
        'hook' => 'Hai fatto un Hook.',
    ],
    'message_notify' => 'Il messaggio è stato inviato correttamente.',
    'event_not_found' => 'L\'evento non è attivo.',
    'event_not_active' => 'L\'evento non è attivo.',
    'event_is_past_delete' => 'Non è possibile eliminare un evento che è già terminato.',
    'event_is_past' => 'L\'evento è già terminato.',
    'event_not_start' => 'L\'evento non è ancora iniziato, riprova più tardi.',
    'ticket_limit_reached' => 'Hai raggiunto il limite di biglietti per questo evento.',
    'ticket_not_found' => 'Il biglietto non esiste o non è attivo.',
    'user_not_login' => 'Devi effettuare il login per poter continuare.',
    'authenticated_user' => 'Utente autenticato correttamente.',
    'password_reset_success' => 'La password è stata reimpostata correttamente.',
    'events_retrieved_successfully' => 'Eventi recuperati con successo.',
    'cities_retrieved_successfully' => 'Città recuperate con successo.',
    'image_order_updated' => 'Ordine delle immagini aggiornato con successo.',
    'image_order_limit' => 'L\'immagine è già al limite dell\'ordine.',
    'payment_not_completed' => 'Il pagamento non è stato completato.',
    'payment_intent_created' => 'Pagamento elaborato correttamente.',
    'user_already_premium' => 'L\'utente è già premium.',
    'notifications_disabled' => 'Le notifiche sono disabilitate nelle tue impostazioni.',
    'notification_scheduled' => 'Tutto pronto! Ti avviseremo quando l\'evento sta per iniziare.',
    'event_attached_by_company' => 'Ti sei unito correttamente all\'evento aziendale.',
    'link_not_valid' => 'Il link da cui hai effettuato l\'accesso non è valido.',
    'notification_already_scheduled' => 'Hai già una notifica programmata per questo evento.',
    'email_event_starting' => [
        'subject' => ':eventname inizia tra 5 minuti!',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
        Ciao :name,<br /><br />
        Preparati! L\'evento <strong>:eventname</strong> sta per iniziare.<br /><br />
        Ti aspettiamo a <strong> :location </strong> tra soli 5 minuti. 
        Assicurati di essere pronto per entrare e goderti la serata.<br /><br />
        Ci vediamo lì!
      </p>',
        'button' => 'Vedi dettagli dell\'evento',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
        <strong>Hooky!</strong> - Connettiti con le persone all\'interno della discoteca e fai nuove amicizie mentre ti godi la serata.<br />
        Se hai domande o hai bisogno di aiuto, non esitare a contattare il nostro team di supporto a <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
        Questo è un messaggio automatico, per favore non rispondere a questa email.<br />
        <small>&copy; 2025 Hooky. Tutti i diritti riservati.</small>
      </p>',
    ],
    'heterosexual' => 'Eterosessuale',
    'gay' => 'Gay',
    'lesbian' => 'Lesbica',
    'bisexual' => 'Bisessuale',
    'not_specified' => 'Non specificato',
    'payment_successful' => 'Pagamento riuscito',
    'payment_success_description' => 'La tua transazione è stata completata con successo.',
    'premium_access_granted' => 'Ora hai accesso alle funzionalità Premium di Hooky.',
    'payment_details' => 'Dettagli del pagamento',
    'total_amount' => 'Importo totale',
    'payment_method' => 'Metodo di pagamento',
    'date' => 'Data',
    'transaction_id' => 'ID transazione',
    'go_to_app' => 'Vai all\'app',
    'all_rights_reserved' => 'Tutti i diritti riservati',
];
