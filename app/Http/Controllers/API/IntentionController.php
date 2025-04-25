<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Intention;
class IntentionController extends Controller
{
public function index()
    {
        return response()->json([
            'intentions' => Intention::all()
        ]);
    }
}