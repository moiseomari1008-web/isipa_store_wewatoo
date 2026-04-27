@volt
<?php

use Livewire\Volt\Component;
use App\Models\Commande;

new class extends Component {
    public string $filtre = 'tous';

    #[Computed]
    public function livraisons()
    {
        return Commande::with(['utilisateur'])
            ->whereNotNull('statut_livraison')
            ->when($this->filtre !== 'tous', fn($q) => $q->where('statut_livraison', $this->filtre))
            ->latest()
            ->paginate(12);
    }

    public function updateLivraison($id, $statut)
    {
        $commande = Commande::findOrFail($id);
        $commande->update(['statut_livraison' => $statut]);
        session()->flash('success', "Statut de livraison de la commande #{$id} mis à jour.");
    }
}; ?>

<x-admin-layout title="Gestion des Livraisons" subtitle="Suivre et gérer les expéditions">

    @if(session('success'))
        <div style="background:#f0fdf4;border-left:4px solid #22c55e;color:#166534;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.875rem;">✅ {{ session('success') }}</div>
    @endif

    {{-- Stats rapides --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
        @foreach([
            ['label'=>'En cours','key'=>'en_cours','bg'=>'#fef3c7','color'=>'#92400e','icon'=>'🚚'],
            ['label'=>'Livrées','key'=>'livree','bg'=>'#dcfce7','color'=>'#166534','icon'=>'✅'],
            ['label'=>'En attente','key'=>'en_attente','bg'=>'#dbeafe','color'=>'#1e40af','icon'=>'⏳'],
        ] as $stat)
        <div style="background:white;border-radius:12px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <p style="font-size:0.8rem;color:#64748b;font-weight:500;">{{ $stat['label'] }}</p>
                    <p style="font-size:1.8rem;font-weight:800;color:#0f172a;">
                        {{ \App\Models\Commande::where('statut_livraison', $stat['key'])->count() }}
                    </p>
                </div>
                <div style="background:{{ $stat['bg'] }};padding:0.7rem;border-radius:10px;font-size:1.3rem;">{{ $stat['icon'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filtres --}}
    <div style="display:flex;gap:0.5rem;margin-bottom:1rem;">
        @foreach(['tous'=>'Tous','en_attente'=>'⏳ En attente','en_cours'=>'🚚 En cours','livree'=>'✅ Livrées'] as $val=>$label)
        <button wire:click="$set('filtre','{{ $val }}')" id="filter-livraison-{{ $val }}"
            style="padding:0.4rem 0.85rem;border-radius:999px;font-size:0.8rem;font-weight:600;cursor:pointer;border:1px solid {{ $filtre===$val ? '#2563eb':'#e2e8f0' }};background:{{ $filtre===$val ? '#dbeafe':'white' }};color:{{ $filtre===$val ? '#1d4ed8':'#64748b' }};">
            {{ $label }}
        </button>
        @endforeach
    </div>

    <div style="background:white;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.06);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Commande</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Client</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Adresse</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Statut Livraison</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->livraisons as $commande)
                <tr style="border-top:1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                    <td style="padding:0.85rem 1.5rem;font-weight:700;color:#0f172a;">#{{ $commande->id }}</td>
                    <td style="padding:0.85rem 1.5rem;">
                        <p style="font-size:0.875rem;font-weight:600;color:#0f172a;">{{ $commande->utilisateur?->nom_complet ?? '—' }}</p>
                        <p style="font-size:0.75rem;color:#94a3b8;">{{ $commande->utilisateur?->telephone ?? '' }}</p>
                    </td>
                    <td style="padding:0.85rem 1.5rem;font-size:0.8rem;color:#64748b;max-width:200px;">
                        {{ $commande->adresse_livraison ?? $commande->utilisateur?->adresse ?? '—' }}
                    </td>
                    <td style="padding:0.85rem 1.5rem;">
                        @php
                            $sl = [
                                'en_attente' => ['bg'=>'#dbeafe','color'=>'#1e40af','txt'=>'⏳ En attente'],
                                'en_cours'   => ['bg'=>'#fef3c7','color'=>'#92400e','txt'=>'🚚 En cours'],
                                'livree'     => ['bg'=>'#dcfce7','color'=>'#166534','txt'=>'✅ Livrée'],
                            ];
                            $sv = $sl[$commande->statut_livraison] ?? ['bg'=>'#f1f5f9','color'=>'#475569','txt'=>$commande->statut_livraison ?? '—'];
                        @endphp
                        <span style="background:{{ $sv['bg'] }};color:{{ $sv['color'] }};font-size:0.75rem;font-weight:600;padding:4px 10px;border-radius:999px;">
                            {{ $sv['txt'] }}
                        </span>
                    </td>
                    <td style="padding:0.85rem 1.5rem;">
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            @if($commande->statut_livraison !== 'en_cours')
                            <button wire:click="updateLivraison({{ $commande->id }}, 'en_cours')" id="btn-start-del-{{ $commande->id }}"
                                style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;font-size:0.72rem;font-weight:600;padding:3px 8px;border-radius:5px;cursor:pointer;">
                                🚚 Expédier
                            </button>
                            @endif
                            @if($commande->statut_livraison !== 'livree')
                            <button wire:click="updateLivraison({{ $commande->id }}, 'livree')" id="btn-done-del-{{ $commande->id }}"
                                style="background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;font-size:0.72rem;font-weight:600;padding:3px 8px;border-radius:5px;cursor:pointer;">
                                ✅ Livrée
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:3rem;text-align:center;color:#94a3b8;">Aucune livraison à afficher</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $this->livraisons->links() }}</div>

</x-admin-layout>
@endvolt
