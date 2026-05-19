<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $year = now()->year;

        // Semua service user
        $services = ServiceRecord::whereHas('vehicle', fn($q) => $q->where('user_id', $user->id))
            ->with('vehicle')
            ->orderByDesc('date')
            ->get();

        // Total tahun ini & keseluruhan
        $totalYear = $services->filter(fn($s) => $s->date->year === $year)->sum('cost');
        $totalAll  = $services->sum('cost');

        // Bar chart 6 bulan terakhir
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $d     = now()->subMonths($i);
            $key   = $d->format('Y-m');
            $label = $d->locale('id')->isoFormat('MMM');
            $val   = $services->filter(fn($s) => $s->date->format('Y-m') === $key)->sum('cost');
            $months[] = ['key' => $key, 'label' => $label, 'val' => $val];
        }

        // Per kendaraan
        $vehicles = Vehicle::where('user_id', $user->id)->get();
        $byVehicle = $vehicles->map(function ($v) use ($services, $totalAll) {
            $total = $services->where('vehicle_id', $v->id)->sum('cost');
            $pct   = $totalAll > 0 ? round(($total / $totalAll) * 100) : 0;
            return ['name' => $v->name, 'total' => $total, 'pct' => $pct];
        })->sortByDesc('total')->values();

        return view('expense.index', compact(
            'totalYear',
            'totalAll',
            'months',
            'byVehicle',
            'services',
            'year',
        ));
    }
}
