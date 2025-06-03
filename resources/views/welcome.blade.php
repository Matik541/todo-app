<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Twoje Zadania') }}</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
  .background {
    background: linear-gradient(to right, #4F46E5, #6B5EE1);
  }
  </style>
</head>

<body class="font-sans antialiased text-gray-900">

  <div class="relative min-h-screen background flex flex-col items-center justify-center pt-6 sm:pt-0">
    <header class="absolute top-0 right-0 p-6 text-right">
      @if (Route::has('login'))
      <div class="space-x-4">
        @auth
        <a href="{{ url('/dashboard') }}"
          class="font-semibold text-white hover:text-gray-200 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
          Dashboard
        </a>
        @else
        <a href="{{ route('login') }}"
          class="font-semibold text-white hover:text-gray-200 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
          Log in
        </a>

        @if (Route::has('register'))
        <a href="{{ route('register') }}"
          class="ml-4 font-semibold text-white hover:text-gray-200 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
          Register
        </a>
        @endif
        @endauth
      </div>
      @endif
    </header>

    <main class="flex flex-col items-center justify-center text-center text-white p-6 md:p-10">
      <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight mb-4">
        Sort Your Life. <br> Task by Task.
      </h1>
      <p class="text-xl md:text-2xl opacity-90 mb-8 max-w-2xl">
        Your simple task management app. Never forget important deadlines again.
      </p>

      <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('register') }}"
          class="border-2 bg-white hover:bg-gray-100 text-indigo-700 font-bold py-3 px-8 rounded-full shadow-lg transition duration-300 transform hover:scale-105">
          Start Now
        </a>
        <a href="{{ route('login') }}"
          class="border-2 border-white text-white hover:bg-white hover:text-indigo-700 font-bold py-3 px-8 rounded-full shadow-lg transition duration-300 transform hover:scale-105">
          Already a Member? Log In
        </a>
      </div>
    </main>

    <footer class="absolute bottom-0 w-full p-4 text-center text-white text-sm opacity-80">
      Made by <a href="github.com/Matik541" class="text-white hover:underline">Mateusz Kowalski</a>
    </footer>
  </div>

</body>

</html>