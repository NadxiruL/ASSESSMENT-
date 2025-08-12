<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Workspaces') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-end items-center mb-6">
                        <a href="{{ route('workspaces.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Create New Workspace') }}
                        </a>
                    </div>

                    @if ($workspaces->count() > 0)
                        <ol class="space-y-3 list-decimal list-inside">
                            @foreach ($workspaces as $workspace)
                                <li class="p-4 rounded-lg hover:outline hover:outline-2 hover:outline-blue-500 dark:hover:outline-blue-400 transition-colors duration-200">
                                    <a href="{{ route('workspaces.show', $workspace) }}"
                                       class="block text-gray-900 dark:text-gray-100 hover:text-blue-500 dark:hover:text-blue-400">
                                        {{ $workspace->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">
                            {{ __('No workspaces found.') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
