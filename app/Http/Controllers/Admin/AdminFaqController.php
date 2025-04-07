<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminFaqController extends Controller
{
    /**
     * Afficher la liste des FAQs.
     */
    public function index()
    {
        return Inertia::render('Admin/FaqAdmin', [
            'faqs' => Faq::latest()->paginate(10)
        ]);
    }

    /**
     * Ajouter une nouvelle FAQ.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string'
        ]);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'FAQ ajoutée avec succès.');
    }

    /**
     * Modifier une FAQ existante.
     */
    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        $faq->update($request->only('question', 'answer', 'is_active'));

        return redirect()->back()->with('success', 'FAQ mise à jour.');
    }

    /**
     * Supprimer une FAQ.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->back()->with('success', 'FAQ supprimée.');
    }
}
