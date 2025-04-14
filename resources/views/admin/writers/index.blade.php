<x-admin-layout title="Writers">
    <x-slot name="header">Writer Management</x-slot>

    <div class="mb-4 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.writers.create') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Writer
            </a>
        </div>
        <div>
            <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200">
                Writer Applications
            </a>
        </div>
    </div>

    <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
        <!-- Filters -->
        <form action="{{ route('admin.writers.index') }}" method="GET" class="mb-6">
            <div class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0">
                <div class="w-full md:w-1/3">
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Status</label>
                    <select id="status" name="status" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="w-full md:w-1/3">
                    <label for="expertise" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Expertise Area</label>
                    <select id="expertise" name="expertise" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        <option value="">All Areas</option>
                        <option value="essays" {{ request('expertise') == 'essays' ? 'selected' : '' }}>Essays</option>
                        <option value="research_papers" {{ request('expertise') == 'research_papers' ? 'selected' : '' }}>Research Papers</option>
                        <option value="dissertations" {{ request('expertise') == 'dissertations' ? 'selected' : '' }}>Dissertations</option>
                        <option value="technical_writing" {{ request('expertise') == 'technical_writing' ? 'selected' : '' }}>Technical Writing</option>
                        <option value="creative_writing" {{ request('expertise') == 'creative_writing' ? 'selected' : '' }}>Creative Writing</option>
                    </select>
                </div>
                <div class="w-full md:w-1/3">
                    <label for="min_rating" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Minimum Rating</label>
                    <select id="min_rating" name="min_rating" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        <option value="">Any Rating</option>
                        <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>4.5+ Stars</option>
                        <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                        <option value="3.5" {{ request('min_rating') == '3.5' ? 'selected' : '' }}>3.5+ Stars</option>
                        <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Writers Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">Name</th>
                        <th scope="col" class="px-4 py-3">Email</th>
                        <th scope="col" class="px-4 py-3">Rating</th>
                        <th scope="col" class="px-4 py-3">Expertise</th>
                        <th scope="col" class="px-4 py-3">Status</th>
                        <th scope="col" class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($writers as $writer)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-3">{{ $writer->name }}</td>
                        <td class="px-4 py-3">{{ $writer->email }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <span class="mr-1 text-yellow-500">★</span>
                                <span>{{ number_format($writer->writerProfile->rating ?? 0, 1) }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($writer->writerProfile && $writer->writerProfile->expertise_areas)
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($writer->writerProfile->expertise_areas, 0, 2) as $area)
                                        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            {{ str_replace('_', ' ', ucfirst($area)) }}
                                        </span>
                                    @endforeach
                                    @if(count($writer->writerProfile->expertise_areas) > 2)
                                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            +{{ count($writer->writerProfile->expertise_areas) - 2 }} more
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500">Not specified</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full {{ $writer->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }} px-2.5 py-0.5 text-xs font-medium">
                                {{ ucfirst($writer->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.writers.show', $writer) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">View</a>
                                <a href="{{ route('admin.writers.edit', $writer) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">Edit</a>
                                <form action="{{ route('admin.writers.toggle-status', $writer) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="font-medium text-amber-600 hover:underline dark:text-amber-400">
                                        {{ $writer->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $writers->withQueryString()->links() }}
        </div>
    </div>
</x-admin-layout> 