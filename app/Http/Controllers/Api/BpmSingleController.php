<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpmSingle;
use App\Http\Resources\BpmResource;

class BpmSingleController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index()
    {
        $bpmsingle = BpmSingle::latest()->paginate(10);

        return new BpmResource(true, 'List Data BpmSingle', $bpmsingle);
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
            'bpm' => 'required',
        ]);

        // Create data
        $bpmSingle = BpmSingle::create([
            'bpm' => $request->bpm,
        ]);

        return new BpmResource(true, 'Data Berhasil Ditambahkan', $bpmSingle);
    }
}
