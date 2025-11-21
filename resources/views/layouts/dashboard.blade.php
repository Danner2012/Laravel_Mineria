<!DOCTYPE html>
<html class="dark" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Game Store - Admin Dashboard</title>
  
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
  
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"/>
  <script src="{{ asset('js/dashboard-config.js') }}"></script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-primary">
  <div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="flex w-64 flex-col bg-background-dark p-4 border-r border-surface-dark/50">
      <div class="flex items-center gap-3 mb-8 px-2">
        <div class="size-8 text-primary bg-white rounded-md p-1">
          <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
            <path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z" fill="currentColor"></path>
          </svg>
        </div>

        <div class="flex flex-col">
          <h1 class="text-white text-lg font-bold">GAMESTORE</h1>
          <p class="text-text-secondary text-xs">Admin Panel</p>
        </div>
      </div>

      <div class="flex flex-1 flex-col justify-between">
        <div class="flex flex-col gap-2">
          
          <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-surface-dark text-white shadow-[0_0_15px_-5px] shadow-primary/50 border border-primary">
            <span class="material-symbols-outlined text-primary">dashboard</span>
            <p class="text-sm font-medium leading-normal">Dashboard</p>
          </a>

          <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-surface-dark/50 text-text-secondary hover:text-white transition-colors duration-200">
            <span class="material-symbols-outlined">sports_esports</span>
            <p class="text-sm font-medium leading-normal">Juegos</p>
          </a>

          <!-- AQUÍ ESTABA EL ERROR -->
          <a href="{{ route('descuentos.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-surface-dark/50 text-text-secondary hover:text-white transition-colors duration-200">
            <span class="material-symbols-outlined">sell</span>
            <p class="text-sm font-medium leading-normal">Descuentos</p>
          </a>

        </div>
      </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-8">
      @yield('content')
    </main>
  </div>
</body>
</html>
