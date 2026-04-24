@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Platform Overview')

@section('content')
<div class="p-8 space-y-8 flex-1">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card 1 -->
        <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group hover:border-blue-500/30 transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition-all duration-300"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-400 mb-1">Total Seats</p>
                    <h3 class="text-3xl font-bold text-white">{{ $totalSeats }}</h3>
                </div>
                <div class="p-3 rounded-xl bg-blue-500/10 text-blue-400">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group hover:border-emerald-500/30 transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-all duration-300"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-400 mb-1">Available Seats</p>
                    <h3 class="text-3xl font-bold text-white">{{ $availableSeats }}</h3>
                </div>
                <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group hover:border-purple-500/30 transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition-all duration-300"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-400 mb-1">Active Students</p>
                    <h3 class="text-3xl font-bold text-white">{{ $totalStudents }}</h3>
                </div>
                <div class="p-3 rounded-xl bg-purple-500/10 text-purple-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group hover:border-amber-500/30 transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/10 rounded-full blur-xl group-hover:bg-amber-500/20 transition-all duration-300"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-400 mb-1">Active Bookings</p>
                    <h3 class="text-3xl font-bold text-white">{{ $activeBookings }}</h3>
                </div>
                <div class="p-3 rounded-xl bg-amber-500/10 text-amber-400">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action -->
    <div class="glass-panel p-8 rounded-2xl border-l-4 border-l-blue-500 flex flex-col md:flex-row justify-between items-center bg-gradient-to-r from-blue-900/10 to-transparent">
        <div class="mb-4 md:mb-0">
            <h3 class="text-xl font-bold text-white mb-2">Welcome to your new Dashboard</h3>
            <p class="text-gray-400">Your core system is up and running. Time to add some physical library seats into the system!</p>
        </div>
        <a href="{{ route('seats.index') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-medium px-6 py-3 rounded-lg shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-1">
            + Add New Seat
        </a>
    </div>
</div>
@endsection
