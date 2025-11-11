@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Edit Menu: {{ $menu->name }}</h1>
        <a href="{{ route('admin.menus.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Menus
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Menu Settings -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Menu Settings</h2>

                <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Menu Name *</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $menu->name) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500"
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <select name="location" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500">
                            <option value="">Select location...</option>
                            @foreach($locations as $key => $label)
                                <option value="{{ $key }}" {{ $menu->location === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" 
                                  rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500">{{ old('description', $menu->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ $menu->is_active ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-purple-600">
                            <span class="ml-2 text-sm">Active</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded">
                        <i class="fas fa-save mr-2"></i>Save Menu
                    </button>
                </form>

                <hr class="my-6">

                <!-- Add Menu Item Section -->
                <h3 class="text-lg font-semibold mb-3">Add Menu Item</h3>
                <div class="space-y-3">
                    <button onclick="showAddCustomLink()" class="w-full text-left px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded">
                        <i class="fas fa-link mr-2"></i>Custom Link
                    </button>
                    <button onclick="showAddPage()" class="w-full text-left px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded">
                        <i class="fas fa-file mr-2"></i>Page
                    </button>
                    <button onclick="showAddPost()" class="w-full text-left px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded">
                        <i class="fas fa-newspaper mr-2"></i>Post
                    </button>
                    <button onclick="showAddCategory()" class="w-full text-left px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded">
                        <i class="fas fa-folder mr-2"></i>Category
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Structure -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Menu Structure</h2>
                    <button onclick="saveMenuOrder()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                        <i class="fas fa-save mr-2"></i>Save Order
                    </button>
                </div>

                <div class="space-y-2" id="menu-items">
                    @foreach($menu->items->where('parent_id', null)->sortBy('order') as $item)
                        @include('admin.menus.partials.menu-item-draggable', ['item' => $item])
                    @endforeach
                </div>

                @if($menu->items->count() == 0)
                    <div class="text-center text-gray-400 py-12" id="empty-state">
                        <i class="fas fa-bars text-4xl mb-3"></i>
                        <p>This menu is empty. Add your first menu item!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Custom Link Modal -->
<div id="customLinkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold mb-4">Add Custom Link</h3>
        <form id="customLinkForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Link Text *</label>
                <input type="text" id="custom_title" class="w-full rounded-md border-gray-300" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">URL *</label>
                <input type="url" id="custom_url" class="w-full rounded-md border-gray-300" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('customLinkModal')" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded">Add Link</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Page Modal -->
<div id="pageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 max-h-[80vh] overflow-y-auto">
        <h3 class="text-xl font-semibold mb-4">Add Page to Menu</h3>
        <div class="space-y-2" id="page-list">
            @foreach($pages as $page)
                <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                    <input type="checkbox" value="{{ $page->id }}" class="page-checkbox mr-3">
                    <span>
                        {{ $page->title }}
                        @if($page->is_homepage)
                            <span class="ml-2 px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded">Homepage</span>
                        @endif
                        @if($page->is_blog)
                            <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded">Blog</span>
                        @endif
                    </span>
                </label>
            @endforeach
        </div>
        <div class="flex justify-end gap-2 mt-4 pt-4 border-t">
            <button type="button" onclick="closeModal('pageModal')" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
            <button type="button" onclick="addSelectedPages()" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded">Add to Menu</button>
        </div>
    </div>
</div>

<!-- Add Post Modal -->
<div id="postModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 max-h-[80vh] overflow-y-auto">
        <h3 class="text-xl font-semibold mb-4">Add Post to Menu</h3>
        <div class="space-y-2" id="post-list">
            @foreach($posts as $post)
                <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                    <input type="checkbox" value="{{ $post->id }}" class="post-checkbox mr-3">
                    <span>{{ $post->title }}</span>
                </label>
            @endforeach
        </div>
        <div class="flex justify-end gap-2 mt-4 pt-4 border-t">
            <button type="button" onclick="closeModal('postModal')" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
            <button type="button" onclick="addSelectedPosts()" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded">Add to Menu</button>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 max-h-[80vh] overflow-y-auto">
        <h3 class="text-xl font-semibold mb-4">Add Category to Menu</h3>
        <div class="space-y-2" id="category-list">
            @foreach($categories as $category)
                <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                    <input type="checkbox" value="{{ $category->id }}" class="category-checkbox mr-3">
                    <span>{{ $category->name }}</span>
                </label>
            @endforeach
        </div>
        <div class="flex justify-end gap-2 mt-4 pt-4 border-t">
            <button type="button" onclick="closeModal('categoryModal')" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
            <button type="button" onclick="addSelectedCategories()" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded">Add to Menu</button>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold mb-4">Edit Menu Item</h3>
        <form id="editItemForm">
            <input type="hidden" id="edit_item_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Navigation Label *</label>
                <input type="text" id="edit_title" class="w-full rounded-md border-gray-300" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                <input type="text" id="edit_url" class="w-full rounded-md border-gray-300">
                <p class="text-xs text-gray-500 mt-1">Leave empty for auto-generated URL</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Target</label>
                <select id="edit_target" class="w-full rounded-md border-gray-300">
                    <option value="_self">Same window/tab</option>
                    <option value="_blank">New window/tab</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">CSS Class</label>
                <input type="text" id="edit_css_class" class="w-full rounded-md border-gray-300">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                <input type="text" id="edit_icon" class="w-full rounded-md border-gray-300" placeholder="fa fa-home">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('editItemModal')" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded">Update</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
const menuId = {{ $menu->id }};
const csrfToken = '{{ csrf_token() }}';

// Initialize Sortable on menu items
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.getElementById('menu-items');
    if (menuItems) {
        new Sortable(menuItems, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'bg-blue-50',
            onEnd: function() {
                // Auto-save on drag end (optional)
                // saveMenuOrder();
            }
        });
    }
});

