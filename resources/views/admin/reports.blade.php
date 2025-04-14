<x-admin-layout title="Reports">
    <x-slot name="header">Reports & Analytics</x-slot>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Monthly Revenue Chart -->
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Monthly Revenue</h3>
            <div class="relative h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Orders by Status</h3>
            <div class="relative h-80">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <!-- Monthly Revenue Table -->
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Revenue Breakdown</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Month</th>
                            <th scope="col" class="px-4 py-3">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthly_revenue as $revenue)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ date('F', mktime(0, 0, 0, $revenue->month, 1)) }}</td>
                                <td class="px-4 py-3">${{ number_format($revenue->revenue, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Status Table -->
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Order Status Breakdown</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Month</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order_counts as $count)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ date('F', mktime(0, 0, 0, $count->month, 1)) }}</td>
                                <td class="px-4 py-3">{{ ucfirst($count->status) }}</td>
                                <td class="px-4 py-3">{{ $count->order_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Revenue Chart
            const revenueData = @json($monthly_revenue);
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueData.map(item => {
                        const date = new Date(2024, item.month - 1);
                        return date.toLocaleString('default', { month: 'long' });
                    }),
                    datasets: [{
                        label: 'Revenue',
                        data: revenueData.map(item => item.revenue),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true
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
                    }
                }
            });

            // Order Status Chart
            const orderData = @json($order_counts);
            const statusColors = {
                'pending': '#FBBF24',
                'in_progress': '#3B82F6',
                'completed': '#10B981',
                'cancelled': '#EF4444',
                'disputed': '#8B5CF6'
            };

            const orderCtx = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(orderCtx, {
                type: 'bar',
                data: {
                    labels: [...new Set(orderData.map(item => {
                        const date = new Date(2024, item.month - 1);
                        return date.toLocaleString('default', { month: 'long' });
                    }))],
                    datasets: Object.keys(statusColors).map(status => ({
                        label: status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' '),
                        data: orderData.filter(item => item.status === status).map(item => item.order_count),
                        backgroundColor: statusColors[status]
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-admin-layout> 