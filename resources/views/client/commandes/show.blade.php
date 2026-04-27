<x-layouts.app>
    <div class="space-y-6">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-zinc-500">Detail commande</p>
                    <h1 class="mt-3 text-3xl font-black text-zinc-900 dark:text-white">Commande #{{ $commande->id }}</h1>
                    <p class="mt-3 text-sm leading-7 text-zinc-600 dark:text-zinc-300">
                        Passee le {{ optional($commande->date_commande)->format('d/m/Y a H:i') }} avec le statut <strong>{{ $commande->statut }}</strong>.
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('client.commandes.index') }}" class="rounded-full border border-zinc-300 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-zinc-700 dark:border-zinc-700 dark:text-zinc-200">
                        Retour
                    </a>
                    @if (in_array($commande->statut, ['en attente', 'confirmé'], true))
                        <form method="POST" action="{{ route('client.commandes.cancel', $commande) }}">
                            @csrf
                            <button type="submit" class="rounded-full bg-rose-600 px-5 py-3 text-sm font-bold uppercase tracking-[0.2em] text-white">
                                Annuler
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if (session('client_success'))
            <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-4 text-sm text-emerald-600 dark:text-emerald-200">
                {{ session('client_success') }}
            </div>
        @endif

        @if (session('client_error'))
            <div class="rounded-2xl border border-rose-500/30 bg-rose-500/10 px-5 py-4 text-sm text-rose-600 dark:text-rose-200">
                {{ session('client_error') }}
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[1fr_0.8fr]">
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs uppercase tracking-[0.35em] text-zinc-500">Articles commandés</p>
                <div class="mt-6 space-y-4">
                    @foreach ($commande->commandeProduits as $ligne)
                        <div class="flex items-center justify-between gap-4 border-b border-zinc-200 pb-4 dark:border-zinc-800">
                            <div>
                                <p class="font-semibold text-zinc-900 dark:text-white">{{ $ligne->produit->nom }}</p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $ligne->quantite }} x {{ number_format((float) $ligne->produit->prix_unitaire, 2, ',', ' ') }}</p>
                            </div>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">
                                {{ number_format($ligne->quantite * (float) $ligne->produit->prix_unitaire, 2, ',', ' ') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <p class="text-xs uppercase tracking-[0.35em] text-zinc-500">Livraison</p>
                    <p class="mt-4 text-sm leading-7 text-zinc-700 dark:text-zinc-300">{{ $commande->adresse_livraison }}</p>
                </div>

                <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <p class="text-xs uppercase tracking-[0.35em] text-zinc-500">Paiement</p>
                    @if ($commande->paiement)
                        <div class="mt-4 space-y-3 text-sm text-zinc-700 dark:text-zinc-300">
                            <p><strong>Mode:</strong> {{ $commande->paiement->type_paiement }}</p>
                            <p><strong>Montant:</strong> {{ number_format((float) $commande->paiement->montant, 2, ',', ' ') }}</p>
                            <p><strong>Numero:</strong> {{ $commande->paiement->num_compte ?: 'Non renseigne' }}</p>
                            <p><strong>Reference:</strong> {{ $commande->paiement->reference_transaction ?: 'Non renseignee' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
