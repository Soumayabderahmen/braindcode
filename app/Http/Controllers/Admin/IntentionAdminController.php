<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Intention;
class IntentionAdminController extends Controller
{
    public function index()
    {
        // Juste afficher la vue Vue.js
        return Inertia::render('Admin/AdminIntentions');
    }



    public function list()
    {
        return response()->json([
            'intentions' => Intention::all()
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:intentions',
            'example_message' => 'required',
            'prompt_template' => 'required',
        ]);

        $intention = Intention::create($request->all());
        return response()->json(['intention' => $intention], 201);
    }

    // ✅ API : Modifier
    public function update(Request $request, Intention $intention)
    {
        $request->validate([
            'name' => 'required',
            'example_message' => 'required',
            'prompt_template' => 'required',
        ]);

        $intention->update($request->all());
        return response()->json(['intention' => $intention]);
    }

    // ✅ API : Supprimer
    public function destroy(Intention $intention)
    {
        $intention->delete();
        return response()->json(['message' => 'Intention supprimée']);
    }
}
