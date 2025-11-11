<div class="space-y-4">
    <!-- Make Selection -->
    <div>
        <label for="make" class="block text-sm font-medium text-gray-700">{{ __('admin.vehicles.make') }}</label>
        <select wire:model.live="selectedMake" name="make" id="make" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
            <option value="">{{ __('admin.vehicles.select_make') }}</option>
            @foreach($makes as $make)
                <option value="{{ $make->id }}">{{ $make->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Model Selection -->
    <div>
        <label for="model" class="block text-sm font-medium text-gray-700">{{ __('admin.vehicles.model') }}</label>
        <select wire:model.live="selectedModel" name="model" id="model" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" {{ empty($selectedMake) ? 'disabled' : '' }}>
            <option value="">{{ __('admin.vehicles.select_model') }}</option>
            @foreach($models as $model)
                <option value="{{ $model }}">{{ $model }}</option>
            @endforeach
        </select>
    </div>
    <input type="hidden" name="model" value="{{ $selectedModel }}">
</div>
