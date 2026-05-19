<?php

namespace App\Http\Controllers;

use App\Helpers\FormatHelper;
use App\Models\Sparepart;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SparepartController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $parts = Sparepart::whereHas('vehicle', fn($q) => $q->where('user_id', $user->id))
            ->with('vehicle')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($p) {
                $p->status     = FormatHelper::getPartStatus($p);
                $p->pct        = FormatHelper::getPartPct($p);
                $p->statusMeta = FormatHelper::statusMeta($p->status);
                return $p;
            });

        $vehicles = Vehicle::where('user_id', $user->id)->orderBy('name')->get();

        return view('parts.index', compact('parts', 'vehicles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id'     => ['required', 'uuid'],
            'name'           => ['required', 'string', 'max:255'],
            'price'          => ['nullable', 'integer', 'min:0'],
            'installed_date' => ['nullable', 'date'],
            'lifespan'       => ['nullable', 'integer', 'min:1'],
            'unit'           => ['required', 'in:bulan,hari'],
        ], [
            'vehicle_id.required' => 'Pilih kendaraan.',
            'name.required'       => 'Nama part wajib diisi.',
        ]);

        // Otorisasi kendaraan
        Vehicle::where('id', $data['vehicle_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Sparepart::create($data);

        return back()->with('toast_ok', 'Sparepart berhasil ditambahkan.');
    }

    public function destroy(Sparepart $sparepart)
    {
        abort_if($sparepart->vehicle->user_id !== Auth::id(), 403);
        $sparepart->delete();
        return back()->with('toast_ok', 'Sparepart berhasil dihapus.');
    }
}
