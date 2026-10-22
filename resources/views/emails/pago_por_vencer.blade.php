<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso de Pago por Vencer</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f3f4f6; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 580px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); padding: 32px 24px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;">
                                Gestión Financiera
                            </h1>
                            <p style="margin: 8px 0 0 0; color: #e0e7ff; font-size: 14px;">
                                Recordatorio de Pago Recurrente
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px 28px;">
                            <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #374151;">
                                Hola, <strong>{{ $pago->user->name ?? 'Usuario' }}</strong>
                            </p>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #4b5563;">
                                Te informamos que tienes un pago recurrente programado que se encuentra próximo a vencer. Por favor, asegúrate de contar con los fondos disponibles para evitar recargos o suspensiones del servicio.
                            </p>

                            <!-- Detalle del Pago Card -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 13px; color: #6b7280; text-transform: uppercase; font-weight: 600;">
                                                    Servicio:
                                                </td>
                                                <td style="padding: 8px 0; font-size: 15px; color: #111827; font-weight: 700; text-align: right;">
                                                    {{ $pago->nombre }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 13px; color: #6b7280; text-transform: uppercase; font-weight: 600; border-top: 1px dashed #e5e7eb;">
                                                    Categoría:
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #374151; font-weight: 500; text-align: right; border-top: 1px dashed #e5e7eb;">
                                                    {{ $pago->categoria->nombre ?? 'General' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 13px; color: #6b7280; text-transform: uppercase; font-weight: 600; border-top: 1px dashed #e5e7eb;">
                                                    Monto a Pagar:
                                                </td>
                                                <td style="padding: 8px 0; font-size: 18px; color: #dc2626; font-weight: 800; text-align: right; border-top: 1px dashed #e5e7eb;">
                                                    S/ {{ number_format($pago->monto, 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 13px; color: #6b7280; text-transform: uppercase; font-weight: 600; border-top: 1px dashed #e5e7eb;">
                                                    Fecha de Vencimiento:
                                                </td>
                                                <td style="padding: 8px 0; font-size: 15px; color: #d97706; font-weight: 700; text-align: right; border-top: 1px dashed #e5e7eb;">
                                                    {{ \Carbon\Carbon::parse($pago->fecha_vencimiento)->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Botón CTA -->
                            <div style="text-align: center; margin: 32px 0 16px 0;">
                                <a href="{{ route('pagos-recurrentes.index') }}" 
                                   style="display: inline-block; background-color: #4f46e5; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; padding: 14px 28px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.4);">
                                    Ir al Sistema y Gestionar Pago &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px 24px; text-align: center; border-top: 1px solid #f3f4f6; font-size: 12px; color: #9ca3af;">
                            <p style="margin: 0 0 4px 0;">
                                Este es un correo automático generado por tu Sistema de Gestión Financiera.
                            </p>
                            <p style="margin: 0;">
                                &copy; {{ date('Y') }} Gestión Financiera. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
