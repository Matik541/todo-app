<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <h3 class="text-2xl font-bold mb-6">Wellcome back, {{ Auth::user()->name }}!</h3>

          <!-- add warning to verifi account -->
          @if (!Auth::user()->hasVerifiedEmail())
          <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4"
            role="alert">
            <strong class="font-bold">Warning!</strong>
            <span class="block sm:inline">Your account is not verified yet. Please check your email for the verification
              link.</span>
            <form action="{{ route('verification.send') }}" method="POST" class="inline-block ml-4">
              @csrf
              <button type="submit" class="text-blue-600 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Resend verification email
              </button>
            </form>
          </div>
          @endif

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="bg-blue-100 p-6 rounded-lg shadow-md">
              <h4 class="text-xl font-semibold mb-4 text-blue-800 flex items-center">
                <i class="fa-solid fa-clipboard-list mr-2 text-blue-600"></i> Tasks for Today
              </h4>
              <ul class="list-disc list-inside text-blue-700">
                @forelse($tasksForToday as $task)
                <li class="mb-1">
                  <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:underline">
                    {{ $task->name }}
                  </a>
                </li>
                @empty
                <li>There are no tasks for today!</li>
                @endforelse
              </ul>
              <p class="mt-4 text-sm text-blue-600">
                <a href="{{ route('tasks.index') }}" class="hover:underline flex items-center">
                  Show all tasks <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>
              </p>
            </div>

            <div class="bg-green-100 p-6 rounded-lg shadow-md">
              <h4 class="text-xl font-semibold mb-4 text-green-800 flex items-center">
                <i class="fa-solid fa-chart-line mr-2 text-green-600"></i> Task progression
              </h4>
              <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                  <div>
                    <span
                      class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200">
                      Done
                    </span>
                  </div>
                  <div class="text-right">
                    <span class="text-2xl font-bold inline-block text-green-600">
                      {{ $completionPercentage }}%
                    </span>
                  </div>
                </div>
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-200">
                  <div style="width:{{ $completionPercentage }}%"
                    class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500 transition-all duration-500 ease-out">
                  </div>
                </div>
              </div>
              <p class="mt-4 text-sm text-green-600">
                <a href="{{ route('tasks.index', ['status' => 'done']) }}" class="hover:underline flex items-center">
                  Show finished tasks <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>
              </p>
            </div>

            <div class="bg-yellow-100 p-6 rounded-lg shadow-md">
              <h4 class="text-xl font-semibold mb-4 text-yellow-800 flex items-center">
                <i class="fa-solid fa-calendar-alt mr-2 text-yellow-600"></i> Upcoming Tasks
              </h4>
              <ul class="list-disc list-inside text-yellow-700">
                @forelse($upcomingTasks as $task)
                <li class="mb-1">
                  <a href="{{ route('tasks.show', $task) }}" class="text-yellow-600 hover:underline">
                    {{ $task->name }} ({{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }})
                  </a>
                </li>
                @empty
                <li> There are no upcoming tasks at the moment!</li>
                @endforelse
              </ul>
              <p class="mt-4 text-sm text-yellow-600">
                <a href="{{ route('tasks.index', ['sort_by' => 'due_date', 'order' => 'asc']) }}"
                  class="hover:underline flex items-center">
                  Show all upcoming tasks <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>
              </p>
            </div>

            <div class="bg-purple-100 p-6 rounded-lg shadow-md md:col-span-2 lg:col-span-1">
              <h4 class="text-xl font-semibold mb-4 text-purple-800 flex items-center">
                <i class="fa-solid fa-bolt mr-2 text-purple-600"></i> Quick Actions
              </h4>
              <div class="flex flex-wrap gap-3">
                <a href="{{ route('tasks.create') }}"
                  class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 flex items-center">
                  <i class="fa-solid fa-plus-circle mr-2"></i> Add new task
                </a>
              </div>
            </div>

            {{-- Blok 5: Ogólne Statystyki --}}
            <div class="bg-gray-100 p-6 rounded-lg shadow-md lg:col-span-2">
              <h4 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                <i class="fa-solid fa-chart-pie mr-2 text-gray-600"></i>Statistics
              </h4>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-sm text-center">
                  <p class="text-sm text-gray-600 mb-1">Tasks created this week:</p>
                  <p class="text-4xl font-bold text-gray-900">{{ $tasksThisWeek }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm text-center">
                  <p class="text-sm text-gray-600 mb-1">Finished tasks (overall):</p>
                  <p class="text-4xl font-bold text-gray-900">{{ $allCompletedTasks }}</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>