@extends('layouts.admin')

@section('title', 'Manage Students')
@section('page_title', 'Library Members')

@section('content')
<div class="p-4 md:p-8 space-y-6 md:space-y-8 flex-1">
    
    <!-- Add Student Section -->
    <div class="glass-panel p-4 md:p-6 rounded-xl md:rounded-2xl">
        <h3 class="text-lg md:text-xl font-semibold text-white mb-4">Register New Student</h3>
        
        <form action="{{ route('students.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
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
    <div class="glass-panel rounded-xl md:rounded-2xl overflow-hidden">
        <div class="px-4 md:px-6 py-4 border-b border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-lg md:text-xl font-semibold text-white">All Students</h3>
            <div class="relative w-full sm:w-72">
                <input type="text" id="studentSearch" onkeyup="filterStudents()" placeholder="Search name, phone, or ID..." class="w-full bg-black/40 border border-gray-700 rounded-lg pl-10 pr-4 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 transition-all border-l-4 border-l-indigo-500">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Desktop Table (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/20 text-gray-400 text-sm">
                        <th class="px-6 py-3 font-medium border-b border-gray-800">S.No</th>
                        <th class="px-6 py-3 font-medium border-b border-gray-800 text-indigo-400">ID</th>
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
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-mono text-indigo-400 text-xs">#{{ $student->id }}</td>
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

        <!-- Mobile Cards (hidden on desktop) -->
        <div class="md:hidden divide-y divide-gray-800/50">
            @forelse($students as $student)
            <div class="p-4 hover:bg-white/5 transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 font-bold text-sm">
                            {{ substr($student->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-white text-sm">{{ $student->name }}</p>
                            <p class="text-[11px] text-gray-500">
                                <span class="text-indigo-400 font-bold">ID: #{{ $student->id }}</span> | S.No: {{ $loop->iteration }}
                            </p>
                        </div>
                    </div>
                    <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs bg-red-500/10 px-3 py-1 rounded-lg border border-red-500/20">Delete</button>
                    </form>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="bg-white/5 rounded-lg p-2">
                        <p class="text-gray-500 mb-0.5">📞 Phone</p>
                        <p class="text-gray-300">{{ $student->phone_number }}</p>
                    </div>
                    <div class="bg-white/5 rounded-lg p-2">
                        <p class="text-gray-500 mb-0.5">📧 Email</p>
                        <p class="text-gray-300 truncate">{{ $student->email ?? '-' }}</p>
                    </div>
                    <div class="bg-white/5 rounded-lg p-2">
                        <p class="text-gray-500 mb-0.5">📍 Address</p>
                        <p class="text-gray-300 truncate">{{ $student->address ?? '-' }}</p>
                    </div>
                    <div class="bg-white/5 rounded-lg p-2">
                        <p class="text-gray-500 mb-0.5">📅 Joined</p>
                        <p class="text-gray-300">{{ $student->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                No students registered yet. Use the form above to add one.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function filterStudents() {
    let input = document.getElementById('studentSearch');
    let filter = input.value.toLowerCase();
    
    // Desktop Table Rows
    let desktopRows = document.querySelectorAll('tbody tr');
    desktopRows.forEach(row => {
        if (row.querySelector('td')) {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        }
    });

    // Mobile Cards
    let mobileCards = document.querySelectorAll('.md\\:hidden > div.p-4');
    mobileCards.forEach(card => {
        let text = card.innerText.toLowerCase();
        card.style.display = text.includes(filter) ? "" : "none";
    });
}
</script>
@endpush
