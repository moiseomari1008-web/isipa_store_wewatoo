@volt
<div>
    <x-admin-layout title="Ajouter une Catégorie" subtitle="Créer une nouvelle catégorie">
        <div class="bg-white rounded-lg shadow p-8 max-w-2xl">
            <form wire:submit="store" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la catégorie</label>
                    <input type="text" wire:model="nom" placeholder="Ex: Vêtements" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea wire:model="description" rows="4" placeholder="Décrivez la catégorie..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Créer la catégorie
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </x-admin-layout>
</div>
@endvolt
