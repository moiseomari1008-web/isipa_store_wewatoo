@volt
<?php

use Livewire\Volt\Component;
use App\Models\Commande;
use App\Models\CommandeProduit;

new class extends Component {
    public int $commande_id;

    public function mount($commande)
    {
        $this->commande_id = $commande;
    }

    #[Computed]
    public function commande()
    {
        return Commande::with(['utilisateur', 'produits.produit', 'paiement'])->findOrFail($this->commande_id);
    }
}; ?>

<x-admin-layout title="Détail Commande" subtitle="Vue complète de la commande">

    <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;max-width:1100px;">

        {{-- Bloc gauche --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Infos commande --}}
            <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;border-bottom:1px solid #f1f5f9;padding-bottom:0.75rem;">
                    <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">Commande #{{ $this->commande->id }}</h3>
                    @php
                        $statuts = [
                            'en_attente' => ['bg'=>'#fef3c7','color'=>'#92400e','txt'=>'⏳ En attente'],
                            'confirmee'  => ['bg'=>'#dcfce7','color'=>'#166534','txt'=>'✅ Confirmée'],
                            'annulee'    => ['bg'=>'#fee2e2','color'=>'#991b1b','txt'=>'❌ Annulée'],
                            'livree'     => ['bg'=>'#dbeafe','color'=>'#1e40af','txt'=>'🚚 Livrée'],
                        ];
                        $s = $statuts[$this->commande->statut] ?? ['bg'=>'#f1f5f9','color'=>'#475569','txt'=>$this->commande->statut];
                    @endphp
                    <span style="background:{{ $s['bg'] }};color:{{ $s['color'] }};font-size:0.8rem;font-weight:600;padding:5px 12px;border-radius:999px;">
                        {{ $s['txt'] }}
                    </span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;font-size:0.875rem;">
                    <div><span style="color:#64748b;">Date :</span> <strong>{{ $this->commande->created_at?->format('d/m/Y H:i') }}</strong></div>
                    <div><span style="color:#64748b;">Total :</span> <strong>{{ number_format($this->commande->montant_total ?? 0, 0, ',', ' ') }} FCFA</strong></div>
                    <div><span style="color:#64748b;">Statut livraison :</span> <strong>{{ $this->commande->statut_livraison ?? '—' }}</strong></div>
                </div>
            </div>

            {{-- Produits commandés --}}
            <div style="background:white;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.06);overflow:hidden;">
                <div style="padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;">
                    <h3 style="font-size:0.95rem;font-weight:700;color:#0f172a;">🛒 Produits commandés</h3>
                </div>
                <table style="width:100%;border-collapse:collapse;">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th style="padding:0.6rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Produit</th>
                            <th style="padding:0.6rem 1.5rem;text-align:center;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Qté</th>
                            <th style="padding:0.6rem 1.5rem;text-align:right;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Prix unit.</th>
                            <th style="padding:0.6rem 1.5rem;text-align:right;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->commande->produits as $ligne)
                        <tr style="border-top:1px solid #f1f5f9;">
                            <td style="padding:0.75rem 1.5rem;font-size:0.875rem;font-weight:500;color:#0f172a;">{{ $ligne->produit?->nom ?? '—' }}</td>
                            <td style="padding:0.75rem 1.5rem;text-align:center;font-size:0.875rem;color:#64748b;">{{ $ligne->quantite }}</td>
                            <td style="padding:0.75rem 1.5rem;text-align:right;font-size:0.875rem;color:#64748b;">{{ number_format($ligne->prix_unitaire ?? 0, 0, ',', ' ') }} FCFA</td>
                            <td style="padding:0.75rem 1.5rem;text-align:right;font-size:0.875rem;font-weight:700;color:#0f172a;">{{ number_format(($ligne->prix_unitaire ?? 0) * $ligne->quantite, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="padding:2rem;text-align:center;color:#94a3b8;">Aucun produit</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot style="background:#f8fafc;border-top:2px solid #e2e8f0;">
                        <tr>
                            <td colspan="3" style="padding:0.85rem 1.5rem;font-weight:700;color:#0f172a;text-align:right;">Total</td>
                            <td style="padding:0.85rem 1.5rem;font-weight:800;color:#0f172a;text-align:right;font-size:1.05rem;">
                                {{ number_format($this->commande->montant_total ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Bloc droit : client + paiement --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Client --}}
            <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                <h3 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:1rem;">👤 Client</h3>
                @php $client = $this->commande->utilisateur; @endphp
                @if($client)
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                    <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#60a5fa,#a78bfa);display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;color:white;">
                        {{ $client->initials() }}
                    </div>
                    <div>
                        <p style="font-weight:700;color:#0f172a;font-size:0.9rem;">{{ $client->nom_complet }}</p>
                        <p style="color:#64748b;font-size:0.8rem;">{{ $client->email }}</p>
                    </div>
                </div>
                <div style="font-size:0.8rem;display:flex;flex-direction:column;gap:0.4rem;">
                    <div><span style="color:#94a3b8;">📞</span> {{ $client->telephone ?? '—' }}</div>
                    <div><span style="color:#94a3b8;">📍</span> {{ $client->adresse ?? '—' }}</div>
                </div>
                @else
                <p style="color:#94a3b8;font-size:0.875rem;">Client inconnu</p>
                @endif
            </div>

            {{-- Paiement --}}
            <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                <h3 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:1rem;">💳 Paiement</h3>
                @if($this->commande->paiement)
                @php $p = $this->commande->paiement; @endphp
                <div style="font-size:0.85rem;display:flex;flex-direction:column;gap:0.5rem;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#64748b;">Mode :</span>
                        <strong>{{ ucfirst(str_replace('_', ' ', $p->mode_paiement ?? '—')) }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#64748b;">Montant :</span>
                        <strong>{{ number_format($p->montant ?? 0, 0, ',', ' ') }} FCFA</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:#64748b;">Statut :</span>
                        @php
                            $sp = ['en_attente'=>'#fef3c7:#92400e:⏳','valide'=>'#dcfce7:#166534:✅','rejete'=>'#fee2e2:#991b1b:❌'];
                            $parts = explode(':', $sp[$p->statut] ?? '#f1f5f9:#475569:?');
                        @endphp
                        <span style="background:{{ $parts[0] }};color:{{ $parts[1] }};font-size:0.72rem;font-weight:600;padding:3px 8px;border-radius:999px;">
                            {{ $parts[2] }} {{ ucfirst($p->statut) }}
                        </span>
                    </div>
                </div>
                @else
                <p style="color:#94a3b8;font-size:0.875rem;">Aucun paiement enregistré</p>
                @endif
            </div>

            {{-- Retour --}}
            <a href="{{ route('admin.commandes.index') }}" id="btn-back-commandes"
                style="display:flex;align-items:center;justify-content:center;gap:0.5rem;background:white;border:1px solid #e2e8f0;color:#475569;font-weight:600;font-size:0.85rem;padding:0.7rem;border-radius:10px;text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                ← Retour aux commandes
            </a>
        </div>
    </div>

</x-admin-layout>
@endvolt
