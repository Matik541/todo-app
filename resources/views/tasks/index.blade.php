<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Twoje Zadania') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">

          <div class="flex justify-between items-center mb-4">
            <a href="{{ route('tasks.create') }}"
              class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
              Add new task
            </a>
          </div>

          <form action="{{ route('tasks.index') }}" method="GET"
            class="mb-4 bg-gray-50 p-4 border border-gray-300 rounded-lg shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label for="priority" class="block text-sm font-medium text-gray-700">Priority:</label>
                <select name="priority" id="priority"
                  class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                  <option value="">any prioryty</option>
                  <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>low</option>
                  <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>medium</option>
                  <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>high</option>
                </select>
              </div>
              <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status:</label>
                <select name="status" id="status"
                  class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                  <option value="">any status</option>
                  <option value="to-do" {{ request('status') == 'to-do' ? 'selected' : '' }}>to do</option>
                  <option value="in progress" {{ request('status') == 'in progress' ? 'selected' : '' }}>in progress
                  </option>
                  <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>done</option>
                </select>
              </div>
              <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due date:</label>
                <input type="date" name="due_date" id="due_date"
                  value="{{ request('due_date') ?? now()->format('Y-m-d') }}"
                  class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
              </div>
            </div>
            <div class="mt-4">
              <button type="submit"
                class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Filter</button>
              <a href="{{ route('tasks.index') }}" class="ml-2 text-gray-600 hover:text-gray-900">Reset filters</a>
            </div>
          </form>


          @if (session('success'))
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
          </div>
          @endif
          @if ($tasks->isEmpty())
          <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4"
            role="alert">
            <strong class="font-bold">No tasks found.</strong>
            <span class="block sm:inline">
              <a href="{{ route('tasks.create') }}" class="text-indigo-600 hover:text-indigo-900">Create a new task</a>.
            </span>
          </div>
          @else
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">name</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">priority</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">status</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">due date</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($tasks as $task)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600 hover:underline">
                      {{ $task->name }}
                    </a>
                  <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($task->priority) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($task->status) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ $task->due_date->format('Y-m-d') }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('tasks.edit', $task) }}"
                      class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-600 hover:text-red-900"
                        onclick="return confirm('Are you sure you want to delete this task? This action cannot be undone.')">Delete</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>