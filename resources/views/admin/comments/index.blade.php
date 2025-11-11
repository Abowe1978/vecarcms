@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Comments Moderation</h1>
        
        <!-- Filter Buttons -->
        <div class="flex gap-2">
            <a href="{{ route('admin.comments.index') }}" 
               class="px-4 py-2 rounded {{ request('status') == null ? 'bg-gray-800 text-white' : 'bg-gray-200 text-gray-700' }}">
                All ({{ $stats['all'] ?? 0 }})
            </a>
            <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                Pending ({{ $stats['pending'] ?? 0 }})
            </a>
            <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}" 
               class="px-4 py-2 rounded {{ request('status') == 'approved' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                Approved ({{ $stats['approved'] ?? 0 }})
            </a>
            <a href="{{ route('admin.comments.index', ['status' => 'spam']) }}" 
               class="px-4 py-2 rounded {{ request('status') == 'spam' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                Spam ({{ $stats['spam'] ?? 0 }})
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">On</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($comments as $comment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img src="{{ $comment->gravatar }}" alt="{{ $comment->author_name }}" 
                                     class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $comment->author_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $comment->author_email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-md truncate">
                                {{ Str::limit($comment->content, 100) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                Post:
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $comment->commentable->title ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($comment->status === 'approved')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                    Approved
                                </span>
                            @elseif($comment->status === 'pending')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @elseif($comment->status === 'spam')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                                    Spam
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">
                                    {{ ucfirst($comment->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $comment->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                @if($comment->status !== 'approved')
                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-800"
                                                title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.comments.edit', $comment) }}" 
                                   class="text-blue-600 hover:text-blue-800"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if($comment->status !== 'spam')
                                    <form action="{{ route('admin.comments.spam', $comment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-orange-600 hover:text-orange-800"
                                                title="Mark as Spam">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Delete this comment permanently?')">
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
                            <i class="fas fa-comments text-4xl text-gray-300 mb-3"></i>
                            <p>No comments found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($comments->hasPages())
        <div class="mt-6">
            {{ $comments->links() }}
        </div>
    @endif
</div>
@endsection
