<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buffplot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BuffplotController extends Controller
{
    public function index(Request $request)
    {
        $query = Buffplot::query();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $data = $query
            ->orderBy('user_id')
            ->orderBy('idx')
            ->get(['user_id', 'idx', 'value']);

        return response()->json([
            'status' => 'success',
            'total'  => $data->count(),
            'data'   => $data
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'        => 'required|string',
            'buffplot'       => 'required|array|min:1',
            'buffplot.*'     => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $userId   = $request->user_id;
        $buffplot = $request->buffplot;

        DB::transaction(function () use ($userId, $buffplot) {
            $data = [];

            foreach ($buffplot as $i => $value) {
                $data[] = [
                    'user_id'    => $userId,
                    'idx'        => $i,
                    'value'      => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Buffplot::insert($data);
        });

        return response()->json([
            'status' => 'success',
            'total'  => count($buffplot)
        ], 201);
    }
}
