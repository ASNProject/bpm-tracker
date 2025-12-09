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
        // Validasi (opsional, tapi disarankan)
        $request->validate([
            'user_id' => 'required',
            'age'     => 'required',
            'gender'  => 'required',
            'status'  => 'required',
            'bpm'     => 'required',
        ]);

        // Cari record terakhir berdasarkan user_id
        $lastRecord = Bpm::where('user_id', $request->user_id)
                        ->orderBy('record_id', 'desc')
                        ->first();

        // Jika ada → record_id = last + 1
        // Jika tidak ada → mulai dari 1
        $nextRecordId = $lastRecord ? $lastRecord->record_id + 1 : 1;

        $bpm = Bpm::create([
            'user_id'       => $request->user_id,
            'record_id'     => $nextRecordId,
            'age'           => $request->age,
            'gender'        => $request->gender,
            'status'        => $request->status,
            'bpm'           => $request->bpm,
        ]);

        return new BpmResource(true, 'Data Berhasil Ditambahkan!', $bpm);
    }
}
