<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function create(Workspace $workspace)
    {
        Gate::authorize('create', Task::class);

        return view('tasks.create', compact('workspace'));
    }

    public function store(Request $request, Workspace $workspace)
    {
        Gate::authorize('create', Task::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after:now',
        ]);

        $workspace->tasks()->create($validated);

        return redirect()->route('workspaces.show', $workspace);
    }

    public function edit(Workspace $workspace, Task $task)
    {

        Gate::authorize('update', $task);

        return view('tasks.edit', compact('task', 'workspace'));
    }

    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after:now',
        ]);

        $updated = $task->update($validated);
        if (!$updated) {
            return back()->withErrors(['update' => 'Failed to update task']);
        }

        return redirect()->route('workspaces.show', $task->workspace)->with('status', 'Task updated successfully');
    }

    public function complete(Task $task)
    {
        Gate::authorize('update', $task);

        $updated = $task->update(['completed_at' => now()]);
        if (!$updated) {
            return back()->withErrors(['update' => 'Failed to mark task as complete']);
        }

        return back()->with('status', 'Task marked as complete');
    }

    public function incomplete(Task $task)
    {
        Gate::authorize('update', $task);

        $updated = $task->update(['completed_at' => null]);
        if (!$updated) {
            return back()->withErrors(['update' => 'Failed to mark task as incomplete']);
        }

        return back()->with('status', 'Task marked as incomplete');
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $deleted = $task->delete();
        if (!$deleted) {
            return back()->withErrors(['delete' => 'Failed to delete task']);
        }
        return redirect()->route('workspaces.show', $task->workspace)->with('deleted', 'Task deleted successfully');
    }
}
