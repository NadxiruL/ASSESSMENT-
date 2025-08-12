<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function index()
    {
        $workspaces = Auth::user()->workspaces;
        return view('workspaces.index', compact('workspaces'));
    }

    public function create()
    {
        return view('workspaces.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        Auth::user()->workspaces()->create($validated);
        return redirect()->route('workspaces.index');
    }

    public function show(Workspace $workspace)
    {
        $this->authorizeWorkspace($workspace);
        $tasks = $workspace->tasks;
        return view('workspaces.show', compact('workspace', 'tasks'));
    }

    private function authorizeWorkspace(Workspace $workspace)
    {
        if ($workspace->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
