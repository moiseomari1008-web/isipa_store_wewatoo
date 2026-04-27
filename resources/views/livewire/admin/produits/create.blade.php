@volt
<div>
    <x-admin-layout title="Ajouter un Produit" subtitle="Créer un nouveau produit">
        <div class="bg-white rounded-lg shadow p-8 max-w-2xl">
            <form wire:submit="store" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom du produit</label>
                    <input type="text" wire:model="nom" placeholder="Ex: T-shirt Premium" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea wire:model="description" rows="4" placeholder="Décrivez le produit..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix</label>
                        <input type="number" wire:model="prix" placeholder="0.00" step="0.01" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('prix') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                        <input type="number" wire:model="stock" placeholder="0" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select wire:model="categorie_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Sélectionner une catégorie</option>
                    </select>
                    @error('categorie_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Créer le produit
                    </button>
                    <a href="{{ route('admin.produits.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </x-admin-layout>
</div>
@endvolt
