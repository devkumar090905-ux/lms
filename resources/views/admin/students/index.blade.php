@extends('layouts.admin')

@section('title', 'Manage Students')
@section('page_title', 'Library Members')

@section('content')
<div class="p-8 space-y-8 flex-1">
    
    <!-- Add Student Section -->
    <div class="glass-panel p-6 rounded-2xl">
        <h3 class="text-xl font-semibold text-white mb-4">Register New Student</h3>
        
        <form action="{{ route('students.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            @csrf
            
            <div class="flex flex-col">
                <label for="name" class="text-sm text-gray-400 mb-1">Full Name *</label>
                <input type="text" name="name" id="name" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="phone_number" class="text-sm text-gray-400 mb-1">Phone Number *</label>
                <input type="text" name="phone_number" id="phone_number" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                @error('phone_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="email" class="text-sm text-gray-400 mb-1">Email</label>
                <input type="email" name="email" id="email" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col border border-transparent">
                <label for="address" class="text-sm text-gray-400 mb-1">Address</label>
                <input type="text" name="address" id="address" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-medium px-4 py-2 rounded-lg shadow-lg shadow-blue-500/20 transition-all border border-blue-500">
                    Register
                </button>
            </div>
        </form>
    </div>

    <!-- Students List -->
    <div class="glass-panel rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800">
            <h3 class="text-xl font-semibold text-white">All Students</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/20 text-gray-400 text-sm">
                        <th class="px-6 py-3 font-medium border-b border-gray-800">ID</th>
                        <th class="px-6 py-3 font-medium border-b border-gray-800">Name</th>
                        <th class="px-6 py-3 font-medium border-b border-gray-800">Contact</th>
                        <th class="px-6 py-3 font-medium border-b border-gray-800">Address</th>
                        <th class="px-6 py-3 font-medium border-b border-gray-800">Joined</th>
                        <th class="px-6 py-3 font-medium border-b border-gray-800 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    @forelse($students as $student)
                    <tr class="hover:bg-white/5 border-b border-gray-800/50 transition">
                        <td class="px-6 py-4">{{ $student->id }}</td>
                        <td class="px-6 py-4 font-medium text-white">{{ $student->name }}</td>
                        <td class="px-6 py-4">
                            <div>{{ $student->phone_number }}</div>
                            <div class="text-xs text-gray-500">{{ $student->email }}</div>
                        </td>
                        <td class="px-6 py-4 truncate max-w-xs">{{ $student->address ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $student->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No students registered yet. Use the form above to add one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
