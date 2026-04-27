@volt
<div>
    <x-admin-layout title="Gestion des Catégories" subtitle="Voir et gérer toutes les catégories">
        <div class="mb-6 flex justify-between items-center">
            <input type="text" placeholder="Rechercher une catégorie..." wire:model.live="search" class="px-4 py-2 border rounded-lg w-64">
            @if(auth()->user()->hasPermission('gerer_categories') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + Ajouter une catégorie
            </a>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nom</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Produits</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b hover:bg-gray-50">
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Aucune catégorie trouvée
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-admin-layout>
</div>
@endvolt
