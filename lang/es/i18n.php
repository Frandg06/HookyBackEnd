<?php

return [

  'register_user_ko' => 'Ocurrió un error al registrar el usuario.',
  'register_company_ok' => 'El usuario ha sido registrado con éxito.',
  'user_exists' => 'Ya existe un usuario registrado con este correo electrónico.',
  'company_not_exists' => 'El organizador no existe. Por favor, escanea nuevamente el código QR.',
  'event_not_active' => 'Actualmente no hay eventos activos.',
  'credentials_ko' => 'El correo electrónico o la contraseña son incorrectos.',
  'login_ok' => 'Sesión iniciada con éxito.',
  'login_ko' => 'Ocurrió un error al iniciar sesión.',
  'update_user_ko' => 'Ocurrió un error al actualizar el usuario.',
  'actual_password_ko' => 'La contraseña actual es incorrecta.',
  'update_password_ko' => 'Ocurrió un error al actualizar la contraseña.',
  'register_company_ko' => 'Ocurrió un error al registrar la empresa.',
  'update_user_interest_ko' => 'Ocurrió un error al actualizar los intereses del usuario.',
  'get_notifications_ko' => 'Ocurrió un error al obtener las notificaciones.',
  'images_extension_ko' => 'Los formatos de imagen permitidos son JPG, PNG y WEBP.',
  'images_size_ko' => 'El tamaño de la imagen es demasiado grande. El máximo permitido es 10 MB.',
  'images_store_ko' => 'Ocurrió un error al guardar la imagen.',
  'image_not_found' => 'La imagen no existe o no se ha encontrado.',
  'image_delete_ko' => 'Ocurrió un error al eliminar la imagen.',
  'get_users_ko' => 'Ocurrió un error al obtener los usuarios.',
  'set_interaction_ko' => 'Ocurrió un error al registrar la interacción.',
  'ws_chat_ko' => 'Ocurrió un error al crear el chat.',
  'user_not_found' => 'El usuario no existe.',
  'password_reset_email' => 'Se ha enviado un correo electrónico con el link para restablecer la contraseña.',
  'password_reset_subject' => 'Restablecer contraseña',
  'email_reset_password' => [
    'subject' => 'Restablecer contraseña',
    'message_1' => '<p style="line-height: 140%; margin: 0px">
      Hola :name,<br /><br />
      Has solicitado restablecer tu contraseña en <strong>Hooky!</strong>.  
      Haz clic en el siguiente enlace para crear una nueva contraseña:<br /><br />
      
      <em><strong>Este enlace expirará en 15 minutos.</strong></em>
      Si no solicitaste este cambio, puedes ignorar este mensaje. 
      <br /><br /><strong>Reference: </strong>:uid 
    </p>',
    'button' => 'Restablecer contraseña',
    'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
      <strong>Hooky!</strong> - Conéctate con personas dentro de la discoteca y haz nuevas amistades mientras disfrutas de la noche.<br />
      Si tienes preguntas o necesitas ayuda, no dudes en ponerte en contacto con nuestro equipo de soporte en <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
      Este es un mensaje automatizado, por favor no respondas a este correo.<br />
      <small>&copy; 2025 Hooky. Todos los derechos reservados.</small>
    </p>'
  ],
  'logged_out' => 'Has cerrado sesión correctamente.',
  'token_not_found' => 'No existe ningun usuario con ese token.',
  'token_expired' => 'El token ha expirado.',
  'unexpected_error' => 'Ha ocurrido un error inesperado. Intentelo mas tarde.',
];
