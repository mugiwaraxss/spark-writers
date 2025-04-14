<x-admin-layout>
    <x-slot:header>Writer Details</x-slot:header>

    <div class="space-y-6">
        <!-- Writer Information Card -->
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Writer Information</h3>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.writers.edit', $writer) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        Edit Writer
                    </a>
                    <form action="{{ route('admin.writers.toggle-status', $writer) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-lg border border-amber-600 px-4 py-2 text-sm font-medium text-amber-600 hover:bg-amber-50 focus:outline-none focus:ring-4 focus:ring-amber-300">
                            {{ $writer->status === 'active' ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Basic Information -->
                <div>
                    <h4 class="mb-4 text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Basic Information</h4>
                    <dl class="grid gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $writer->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $writer->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd>
                                <span class="rounded-full {{ $writer->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }} px-2.5 py-0.5 text-xs font-medium">
                                    {{ ucfirst($writer->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Joined</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $writer->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Writer Profile -->
                <div>
                    <h4 class="mb-4 text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Writer Profile</h4>
                    <dl class="grid gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Education Level</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $writer->writerProfile->education_level }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Rating</dt>
                            <dd class="flex items-center">
                                <span class="mr-1 text-yellow-500">★</span>
                                <span class="text-gray-900 dark:text-white">{{ number_format($writer->writerProfile->rating ?? 0, 1) }}</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hourly Rate</dt>
                            <dd class="text-gray-900 dark:text-white">${{ number_format($writer->writerProfile->hourly_rate, 2) }}/hour</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Expertise Areas</dt>
                            <dd class="flex flex-wrap gap-2">
                                @foreach($writer->writerProfile->expertise_areas as $area)
                                    <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        {{ str_replace('_', ' ', ucfirst($area)) }}
                                    </span>
                                @endforeach
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Bio -->
            <div class="mt-6">
                <h4 class="mb-2 text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Bio</h4>
                <p class="text-gray-900 dark:text-white">{{ $writer->writerProfile->bio }}</p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
                <div class="flex items-center">
                    <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_orders'] }}</h2>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
                <div class="flex items-center">
                    <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed Orders</p>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['completed_orders'] }}</h2>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
                <div class="flex items-center">
                    <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Earnings</p>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($stats['total_earnings'], 2) }}</h2>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
                <div class="flex items-center">
                    <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Average Rating</p>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['average_rating'], 1) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 