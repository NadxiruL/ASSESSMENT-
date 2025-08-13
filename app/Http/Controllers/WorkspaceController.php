<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Workspace::class);

        $workspaces = Auth::user()->workspaces;

        return view('workspaces.index', compact('workspaces'));
    }

    public function create()
    {
        Gate::authorize('create', Workspace::class);

        return view('workspaces.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Workspace::class);

        $validated = $request->validate(['name' => 'required|string|max:255']);

        Auth::user()->workspaces()->create($validated);

        return redirect()->route('workspaces.index');
    }

    public function show(Workspace $workspace)
    {
        Gate::authorize('view', $workspace);

        $tasks = $workspace->tasks;

        return view('workspaces.show', compact('workspace', 'tasks'));
    }

    public function edit(Workspace $workspace)
    {
        Gate::authorize('update', $workspace);

        return view('workspaces.edit', compact('workspace'));
    }

    public function update(Request $request, Workspace $workspace)
    {
        Gate::authorize('update', $workspace);

        $validated = $request->validate(['name' => 'required|string|max:255']);

        $workspace->update($validated);

        return redirect()->route('workspaces.index', $workspace)->with('updated', 'Workspace updated successfully.');
    }

    public function destroy(Workspace $workspace)
    {
        Gate::authorize('delete', $workspace);

        $workspace->delete();

        return redirect()->route('workspaces.index')->with('deleted', 'Workspace deleted successfully.');
    }
}
