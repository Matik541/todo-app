<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Add new task') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            @include('tasks._form')
            <div class="mt-4">
              <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Add task
              </button>
              <a href="{{ route('tasks.index') }}" class="ml-2 text-gray-600 hover:text-gray-900">
                {{ __('Back to tasks') }}
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>