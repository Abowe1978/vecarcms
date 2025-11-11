<div class="space-y-6">
    <div>
        <h3 class="text-lg font-medium text-gray-900">{{ __('admin.members.sections') }}</h3>
        <p class="mt-1 text-sm text-gray-500">{{ __('admin.members.sections_description') }}</p>
    </div>

    <div class="space-y-4">
        <div>
            <label for="primary_section" class="block text-sm font-medium text-gray-700">
                {{ __('admin.members.primary_section') }}
            </label>
            <select wire:model.live="primarySectionId" wire:change="updatePrimarySection" id="primary_section"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="">{{ __('admin.members.select_primary_section') }}</option>
                @foreach($userSections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="border rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('admin.sections.name') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('admin.sections.city') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('admin.sections.actions') }}
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($sections as $section)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $section->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $section->city }}, {{ $section->country }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($userSections->contains($section->id))
                                <button wire:click="removeFromSection({{ $section->id }})"
                                        wire:confirm="{{ __('admin.sections.confirm_remove_member') }}"
                                        class="text-red-600 hover:text-red-900">
                                    {{ __('admin.sections.remove_member') }}
                                </button>
                            @else
                                <button wire:click="addToSection({{ $section->id }})"
                                        class="text-blue-600 hover:text-blue-900">
                                    {{ __('admin.sections.add_member') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 