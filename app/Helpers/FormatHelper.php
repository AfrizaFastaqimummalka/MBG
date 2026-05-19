<?php

namespace App\Helpers;

use DateTime;

class FormatHelper
{
    /**
     * Format angka ke Rupiah.
     */
    public static function rp(int $n, bool $compact = false): string
    {
        if ($compact && $n >= 1_000_000) {
            return 'Rp ' . number_format($n / 1_000_000, 1, ',', '.') . 'jt';
        }
        if ($compact && $n >= 1_000) {
            return 'Rp ' . number_format($n / 1_000, 0, ',', '.') . 'k';
        }
        return 'Rp ' . number_format($n, 0, ',', '.');
    }

    /**
     * Format tanggal ke Indonesia.
     */
    public static function fd(string $date, bool $short = false): string
    {
        $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        try {
            $d = new DateTime($date);
        } catch (\Exception $e) {
            return '–';
        }
        $day   = $d->format('j');
        $mon   = $bulan[(int)$d->format('n') - 1];
        $year  = $d->format('Y');
        return $short ? "$day $mon" : "$day $mon $year";
    }

    /**
     * Tanggal relatif (N hari lagi / hari ini / besok / N hari lalu).
     */
    public static function relDate(?string $date): string
    {
        if (!$date) return '–';
        $today = strtotime('today');
        $days  = (int) ceil((strtotime($date) - $today) / 86400);
        if ($days < 0)  return abs($days) . ' hari lalu';
        if ($days === 0) return 'Hari ini';
        if ($days === 1) return 'Besok';
        if ($days <= 30) return $days . ' hari lagi';
        return round($days / 30) . ' bulan lagi';
    }

    /**
     * Ambil 2 inisial huruf dari nama.
     */
    public static function initials(string $name): string
    {
        $words = explode(' ', trim($name));
        $first = strtoupper(substr($words[0] ?? 'U', 0, 1));
        $second = isset($words[1]) ? strtoupper(substr($words[1], 0, 1)) : '';
        return $first . $second;
    }

    /**
     * Hitung status kendaraan berdasarkan tanggal & KM servis berikutnya.
     * Return: 'overdue' | 'urgent' | 'upcoming' | 'ok'
     */
    public static function getStatus($vehicle): string
    {
        $days  = null;
        $kmLeft = null;

        if ($vehicle->next_service_date) {
            $days = (int) ceil(
                (strtotime($vehicle->next_service_date) - strtotime('today')) / 86400
            );
        }
        if ($vehicle->next_service_km !== null) {
            $kmLeft = $vehicle->next_service_km - $vehicle->odometer;
        }

        if (($days !== null && $days <= 0) || ($kmLeft !== null && $kmLeft <= 0)) {
            return 'overdue';
        }
        if (($days !== null && $days <= 7) || ($kmLeft !== null && $kmLeft <= 500)) {
            return 'urgent';
        }
        if (($days !== null && $days <= 30) || ($kmLeft !== null && $kmLeft <= 1000)) {
            return 'upcoming';
        }
        return 'ok';
    }

    /**
     * Hitung status sparepart berdasarkan % umur pakai.
     * Return: 'overdue' | 'urgent' | 'upcoming' | 'ok'
     */
    public static function getPartStatus($part): string
    {
        $pct = self::getPartPct($part);
        if ($pct >= 100) return 'overdue';
        if ($pct >= 85)  return 'urgent';
        if ($pct >= 70)  return 'upcoming';
        return 'ok';
    }

    /**
     * Hitung persentase umur pakai sparepart (0–100).
     */
    public static function getPartPct($part): float
    {
        if (!$part->installed_date || !$part->lifespan) return 0;
        $elapsed     = (time() - strtotime($part->installed_date)) / 86400;
        $lifespanDays = $part->unit === 'hari' ? $part->lifespan : $part->lifespan * 30;
        return min(100, ($elapsed / $lifespanDays) * 100);
    }

    /**
     * Label dan warna untuk setiap status.
     */
    public static function statusMeta(string $status): array
    {
        return match ($status) {
            'overdue'  => ['label' => 'Terlambat', 'dot' => '#DC2626', 'bg' => '#FEF2F2', 'color' => '#991B1B', 'border' => '#FECACA'],
            'urgent'   => ['label' => 'Mendesak',  'dot' => '#D97706', 'bg' => '#FFFBEB', 'color' => '#92400E', 'border' => '#FDE68A'],
            'upcoming' => ['label' => 'Segera',    'dot' => '#2563EB', 'bg' => '#EFF6FF', 'color' => '#1E40AF', 'border' => '#BFDBFE'],
            default    => ['label' => 'Aman',       'dot' => '#059669', 'bg' => '#ECFDF5', 'color' => '#065F46', 'border' => '#A7F3D0'],
        };
    }
}
