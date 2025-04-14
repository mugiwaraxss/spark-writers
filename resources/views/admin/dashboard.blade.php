<x-admin-layout>
    <x-slot:header>Admin Dashboard</x-slot:header>
    
    <!-- Stats Overview -->
    <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <!-- Total Orders -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_orders'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Active Writers -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Writers</p>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['active_writers'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Total Clients -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Clients</p>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_clients'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Revenue</p>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mb-6 grid gap-6 lg:grid-cols-2">
        <!-- Orders Status Chart -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Orders by Status</h3>
            </div>
            <div class="flex justify-center">
                <canvas id="ordersStatusChart" class="h-64 w-full"></canvas>
            </div>
        </div>
        
        <!-- Revenue Chart -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Revenue</h3>
            </div>
            <div class="flex justify-center">
                <canvas id="revenueChart" class="h-64 w-full"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
            <a href="{{ route('admin.orders.index') }}" class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Manage Orders</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">View and manage orders</p>
                </div>
            </a>
            <a href="{{ route('admin.writers.index') }}" class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Manage Writers</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">View and manage writers</p>
                </div>
            </a>
            <a href="{{ route('admin.messages.users') }}" class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Start Message</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Contact writers and clients</p>
                </div>
            </a>
            <a href="{{ route('admin.payments.client') }}" class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Payment History</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">View payment records</p>
                </div>
            </a>
            <a href="{{ route('admin.settings') }}" class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Platform Settings</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Configure platform settings</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">View All</a>
        </div>
        
        @if(count($recentOrders ?? []) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Order ID</th>
                            <th scope="col" class="px-4 py-3">Client</th>
                            <th scope="col" class="px-4 py-3">Title</th>
                            <th scope="col" class="px-4 py-3">Assigned To</th>
                            <th scope="col" class="px-4 py-3">Due Date</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr class="border-b dark:border-gray-700">
                                <td class="whitespace-nowrap px-4 py-3">{{ $order->order_number ?? $order->id }}</td>
                                <td class="px-4 py-3">{{ $order->client->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ Str::limit($order->title ?? '', 30) }}</td>
                                <td class="px-4 py-3">
                                    @if($order->writer)
                                        {{ $order->writer->name }}
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Unassigned</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $order->deadline instanceof \DateTime || $order->deadline instanceof \Carbon\Carbon ? $order->deadline->format('M d, Y') : ($order->deadline ?: 'N/A') }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-1 text-xs font-medium 
                                        @if($order->status == 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($order->status == 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($order->status == 'revision') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status ?? 'unknown')) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                <p class="text-gray-500 dark:text-gray-400">No recent orders found.</p>
            </div>
        @endif
    </div>

    <!-- Latest Writer Applications -->
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Latest Writer Applications</h3>
            <a href="{{ route('admin.applications.index') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">View All</a>
        </div>
        
        @if(count($writerApplications ?? []) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Name</th>
                            <th scope="col" class="px-4 py-3">Email</th>
                            <th scope="col" class="px-4 py-3">Specialization</th>
                            <th scope="col" class="px-4 py-3">Experience</th>
                            <th scope="col" class="px-4 py-3">Application Date</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($writerApplications as $application)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ $application->name }}</td>
                                <td class="px-4 py-3">{{ $application->email }}</td>
                                <td class="px-4 py-3">{{ is_array($application->specialization_areas) ? implode(', ', array_slice($application->specialization_areas, 0, 2)) : $application->specialization_areas }}</td>
                                <td class="px-4 py-3">{{ $application->experience }} years</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $application->created_at instanceof \DateTime || $application->created_at instanceof \Carbon\Carbon ? $application->created_at->format('M d, Y') : $application->created_at }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-1 text-xs font-medium 
                                        @if($application->status == 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($application->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($application->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @endif">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.applications.view', $application) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                <p class="text-gray-500 dark:text-gray-400">No pending writer applications found.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Use real data from controller
            const orderStatusData = @json($orderStatusData ?? ['labels' => [], 'data' => []]);
            const revenueData = @json($revenueChartData ?? ['labels' => [], 'data' => []]);
            
            // Orders Status Chart
            const ordersCtx = document.getElementById('ordersStatusChart').getContext('2d');
            const ordersChart = new Chart(ordersCtx, {
                type: 'doughnut',
                data: {
                    labels: orderStatusData.labels,
                    datasets: [{
                        data: orderStatusData.data,
                        backgroundColor: [
                            '#FBBF24', // Amber for pending
                            '#3B82F6', // Blue for in progress
                            '#10B981', // Green for completed
                            '#8B5CF6', // Purple for revision
                            '#EF4444'  // Red for cancelled
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: revenueData.labels,
                    datasets: [{
                        label: 'Monthly Revenue',
                        data: revenueData.data,
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
</x-admin-layout> 