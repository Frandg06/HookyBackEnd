<?php

declare(strict_types=1);

return [

    'user_data_completed' => 'Your data has been completed successfully.',
    'event_too_soon' => 'Less than 5 minutes left for the event, you can no longer schedule the alert.',
    'register_user_ko' => 'An error occurred while registering the user.',
    'register_company_ok' => 'The user has been registered successfully.',
    'user_exists' => 'A user with this email already exists.',
    'company_not_exists' => 'The organizer does not exist. Please scan the QR code again.',
    'event_not_active' => 'There are currently no active events.',
    'credentials_ko' => 'The email or password is incorrect.',
    'login_ok' => 'Logged in successfully.',
    'login_ko' => 'An error occurred while logging in.',
    'update_user_ko' => 'An error occurred while updating the user.',
    'actual_password_ko' => 'The current password is incorrect.',
    'update_password_ko' => 'An error occurred while updating the password.',
    'register_company_ko' => 'An error occurred while registering the company.',
    'update_user_interest_ko' => 'An error occurred while updating user interests.',
    'get_notifications_ko' => 'An error occurred while fetching notifications.',
    'images_extension_ko' => 'Allowed image formats are JPG, PNG, and WEBP.',
    'images_size_ko' => 'The image size is too large. The maximum allowed is 10 MB.',
    'images_store_ko' => 'An error occurred while saving the image.',
    'image_not_found' => 'The image does not exist or was not found.',
    'image_delete_ko' => 'An error occurred while deleting the image.',
    'get_users_ko' => 'An error occurred while fetching users.',
    'set_interaction_ko' => 'An error occurred while recording the interaction.',
    'ws_chat_ko' => 'An error occurred while creating the chat.',
    'user_not_found' => 'The user does not exist.',
    'password_reset_email' => 'An email with the link to reset your password has been sent.',
    'password_reset_subject' => 'Reset password',
    'email_reset_password' => [
        'subject' => 'Reset password',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
      Hello :name,<br /><br />
      You have requested to reset your password on <strong>Hooky!</strong>.  
      Click the following link to create a new password:<br /><br />
      
      <em><strong>This link will expire in 15 minutes.</strong></em>
      If you did not request this change, you can ignore this message. 
      <br /><br /><strong>Reference: </strong>:uid 
    </p>',
        'button' => 'Reset password',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
      <strong>Hooky!</strong> - Connect with people inside the club and make new friends while enjoying the night.<br />
      If you have questions or need help, do not hesitate to contact our support team at <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
      This is an automated message, please do not reply to this email.<br />
      <small>&copy; 2025 Hooky. All rights reserved.</small>
    </p>',
    ],
    'logged_out' => 'You have logged out successfully.',
    'token_not_found' => 'No user exists with that token.',
    'token_expired' => 'The token has expired.',
    'unexpected_error' => 'An unexpected error occurred. Please try again later.',
    'event_same_day' => 'There is already an event on the chosen date, choose another day.',
    'event_limit_reached' => 'You cannot create more events, you have reached the limit.',
    'start_date_past' => 'The start date cannot be earlier than today.',
    'end_date_before_start' => 'The end date must be later than the start date.',
    'event_duration_exceeded' => 'The maximum duration of an event is 12 hours.',
    'event_duration_too_short' => 'The minimum duration of an event is 2 hours.',
    'create_event_ko' => 'An error occurred while creating the event.',
    'tickets_not_numeric' => 'The number of tickets must be a number.',
    'tickets_minimum' => 'The number of tickets must be greater than 1.',
    'tickets_maximum' => 'You can only generate a maximum of 3000 tickets at a time.',
    'ticket_invalid' => 'The code has already been redeemed or does not exist.',
    'timezone_not_found' => 'The selected timezone does not exist.',
    'update_company_ko' => 'An error occurred while updating the data.',
    'invalid_image_count' => 'The user can only upload 3 images.',
    'update_user_interest_ko' => 'An error occurred while updating user interests.',
    'password_changed' => 'The password has been changed successfully.',
    'user_images_limit' => 'The user already has 3 images.',
    'image_delete_ko' => 'An error occurred while deleting the image.',
    'delete_image_unexpected_error' => 'Unexpected error while deleting the image.',
    'store_image_unexpected_error' => 'Unexpected error while storing the new image.',
    'read_notifications_ko' => 'An error occurred while reading notifications.',
    'notification_ko' => 'An error occurred while sending the notification.',
    'email_required' => 'Email is required.',
    'not_aviable_user' => 'You do not have permission to view this user.',
    'event_not_active_or_next' => 'There is no active or upcoming event.',
    'limit_users_reached' => 'The event has reached the user limit.',
    'event_at_same_time' => 'There cannot be more than one event at the same time.',
    'notify' => [
        'base' => 'You received a :interaction.',
        'hook' => 'You made a Hook.',
    ],
    'message_notify' => 'The message has been sent successfully.',
    'event_not_found' => 'The event is not active.',
    'event_not_active' => 'The event is not active.',
    'event_is_past_delete' => 'You cannot delete an event that has already ended.',
    'event_is_past' => 'The event has already ended.',
    'event_not_start' => 'The event has not started yet, please try again later.',
    'ticket_limit_reached' => 'You have reached the ticket limit for this event.',
    'ticket_not_found' => 'The ticket does not exist or is not active.',
    'user_not_login' => 'You must log in to continue.',
    'authenticated_user' => 'User authenticated successfully.',
    'password_reset_success' => 'The password has been reset successfully.',
    'events_retrieved_successfully' => 'Events retrieved successfully.',
    'cities_retrieved_successfully' => 'Cities retrieved successfully.',
    'image_order_updated' => 'Image order updated successfully.',
    'image_order_limit' => 'The image is already at the limit of the order.',
    'payment_not_completed' => 'Payment was not completed.',
    'payment_intent_created' => 'Payment processed successfully.',
    'user_already_premium' => 'The user is already premium.',
    'notifications_disabled' => 'Notifications are disabled in your settings.',
    'notification_scheduled' => 'All set! We will notify you when the event is about to start.',
    'event_attached_by_company' => 'You have successfully joined the company event.',
    'link_not_valid' => 'The link you accessed is invalid.',
    'notification_already_scheduled' => 'You already have a notification scheduled for this event.',
    'email_event_starting' => [
        'subject' => ':eventname starts in 5 minutes!',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
        Hello :name,<br /><br />
        Get ready! The event <strong>:eventname</strong> is about to start.<br /><br />
        We await you at<strong> :location </strong>in just 5 minutes. 
        Make sure you are ready to enter and enjoy the night.<br /><br />
        See you there!
      </p>',
        'button' => 'View event details',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
        <strong>Hooky!</strong> - Connect with people inside the club and make new friends while enjoying the night.<br />
        If you have questions or need help, do not hesitate to contact our support team at <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
        This is an automated message, please do not reply to this email.<br />
        <small>&copy; 2025 Hooky. All rights reserved.</small>
      </p>',
    ],
    'heterosexual' => 'Heterosexual',
    'gay' => 'Gay',
    'lesbian' => 'Lesbian',
    'bisexual' => 'Bisexual',
    'not_specified' => 'Not specified',
    'payment_successful' => 'Payment successful',
    'payment_success_description' => 'Your transaction was completed successfully.',
    'premium_access_granted' => 'You now have access to Hooky Premium features.',
    'payment_details' => 'Payment details',
    'total_amount' => 'Total amount',
    'payment_method' => 'Payment method',
    'date' => 'Date',
    'transaction_id' => 'Transaction ID',
    'go_to_app' => 'Go to app',
    'all_rights_reserved' => 'All rights reserved',
];
