<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
    {
        $agents = Agent::all();
        return view('Admin.AgentIA.ListAgent',[
            'agents' => $agents,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    return view('Admin.agentIa.ListAgent');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'type' => 'required|in:private,public',
            'sections' => 'array',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.tasks' => 'array',
            'sections.*.tasks.*' => 'required|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('agents', 'public');
        }

        $agent = Agent::create([
            'name' => $validated['name'],
            'domain' => $validated['domain'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'type' => $validated['type'],
        ]);

        foreach ($validated['sections'] ?? [] as $sectionData) {
            $section = $agent->sections()->create([
                'title' => $sectionData['title'],
            ]);

            foreach ($sectionData['tasks'] ?? [] as $task) {
                $section->tasks()->create(['content' => $task]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Agent IA ajouté avec succès.']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $agent = Agent::with('sections.tasks')->findOrFail($id);
      return view('Admin.AgentIA.DetailsAgent', compact('agent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }



public function update(Request $request, $id)
{
    $agent = Agent::with('sections.tasks')->findOrFail($id);

    // ✅ Log les champs principaux reçus
    Log::info('🔍 Champs principaux reçus', $request->only(['name', 'domain', 'type', 'description']));

    $agent->name = $request->input('name');
    $agent->domain = $request->input('domain');
    $agent->type = $request->input('type');
    $agent->description = $request->input('description');

    if ($request->hasFile('image')) {
        $agent->image = $request->file('image')->store('agents', 'public');
        Log::info('🖼️ Nouvelle image mise à jour', ['path' => $agent->image]);
    }

    $agent->save();
    Log::info('✅ Agent enregistré', ['id' => $agent->id]);

    try {
        // ✅ On récupère les sections correctement
$sectionsJson = $request->input('sections');
$sections = json_decode($sectionsJson, true);

if (!is_array($sections)) {
    return response()->json(['success' => false, 'message' => 'Format des sections invalide.'], 422);
}
        Log::info('🧩 Sections via input()', $sections);

        foreach ($sections as $sectionData) {
            $section = $agent->sections()->where('id', $sectionData['id'])->first();

            if (!$section) {
                Log::warning('⚠️ Section non trouvée', ['id' => $sectionData['id']]);
                continue;
            }

            $oldTitle = $section->title;
            $section->title = $sectionData['title'] ?? $section->title;
            $section->save();

            Log::debug('✅ Section mise à jour', [
                'id' => $section->id,
                'old_title' => $oldTitle,
                'new_title' => $section->title
            ]);

            foreach ($sectionData['tasks'] ?? [] as $taskData) {
                $task = $section->tasks()->where('id', $taskData['id'])->first();
                if ($task) {
                    $oldContent = $task->content;
                    $task->content = $taskData['content'] ?? $task->content;
                    $task->save();

                    Log::debug('📌 Tâche mise à jour', [
                        'id' => $task->id,
                        'old_content' => $oldContent,
                        'new_content' => $task->content
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Agent mis à jour avec succès.']);
    } catch (\Exception $e) {
        Log::error('❌ Erreur update agent : ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Erreur serveur.'], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
