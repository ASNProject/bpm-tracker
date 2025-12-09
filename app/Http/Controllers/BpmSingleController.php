<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BpmSingle;

class BpmSingleController extends Controller
{
    public function index()
    {
        return view('cek-bpm'); // view yang kamu buat tadi
    }

    public function data()
    {
        // Ambil data BPM terbaru, misal 20 data terakhir
        $data = BpmSingle::latest()->take(20)->get();

        return response()->json($data);
    }
}
