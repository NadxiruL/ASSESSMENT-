<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $workspace->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">{{ $workspace->name }}</h1>
                        <a href="{{ route('tasks.create', $workspace) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add Task
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Title</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Time</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($tasks as $task)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $task->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $task->isCompleted() ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                {{ $task->isCompleted() ? 'Completed' : 'Incomplete' }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if ($task->isCompleted())
                                                Completed {{ $task->completed_at->diffForHumans() }}
                                            @else
                                                @if ($task->deadline)
                                                    @php
                                                        $diff = now()->diff($task->deadline);
                                                        $parts = [];
                                                        if ($diff->y > 0) {
                                                            $parts[] = $diff->y . ' years';
                                                        }
                                                        if ($diff->m > 0) {
                                                            $parts[] = $diff->m . ' months';
                                                        }
                                                        if ($diff->d > 0) {
                                                            $parts[] = $diff->d . ' days';
                                                        }
                                                        if ($diff->h > 0) {
                                                            $parts[] = $diff->h . ' hours';
                                                        }
                                                        if ($diff->i > 0) {
                                                            $parts[] = $diff->i . ' minutes';
                                                        }

                                                        echo implode(' ', $parts) . ' remaining';
                                                    @endphp
                                                @else
                                                    No deadline
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this task? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 bg-red-600 dark:bg-red-700 rounded-md text-white hover:bg-red-700 dark:hover:bg-red-800">
                                                    Delete
                                                </button>
                                            </form>
                                            <a href="{{ route('tasks.edit', ['workspace' => $workspace, 'task' => $task]) }}"
                                                class="px-4 py-2 bg-yellow-500 dark:bg-yellow-600 rounded-md text-white hover:bg-yellow-600 dark:hover:bg-yellow-700">
                                                Edit
                                            </a>
                                            @if ($task->isCompleted())
                                                <form action="{{ route('tasks.incomplete', $task) }}" method="POST"
                                                    class="inline">
                                                    @method('PUT')
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md text-yellow-600 dark:text-yellow-400 hover:bg-gray-300 dark:hover:bg-gray-600">
                                                        Mark Incomplete
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('tasks.complete', $task) }}" method="POST"
                                                    class="inline">
                                                    @method('PUT')
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md text-green-600 dark:text-green-400 hover:bg-gray-300 dark:hover:bg-gray-600">
                                                        Mark Complete
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