// Modal Functions
function showAddCustomLink() {
    document.getElementById('customLinkModal').classList.remove('hidden');
    document.getElementById('customLinkModal').classList.add('flex');
}

function showAddPage() {
    document.getElementById('pageModal').classList.remove('hidden');
    document.getElementById('pageModal').classList.add('flex');
}

function showAddPost() {
    document.getElementById('postModal').classList.remove('hidden');
    document.getElementById('postModal').classList.add('flex');
}

function showAddCategory() {
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('categoryModal').classList.add('flex');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
}

// Add Custom Link
document.getElementById('customLinkForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const title = document.getElementById('custom_title').value;
    const url = document.getElementById('custom_url').value;
    
    try {
        const response = await fetch(`/admin/menus/${menuId}/items`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                type: 'custom',
                title: title,
                url: url
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.toast.success(data.message || 'Item added successfully!');
            setTimeout(() => location.reload(), 1000);
        } else {
            window.toast.error(data.message || 'Error adding item');
        }
    } catch (error) {
        console.error('Error:', error);
        window.toast.error('Error adding item');
    }
});

// Add Selected Pages
async function addSelectedPages() {
    const checkboxes = document.querySelectorAll('.page-checkbox:checked');
    const pageIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (pageIds.length === 0) {
        window.toast.warning('Please select at least one page');
        return;
    }
    
    for (const pageId of pageIds) {
        try {
            await fetch(`/admin/menus/${menuId}/items`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    type: 'page',
                    object_id: pageId
                })
            });
        } catch (error) {
            console.error('Error:', error);
        }
    }
    
    location.reload();
}

// Add Selected Posts
async function addSelectedPosts() {
    const checkboxes = document.querySelectorAll('.post-checkbox:checked');
    const postIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (postIds.length === 0) {
        window.toast.warning('Please select at least one post');
        return;
    }
    
    for (const postId of postIds) {
        try {
            await fetch(`/admin/menus/${menuId}/items`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    type: 'post',
                    object_id: postId
                })
            });
        } catch (error) {
            console.error('Error:', error);
        }
    }
    
    location.reload();
}

// Add Selected Categories
async function addSelectedCategories() {
    const checkboxes = document.querySelectorAll('.category-checkbox:checked');
    const categoryIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (categoryIds.length === 0) {
        window.toast.warning('Please select at least one category');
        return;
    }
    
    for (const categoryId of categoryIds) {
        try {
            await fetch(`/admin/menus/${menuId}/items`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    type: 'category',
                    object_id: categoryId
                })
            });
        } catch (error) {
            console.error('Error:', error);
        }
    }
    
    location.reload();
}

// Edit Item
function editItem(itemId) {
    // Fetch item data
    const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
    const title = itemElement.querySelector('.item-title').textContent;
    const url = itemElement.querySelector('.item-url').textContent;
    
    document.getElementById('edit_item_id').value = itemId;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_url').value = url;
    
    document.getElementById('editItemModal').classList.remove('hidden');
    document.getElementById('editItemModal').classList.add('flex');
}

// Update Item
document.getElementById('editItemForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const itemId = document.getElementById('edit_item_id').value;
    const title = document.getElementById('edit_title').value;
    const url = document.getElementById('edit_url').value || null;
    const target = document.getElementById('edit_target').value;
    const cssClass = document.getElementById('edit_css_class').value;
    const icon = document.getElementById('edit_icon').value;
    
    try {
        const response = await fetch(`/admin/menus/${menuId}/items/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                title,
                url,
                target,
                css_class: cssClass,
                icon
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error updating item');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error updating item');
    }
});

// Delete Item
async function deleteItem(itemId) {
    if (!confirm('Are you sure you want to delete this menu item?')) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/menus/${menuId}/items/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error deleting item');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error deleting item');
    }
}

// Save Menu Order
async function saveMenuOrder() {
    const menuItems = document.getElementById('menu-items');
    
    if (!menuItems || menuItems.children.length === 0) {
        window.toast.warning('No menu items to save');
        return;
    }
    
    const items = Array.from(menuItems.children).map((item, index) => ({
        id: item.dataset.itemId,
        order: index
    }));
    
    console.log('Saving menu order:', items);
    
    try {
        const response = await fetch(`/admin/menus/${menuId}/reorder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ items })
        });
        
        console.log('Response status:', response.status);
        
        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.success) {
            window.toast.success(data.message || 'Menu order saved!');
        } else {
            console.error('Server error:', data);
            window.toast.error(data.message || 'Error saving order');
        }
    } catch (error) {
        console.error('Fetch error:', error);
        window.toast.error('Error saving order: ' + error.message);
    }
}
</script>
@endpush
@endsection

