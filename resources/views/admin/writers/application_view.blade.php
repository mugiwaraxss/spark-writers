<x-admin-layout>
    <x-slot:header>Review Writer Application</x-slot:header>
    
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <!-- Application Status Badge -->
        <div class="mb-6 flex justify-between">
            <div>
                <span class="mr-2 rounded-full px-3 py-1 text-sm font-medium 
                    @if($application->status == 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                    @elseif($application->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                    @elseif($application->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                    @endif">
                    {{ ucfirst($application->status) }}
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">Submitted: {{ $application->created_at->format('M d, Y') }}</span>
            </div>
            <a href="{{ route('admin.writers.applications') }}" class="rounded bg-gray-200 px-3 py-1 text-sm font-medium text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                Back to List
            </a>
        </div>
        
        <!-- Personal Information -->
        <div class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Personal Information</h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</p>
                    <p class="text-gray-900 dark:text-white">{{ $application->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                    <p class="text-gray-900 dark:text-white">{{ $application->email }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</p>
                    <p class="text-gray-900 dark:text-white">{{ $application->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Education Level</p>
                    <p class="text-gray-900 dark:text-white">{{ $application->education_level }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Experience</p>
                    <p class="text-gray-900 dark:text-white">{{ $application->experience }} years</p>
                </div>
            </div>
        </div>
        
        <!-- Specialization Areas -->
        <div class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Specialization Areas</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($application->specialization_areas as $area)
                    <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                        {{ $area }}
                    </span>
                @endforeach
            </div>
        </div>
        
        <!-- Cover Letter -->
        <div class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Cover Letter</h3>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                <p class="whitespace-pre-line text-gray-700 dark:text-gray-300">{{ $application->cover_letter }}</p>
            </div>
        </div>
        
        <!-- Resume Download -->
        @if($application->resume_path)
            <div class="mb-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Resume</h3>
                <a href="{{ Storage::url($application->resume_path) }}" target="_blank" class="inline-flex items-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download Resume
                </a>
            </div>
        @endif
        
        <!-- Application Actions -->
        @if($application->status === 'pending')
            <div class="mt-8 flex flex-wrap gap-4">
                <!-- Approve Application -->
                <form action="{{ route('admin.writers.applications.approve', $application) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="hourly_rate" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Hourly Rate ($)</label>
                        <input type="number" id="hourly_rate" name="hourly_rate" min="5" max="100" value="15" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                    </div>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-green-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approve Application
                    </button>
                </form>
                
                <!-- Reject Application -->
                <form action="{{ route('admin.writers.applications.reject', $application) }}" method="POST" class="ml-4">
                    @csrf
                    <div class="mb-3">
                        <label for="rejection_reason" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Rejection Reason</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required></textarea>
                    </div>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Reject Application
                    </button>
                </form>
            </div>
        @elseif($application->status === 'approved' && $application->user_id)
            <div class="mt-8">
                <a href="{{ route('admin.writers.show', $application->user_id) }}" class="inline-flex items-center rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    View Writer Profile
                </a>
            </div>
        @elseif($application->status === 'rejected')
            <div class="mt-8">
                <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Rejection Reason</h3>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-gray-700 dark:text-gray-300">{{ $application->rejection_reason }}</p>
                </div>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Rejected on {{ $application->processed_at->format('M d, Y') }} 
                    by {{ optional($application->processedBy)->name ?? 'System' }}
                </p>
            </div>
        @endif
    </div>
</x-admin-layout> 