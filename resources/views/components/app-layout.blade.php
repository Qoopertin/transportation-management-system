<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Leaflet CSS for Maps -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Custom scrollbar for dark theme */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #1f2937;
            }
            ::-webkit-scrollbar-thumb {
                background: #4b5563;
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #6b7280;
            }
        </style>
    </head>
    <body class="font-inter antialiased bg-gray-900 text-white">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <aside class="w-64 bg-gray-950 border-r border-gray-800 flex flex-col">
                <!-- Logo -->
                <div class="h-16 flex items-center px-6 border-b border-gray-800">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold">TMS</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    @can('view loads')
                    <a href="{{ route('loads.index') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('loads.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Loads
                    </a>
                    @endcan

                    @hasrole('dispatcher|admin')
                    <a href="{{ route('drivers.map') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('drivers.map') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        Driver Map
                    </a>
                    @endhasrole

                    @hasrole('driver')
                    <a href="{{ route('driver.index') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('driver.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        My Loads
                    </a>
                    @endhasrole
                </nav>

                <!-- User Menu -->
                <div class="p-3 border-t border-gray-800">
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-800 cursor-pointer" x-data="{ open: false }" @click="open = !open">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-sm font-medium">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Bar -->
                <header class="h-16 bg-gray-900 border-b border-gray-800 flex items-center justify-between px-6">
                    <div class="flex items-center space-x-4">
                        @if (isset($header))
                            <h1 class="text-xl font-semibold">{{ $header }}</h1>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-auto bg-gray-900">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    </body>
</html>
