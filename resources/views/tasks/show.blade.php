<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Task details') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 ">
          <div class="grid grid-flow-col gap-3 ">
            <div class="col-span-4">
              <h3 class="text-lg font-bold mb-4">{{ $task->name }}</h3>
              <p class="mb-2">{{ $task->description }}</p>
            </div>
            <div class="col-span-1 border border-gray-300 rounded-lg p-4 bg-gray-50">
              <p class="mb-2"><strong>priority:</strong> {{ ucfirst($task->priority) }}</p>
              <p class="mb-2"><strong>status:</strong> {{ ucfirst($task->status) }}</p>
              <p class="mb-4"><strong>due date:</strong> {{ $task->due_date->format('Y-m-d') }}</p>
            </div>
          </div>
          <div class="mt-8">
            <h3 class="text-lg font-bold mb-4">Edit History</h3>
            @if ($task->histories->isEmpty())
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
              No edit history available for this task.
            </div>

            @else
            <div class="space-y-4">
              @foreach ($task->histories->sortByDesc('created_at') as $history)
              <div class="border border-gray-200 p-4 rounded-lg bg-gray-50">
                <p class="text-sm text-gray-600">
                  Changed at {{ $history->created_at->format('Y-m-d H:i') }}
                  @if ($history->user)
                  by {{ $history->user->name }}
                  @endif
                </p>
                <p class="mt-2">{{ $history->change_summary }}</p>
                <details class="mt-2">
                  <summary class="cursor-pointer text-indigo-600 hover:underline">Details</summary>
                  <div class="grid grid-flow-col gap-3">
                    <pre class="bg-gray-100 p-4 rounded border text-xs mt-2">{{ json_encode($history->old_data, JSON_PRETTY_PRINT) }}</pre>
                    <pre class="bg-gray-100 p-4 rounded border text-xs mt-2">{{ json_encode($history->new_data, JSON_PRETTY_PRINT) }}</pre>
                  </div>
                </details>
              </div>
              @endforeach
            </div>
            @endif
          </div>
          <div class="mt-6 flex">
            <a href="{{ route('tasks.edit', $task) }}"
              class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
              Edit
            </a>
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                onclick="return confirm('Are you sure you want to delete this task?')">
                Delete
              </button>
            </form>
            <a href="{{ route('tasks.index') }}"
              class="ml-auto bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
              Back to tasks
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>