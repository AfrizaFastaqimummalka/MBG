<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $token;
    protected string $baseUrl = 'https://api.fonnte.com';

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
    }

    /**
     * Kirim pesan WhatsApp via Fonnte
     *
     * @param  string  $phone  Nomor tujuan (format: 628xxxxxxxx)
     * @param  string  $message
     * @return bool
     */
    public function send(string $phone, string $message): bool
    {
        if (empty($this->token)) {
            Log::warning('Fonnte: token belum dikonfigurasi');
            return false;
        }

        // Normalisasi nomor: hilangkan +, ganti awalan 0 dengan 62
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $this->token,
                ])->post("{$this->baseUrl}/send", [
                    'target'  => $phone,
                    'message' => $message,
                ]);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? false)) {
                return true;
            }

            Log::warning('Fonnte: gagal kirim pesan', ['response' => $body]);
            return false;

        } catch (\Throwable $e) {
            Log::error('Fonnte: exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Kirim reminder service kendaraan
     */
    public function sendServiceReminder(string $phone, string $vehicleName, string $date, int $km): bool
    {
        $message = "🔔 *Reminder Service MBG*\n\n"
            . "Halo! Ini pengingat service kendaraan kamu:\n\n"
            . "🏍️ *Kendaraan:* {$vehicleName}\n"
            . "📅 *Jadwal service:* {$date}\n"
            . "🛣️ *Target odometer:* " . number_format($km, 0, ',', '.') . " km\n\n"
            . "Jangan lupa service tepat waktu ya! 😊\n"
            . "_— MBG Motor Book Garage_";

        return $this->send($phone, $message);
    }

    /**
     * Kirim alert sparepart hampir habis
     */
    public function sendSparepartAlert(string $phone, string $partName, string $vehicleName, int $kmLeft): bool
    {
        $message = "⚠️ *Alert Sparepart MBG*\n\n"
            . "Sparepart kamu perlu perhatian:\n\n"
            . "🔧 *Part:* {$partName}\n"
            . "🏍️ *Kendaraan:* {$vehicleName}\n"
            . "🛣️ *Sisa jarak:* " . number_format($kmLeft, 0, ',', '.') . " km\n\n"
            . "Segera ganti agar kendaraan tetap prima! 💪\n"
            . "_— MBG Motor Book Garage_";

        return $this->send($phone, $message);
    }
}