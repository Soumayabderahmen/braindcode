<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupportMessageAdminController extends Controller {
    /**
     * Liste des messages de support pour l'admin.
     */
    public function index() {
        return Inertia::render('Admin/SupportMessages', [
            'messages' => SupportMessage::latest()->paginate(10) // Assure la pagination
        ]);
    }

    /**
     * Afficher un message en détail.
     */
    public function show($id) {
        $message = SupportMessage::findOrFail($id);
        return Inertia::render('Admin/ViewMessage', [
            'message' => $message
        ]);
    }

    /**
     * Supprimer un message de support.
     */
    public function destroy($id) {
        SupportMessage::findOrFail($id)->delete();
        return redirect()->route('admin.support.messages')->with('success', 'Message supprimé.');
    }
}

