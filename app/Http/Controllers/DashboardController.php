<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bpm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Semua record_id unik untuk dropdown
        $recordIds = Bpm::where('user_id', $user->name)
                        ->orderBy('record_id', 'desc')
                        ->pluck('record_id')
                        ->unique();

        $query = Bpm::where('user_id', $user->name);

        if($request->filled('record_id')){
            $query->where('record_id', $request->record_id);
            $lastRecordId = $request->record_id;
        } else {
            $lastRecordId = $query->max('record_id');
            if($lastRecordId) $query->where('record_id', $lastRecordId);
        }

        $records = $query->orderBy('created_at', 'asc')->get();
        $latestData = $records->last();

        return view('dashboard', compact('user','records','latestData','lastRecordId','recordIds'));
    }


    // Delete semua data user
    public function truncate(Request $request)
    {
        $user = Auth::user();
        Bpm::where('user_id', $user->name)->delete();

        return redirect()->back()->with('success', 'All records deleted successfully.');
    }

    // Export CSV semua data user
    public function export(Request $request)
    {
        $user = Auth::user();

        $response = new StreamedResponse(function() use ($user) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID','Age','Gender','Status','BPM','Record ID','Created At']);

            $bpms = Bpm::where('user_id', $user->name)->orderBy('record_id', 'asc')->get();

            foreach($bpms as $bpm) {
                fputcsv($handle, [
                    $bpm->id,
                    $bpm->age,
                    $bpm->gender,
                    $bpm->status,
                    $bpm->bpm,
                    $bpm->record_id,
                    $bpm->created_at
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition','attachment; filename="bpm_data.csv"');

        return $response;
    }

    // Realtime AJAX endpoint
    public function realtime(Request $request)
    {
        $user = Auth::user();
        $query = Bpm::where('user_id', $user->name);

        if($request->filled('record_id')) $query->where('record_id', $request->record_id);
        else {
            $lastRecordId = $query->max('record_id');
            if($lastRecordId) $query->where('record_id', $lastRecordId);
        }

        $records = $query->orderBy('created_at','asc')->get();
        $latest = $records->last();

        return response()->json([
            'records' => $records,
            'latest'  => $latest
        ]);
    }
}
