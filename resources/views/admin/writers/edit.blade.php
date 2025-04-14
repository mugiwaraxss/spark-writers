<x-admin-layout>
    <x-slot:header>Edit Writer</x-slot:header>

    <div class="mx-auto max-w-3xl">
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
            <form action="{{ route('admin.writers.update', $writer) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div>
                    <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Basic Information</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $writer->name) }}" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $writer->email) }}" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">New Password</label>
                            <input type="password" id="password" name="password"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Leave blank to keep current password</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <select id="status" name="status" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                                <option value="active" {{ old('status', $writer->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $writer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Writer Profile -->
                <div>
                    <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Writer Profile</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="education_level" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Education Level</label>
                            <select id="education_level" name="education_level" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                                <option value="">Select Education Level</option>
                                <option value="Bachelor" {{ old('education_level', $writer->writerProfile->education_level) == 'Bachelor' ? 'selected' : '' }}>Bachelor's Degree</option>
                                <option value="Master" {{ old('education_level', $writer->writerProfile->education_level) == 'Master' ? 'selected' : '' }}>Master's Degree</option>
                                <option value="PhD" {{ old('education_level', $writer->writerProfile->education_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                            </select>
                            @error('education_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="hourly_rate" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Hourly Rate ($)</label>
                            <input type="number" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $writer->writerProfile->hourly_rate) }}" min="5" max="100" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                            @error('hourly_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Expertise Areas</label>
                            <div class="grid gap-4 md:grid-cols-3">
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="expertise_areas[]" value="essays" class="text-blue-600 focus:ring-blue-500"
                                            {{ in_array('essays', old('expertise_areas', $writer->writerProfile->expertise_areas ?? [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-white">Essays</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="expertise_areas[]" value="research_papers" class="text-blue-600 focus:ring-blue-500"
                                            {{ in_array('research_papers', old('expertise_areas', $writer->writerProfile->expertise_areas ?? [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-white">Research Papers</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="expertise_areas[]" value="dissertations" class="text-blue-600 focus:ring-blue-500"
                                            {{ in_array('dissertations', old('expertise_areas', $writer->writerProfile->expertise_areas ?? [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-white">Dissertations</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="expertise_areas[]" value="technical_writing" class="text-blue-600 focus:ring-blue-500"
                                            {{ in_array('technical_writing', old('expertise_areas', $writer->writerProfile->expertise_areas ?? [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-white">Technical Writing</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="expertise_areas[]" value="creative_writing" class="text-blue-600 focus:ring-blue-500"
                                            {{ in_array('creative_writing', old('expertise_areas', $writer->writerProfile->expertise_areas ?? [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-white">Creative Writing</span>
                                    </label>
                                </div>
                            </div>
                            @error('expertise_areas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="bio" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Bio</label>
                            <textarea id="bio" name="bio" rows="4" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">{{ old('bio', $writer->writerProfile->bio) }}</textarea>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.writers.show', $writer) }}" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        Update Writer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout> 