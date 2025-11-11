@props(['selectedCategories' => collect()])

<div class="space-y-2">
    @foreach(\App\Models\Category::orderBy('name')->get() as $category)
        <div class="flex items-center">
            <input type="checkbox" 
                   name="categories[]" 
                   id="category-{{ $category->id }}" 
                   value="{{ $category->id }}"
                   {{ $selectedCategories->contains($category->id) ? 'checked' : '' }}
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            <label for="category-{{ $category->id }}" class="ml-2 flex items-center">
                <div class="w-3 h-3 rounded mr-2" style="background-color: {{ $category->color }}"></div>
                <span class="text-sm text-gray-700">{{ $category->name }}</span>
            </label>
        </div>
    @endforeach
</div> 