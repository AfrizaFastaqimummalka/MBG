<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Models\Vehicle;
use App\Helpers\FormatHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $services = ServiceRecord::whereHas('vehicle', fn($q) => $q->where('user_id', $user->id))
            ->with('vehicle')
            ->orderByDesc('date')
            ->get();

        $vehicles = Vehicle::where('user_id', $user->id)->orderBy('name')->get();

        return view('service.index', compact('services', 'vehicles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'uuid'],
            'date'       => ['required', 'date'],
            'odometer'   => ['nullable', 'integer', 'min:0'],
            'type'       => ['required', 'string', 'max:255'],
            'workshop'   => ['nullable', 'string', 'max:255'],
            'cost'       => ['nullable', 'integer', 'min:0'],
            'notes'      => ['nullable', 'string'],
            'next_date'  => ['nullable', 'date'],
            'next_km'    => ['nullable', 'integer', 'min:0'],
        ], [
            'vehicle_id.required' => 'Pilih kendaraan.',
            'date.required'       => 'Tanggal wajib diisi.',
            'type.required'       => 'Jenis service wajib diisi.',
        ]);

        // Pastikan kendaraan milik user ini
        $vehicle = Vehicle::where('id', $data['vehicle_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $service = ServiceRecord::create($data);

        // Update jadwal berikutnya di kendaraan jika diisi
        $updates = [];
        if (!empty($data['next_date'])) $updates['next_service_date'] = $data['next_date'];
        if (!empty($data['next_km']))   $updates['next_service_km']   = $data['next_km'];
        if ($updates) $vehicle->update($updates);

        return back()->with('toast_ok', 'Catatan service berhasil disimpan.');
    }

    public function destroy(ServiceRecord $service)
    {
        // Otorisasi: pastikan service milik kendaraan user ini
        abort_if($service->vehicle->user_id !== Auth::id(), 403);
        $service->delete();
        return back()->with('toast_ok', 'Catatan service dihapus.');
    }
}
