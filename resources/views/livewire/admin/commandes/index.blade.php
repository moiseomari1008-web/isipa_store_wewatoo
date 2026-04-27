@volt
<?php

use Livewire\Volt\Component;
use App\Models\Commande;

new class extends Component {
    public string $search = '';
    public string $filtre = 'tous';

    #[Computed]
    public function commandes()
    {
        return Commande::with(['utilisateur', 'produits'])
            ->when($this->filtre !== 'tous', fn($q) => $q->where('statut', $this->filtre))
            ->when($this->search, fn($q) => $q->whereHas('utilisateur', fn($u) =>
                $u->where('nom_complet', 'like', "%{$this->search}%")
            ))
            ->latest()
            ->paginate(10);
    }

    public function changerStatut($id, $statut)
    {
        $commande = Commande::findOrFail($id);
        $commande->update(['statut' => $statut]);
        session()->flash('success', "Statut de la commande #{$commande->id} mis à jour.");
    }
}; ?>

<x-admin-layout title="Gestion des Commandes" subtitle="Suivre et gérer toutes les commandes">

    @if(session('success'))
        <div style="background:#f0fdf4;border-left:4px solid #22c55e;color:#166534;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.875rem;">✅ {{ session('success') }}</div>
    @endif

    {{-- Filtres --}}
    <div style="display:flex;gap:0.5rem;margin-bottom:1rem;flex-wrap:wrap;align-items:center;justify-content:space-between;">
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
            @foreach(['tous' => '🔵 Tous', 'en_attente' => '⏳ En attente', 'confirmee' => '✅ Confirmées', 'annulee' => '❌ Annulées'] as $val => $label)
            <button wire:click="$set('filtre','{{ $val }}')" id="filter-{{ $val }}"
                style="padding:0.4rem 0.85rem;border-radius:999px;font-size:0.8rem;font-weight:600;cursor:pointer;border:1px solid {{ $filtre === $val ? '#2563eb' : '#e2e8f0' }};background:{{ $filtre === $val ? '#dbeafe' : 'white' }};color:{{ $filtre === $val ? '#1d4ed8' : '#64748b' }};">
                {{ $label }}
            </button>
            @endforeach
        </div>
        <input wire:model.live.debounce.400ms="search" type="text" placeholder="Rechercher client..."
            id="search-commandes"
            style="padding:0.45rem 0.85rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.8rem;width:220px;">
    </div>

    <div style="background:white;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.06);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">#</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Client</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Total</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Statut</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Date</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->commandes as $commande)
                <tr style="border-top:1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                    <td style="padding:0.85rem 1.5rem;font-weight:700;color:#0f172a;">#{{ $commande->id }}</td>
                    <td style="padding:0.85rem 1.5rem;">
                        <p style="font-size:0.875rem;font-weight:600;color:#0f172a;">{{ $commande->utilisateur?->nom_complet ?? 'N/A' }}</p>
                        <p style="font-size:0.75rem;color:#94a3b8;">{{ $commande->utilisateur?->email }}</p>
                    </td>
                    <td style="padding:0.85rem 1.5rem;font-weight:700;color:#0f172a;">{{ number_format($commande->montant_total ?? 0, 0, ',', ' ') }} FCFA</td>
                    <td style="padding:0.85rem 1.5rem;">
                        @php
                            $statuts = [
                                'en_attente' => ['bg' => '#fef3c7', 'color' => '#92400e', 'txt' => '⏳ En attente'],
                                'confirmee'  => ['bg' => '#dcfce7', 'color' => '#166534', 'txt' => '✅ Confirmée'],
                                'annulee'    => ['bg' => '#fee2e2', 'color' => '#991b1b', 'txt' => '❌ Annulée'],
                                'livree'     => ['bg' => '#dbeafe', 'color' => '#1e40af', 'txt' => '🚚 Livrée'],
                            ];
                            $s = $statuts[$commande->statut] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'txt' => $commande->statut];
                        @endphp
                        <span style="background:{{ $s['bg'] }};color:{{ $s['color'] }};font-size:0.75rem;font-weight:600;padding:4px 10px;border-radius:999px;">
                            {{ $s['txt'] }}
                        </span>
                    </td>
                    <td style="padding:0.85rem 1.5rem;font-size:0.8rem;color:#64748b;">
                        {{ $commande->created_at?->format('d/m/Y H:i') }}
                    </td>
                    <td style="padding:0.85rem 1.5rem;">
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            @if($commande->statut === 'en_attente')
                            <button wire:click="changerStatut({{ $commande->id }}, 'confirmee')" id="btn-confirm-{{ $commande->id }}"
                                style="background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;font-size:0.72rem;font-weight:600;padding:3px 8px;border-radius:5px;cursor:pointer;">
                                ✅ Confirmer
                            </button>
                            <button wire:click="changerStatut({{ $commande->id }}, 'annulee')" id="btn-cancel-{{ $commande->id }}"
                                onclick="return confirm('Annuler cette commande ?')"
                                style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca;font-size:0.72rem;font-weight:600;padding:3px 8px;border-radius:5px;cursor:pointer;">
                                ❌ Annuler
                            </button>
                            @elseif($commande->statut === 'confirmee')
                            <button wire:click="changerStatut({{ $commande->id }}, 'livree')" id="btn-deliver-{{ $commande->id }}"
                                style="background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe;font-size:0.72rem;font-weight:600;padding:3px 8px;border-radius:5px;cursor:pointer;">
                                🚚 Livrer
                            </button>
                            @endif
                            <a href="{{ route('admin.commandes.show', $commande) }}" id="btn-show-{{ $commande->id }}"
                                style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;font-size:0.72rem;font-weight:600;padding:3px 8px;border-radius:5px;text-decoration:none;">
                                👁️ Détail
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:3rem;text-align:center;color:#94a3b8;">Aucune commande trouvée</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $this->commandes->links() }}</div>

</x-admin-layout>
@endvolt
