<x-client-layout>
    <x-slot:header>My Profile</x-slot:header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900 dark:text-green-300" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 grid gap-6 md:grid-cols-12">
        <!-- Profile Summary -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-gray-800 md:col-span-4">
            <div class="mb-6 flex flex-col items-center">
                <div class="mb-4 h-32 w-32 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                    <svg class="h-full w-full text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $client->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $client->email }}</p>
                
                <div class="mt-4 w-full">
                    <div class="mb-2 flex items-center justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Created</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $client->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="mb-2 flex items-center justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                            {{ ucfirst($client->status) }}
                        </span>
                    </div>
                    
                    <div class="mb-2 flex items-center justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $client->phone ?? 'Not provided' }}</span>
                    </div>
                    
                    <div class="mb-2 flex items-center justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Institution</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $client->clientProfile->institution ?? 'Not provided' }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Study Level</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $client->clientProfile->study_level ?? 'Not provided' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-gray-800 md:col-span-8">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Edit Profile</h3>
            
            <form action="{{ route('client.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="institution" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Institution</label>
                        <input type="text" name="institution" id="institution" value="{{ old('institution', $client->clientProfile->institution ?? '') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                        @error('institution')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="study_level" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Study Level</label>
                        <select name="study_level" id="study_level" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                            <option value="">Select Study Level</option>
                            <option value="high_school" {{ (old('study_level', $client->clientProfile->study_level ?? '') == 'high_school') ? 'selected' : '' }}>High School</option>
                            <option value="undergraduate" {{ (old('study_level', $client->clientProfile->study_level ?? '') == 'undergraduate') ? 'selected' : '' }}>Undergraduate</option>
                            <option value="masters" {{ (old('study_level', $client->clientProfile->study_level ?? '') == 'masters') ? 'selected' : '' }}>Masters</option>
                            <option value="phd" {{ (old('study_level', $client->clientProfile->study_level ?? '') == 'phd') ? 'selected' : '' }}>PhD</option>
                        </select>
                        @error('study_level')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <h4 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Change Password</h4>
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Leave blank if you don't want to change your password.</p>
                    
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">New Password</label>
                            <input type="password" name="password" id="password" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-client-layout> 