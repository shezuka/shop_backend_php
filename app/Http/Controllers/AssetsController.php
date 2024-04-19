<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetsController extends Controller
{
    public function get($id)
    {
        $asset = Asset::findOrFail($id);
        return response()
            ->make(stream_get_contents($asset->data), headers: [
                'Content-Type' => $asset->type,
            ]);
    }
}
