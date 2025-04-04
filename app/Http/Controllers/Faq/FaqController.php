<?php

namespace App\Http\Controllers\Faq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Faq;
use Illuminate\Support\Facades\Auth;






class FaqController extends Controller
{
    public function index()
    {
        return Faq::where('is_active', true)->get();
    }
}
