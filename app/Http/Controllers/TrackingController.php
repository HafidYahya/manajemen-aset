<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetLocation;
use Carbon\Carbon;

class TrackingController extends Controller
{
    public function receive(Request $request)
    {
        $data = $request->validate([
            'imei' => 'required|string',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'uploadTime' => 'required|numeric',
            'electricity' => 'nullable|integer',
        ]);

        AssetLocation::create([
            'imei' => $data['imei'],
            'longitude' => $data['x'],
            'latitude' => $data['y'],
            'upload_time' => Carbon::createFromTimestampMs($data['uploadTime']),
            'electricity' => $data['electricity'] ?? null,
        ]);

        return response()->json(['success' => true]);
    }
}

