<x-layout-writer>
    <x-slot:header>My Profile</x-slot:header>
    
    <div class="mb-6 grid gap-6 lg:grid-cols-3">
        <!-- Profile Summary Card -->
        <div class="lg:col-span-1">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
                <div class="mb-6 flex flex-col items-center">
                    <div class="mb-4 h-24 w-24 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                        @if($writer->profile_photo_path)
                            <img src="{{ Storage::url($writer->profile_photo_path) }}" alt="{{ $writer->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-blue-100 text-2xl font-bold text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                                {{ $writer->initials() }}
                            </div>
                        @endif
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $writer->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $writer->email }}</p>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Status:</span>
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $writer->available ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300' }}">
                            {{ $writer->available ? 'Available' : 'Busy' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed Orders:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $stats['completed_orders'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Rating:</span>
                        <div class="flex items-center">
                            <div class="mr-1 flex">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($writer->rating))
                                        <svg class="h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($writer->rating, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Member Since:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $writer->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Form -->
        <div class="lg:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Update Profile</h3>
                
                <form action="{{ route('writer.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6 grid gap-6 md:grid-cols-2">
                        <!-- Name -->
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $writer->name) }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $writer->email) }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $writer->phone) }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Profile Photo -->
                        <div>
                            <label for="profile_photo" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Profile Photo</label>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG or GIF (MAX. 2MB)</p>
                            @error('profile_photo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="bio" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">About Me</label>
                        <textarea id="bio" name="bio" rows="4" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Write a short bio about yourself...">{{ old('bio', $writer->bio) }}</textarea>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Areas of Expertise</label>
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                            @foreach($subjects as $value => $label)
                                <div class="flex items-center">
                                    <input id="subject_{{ $value }}" type="checkbox" name="subjects[]" value="{{ $value }}" {{ in_array($value, old('subjects', $writerSubjects)) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                    <label for="subject_{{ $value }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('subjects')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="education" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Education</label>
                        <input type="text" id="education" name="education" value="{{ old('education', $writer->education) }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="e.g. Ph.D. in English Literature, Harvard University">
                        @error('education')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="experience_years" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Years of Experience</label>
                        <input type="number" id="experience_years" name="experience_years" min="0" max="50" value="{{ old('experience_years', $writer->experience_years) }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        @error('experience_years')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Certifications & Documents</label>
                        
                        <x-file-upload
                            name="documents"
                            label="Upload Documents"
                            multiple="true"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            maxSizeMB="5"
                            allowedExtensions="pdf,doc,docx,jpg,jpeg,png"
                            help="Upload your degree certificates, writing samples, or other relevant documents (Max 5MB each)"
                            :existingFiles="$documents"
                        />
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Password Section -->
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Change Password</h3>
        
        <form action="{{ route('writer.password.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6 grid gap-6 md:grid-cols-3">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- New Password -->
                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">New Password</label>
                    <input type="password" id="password" name="password" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</x-layout-writer> 