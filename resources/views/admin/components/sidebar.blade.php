<!-- VeCarCMS Sidebar -->
<aside class="sidebar transition-all duration-300 ease-in-out bg-gray-900 text-white h-screen flex flex-col"
    :class="{
        'expanded': sidebarOpen && !isMobile,
        'collapsed': !sidebarOpen && !isMobile,
        'mobile-open': sidebarOpen && isMobile,
        '-translate-x-full': !sidebarOpen && isMobile
    }">
    
    <!-- Logo -->
    <div class="flex items-center h-16 px-4 bg-gray-800 flex-shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
            <span class="text-xl font-semibold transition-opacity duration-300"
                  :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                {{ __('admin.sidebar.title') }}
            </span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="px-4 py-4 flex-1 overflow-y-auto">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Dashboard
                    </span>
                </a>
            </li>

            <!-- CONTENT MANAGEMENT -->
            <li class="mt-6">
                <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider"
                     :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                    Content
                </div>
            </li>

            @can('manage_posts')
            <li>
                <a href="{{ route('admin.posts.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.posts.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-newspaper w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Posts
                    </span>
                </a>
            </li>
            @endcan

            @can('manage_pages')
            <li>
                <a href="{{ route('admin.pages.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.pages.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Pages
                    </span>
                </a>
            </li>
            @endcan

            @can('manage_categories')
            <li>
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-folder w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Categories
                    </span>
                </a>
            </li>
            @endcan

            @can('manage_tags')
            <li>
                <a href="{{ route('admin.tags.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.tags.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-tags w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Tags
                    </span>
                </a>
            </li>
            @endcan

            @can('manage_media')
            <li>
                <a href="{{ route('admin.media.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.media.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-images w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Media Library
                    </span>
                </a>
            </li>
            @endcan

            @can('manage_comments')
            <li>
                <a href="{{ route('admin.comments.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.comments.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-comments w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Comments
                    </span>
                </a>
            </li>
            @endcan

            <!-- DESIGN & APPEARANCE -->
            <li class="mt-6">
                <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider"
                     :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                    Design
                </div>
            </li>

            @can('manage_menus')
            <li>
                <a href="{{ route('admin.menus.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.menus.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-bars w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Menus
                    </span>
                </a>
            </li>
            @endcan

            @can('manage_widgets')
            <li>
                <a href="{{ route('admin.widgets.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.widgets.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-th-large w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Widgets
                    </span>
                </a>
            </li>
            @endcan

            @can('manage_themes')
            <li>
                <a href="{{ route('admin.themes.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.themes.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-paint-brush w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Themes
                    </span>
                </a>
            </li>
            @endcan

            @can('manage_plugins')
            <li>
                <a href="{{ route('admin.plugins.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.plugins.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-plug w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Plugins
                    </span>
                </a>
            </li>
            @endcan

            <!-- USERS & PERMISSIONS -->
            <li class="mt-6">
                <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider"
                     :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                    Users
                </div>
            </li>

            @can('manage_users')
            <li>
                <a href="{{ route('admin.admins.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.admins.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Administrators
                    </span>
                </a>
            </li>
            @endcan

            @can('manage_roles')
            <li>
                <a href="{{ route('admin.roles.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.roles.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-user-shield w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Roles & Permissions
                    </span>
                </a>
            </li>
            @endcan

            <!-- TOOLS -->
            <li class="mt-6">
                <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider"
                     :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                    Tools
                </div>
            </li>

            @can('view_submissions')
            <li>
                <a href="{{ route('admin.contact.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.contact.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-envelope w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Contact Forms
                    </span>
                </a>
            </li>
            @endcan

            @can('manage_seo')
            <li>
                <a href="{{ route('admin.seo.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.seo.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-search w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        SEO Tools
                    </span>
                </a>
            </li>
            @endcan

            <!-- SETTINGS -->
            <li class="mt-6">
                <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider"
                     :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                    System
                </div>
            </li>

            <li>
                <a href="{{ route('admin.settings.index') }}" 
                   class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-cog w-5"></i>
                    <span class="ml-3 transition-opacity duration-300"
                          :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                        Settings
                    </span>
                </a>
            </li>

        </ul>
    </nav>

    <!-- User Profile at Bottom -->
    <div class="px-4 py-4 border-t border-gray-800">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center">
                <i class="fas fa-user text-gray-400"></i>
            </div>
            <div class="flex-1 transition-opacity duration-300"
                 :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                <div class="text-xs text-gray-400">{{ auth()->user()->getRoleNames()->first() }}</div>
            </div>
        </div>
    </div>
</aside>
