<?php

namespace App\Http\Controllers;

use App\Models\NotificationSetting;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $setting = NotificationSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'wa_service_reminder' => true,
                'wa_sparepart_alert'  => true,
                'wa_phone'            => $user->phone,
            ]
        );

        return view('account.notification', compact('user', 'setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'wa_phone'            => ['nullable', 'string', 'max:20'],
            'wa_service_reminder' => ['nullable'],
            'wa_sparepart_alert'  => ['nullable'],
        ]);

        $user    = Auth::user();
        $setting = NotificationSetting::firstOrCreate(['user_id' => $user->id]);

        $setting->update([
            'wa_phone'            => $request->input('wa_phone') ?: $user->phone,
            'wa_service_reminder' => $request->boolean('wa_service_reminder'),
            'wa_sparepart_alert'  => $request->boolean('wa_sparepart_alert'),
        ]);

        return back()->with('success', 'Pengaturan notifikasi disimpan.');
    }

    public function testSend(FonnteService $fonnte)
    {
        $user    = Auth::user();
        $setting = NotificationSetting::where('user_id', $user->id)->first();
        $phone   = $setting?->wa_phone ?: $user->phone;

        if (!$phone) {
            return back()->with('error', 'Nomor WhatsApp belum diisi.');
        }

        $ok = $fonnte->send($phone,
            "✅ *Test Notifikasi MBG*\n\nHalo {$user->name}! Notifikasi WhatsApp kamu aktif dan berjalan dengan baik. 🎉\n_— MBG Motor Book Garage_"
        );

        return back()->with(
            $ok ? 'success' : 'error',
            $ok ? 'Pesan test berhasil dikirim ke WhatsApp kamu!' : 'Gagal kirim pesan. Cek token Fonnte di .env.'
        );
    }
}
