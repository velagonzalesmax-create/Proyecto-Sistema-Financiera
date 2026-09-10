<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Movimientos</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #1E1B4B;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #6B7280;
            font-size: 11px;
        }
        .summary-container {
            width: 100%;
            margin-bottom: 25px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-box {
            padding: 12px;
            border-radius: 6px;
            text-align: center;
        }
        .box-ingreso {
            background-color: #ECFDF5;
            border: 1px solid #10B981;
            color: #065F46;
        }
        .box-gasto {
            background-color: #FEF2F2;
            border: 1px solid #EF4444;
            color: #991B1B;
        }
        .box-balance {
            background-color: #EFF6FF;
            border: 1px solid #3B82F6;
            color: #1E40AF;
        }
        .summary-title {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .summary-amount {
            font-size: 16px;
            font-weight: bold;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-data th {
            background-color: #F3F4F6;
            color: #374151;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            padding: 8px;
            border-bottom: 2px solid #E5E7EB;
            text-align: left;
        }
        .table-data td {
            padding: 8px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-ingreso {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .badge-gasto {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #9CA3AF;
            border-top: 1px solid #E5E7EB;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Reporte de Movimientos Financieros</h1>
        <p>Generado el {{ date('d/m/Y H:i') }} | Sistema de Control Financiero</p>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="summary-container">
        <table class="summary-table">
            <tr>
                <td width="32%">
                    <div class="summary-box box-ingreso">
                        <div class="summary-title">Total Ingresos</div>
                        <div class="summary-amount">S/ {{ number_format($totalIngresos, 2) }}</div>
                    </div>
                </td>
                <td width="2%"></td>
                <td width="32%">
                    <div class="summary-box box-gasto">
                        <div class="summary-title">Total Gastos</div>
                        <div class="summary-amount">S/ {{ number_format($totalGastos, 2) }}</div>
                    </div>
                </td>
                <td width="2%"></td>
                <td width="32%">
                    <div class="summary-box box-balance">
                        <div class="summary-title">Balance Neto</div>
                        <div class="summary-amount">S/ {{ number_format($balance, 2) }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tabla Detallada -->
    <table class="table-data">
        <thead>
            <tr>
                <th width="15%">Fecha</th>
                <th width="20%">Categoría</th>
                <th width="15%">Tipo</th>
                <th width="35%">Descripción</th>
                <th width="15%" class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movimientos as $mov)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}</td>
                    <td><strong>{{ $mov->categoria->nombre ?? '-' }}</strong></td>
                    <td class="text-center">
                        <span class="badge {{ $mov->tipo === 'ingreso' ? 'badge-ingreso' : 'badge-gasto' }}">
                            {{ $mov->tipo }}
                        </span>
                    </td>
                    <td>{{ $mov->descripcion ?? '-' }}</td>
                    <td class="text-right" style="font-weight: bold; color: {{ $mov->tipo === 'ingreso' ? '#059669' : '#DC2626' }};">
                        {{ $mov->tipo === 'ingreso' ? '+' : '-' }} S/ {{ number_format($mov->monto, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 15px; color: #6B7280;">
                        No se encontraron registros de movimientos con los filtros aplicados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Este documento es un reporte oficial generado automáticamente por el Sistema de Control Financiero.
    </div>

</body>
</html>
