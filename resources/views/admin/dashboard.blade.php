@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600">Welcome back! Here's what's happening with your site.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Users -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Users</p>
                    <h2 class="text-4xl font-bold mt-1">{{ $stats['users'] ?? 0 }}</h2>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
            <div class="flex items-center text-blue-100 text-sm">
                <i class="fas fa-arrow-up mr-1"></i>
                <span>Active accounts</span>
            </div>
        </div>

        <!-- Total Posts -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Posts</p>
                    <h2 class="text-4xl font-bold mt-1">{{ $stats['posts'] ?? 0 }}</h2>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
            <div class="flex items-center text-green-100 text-sm">
                <a href="{{ route('admin.posts.index') }}" class="hover:text-white transition">View all posts →</a>
            </div>
        </div>

        <!-- Total Pages -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Pages</p>
                    <h2 class="text-4xl font-bold mt-1">{{ $stats['pages'] ?? 0 }}</h2>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-file text-2xl"></i>
                </div>
            </div>
            <div class="flex items-center text-purple-100 text-sm">
                <a href="{{ route('admin.pages.index') }}" class="hover:text-white transition">View all pages →</a>
            </div>
        </div>
        
        <!-- Total Comments -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Total Comments</p>
                    <h2 class="text-4xl font-bold mt-1">{{ $stats['comments'] ?? 0 }}</h2>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-comments text-2xl"></i>
                </div>
            </div>
            <div class="flex items-center text-orange-100 text-sm">
                <a href="{{ route('admin.comments.index') }}" class="hover:text-white transition">Moderate comments →</a>
            </div>
        </div>
        
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Content Growth Chart --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Content Growth</h2>
            <canvas id="contentGrowthChart" height="200"></canvas>
        </div>

        {{-- Category Distribution --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Category Distribution</h2>
            <canvas id="categoryChart" height="200"></canvas>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Posts -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800">{{ __('admin.dashboard.recent_posts') }}</h2>
            </div>
            <div class="p-6">
                @if(isset($recentPosts) && count($recentPosts) > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($recentPosts as $post)
                            <li class="py-3">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $post->title }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $post->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600">{{ __('admin.dashboard.no_posts') }}</p>
                @endif
            </div>
        </div>

        <!-- Site Statistics -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800">{{ __('admin.dashboard.site_statistics') }}</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('admin.dashboard.disk_usage') }}</h3>
                        <div class="h-4 bg-gray-200 rounded mt-2">
                            <div class="h-4 bg-green-500 rounded" style="width: 35%"></div>
                        </div>
                        <div class="flex justify-between mt-1 text-xs text-gray-500">
                            <span>{{ __('admin.dashboard.used') }}: 35%</span>
                            <span>{{ __('admin.dashboard.free') }}: 65%</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('admin.dashboard.media_files') }}</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ rand(50, 150) }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('admin.dashboard.database_size') }}</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ rand(10, 50) }} MB</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.posts.create') }}" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                    <i class="fas fa-plus-circle text-green-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">New Post</span>
                </a>
                <a href="{{ route('admin.pages.create') }}" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                    <i class="fas fa-file-medical text-purple-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">New Page</span>
                </a>
                <a href="{{ route('admin.media.index') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                    <i class="fas fa-images text-blue-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Media</span>
                </a>
                <a href="{{ route('admin.seo.index') }}" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition">
                    <i class="fas fa-chart-line text-orange-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">SEO</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Content Growth Chart
const contentGrowthCtx = document.getElementById('contentGrowthChart');
if (contentGrowthCtx) {
    new Chart(contentGrowthCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Posts',
                    data: [12, 19, 15, 25, 22, 30],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Pages',
                    data: [5, 7, 8, 10, 12, 15],
                    borderColor: 'rgb(168, 85, 247)',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Category Distribution Chart
const categoryCtx = document.getElementById('categoryChart');
if (categoryCtx) {
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Technology', 'Business', 'Design', 'Marketing', 'Other'],
            datasets: [{
                data: [30, 25, 20, 15, 10],
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                    'rgb(168, 85, 247)',
                    'rgb(251, 146, 60)',
                    'rgb(156, 163, 175)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}
</script>
@endpush
@endsection
