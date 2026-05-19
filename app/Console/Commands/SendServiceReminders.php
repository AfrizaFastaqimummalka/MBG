<?php

namespace App\Console\Commands;

use App\Models\NotificationSetting;
use App\Models\Vehicle;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendServiceReminders extends Command
{
    protected $signature   = 'mbg:send-reminders';
    protected $description = 'Kirim reminder service kendaraan via WhatsApp';

    public function handle(FonnteService $fonnte): void
    {
        $today    = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Ambil semua kendaraan yang next_service_date = hari ini atau besok
        $vehicles = Vehicle::with('user')
            ->whereNotNull('next_service_date')
            ->whereIn('next_service_date', [$today->toDateString(), $tomorrow->toDateString()])
            ->get();

        if ($vehicles->isEmpty()) {
            $this->info('Tidak ada reminder yang perlu dikirim hari ini.');
            return;
        }

        foreach ($vehicles as $vehicle) {
            $user    = $vehicle->user;
            $setting = NotificationSetting::where('user_id', $user->id)->first();

            // Skip kalau user matikan reminder
            if (!$setting || !$setting->wa_service_reminder) {
                continue;
            }

            $phone = $setting->wa_phone ?: $user->phone;

            if (!$phone) {
                Log::warning("MBG Reminder: user {$user->id} tidak punya nomor WA");
                continue;
            }

            $tanggal = Carbon::parse($vehicle->next_service_date)->translatedFormat('d F Y');
            $label   = $vehicle->next_service_date === $today->toDateString() ? 'HARI INI' : 'besok';

            $message = "🔔 *Reminder Service MBG*\n\n"
                . "Halo {$user->name}! Kendaraan kamu perlu service {$label}:\n\n"
                . "🏍️ *Kendaraan:* {$vehicle->name}\n"
                . "📅 *Jadwal:* {$tanggal}\n"
                . ($vehicle->next_service_km ? "🛣️ *Target KM:* " . number_format($vehicle->next_service_km, 0, ',', '.') . " km\n" : "")
                . "\nJangan lupa service tepat waktu ya! 😊\n"
                . "_— MBG Motor Book Garage_";

            $ok = $fonnte->send($phone, $message);

            if ($ok) {
                $this->info("✅ Reminder terkirim: {$user->name} - {$vehicle->name}");
                Log::info("MBG Reminder: terkirim ke {$user->name} untuk {$vehicle->name}");
            } else {
                $this->error("❌ Gagal kirim: {$user->name} - {$vehicle->name}");
                Log::error("MBG Reminder: gagal kirim ke {$user->name} untuk {$vehicle->name}");
            }
        }

        $this->info('Selesai.');
    }
}