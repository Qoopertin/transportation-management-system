<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Load') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('loads.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="pickup_address" class="block text-sm font-medium text-gray-700">Pickup Address</label>
                            <textarea id="pickup_address" name="pickup_address" rows="2" class="input" required>{{ old('pickup_address') }}</textarea>
                            @error('pickup_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="delivery_address" class="block text-sm font-medium text-gray-700">Delivery Address</label>
                            <textarea id="delivery_address" name="delivery_address" rows="2" class="input" required>{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="pickup_at" class="block text-sm font-medium text-gray-700">Pickup Date/Time</label>
                                <input type="datetime-local" id="pickup_at" name="pickup_at" class="input" value="{{ old('pickup_at') }}">
                                @error('pickup_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="delivery_at" class="block text-sm font-medium text-gray-700">Delivery Date/Time</label>
                                <input type="datetime-local" id="delivery_at" name="delivery_at" class="input" value="{{ old('delivery_at') }}">
                                @error('delivery_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="assigned_driver_id" class="block text-sm font-medium text-gray-700">Assign Driver (Optional)</label>
                            <select id="assigned_driver_id" name="assigned_driver_id" class="input">
                                <option value="">-- Select Driver --</option>
                                @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('assigned_driver_id') == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('assigned_driver_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="input">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="btn">Create Load</button>
                            <a href="{{ route('loads.index') }}" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
