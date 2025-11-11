@extends('themes.dwntheme::layouts.main')

@section('title', $post->title . ' - ' . settings('site_name', 'VeCarCMS'))
@section('meta_description', $post->meta_description ?? str($post->excerpt ?? $post->content)->limit(160))

@section('content')

{{-- Breadcrumbs --}}
<section class="py-5 bg-light border-bottom">
    <div class="container">
        {!! render_breadcrumbs($post) !!}
    </div>
</section>

{{-- Post Content - Full Width --}}
<article class="py-10">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                
                {{-- Post Header --}}
                <header class="mb-8 text-center">
                    @if($post->categories->count() > 0)
                    <a href="{{ get_term_link('category', $post->categories->first()) }}" class="badge bg-primary text-white text-decoration-none mb-3">
                        {{ $post->categories->first()->name }}
                    </a>
                    @endif
                    
                    <h1 class="display-4 fw-bold mb-4">{{ $post->title }}</h1>
                    
                    <div class="d-flex align-items-center justify-content-center text-muted mb-4">
                        <time datetime="{{ $post->published_at }}">
                            {{ $post->published_at->format('F j, Y') }}
                        </time>
                        <span class="mx-3">•</span>
                        <span>By {{ $post->author->name }}</span>
                        @if($post->read_time)
                            <span class="mx-3">•</span>
                            <span>{{ $post->read_time }} min read</span>
                        @endif
                    </div>

                    {{-- Featured Image --}}
                    @if($post->featured_image)
                    <div class="mb-5">
                        <img src="{{ $post->getImageUrl() }}" alt="{{ $post->title }}" class="img-fluid rounded-3 shadow-lg w-100" style="max-height: 600px; object-fit: cover;">
                    </div>
                    @endif
                </header>

                {{-- Post Body --}}
                <div class="prose prose-lg max-w-full mb-8">
                    {!! $post->content !!}
                </div>

                {{-- Tags --}}
                @if($post->tags->count() > 0)
                <div class="mb-8 pb-8 border-bottom">
                    <h6 class="fw-bold mb-3">Tags:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <a href="{{ get_term_link('tag', $tag) }}" class="badge bg-light text-dark text-decoration-none hover-bg-primary">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Author Bio --}}
                <div class="card border-0 bg-light mb-8">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-start">
                            <img src="{{ $post->author->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) }}" 
                                 alt="{{ $post->author->name }}" 
                                 class="rounded-circle me-4" 
                                 width="80" height="80">
                            <div>
                                <h5 class="fw-bold mb-2">{{ $post->author->name }}</h5>
                                @if($post->author->bio)
                                    <p class="text-muted mb-0">{{ $post->author->bio }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Share Buttons --}}
                <div class="d-flex align-items-center justify-content-between mb-8 pb-8 border-bottom">
                    <h6 class="fw-bold mb-0">Share this post:</h6>
                    <div class="d-flex gap-3">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="ri-twitter-line"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="ri-facebook-line"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="ri-linkedin-line"></i>
                        </a>
                    </div>
                </div>

                {{-- Comments Section --}}
                @if(settings('enable_comments', true))
                <div id="comments" class="mb-8">
                    <h4 class="fw-bold mb-5">Comments</h4>
                    
                    @if($post->comments && $post->comments->count() > 0)
                        <div class="space-y-4">
                            @foreach($post->comments()->whereNull('parent_id')->latest()->get() as $comment)
                                <div class="card border-0 bg-light p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <img src="{{ $comment->user ? $comment->user->profile_photo_url : 'https://ui-avatars.com/api/?name=' . urlencode($comment->author_name ?? 'Guest') }}" 
                                             alt="{{ $comment->author_name ?? 'Guest' }}" 
                                             class="rounded-circle me-3" 
                                             width="48" height="48">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $comment->author_name ?? $comment->user->name ?? 'Guest' }}</h6>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div class="text-muted">
                                        {!! nl2br(e($comment->content)) !!}
                                    </div>
                                    
                                    {{-- Nested Replies --}}
                                    @if($comment->replies && $comment->replies->count() > 0)
                                        <div class="ms-5 mt-3 space-y-3">
                                            @foreach($comment->replies as $reply)
                                                <div class="card border-0 bg-white p-3">
                                                    <div class="d-flex align-items-start mb-2">
                                                        <img src="{{ $reply->user ? $reply->user->profile_photo_url : 'https://ui-avatars.com/api/?name=' . urlencode($reply->author_name ?? 'Guest') }}" 
                                                             alt="{{ $reply->author_name ?? 'Guest' }}" 
                                                             class="rounded-circle me-2" 
                                                             width="32" height="32">
                                                        <div>
                                                            <h6 class="fw-bold mb-0 small">{{ $reply->author_name ?? $reply->user->name ?? 'Guest' }}</h6>
                                                            <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="text-muted small">
                                                        {!! nl2br(e($reply->content)) !!}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 bg-light rounded">
                            <i class="ri-chat-3-line ri-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No comments yet. Be the first to comment!</p>
                        </div>
                    @endif

                    {{-- Comment Form (for logged in users) --}}
                    @auth
                    <div class="mt-5">
                        <h5 class="fw-bold mb-4">Leave a Comment</h5>
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="commentable_type" value="App\Models\Post">
                            <input type="hidden" name="commentable_id" value="{{ $post->id }}">
                            
                            <div class="mb-3">
                                <textarea name="content" 
                                          class="form-control @error('content') is-invalid @enderror" 
                                          rows="4" 
                                          placeholder="Share your thoughts..."
                                          required></textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-send-plane-line me-2"></i>Post Comment
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="mt-5 text-center py-4 bg-light rounded">
                        <p class="mb-3">Please <a href="{{ route('login') }}" class="fw-bold">login</a> to leave a comment.</p>
                    </div>
                    @endauth
                </div>
                @endif

            </div>
        </div>
    </div>
</article>

{{-- Related Posts --}}
@if($relatedPosts = $post->getRelatedPosts(3))
<section class="py-10 bg-light">
    <div class="container">
        <h3 class="fw-bold mb-8 text-center">Related Articles</h3>
        <div class="row g-5">
            @foreach($relatedPosts as $related)
            <div class="col-12 col-md-6 col-lg-4">
                <article class="card border-0 shadow-sm h-100 hover-lift transition-all">
                    @if($related->featured_image)
                    <a href="{{ url('/' . $related->slug) }}">
                        <img src="{{ $related->getImageUrl() }}" alt="{{ $related->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    </a>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title fw-bold">
                            <a href="{{ url('/' . $related->slug) }}" class="text-dark text-decoration-none hover-primary">
                                {{ $related->title }}
                            </a>
                        </h5>
                        @if($related->excerpt)
                        <p class="card-text text-muted small">{{ str($related->excerpt)->limit(100) }}</p>
                        @endif
                    </div>
                </article>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection


