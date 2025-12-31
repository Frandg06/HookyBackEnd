<?php

declare(strict_types=1);

return [
    'user_data_completed' => 'Seus dados foram completados com sucesso.',
    'event_too_soon' => 'Faltam menos de 5 minutos para o evento, já não é possível agendar o aviso.',
    'message_notify' => 'Mensagem enviada com sucesso.',
    'register_user_ko' => 'Ocorreu um erro ao registrar o usuário.',
    'register_company_ok' => 'O usuário foi registrado com sucesso.',
    'user_exists' => 'Já existe um usuário registrado com este e-mail.',
    'company_not_exists' => 'O organizador não existe. Por favor, escaneie novamente o código QR.',
    'event_not_active' => 'Atualmente não há eventos ativos.',
    'credentials_ko' => 'O e-mail ou a senha estão incorretos.',
    'login_ok' => 'Sessão iniciada com sucesso.',
    'login_ko' => 'Ocorreu um erro ao iniciar a sessão.',
    'update_user_ko' => 'Ocorreu um erro ao atualizar o usuário.',
    'actual_password_ko' => 'A senha atual está incorreta.',
    'update_password_ko' => 'Ocorreu um erro ao atualizar a senha.',
    'register_company_ko' => 'Ocorreu um erro ao registrar a empresa.',
    'update_user_interest_ko' => 'Ocorreu um erro ao atualizar os interesses do usuário.',
    'get_notifications_ko' => 'Ocorreu um erro ao obter as notificações.',
    'images_extension_ko' => 'Os formatos de imagem permitidos são JPG, PNG e WEBP.',
    'images_size_ko' => 'O tamanho da imagem é muito grande. O máximo permitido é 10 MB.',
    'images_store_ko' => 'Ocorreu um erro ao salvar a imagem.',
    'image_not_found' => 'A imagem não existe ou não foi encontrada.',
    'image_delete_ko' => 'Ocorreu um erro ao excluir a imagem.',
    'get_users_ko' => 'Ocorreu um erro ao obter os usuários.',
    'set_interaction_ko' => 'Ocorreu um erro ao registrar a interação.',
    'ws_chat_ko' => 'Ocorreu um erro ao criar o chat.',
    'user_not_found' => 'O usuário não existe.',
    'password_reset_email' => 'Foi enviado um e-mail com o link para redefinir a senha.',
    'password_reset_subject' => 'Redefinir senha',
    'email_reset_password' => [
        'subject' => 'Redefinir senha',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
      Olá :name,<br /><br />
      Você solicitou a redefinição de senha no <strong>Hooky!</strong>.  
      Clique no link abaixo para criar uma nova senha:<br /><br />
      
      <em><strong>Este link expirará em 15 minutos.</strong></em>
      Se você não solicitou essa alteração, pode ignorar esta mensagem. 
      <br /><br /><strong>Referência: </strong>:uid 
    </p>',
        'button' => 'Redefinir senha',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
      <strong>Hooky!</strong> - Conecte-se com pessoas dentro da balada e faça novas amizades enquanto aproveita a noite.<br />
      Se tiver dúvidas ou precisar de ajuda, entre em contato com nossa equipe de suporte pelo e-mail <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
      Esta é uma mensagem automática, por favor, não responda este e-mail.<br />
      <small>&copy; 2025 Hooky. Todos os direitos reservados.</small>
    </p>',
    ],
    'logged_out' => 'Você saiu da sessão com sucesso.',
    'token_not_found' => 'Não existe nenhum usuário com esse token.',
    'token_expired' => 'O token expirou.',
    'unexpected_error' => 'Ocorreu um erro inesperado. Tente novamente mais tarde.',
    'event_same_day' => 'Já há um evento na data escolhida, por favor escolha outra data.',
    'event_limit_reached' => 'Você não pode criar mais eventos, atingiu o limite.',
    'start_date_past' => 'A data de início deve ser posterior à data atual.',
    'end_date_before_start' => 'A data de término deve ser posterior à data de início.',
    'event_duration_exceeded' => 'A duração máxima de um evento é de 12 horas.',
    'event_duration_too_short' => 'A duração mínima de um evento é de 2 horas.',
    'create_event_ko' => 'Ocorreu um erro ao criar o evento.',
    'tickets_not_numeric' => 'O número de ingressos deve ser um número.',
    'tickets_minimum' => 'O número de ingressos deve ser maior que 1.',
    'tickets_maximum' => 'O número de ingressos não pode ser maior que 1000.',
    'ticket_invalid' => 'O código já foi resgatado ou não existe.',
    'timezone_not_found' => 'O fuso horário selecionado não existe.',
    'update_company_ko' => 'Ocorreu um erro ao atualizar os dados da empresa.',
    'invalid_image_count' => 'O usuário pode enviar no máximo 3 imagens.',
    'update_user_interest_ko' => 'Ocorreu um erro ao atualizar os interesses do usuário.',
    'password_changed' => 'A senha foi alterada com sucesso.',
    'user_images_limit' => 'O usuário já tem 3 imagens.',
    'image_delete_ko' => 'Ocorreu um erro ao excluir a imagem.',
    'delete_image_unexpected_error' => 'Erro inesperado ao excluir a imagem.',
    'store_image_unexpected_error' => 'Erro inesperado ao salvar a nova imagem.',
    'read_notifications_ko' => 'Ocorreu um erro ao ler as notificações.',
    'notification_ko' => 'Ocorreu um erro ao enviar a notificação.',
    'email_required' => 'O e-mail é obrigatório.',
    'not_aviable_user' => 'Você não tem permissão para ver este usuário.',
    'event_not_active_or_next' => 'Não há nenhum evento ativo ou próximo.',
    'limit_users_reached' => 'O evento atingiu o limite de usuários.',
    'authenticated_user' => 'Usuário autenticado com sucesso.',
    'password_reset_success' => 'A senha foi redefinida com sucesso.',
    'user_not_login' => 'Você precisa fazer login para continuar.',
    'events_retrieved_successfully' => 'Eventos obtidos com sucesso.',
    'cities_retrieved_successfully' => 'Cidades obtidas com sucesso.',
    'image_order_updated' => 'Ordem da imagem atualizada com sucesso.',
    'image_order_limit' => 'A imagem já está no limite da ordem.',
    'payment_not_completed' => 'O pagamento não foi concluído.',
    'payment_intent_created' => 'Pagamento processado com sucesso.',
    'user_already_premium' => 'O usuário já é premium.',
    'notification_scheduled' => 'Tudo pronto! Vamos te avisar quando o evento estiver prestes a começar.',
    'event_attached_by_company' => 'Você entrou no evento da empresa com sucesso.',
    'link_not_valid' => 'O link pelo qual você acessou não é válido.',
    'notification_already_scheduled' => 'Você já tem uma notificação agendada para este evento.',
    'email_event_starting' => [
        'subject' => ':eventname começa em 5 minutos!',
        'message_1' => '<p style="line-height: 140%; margin: 0px">
        Olá :name,<br /><br />
        Prepara-te! O evento <strong>:eventname</strong> está prestes a começar.<br /><br />
        Esperamos por ti em<strong> :location </strong>em apenas 5 minutos. 
        Certifica-te de que estás pronto para entrar e aproveitar a noite.<br /><br />
        Vemo-nos lá!
      </p>',
        'button' => 'Ver detalhes do evento',
        'footer' => '<p style="font-size: 12px; text-align: center; margin-top: 20px;">
        <strong>Hooky!</strong> - Conecta-te com pessoas na discoteca e faz novas amizades enquanto desfrutas da noite.<br />
        Se tiveres dúvidas ou precisares de ajuda, não hesites em contactar a nossa equipa de suporte em <a href="mailto:support@hooky.com">support@hooky.com</a>.<br /><br />
        Esta é uma mensagem automática, por favor não respondas a este e-mail.<br />
        <small>&copy; 2025 Hooky. Todos os direitos reservados.</small>
      </p>',
    ],
];
