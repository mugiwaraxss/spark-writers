<x-admin-layout>
    <x-slot:header>Writer Applications</x-slot:header>
    
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <!-- Filter Controls -->
        <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
            <form action="{{ route('admin.applications.index') }}" method="GET" class="flex items-center space-x-2">
                <div>
                    <label for="status" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select id="status" name="status" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        @foreach($status_options as $option)
                            <option value="{{ $option }}" {{ request('status') == $option ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-6">
                    <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Filter
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Applications Table -->
        @if(count($applications) > 0)
            <div class="relative overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Education</th>
                            <th scope="col" class="px-6 py-3">Experience</th>
                            <th scope="col" class="px-6 py-3">Application Date</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                                <td class="px-6 py-4">{{ $application->name }}</td>
                                <td class="px-6 py-4">{{ $application->email }}</td>
                                <td class="px-6 py-4">{{ $application->education_level }}</td>
                                <td class="px-6 py-4">{{ $application->experience }} years</td>
                                <td class="px-6 py-4">{{ $application->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full px-2 py-1 text-xs font-medium 
                                        @if($application->status == 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($application->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($application->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @endif">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.applications.view', $application) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $applications->links() }}
            </div>
        @else
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                <p class="text-gray-500 dark:text-gray-400">No writer applications found.</p>
            </div>
        @endif
    </div>
</x-admin-layout> 