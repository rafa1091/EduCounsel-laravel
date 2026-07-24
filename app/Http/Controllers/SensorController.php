<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    
    /**
     * Menerima data dari ESP32 dan memperbarui status kehadiran dosen.
     */
    public function storeData(Request $request)
{
    Log::info("===== REQUEST DARI ESP32 =====");
    Log::info($request->getContent());

    $data = json_decode($request->getContent(), true);

    Log::info($data);

    $ruanganId = $data['ruangan_id'] ?? null;
    $statusSensor = $data['status_sensor'] ?? null;

    $dosen = Dosen::where('ruangan', $ruanganId)->first();

    if (!$dosen) {
        return response()->json([
            'success' => false,
            'message' => 'Dosen tidak ditemukan.'
        ],404);
    }

    if ($statusSensor == 1) {

        $dosen->update([
            'status' => 'di_ruangan'
        ]);

    } else {

        $dosen->update([
            'status' => 'tidak_ada'
        ]);

    }

    return response()->json([
        'success' => true,
        'message' => 'Status berhasil diubah.',
        'ruangan' => $ruanganId,
        'status_sensor' => $statusSensor
    ]);
}
}