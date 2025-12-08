<?php

declare(strict_types=1);

return [

    'register_user_ko' => 'An error occurred while registering the user.',
    'register_company_ok' => 'The user has been successfully registered.',
    'user_exists' => 'A user with this email address already exists.',
    'company_not_exists' => 'The organizer does not exist. Please scan the QR code again.',
    'event_not_active' => 'There are currently no active events.',
    'credentials_ko' => 'The email or password is incorrect.',
    'login_ok' => 'Successfully logged in.',
    'login_ko' => 'An error occurred while logging in.',
    'update_user_ko' => 'An error occurred while updating the user.',
    'actual_password_ko' => 'The current password is incorrect.',
    'update_password_ko' => 'An error occurred while updating the password.',
    'register_company_ko' => 'An error occurred while registering the company.',
    'update_user_interest_ko' => 'An error occurred while updating the user interests.',
    'get_notifications_ko' => 'An error occurred while retrieving notifications.',
    'images_extension_ko' => 'Allowed image formats are JPG, PNG, and WEBP.',
    'images_size_ko' => 'The image size is too large. The maximum allowed is 10 MB.',
    'images_store_ko' => 'An error occurred while saving the image.',
    'image_not_found' => 'The image does not exist or was not found.',
    'image_delete_ko' => 'An error occurred while deleting the image.',
    'get_users_ko' => 'An error occurred while retrieving users.',
    'set_interaction_ko' => 'An error occurred while registering the interaction.',
    'ws_chat_ko' => 'An error occurred while creating the chat.',
    'user_not_found' => 'The user does not exist.',
    'password_reset_email' => 'An email has been sent with the link to reset your password.',
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
      If you have questions or need help, please contact our support team at <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
      This is an automated message, please do not reply.<br />
      <small>&copy; 2025 Hooky. All rights reserved.</small>
    </p>',
    ],
    'logged_out' => 'You have successfully logged out.',
    'token_not_found' => 'No user exists with that token.',
    'token_expired' => 'The token has expired.',
    'unexpected_error' => 'An unexpected error occurred. Please try again later.',
    'event_same_day' => 'There is already an event on the chosen date, please select another day.',
    'event_limit_reached' => 'You cannot create more events, you have reached the limit.',
    'start_date_past' => 'The start date must be later than the current date.',
    'end_date_before_start' => 'The end date must be later than the start date.',
    'event_duration_exceeded' => 'The maximum event duration is 12 hours.',
    'event_duration_too_short' => 'The minimum event duration is 2 hours.',
    'create_event_ko' => 'An error occurred while creating the event.',
    'tickets_not_numeric' => 'The number of tickets must be a number.',
    'tickets_minimum' => 'The number of tickets must be greater than 1.',
    'tickets_maximum' => 'The number of tickets cannot exceed 1000.',
    'ticket_invalid' => 'The code has already been redeemed or does not exist.',
    'timezone_not_found' => 'The selected timezone does not exist.',
    'update_company_ko' => 'An error occurred while updating the data.',
    'invalid_image_count' => 'The user can only upload 3 images.',
    'password_changed' => 'The password has been changed successfully.',
    'user_images_limit' => 'The user already has 3 images.',
    'delete_image_unexpected_error' => 'Unexpected error while deleting the image.',
    'store_image_unexpected_error' => 'Unexpected error while storing the new image.',
    'read_notifications_ko' => 'An error occurred while reading notifications.',
    'notification_ko' => 'An error occurred while sending the notification.',
    'email_required' => 'The email is required.',
    'not_aviable_user' => 'You do not have permission to view this user.',
    'event_not_active_or_next' => 'There is no active or upcoming event.',
    'limit_users_reached' => 'The event has reached the user limit.',

];
