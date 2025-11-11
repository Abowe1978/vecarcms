@auth
{{-- WordPress-like Admin Bar (Framework Independent) --}}
<div class="admin-bar">
    <div class="admin-bar-container">
        <div class="admin-bar-flex">
            {{-- Left Side --}}
            <div class="admin-bar-left">
                {{-- VeCarCMS Logo --}}
                <a href="{{ route('admin.dashboard') }}" class="admin-bar-logo">
                    <i class="ri-dashboard-line"></i>
                    <span>VeCarCMS</span>
                </a>

                {{-- New Content Dropdown (Alpine.js) --}}
                <div class="admin-bar-dropdown" x-data="{ open: false }" @click.away="open = false">
                    <a class="admin-bar-item" href="#" @click.prevent="open = !open">
                        <i class="ri-add-line"></i>
                        <span>New</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </a>
                    <div x-show="open" x-transition class="admin-bar-dropdown-menu align-left" x-cloak>
                        <a href="{{ route('admin.posts.create') }}" class="admin-bar-dropdown-item">
                            <i class="ri-article-line"></i>
                            <span>New Post</span>
                        </a>
                        <a href="{{ route('admin.pages.create') }}" class="admin-bar-dropdown-item">
                            <i class="ri-file-text-line"></i>
                            <span>New Page</span>
                        </a>
                        <div class="admin-bar-dropdown-divider"></div>
                        <a href="{{ route('admin.posts.index') }}" class="admin-bar-dropdown-item">
                            <i class="ri-list-check"></i>
                            <span>All Posts</span>
                        </a>
                        <a href="{{ route('admin.pages.index') }}" class="admin-bar-dropdown-item">
                            <i class="ri-pages-line"></i>
                            <span>All Pages</span>
                        </a>
                        <a href="{{ route('admin.media.index') }}" class="admin-bar-dropdown-item">
                            <i class="ri-image-line"></i>
                            <span>Media Library</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right Side --}}
            <div class="admin-bar-right">
                {{-- Clear Cache --}}
                <form method="POST" action="{{ route('admin.clear-cache') }}" style="margin: 0; display: inline;">
                    @csrf
                    <button type="submit" class="admin-bar-item" style="border: none; cursor: pointer;" title="Clear Cache">
                        <i class="ri-delete-bin-line"></i>
                        <span class="admin-bar-mobile-hide">Clear Cache</span>
                    </button>
                </form>

                {{-- View Site --}}
                <a href="{{ url('/') }}" class="admin-bar-item admin-bar-mobile-hide" target="_blank">
                    <i class="ri-eye-line"></i>
                    <span>View Site</span>
                </a>

                {{-- User Menu (Alpine.js) --}}
                <div class="admin-bar-dropdown" x-data="{ open: false }" @click.away="open = false">
                    <a class="admin-bar-item" href="#" @click.prevent="open = !open">
                        <i class="ri-user-line"></i>
                        <span>{{ auth()->user()->name }}</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </a>
                    <div x-show="open" x-transition class="admin-bar-dropdown-menu align-right" x-cloak>
                        <a href="{{ route('admin.profile.show') }}" class="admin-bar-dropdown-item">
                            <i class="ri-user-settings-line"></i>
                            <span>Profile</span>
                        </a>
                        <div class="admin-bar-dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="admin-bar-dropdown-item" style="width: 100%; text-align: left;">
                                <i class="ri-logout-box-line"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Load Remix Icon --}}
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
@endauth
