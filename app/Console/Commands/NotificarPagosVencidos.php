<?php

namespace App\Console\Commands;

use App\Mail\PagoPorVencerMail;
use App\Models\PagoRecurrente;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificarPagosVencidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagos:notificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía alertas por correo de pagos recurrentes que vencen hoy, en 1 día o en 3 días';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $fechas = [
            Carbon::now()->toDateString(),
            Carbon::now()->addDays(1)->toDateString(),
            Carbon::now()->addDays(3)->toDateString(),
        ];

        $pagos = PagoRecurrente::where('estado', 'activo')
            ->whereIn('fecha_vencimiento', $fechas)
            ->with(['user', 'categoria'])
            ->get();

        $this->info('Consultando pagos recurrentes con vencimiento en: ' . implode(', ', $fechas));
        $this->info("Pagos encontrados: {$pagos->count()}");

        $enviados = 0;

        foreach ($pagos as $pago) {
            if ($pago->user && !empty($pago->user->email)) {
                try {
                    Mail::to($pago->user->email)->send(new PagoPorVencerMail($pago));
                    $this->info("Correo enviado a {$pago->user->email} para el pago: {$pago->nombre}");
                    $enviados++;
                } catch (\Exception $e) {
                    $this->error("Error enviando correo a {$pago->user->email}: " . $e->getMessage());
                    Log::error("Error enviando notificación de pago recurrente ID {$pago->id}: " . $e->getMessage());
                }
            }
        }

        $this->info("Proceso completado. Total de correos enviados: {$enviados}");

        return Command::SUCCESS;
    }
}
