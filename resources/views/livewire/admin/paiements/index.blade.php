@volt
<?php

use Livewire\Volt\Component;
use App\Models\Paiement;
use App\Models\Commande;

new class extends Component {
    public string $filtre = 'tous';

    #[Computed]
    public function paiements()
    {
        return Paiement::with(['commande.utilisateur'])
            ->when($this->filtre !== 'tous', fn($q) => $q->where('statut', $this->filtre))
            ->latest()
            ->paginate(12);
    }

    public function valider($id)
    {
        $paiement = Paiement::findOrFail($id);
        $paiement->update(['statut' => 'valide']);
        // Confirmer la commande associée
        if ($paiement->commande) {
            $paiement->commande->update(['statut' => 'confirmee']);
        }
        session()->flash('success', "Paiement #{$id} validé avec succès.");
    }

    public function rejeter($id)
    {
        $paiement = Paiement::findOrFail($id);
        $paiement->update(['statut' => 'rejete']);
        session()->flash('success', "Paiement #{$id} rejeté.");
    }
}; ?>

<x-admin-layout title="Validation des Paiements" subtitle="Vérifier et valider les paiements clients">

    @if(session('success'))
        <div style="background:#f0fdf4;border-left:4px solid #22c55e;color:#166534;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.875rem;">✅ {{ session('success') }}</div>
    @endif

    {{-- Résumé --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
        @foreach([
            ['label'=>'En attente','key'=>'en_attente','bg'=>'#fef3c7','color'=>'#92400e','icon'=>'⏳'],
            ['label'=>'Validés','key'=>'valide','bg'=>'#dcfce7','color'=>'#166534','icon'=>'✅'],
            ['label'=>'Rejetés','key'=>'rejete','bg'=>'#fee2e2','color'=>'#991b1b','icon'=>'❌'],
        ] as $stat)
        <div style="background:white;border-radius:12px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);border:1px solid #f1f5f9;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <p style="font-size:0.8rem;color:#64748b;font-weight:500;">{{ $stat['label'] }}</p>
                    <p style="font-size:1.8rem;font-weight:800;color:#0f172a;">
                        {{ \App\Models\Paiement::where('statut', $stat['key'])->count() }}
                    </p>
                </div>
                <div style="background:{{ $stat['bg'] }};padding:0.7rem;border-radius:10px;font-size:1.3rem;">{{ $stat['icon'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filtres --}}
    <div style="display:flex;gap:0.5rem;margin-bottom:1rem;">
        @foreach(['tous'=>'Tous','en_attente'=>'⏳ En attente','valide'=>'✅ Validés','rejete'=>'❌ Rejetés'] as $val=>$label)
        <button wire:click="$set('filtre','{{ $val }}')" id="filter-paiement-{{ $val }}"
            style="padding:0.4rem 0.85rem;border-radius:999px;font-size:0.8rem;font-weight:600;cursor:pointer;border:1px solid {{ $filtre===$val ? '#2563eb':'#e2e8f0' }};background:{{ $filtre===$val ? '#dbeafe':'white' }};color:{{ $filtre===$val ? '#1d4ed8':'#64748b' }};">
            {{ $label }}
        </button>
        @endforeach
    </div>

    <div style="background:white;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.06);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">#</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Client</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Montant</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Mode</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Statut</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Date</th>
                    <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->paiements as $paiement)
                <tr style="border-top:1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                    <td style="padding:0.85rem 1.5rem;font-weight:700;color:#0f172a;">
                        #{{ $paiement->id }}<br>
                        <span style="font-size:0.72rem;color:#94a3b8;">Cmd #{{ $paiement->commande_id }}</span>
                    </td>
                    <td style="padding:0.85rem 1.5rem;font-size:0.875rem;">
                        <p style="font-weight:600;color:#0f172a;">{{ $paiement->commande?->utilisateur?->nom_complet ?? '—' }}</p>
                        <p style="color:#94a3b8;font-size:0.75rem;">{{ $paiement->commande?->utilisateur?->email }}</p>
                    </td>
                    <td style="padding:0.85rem 1.5rem;font-weight:700;color:#0f172a;">
                        {{ number_format($paiement->montant ?? 0, 0, ',', ' ') }} FCFA
                    </td>
                    <td style="padding:0.85rem 1.5rem;">
                        <span style="background:#f1f5f9;color:#475569;font-size:0.75rem;padding:3px 10px;border-radius:999px;font-weight:500;">
                            {{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement ?? 'N/A')) }}
                        </span>
                    </td>
                    <td style="padding:0.85rem 1.5rem;">
                        @php
                            $sp = [
                                'en_attente' => ['bg'=>'#fef3c7','color'=>'#92400e','txt'=>'⏳ En attente'],
                                'valide'     => ['bg'=>'#dcfce7','color'=>'#166534','txt'=>'✅ Validé'],
                                'rejete'     => ['bg'=>'#fee2e2','color'=>'#991b1b','txt'=>'❌ Rejeté'],
                            ];
                            $sv = $sp[$paiement->statut] ?? ['bg'=>'#f1f5f9','color'=>'#475569','txt'=>$paiement->statut];
                        @endphp
                        <span style="background:{{ $sv['bg'] }};color:{{ $sv['color'] }};font-size:0.75rem;font-weight:600;padding:4px 10px;border-radius:999px;">
                            {{ $sv['txt'] }}
                        </span>
                    </td>
                    <td style="padding:0.85rem 1.5rem;font-size:0.8rem;color:#64748b;">
                        {{ $paiement->created_at?->format('d/m/Y H:i') }}
                    </td>
                    <td style="padding:0.85rem 1.5rem;">
                        @if($paiement->statut === 'en_attente')
                        <div style="display:flex;gap:0.4rem;">
                            <button wire:click="valider({{ $paiement->id }})" id="btn-validate-pay-{{ $paiement->id }}"
                                style="background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;font-size:0.72rem;font-weight:600;padding:4px 10px;border-radius:5px;cursor:pointer;">
                                ✅ Valider
                            </button>
                            <button wire:click="rejeter({{ $paiement->id }})" id="btn-reject-pay-{{ $paiement->id }}"
                                onclick="return confirm('Rejeter ce paiement ?')"
                                style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca;font-size:0.72rem;font-weight:600;padding:4px 10px;border-radius:5px;cursor:pointer;">
                                ❌ Rejeter
                            </button>
                        </div>
                        @else
                            <span style="color:#94a3b8;font-size:0.8rem;">Traité</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:3rem;text-align:center;color:#94a3b8;">Aucun paiement trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $this->paiements->links() }}</div>

</x-admin-layout>
@endvolt
