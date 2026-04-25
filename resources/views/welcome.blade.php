<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Library Management System - Welcome</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .glass {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .dark-bg { background-color: #0f172a; }
            .accent-gradient {
                background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            }
        </style>
    </head>
    <body class="dark-bg text-white min-h-screen flex items-center justify-center p-4">
        <div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            
            <!-- Left Side: Branding -->
            <div class="space-y-6">
                <div class="inline-block p-3 rounded-2xl bg-indigo-500/10 text-indigo-400">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.168.477-4.5 1.253"></path></svg>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold leading-tight tracking-tight">
                    Manage Your <br>
                    <span class="text-indigo-500">Library</span> Smarter.
                </h1>
                <p class="text-gray-400 text-lg">
                    The ultimate platform for library owners to manage seats, students, and bookings with ease.
                </p>
                <div class="flex gap-4">
                    <div class="flex -space-x-2">
                        <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-indigo-500 flex items-center justify-center text-xs">A</div>
                        <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-purple-500 flex items-center justify-center text-xs">B</div>
                        <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-pink-500 flex items-center justify-center text-xs">C</div>
                    </div>
                    <p class="text-sm text-gray-500 self-center">Joined by 100+ owners</p>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="glass p-8 rounded-3xl shadow-2xl" x-data="{ tab: 'register' }">
                <!-- Tab Headers -->
                <div class="flex p-1 bg-white/5 rounded-xl mb-8">
                    <button @click="tab = 'login'" :class="tab === 'login' ? 'bg-indigo-600 shadow-lg' : 'hover:bg-white/5'" class="flex-1 py-2 rounded-lg text-sm font-medium transition-all">Login</button>
                    <button @click="tab = 'register'" :class="tab === 'register' ? 'bg-indigo-600 shadow-lg' : 'hover:bg-white/5'" class="flex-1 py-2 rounded-lg text-sm font-medium transition-all">Register Library</button>
                </div>

                <!-- Registration Form -->
                <form x-show="tab === 'register'" action="{{ route('setup') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs text-gray-400 uppercase tracking-wider">Owner Name</label>
                            <input type="text" name="owner_name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 focus:border-indigo-500 outline-none transition-all" placeholder="John Doe">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-gray-400 uppercase tracking-wider">Library Name</label>
                            <input type="text" name="library_name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 focus:border-indigo-500 outline-none transition-all" placeholder="City Library">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs text-gray-400 uppercase tracking-wider">Address</label>
                        <textarea name="address" required rows="2" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 focus:border-indigo-500 outline-none transition-all" placeholder="123 Street, City..."></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs text-gray-400 uppercase tracking-wider">Total Seats</label>
                            <input type="number" name="total_seats" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 focus:border-indigo-500 outline-none transition-all" placeholder="50">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-gray-400 uppercase tracking-wider">Opening</label>
                            <input type="time" name="opening_time" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-gray-400 uppercase tracking-wider">Closing</label>
                            <input type="time" name="closing_time" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs text-gray-400 uppercase tracking-wider">Email Address</label>
                            <input type="email" name="email" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 focus:border-indigo-500 outline-none transition-all" placeholder="owner@example.com">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-gray-400 uppercase tracking-wider">Mobile Number</label>
                            <input type="tel" name="mobile_number" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 focus:border-indigo-500 outline-none transition-all" placeholder="+91 9876543210">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs text-gray-400 uppercase tracking-wider">Password</label>
                        <div class="relative w-full">
                            <input type="password" id="reg-password" name="password" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-indigo-500 outline-none transition-all pr-12" placeholder="••••••••">
                            <button type="button" onclick="togglePassword('reg-password', 'reg-eye-icon')" class="absolute top-1/2 -translate-y-1/2 right-4 text-gray-400 hover:text-white transition-colors flex items-center justify-center">
                                <svg id="reg-eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full accent-gradient py-3 rounded-xl font-bold shadow-xl shadow-indigo-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Get Started
                    </button>
                </form>

                <!-- Login Form -->
                <form x-show="tab === 'login'" action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-xs text-gray-400 uppercase tracking-wider">Email Address</label>
                        <input type="email" name="email" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:border-indigo-500 outline-none transition-all" placeholder="owner@example.com">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs text-gray-400 uppercase tracking-wider">Password</label>
                        <div class="relative w-full">
                            <input type="password" id="login-password" name="password" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-indigo-500 outline-none transition-all pr-12" placeholder="••••••••">
                            <button type="button" onclick="togglePassword('login-password', 'login-eye-icon')" class="absolute top-1/2 -translate-y-1/2 right-4 text-gray-400 hover:text-white transition-colors flex items-center justify-center">
                                <svg id="login-eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full accent-gradient py-4 rounded-xl font-bold shadow-xl shadow-indigo-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Sign In
                    </button>
                </form>

                @if($errors->any())
                    <div class="mt-4 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- Alpine.js for Tabs -->
        <script src="//unpkg.com/alpinejs" defer></script>

        <script>
            function togglePassword(inputId, iconId) {
                const passwordInput = document.getElementById(inputId);
                const eyeIcon = document.getElementById(iconId);
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />`;
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />`;
                }
            }
        </script>
    </body>
</html>
