@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Contact Submissions</h1>
        
        <!-- Stats Cards -->
        <div class="flex gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['all'] ?? 0 }}</div>
                <div class="text-xs text-gray-500">Total</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['new'] ?? 0 }}</div>
                <div class="text-xs text-gray-500">New</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['read'] ?? 0 }}</div>
                <div class="text-xs text-gray-500">Read</div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-4 flex gap-2">
        <a href="{{ route('admin.contact.index') }}" 
           class="px-4 py-2 rounded {{ $status == 'all' ? 'bg-gray-800 text-white' : 'bg-gray-200 text-gray-700' }}">
            All
        </a>
        <a href="{{ route('admin.contact.index', ['status' => 'new']) }}" 
           class="px-4 py-2 rounded {{ $status == 'new' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }}">
            New
        </a>
        <a href="{{ route('admin.contact.index', ['status' => 'read']) }}" 
           class="px-4 py-2 rounded {{ $status == 'read' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
            Read
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($submissions as $submission)
                    <tr class="hover:bg-gray-50 {{ $submission->status === 'new' ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $submission->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $submission->email }}
                            </div>
                            @if($submission->phone)
                                <div class="text-xs text-gray-400">
                                    <i class="fas fa-phone"></i> {{ $submission->phone }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $submission->subject ?: 'No subject' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-md truncate">
                                {{ Str::limit($submission->message, 80) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($submission->status === 'new')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">
                                    New
                                </span>
                            @elseif($submission->status === 'read')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                    Read
                                </span>
                            @elseif($submission->status === 'replied')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                    Replied
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $submission->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.contact.show', $submission) }}" 
                                   class="text-blue-600 hover:text-blue-800"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <form action="{{ route('admin.contact.destroy', $submission) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Delete this submission?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-envelope text-4xl text-gray-300 mb-3"></i>
                            <p>No contact submissions found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($submissions->hasPages())
        <div class="mt-6">
            {{ $submissions->links() }}
        </div>
    @endif
</div>
@endsection
