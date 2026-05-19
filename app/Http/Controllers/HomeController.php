<?php

namespace App\Http\Controllers;

use App\Helpers\FormatHelper;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $year     = now()->year;

        // Kendaraan milik user, eager load services & spareparts
        $vehicles = Vehicle::where('user_id', $user->id)
            ->with(['services', 'spareparts'])
            ->get()
            ->map(function ($v) {
                $v->status     = FormatHelper::getStatus($v);
                $v->statusMeta = FormatHelper::statusMeta($v->status);
                return $v;
            })
            ->sortBy(fn($v) => match ($v->status) {
                'overdue'  => 0,
                'urgent'   => 1,
                'upcoming' => 2,
                default    => 3,
            })
            ->values();

        // Stats
        $totalCostYear = \App\Models\ServiceRecord::whereHas('vehicle', fn($q) => $q->where('user_id', $user->id))
            ->whereYear('date', $year)
            ->sum('cost');

        $historyCount = \App\Models\ServiceRecord::whereHas('vehicle', fn($q) => $q->where('user_id', $user->id))
            ->whereYear('date', $year)
            ->count();

        $needAttention = $vehicles->filter(fn($v) => in_array($v->status, ['overdue', 'urgent']))->count();

        return view('home.index', compact(
            'vehicles',
            'totalCostYear',
            'historyCount',
            'needAttention',
            'year',
        ));
    }

    public function storeVehicle(Request $request)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'brand'             => ['nullable', 'string', 'max:100'],
            'type'              => ['nullable', 'string', 'in:Motor Matic,Motor Manual,Motor Sport,Mobil,Truk/Pickup'],
            'year'              => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'plate'             => ['nullable', 'string', 'max:20'],
            'odometer'          => ['nullable', 'integer', 'min:0'],
            'owner'             => ['nullable', 'string', 'max:100'],
            'next_service_date' => ['nullable', 'date'],
            'next_service_km'   => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama kendaraan wajib diisi.',
        ]);

        $data['user_id'] = Auth::id();

        Vehicle::create($data);

        return back()->with('toast_ok', 'Kendaraan berhasil ditambahkan.');
    }

    public function destroyVehicle(Vehicle $vehicle)
    {
        abort_if($vehicle->user_id !== Auth::id(), 403);
        $vehicle->delete();
        return back()->with('toast_ok', 'Kendaraan berhasil dihapus.');
    }
}
