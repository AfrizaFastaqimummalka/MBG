<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Models\Sparepart;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $vehicleCount = Vehicle::where('user_id', $user->id)->count();

        $serviceCount = ServiceRecord::whereHas(
            'vehicle', fn($q) => $q->where('user_id', $user->id)
        )->count();

        $partCount = Sparepart::whereHas(
            'vehicle', fn($q) => $q->where('user_id', $user->id)
        )->count();

        $tips = [
            ['title' => 'Ganti Oli Rutin',                   'desc' => 'Setiap 2.000–3.000 km atau 3 bulan. Jaga performa mesin tetap optimal.',           'tag' => 'Mesin'],
            ['title' => 'Periksa Tekanan Ban',                'desc' => 'Cek setiap minggu. Ban yang tepat menghemat BBM dan menjaga keselamatan.',          'tag' => 'Ban'],
            ['title' => 'Bersihkan Filter Udara',             'desc' => 'Setiap 4.000 km. Filter bersih = pembakaran sempurna = tarikan ringan.',            'tag' => 'Filter'],
            ['title' => 'Periksa Aki',                        'desc' => 'Setiap 6 bulan. Aki sehat mencegah starter susah di pagi hari.',                   'tag' => 'Elektrik'],
            ['title' => 'Ganti Busi',                         'desc' => 'Setiap 6.000–8.000 km. Busi baru memastikan pembakaran efisien.',                  'tag' => 'Pengapian'],
            ['title' => 'Lumasi Rantai',                      'desc' => 'Setiap 1.000 km. Rantai terlumasi mengurangi keausan gir dan sproket.',            'tag' => 'Transmisi'],
            ['title' => 'Cek Kampas Rem',                     'desc' => 'Setiap 10.000 km. Rem prima = keselamatan berkendara.',                            'tag' => 'Rem'],
            ['title' => 'Gunakan BBM Sesuai Rekomendasi',     'desc' => 'BBM berkualitas menjaga injeksi bersih dan mesin awet.',                           'tag' => 'BBM'],
        ];

        return view('account.index', compact(
            'user',
            'vehicleCount',
            'serviceCount',
            'partCount',
            'tips',
        ));
    }
}
