<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BpmResource;
use App\Models\Bpm;

class BpmController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index()
    {
        // get all bpms
        $bpms = Bpm::latest()->paginate(10);

        return new BpmResource(true, 'List Data BPM', $bpms);
    }

    /**
     * store
     * 
     * @param mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'user_id' => 'required',
            'age'     => 'required',
            'gender'  => 'required',
            'status'  => 'required',
            'bpm'     => 'required',
        ]);

        $userId = $request->user_id;
        $now = now();

        // Cari record terakhir user
        $lastRecord = Bpm::where('user_id', $userId)
                        ->orderBy('record_id', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->first();

        if ($lastRecord) {
            // Hitung selisih waktu antara data terakhir dan sekarang
            $diffSeconds = $lastRecord->created_at->diffInSeconds($now);

            if ($diffSeconds > 60) {
                // Lebih dari 60 detik → buat record_id baru
                $recordId = $lastRecord->record_id + 1;
            } else {
                // Masih dalam 60 detik → gunakan record_id yang sama
                $recordId = $lastRecord->record_id;
            }
        } else {
            // Tidak ada data sebelumnya → mulai dari 1
            $recordId = 1;
        }

        // Simpan data
        $bpm = Bpm::create([
            'user_id'   => $userId,
            'record_id' => $recordId,
            'age'       => $request->age,
            'gender'    => $request->gender,
            'status'    => $request->status,
            'bpm'       => $request->bpm,
        ]);

        return new BpmResource(true, 'Data berhasil disimpan', $bpm);
    }

}
