@volt
<?php

use Livewire\Volt\Component;
use App\Models\Produit;
use App\Models\CategorieProduit;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public ?int $produit_id = null;
    public string $mode = 'list';

    // Form fields
    public string $nom = '';
    public string $description = '';
    public float $prix = 0;
    public int $stock = 0;
    public ?int $categorie_id = null;

    #[Computed]
    public function produits()
    {
        return Produit::where('nom', 'like', "%{$this->search}%")
            ->paginate(10);
    }

    #[Computed]
    public function categories()
    {
        return CategorieProduit::all();
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255|min:3',
            'description' => 'required|string|min:10',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categorie_produits,id',
        ];
    }

    public function store()
    {
        $this->validate();
        Produit::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'prix' => $this->prix,
            'stock' => $this->stock,
            'id_categorie' => $this->categorie_id,
        ]);
        session()->flash('success', 'Produit créé avec succès!');
        $this->resetForm();
        $this->mode = 'list';
    }

    public function edit($id)
    {
        $produit = Produit::findOrFail($id);
        $this->produit_id = $produit->id;
        $this->nom = $produit->nom;
        $this->description = $produit->description;
        $this->prix = $produit->prix;
        $this->stock = $produit->stock;
        $this->categorie_id = $produit->id_categorie;
        $this->mode = 'edit';
    }

    public function update()
    {
        $this->validate();
        $produit = Produit::findOrFail($this->produit_id);
        $produit->update([
            'nom' => $this->nom,
            'description' => $this->description,
            'prix' => $this->prix,
            'stock' => $this->stock,
            'id_categorie' => $this->categorie_id,
        ]);
        session()->flash('success', 'Produit mis à jour avec succès!');
        $this->resetForm();
        $this->mode = 'list';
    }

    public function delete($id)
    {
        Produit::findOrFail($id)->delete();
        session()->flash('success', 'Produit supprimé avec succès!');
    }

    public function resetForm()
    {
        $this->nom = '';
        $this->description = '';
        $this->prix = 0;
        $this->stock = 0;
        $this->categorie_id = null;
        $this->produit_id = null;
        $this->resetValidation();
    }

    public function cancel()
    {
        $this->resetForm();
        $this->mode = 'list';
    }
}
?>

<x-admin-layout title="Gestion des Produits" subtitle="Gérer l'inventaire des produits">
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($mode === 'list')
        <div class="mb-6 flex justify-between items-center">
            <input 
                type="text" 
                placeholder="Rechercher un produit..." 
                wire:model.live.debounce.500ms="search"
                class="px-4 py-2 border rounded-lg w-64 focus:ring-2 focus:ring-blue-500"
            >
            @if(auth()->user()->hasPermission('gerer_produits') || auth()->user()->isSuperAdmin())
            <button 
                wire:click="$set('mode', 'create')"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
            >
                + Ajouter un produit
            </button>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nom</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Catégorie</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Prix</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Stock</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->produits as $produit)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="font-medium">{{ $produit->nom }}</p>
                                <p class="text-gray-600 text-sm">{{ Str::limit($produit->description, 50) }}</p>
                            </td>
                            <td class="px-6 py-3">{{ $produit->categorie?->nom ?? 'N/A' }}</td>
                            <td class="px-6 py-3 font-semibold">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</td>
                            <td class="px-6 py-3">
                                <span class="px-3 py-1 rounded-full text-sm {{ $produit->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $produit->stock }} unités
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <button wire:click="edit({{ $produit->id }})" class="text-blue-600 text-sm hover:underline">✏️ Éditer</button>
                                <button wire:click="delete({{ $produit->id }})" onclick="return confirm('Sûr?')" class="text-red-600 text-sm ml-2 hover:underline">🗑️ Supprimer</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Aucun produit trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $this->produits->links() }}</div>

    @else
        <div class="bg-white rounded-lg shadow p-8 max-w-2xl">
            <h3 class="text-xl font-bold mb-6">{{ $mode === 'create' ? 'Ajouter un Produit' : 'Éditer le Produit' }}</h3>
            <form wire:submit="{{ $mode === 'create' ? 'store' : 'update' }}" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                    <input type="text" wire:model="nom" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('nom') ? 'border-red-500' : '' }}">
                    @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea wire:model="description" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('description') ? 'border-red-500' : '' }}"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix *</label>
                        <input type="number" wire:model="prix" step="0.01" min="0" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('prix') ? 'border-red-500' : '' }}">
                        @error('prix') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                        <input type="number" wire:model="stock" min="0" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('stock') ? 'border-red-500' : '' }}">
                        @error('stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                    <select wire:model="categorie_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('categorie_id') ? 'border-red-500' : '' }}">
                        <option value="">Sélectionner</option>
                        @foreach($this->categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                        @endforeach
                    </select>
                    @error('categorie_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        {{ $mode === 'create' ? 'Créer' : 'Mettre à jour' }}
                    </button>
                    <button type="button" wire:click="cancel" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    @endif
</x-admin-layout>
@endvolt
