<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div
      class="w-full max-w-sm md:max-w-lg lg:max-w-3xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
        {{ __('Shared task') }}
      </h2>
      <div class="grid grid-flow-col gap-3 ">
        <div class="col-span-4">
          <h3 class="text-lg font-bold mb-4">{{ $task->name }}</h3>
          <p class="mb-2">{{ $task->description ?? '' }}</p>
        </div>
        <div class="col-span-1 border border-gray-300 rounded-lg p-4 bg-gray-50">
          <p class="mb-2"><strong>priority:</strong> {{ ucfirst($task->priority) }}</p>
          <p class="mb-2"><strong>status:</strong> {{ ucfirst($task->status) }}</p>
          <p class="mb-2"><strong>due date:</strong> {{ $task->due_date->format('Y-m-d') }}</p>
          <p class="mb-2"><strong>author:</strong> {{ $task->user->name ?? 'Unknown' }}</p>
        </div>
      </div>
      <div class="mt-6 text-center text-gray-600 text-sm">
        This link will expires at: {{ $task->token_expires_at->format('Y-m-d H:i') }} (UTC +0)
      </div>
    </div>
  </div>
</body>

</html>