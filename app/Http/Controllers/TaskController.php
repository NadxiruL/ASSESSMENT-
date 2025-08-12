<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function create(Workspace $workspace)
    {
        $this->authorizeWorkspace($workspace);
        return view('tasks.create', compact('workspace'));
    }

    public function store(Request $request, Workspace $workspace)
    {
        $this->authorizeWorkspace($workspace);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after:now',
        ]);

        $workspace->tasks()->create($validated);

        return redirect()->route('workspaces.show', $workspace);
    }

    public function complete(Task $task)
    {
        $this->authorizeTask($task);
        $updated = $task->update(['completed_at' => now()]);
        if (!$updated) {
            return back()->withErrors(['update' => 'Failed to mark task as complete']);
        }
        return back()->with('status', 'Task marked as complete');
    }

    public function incomplete(Task $task)
    {
        $this->authorizeTask($task);
        $updated = $task->update(['completed_at' => null]);
        if (!$updated) {
            return back()->withErrors(['update' => 'Failed to mark task as incomplete']);
        }
        return back()->with('status', 'Task marked as incomplete');
    }

    private function authorizeWorkspace(Workspace $workspace)
    {
        if ($workspace->user_id !== Auth::id()) {
            abort(403);
        }
    }

    private function authorizeTask(Task $task)
    {
        if ($task->workspace->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
