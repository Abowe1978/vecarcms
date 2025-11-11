@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Edit Comment</h1>
        <a href="{{ route('admin.comments.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Comments
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.comments.update', $comment) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Comment Info -->
            <div class="mb-6 p-4 bg-gray-50 rounded">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-semibold">Author:</span> {{ $comment->author_name ?: $comment->user->name }}
                    </div>
                    <div>
                        <span class="font-semibold">Email:</span> {{ $comment->author_email ?: $comment->user->email }}
                    </div>
                    <div>
                        <span class="font-semibold">On Post:</span> 
                        {{ $comment->commentable->title ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-semibold">Date:</span> {{ $comment->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

            <!-- Comment Content -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Comment Content
                </label>
                <textarea name="content" 
                          rows="8"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                          required>{{ old('content', $comment->content) }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded">
                    <i class="fas fa-save mr-2"></i>Update Comment
                </button>
                
                @if($comment->status !== 'approved')
                    <button type="button" 
                            onclick="approveComment({{ $comment->id }})"
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded">
                        <i class="fas fa-check mr-2"></i>Approve
                    </button>
                @endif

                <button type="button" 
                        onclick="deleteComment({{ $comment->id }})"
                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function approveComment(id) {
    if (confirm('Approve this comment?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/comments/${id}/approve`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteComment(id) {
    if (confirm('Delete this comment permanently?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/comments/${id}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection

