<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-bold text-gray-800 mb-8 px-4 sm:px-0">Dashboard Overview</h1>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 px-4 sm:px-0">
            <!-- Total Sales -->
            <div class="bg-white overflow-hidden shadow-sm p-6 border border-black rounded-none">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-2 text-sm font-medium text-gray-500">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-800">LKR {{ $formattedSales }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white overflow-hidden shadow-sm p-6 border border-black rounded-none">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-50 text-purple-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-2 text-sm font-medium text-gray-500">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Avg Order Value (Calculated in View) -->
            <div class="bg-white overflow-hidden shadow-sm p-6 border border-black rounded-none">
                 <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-50 text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-2 text-sm font-medium text-gray-500">Avg. Order Value</p>
                        <p class="text-2xl font-bold text-gray-800">
                            LKR {{ $totalOrders > 0 ? number_format(($totalSales / 100) / $totalOrders, 2) : '0.00' }}
                        </p>
                    </div>
                </div>
            </div>
            
             <!-- Top Product (Dynamic content based on first item) -->
             <div class="bg-white overflow-hidden shadow-sm p-6 border border-black rounded-none">
                 <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-50 text-yellow-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <div class="ml-4 truncate">
                        <p class="mb-2 text-sm font-medium text-gray-500">Top Product</p>
                        <p class="text-lg font-bold text-gray-800 truncate" title="{{ $topProducts->first()->product_name ?? 'N/A' }}">
                            {{ $topProducts->first()->product_name ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 px-4 sm:px-0">
            <!-- Line Chart: Sales Trend -->
            <div class="bg-white p-6 shadow-sm border border-black rounded-none">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Trends (Last 7 Days)</h3>
                <div class="relative h-64 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Doughnut Chart: Order Status -->
            <div class="bg-white p-6 shadow-sm border border-black rounded-none">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Status Distribution</h3>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Top Products List -->
        <div class="bg-white shadow-sm border border-black rounded-none mb-8 mx-4 sm:mx-0 overflow-hidden">
             <div class="p-6 border-b border-black">
                <h3 class="text-lg font-semibold text-gray-800">Top Selling Products</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Product Name</th>
                            <th class="px-6 py-3 font-semibold text-center">Units Sold</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($topProducts as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $product->product_name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $product->total_quantity }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-8 text-center text-gray-500">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('livewire:initialized', () => {
             // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($salesChartLabels),
                    datasets: [{
                        label: 'Revenue (LKR)',
                        data: @json($salesChartValues),
                        borderColor: '#4FB5D0', // Updated Line Color
                        backgroundColor: '#6FAE8D', // Updated Background Color
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#4FB5D0',
                        pointBorderColor: '#4FB5D0',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'LKR ' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 4], color: '#f3f4f6' },
                            ticks: { callback: function(value) { return 'LKR ' + value; } }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($statusLabels),
                    datasets: [{
                        data: @json($statusValues),
                        backgroundColor: @json($statusLabels).map(status => {
                            switch(status.toLowerCase()) {
                                case 'processing': return '#D1D5DB'; // Grey
                                case 'shipped': return '#4FB5D0';
                                case 'delivered': return '#6FAE8D';
                                case 'pending': return '#F59E0B';
                                case 'cancelled': return '#EF4444';
                                default: return '#E5E7EB'; // Default grey
                            }
                        }),
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        });
    </script>
</div>
