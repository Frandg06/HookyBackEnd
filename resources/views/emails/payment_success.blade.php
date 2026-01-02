<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('i18n.payment_successful') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; background-color: #0f0f0f; font-family: 'Plus Jakarta Sans', Helvetica, Arial, sans-serif; }
        table { border-spacing: 0; }
        td { padding: 0; }
        img { border: 0; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #0f0f0f; padding-bottom: 40px; }
        .webkit { max-width: 600px; background-color: #0f0f0f; margin: 0 auto; }
        .outer { margin: 0 auto; width: 100%; max-width: 600px; font-family: 'Plus Jakarta Sans', Helvetica, Arial, sans-serif; color: #f8fafc; }
        
        /* Estilos específicos para botones en hover (solo funcionará en algunos clientes) */
        .btn-primary:hover { background-color: #e11d48 !important; }
        
        @media only screen and (max-width: 600px) {
            .inner-padding { padding: 20px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #0f0f0f; color: #f8fafc;">

    <center class="wrapper" style="width: 100%; table-layout: fixed; background-color: #0f0f0f;">
        <div class="webkit" style="max-width: 600px; margin: 0 auto;">
            
            <table class="outer" align="center" style="margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; font-family: 'Plus Jakarta Sans', Helvetica, Arial, sans-serif; color: #f8fafc;">
                <tr>
                    <td style="padding-top: 40px;"></td>
                </tr>
                
                <tr>
                    <td style="text-align: center; padding-bottom: 30px;">
                        <div style="font-weight: 700; font-size: 24px; color: #f8fafc; display: inline-flex; align-items: center; gap: 4px;">
                            <div style="display: inline-flex; align-items: center; justify-content: center; padding: 8px; background-color: #f43f5e; border-radius: 1rem;">
                                <img src="https://cdn.hookyapp.es/hooky/app/logos/unicolor/ligth/logoXs.webp" alt="Logo" style="border-radius: 50%; width: 30px; height: 30px; vertical-align: middle;"> 
                            </div>
                            <span style="vertical-align: middle; margin-left: 8px;">Hooky!</span>
                         </div>
                    </td>
                </tr>

                <tr>
                    <td style="text-align: center;">
                        <div style="display: inline-block; width: 80px; height: 80px; background-color: rgba(244, 63, 94, 0.2); border-radius: 50%; text-align: center; line-height: 80px;">
                            <div style="display: inline-block; width: 50px; height: 50px; background-color: #f43f5e; border-radius: 50%; margin-top: 15px; text-align: center; line-height: 50px; box-shadow: 0 0 15px rgba(244, 63, 94, 0.4);">
                                <span style="font-size: 24px; color: #ffffff; font-weight: bold;">&#10003;</span>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td style="text-align: center; padding-top: 20px; padding-bottom: 10px;">
                        <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #f8fafc;">{{ __('i18n.payment_successful') }}</h1>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; padding-bottom: 30px; padding-left: 20px; padding-right: 20px;">
                        <p style="margin: 0; font-size: 14px; color: #94a3b8; line-height: 1.5;">
                            {{ __('i18n.payment_success_description') }}<br>
                            {{ __('i18n.premium_access_granted') }}
                        </p>
                    </td>
                </tr>

                <tr>
                    <td class="inner-padding" style="padding: 0 40px;">
                        <table width="100%" style="background-color: #1a1a1a; border-radius: 16px; padding: 25px; border-spacing: 0;">
                            <tr>
                                <td style="padding-bottom: 20px; border-bottom: 1px solid #262626;">
                                    <p style="margin: 0; font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">{{ __('i18n.payment_details') }}</p>
                                </td>
                            </tr>
                            
                            <tr>
                                <td style="padding-top: 20px; padding-bottom: 15px;">
                                    <table width="100%">
                                        <tr>
                                            <td style="color: #94a3b8; font-size: 14px;">{{ __('i18n.total_amount') }}</td>
                                            <td style="text-align: right; color: #f8fafc; font-size: 16px; font-weight: 700;">{{ $data['amount'] }}€</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding-bottom: 15px;">
                                    <table width="100%">
                                        <tr>
                                            <td style="color: #94a3b8; font-size: 14px;">{{ __('i18n.payment_method') }}</td>
                                            <td style="text-align: right; color: #f8fafc; font-size: 14px;">
                                                {{ $data['method'] }} •••• {{ $data['last4'] }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding-bottom: 15px;">
                                    <table width="100%">
                                        <tr>
                                            <td style="color: #94a3b8; font-size: 14px;">{{ __('i18n.date') }}</td>
                                            <td style="text-align: right; color: #f8fafc; font-size: 14px;">{{ $data['date'] }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding-bottom: 5px;">
                                    <table width="100%">
                                        <tr>
                                            <td style="color: #94a3b8; font-size: 14px;">{{ __('i18n.transaction_id') }}</td>
                                            <td style="text-align: right; color: #f8fafc; font-size: 12px; font-family: 'JetBrains Mono', monospace;">{{ $data['transaction_id'] }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr><td style="height: 30px;"></td></tr>

                <tr>
                    <td class="inner-padding" style="padding: 0 40px; text-align: center;">
                        <a href="{{ config('app.front_url') }}" style="display: block; width: 100%; background-color: #f43f5e; color: #ffffff; text-decoration: none; padding: 14px 0; border-radius: 12px; font-weight: 600; font-size: 16px; text-align: center; margin-bottom: 12px;">
                           {{ __('i18n.go_to_app') }}
                        </a>
                    </td>
                </tr>

                <tr>
                    <td style="padding-top: 40px; padding-bottom: 40px; text-align: center; color: #52525b; font-size: 12px;">
                        <p style="margin: 0;">&copy; {{ date('Y') }} Spark Social. {{ __('i18n.all_rights_reserved') }}.</p>
                    </td>
                </tr>

            </table>
        </div>
    </center>
</body>
</html>