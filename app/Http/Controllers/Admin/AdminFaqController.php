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
        return view('Faq.faq', [
            'faqs' => Faq::orderBy('id')->get()
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

        $faq = Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'is_active' => $request->has('is_active') ? $request->is_active : true
        ]);

        // Retourner réponse JSON pour les requêtes AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'FAQ ajoutée avec succès.',
                'faq' => $faq
            ]);
        }

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
            'is_active' => 'boolean'
        ]);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'is_active' => $request->has('is_active') ? $request->is_active : $faq->is_active
        ]);

        // Retourner réponse JSON pour les requêtes AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'FAQ mise à jour avec succès.',
                'faq' => $faq
            ]);
        }

        return redirect()->back()->with('success', 'FAQ mise à jour.');
    }

  /**
 * Supprimer une FAQ.
 */
public function destroy(Request $request, Faq $faq)
{
    $faq->delete();
    
    // Réponse JSON pour les requêtes AJAX
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'message' => 'FAQ supprimée avec succès'
        ]);
    }
    
    return redirect()->back()->with('success', 'FAQ supprimée avec succès.');
}
}
