@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Contact Submission Details</h1>
        <a href="{{ route('admin.contact.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Submissions
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Message</h2>
                
                @if($contactSubmission->subject)
                    <div class="mb-4">
                        <span class="text-sm font-medium text-gray-500">Subject:</span>
                        <p class="text-lg font-semibold">{{ $contactSubmission->subject }}</p>
                    </div>
                @endif

                <div class="prose max-w-none">
                    {{ $contactSubmission->message }}
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Admin Notes</h2>
                
                <form action="{{ route('admin.contact.update', $contactSubmission) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <textarea name="admin_notes" 
                              rows="4"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 mb-3"
                              placeholder="Add internal notes...">{{ old('admin_notes', $contactSubmission->admin_notes) }}</textarea>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" 
                                class="rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="new" {{ $contactSubmission->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="read" {{ $contactSubmission->status === 'read' ? 'selected' : '' }}>Read</option>
                            <option value="replied" {{ $contactSubmission->status === 'replied' ? 'selected' : '' }}>Replied</option>
                            <option value="archived" {{ $contactSubmission->status === 'archived' ? 'selected' : '' }}>Archived</option>
                            <option value="spam" {{ $contactSubmission->status === 'spam' ? 'selected' : '' }}>Spam</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded">
                        <i class="fas fa-save mr-2"></i>Save Notes
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Name:</span>
                        <p class="text-sm">{{ $contactSubmission->name }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Email:</span>
                        <p class="text-sm">
                            <a href="mailto:{{ $contactSubmission->email }}" class="text-blue-600 hover:underline">
                                {{ $contactSubmission->email }}
                            </a>
                        </p>
                    </div>
                    
                    @if($contactSubmission->phone)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Phone:</span>
                            <p class="text-sm">
                                <a href="tel:{{ $contactSubmission->phone }}" class="text-blue-600 hover:underline">
                                    {{ $contactSubmission->phone }}
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Metadata</h3>
                
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="font-medium text-gray-500">Submitted:</span>
                        <p>{{ $contactSubmission->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <span class="font-medium text-gray-500">IP Address:</span>
                        <p class="font-mono text-xs">{{ $contactSubmission->ip_address ?: 'N/A' }}</p>
                    </div>
                    
                    @if($contactSubmission->read_at)
                        <div>
                            <span class="font-medium text-gray-500">Read At:</span>
                            <p>{{ $contactSubmission->read_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif

                    @if($contactSubmission->readByUser)
                        <div>
                            <span class="font-medium text-gray-500">Read By:</span>
                            <p>{{ $contactSubmission->readByUser->name }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

