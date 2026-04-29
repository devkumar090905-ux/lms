<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Library - Super Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #020617; color: white; }
        .glass { background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 md:p-8">
    
    <div class="max-w-2xl w-full glass p-5 md:p-8 rounded-2xl md:rounded-3xl shadow-2xl">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 md:mb-8 gap-3">
            <div>
                <h1 class="text-xl md:text-2xl font-bold">Edit Library Profile</h1>
                <p class="text-gray-400 text-sm">Update settings for <strong>{{ $library->library_name }}</strong></p>
            </div>
            <a href="{{ route('superadmin.dashboard') }}" class="text-gray-400 hover:text-white transition-all text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>
        </div>

        <form action="{{ route('superadmin.library.update', $library->id) }}" method="POST" class="space-y-5 md:space-y-6">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                <div class="space-y-2">
                    <label class="text-xs text-gray-500 uppercase tracking-widest">Owner Name</label>
                    <input type="text" name="owner_name" value="{{ $library->owner_name }}" required class="w-full bg-white/5 border border-white/10 rounded-xl md:rounded-2xl px-4 py-3 text-white focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-xs text-gray-500 uppercase tracking-widest">Library Name</label>
                    <input type="text" name="library_name" value="{{ $library->library_name }}" required class="w-full bg-white/5 border border-white/10 rounded-xl md:rounded-2xl px-4 py-3 text-white focus:border-indigo-500 outline-none transition-all">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs text-gray-500 uppercase tracking-widest">Address</label>
                <textarea name="address" rows="2" class="w-full bg-white/5 border border-white/10 rounded-xl md:rounded-2xl px-4 py-3 text-white focus:border-indigo-500 outline-none transition-all">{{ $library->address }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                <div class="space-y-2">
                    <label class="text-xs text-gray-500 uppercase tracking-widest">Total Seats Capacity</label>
                    <input type="number" name="total_seats" value="{{ $library->total_seats }}" required class="w-full bg-white/5 border border-white/10 rounded-xl md:rounded-2xl px-4 py-3 text-white focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-xs text-gray-500 uppercase tracking-widest text-indigo-400">Change Password (Optional)</label>
                    <input type="password" name="password" placeholder="Leave blank to keep same" class="w-full bg-indigo-500/5 border border-indigo-500/20 rounded-xl md:rounded-2xl px-4 py-3 text-white focus:border-indigo-500 outline-none transition-all placeholder:text-gray-600">
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 py-3.5 md:py-4 rounded-xl md:rounded-2xl font-bold text-white shadow-xl shadow-indigo-500/20 transition-all">
                Save Changes
            </button>
        </form>
    </div>

</body>
</html>
