<x-layouts.app>
    <div class="space-y-6">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-xs uppercase tracking-[0.35em] text-zinc-500">Commandes</p>
            <h1 class="mt-3 text-3xl font-black text-zinc-900 dark:text-white">Mon historique</h1>
            <p class="mt-3 text-sm leading-7 text-zinc-600 dark:text-zinc-300">
                Retrouvez toutes vos commandes, leur statut et les details du paiement choisi.
            </p>
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

        <div class="space-y-4">
            @forelse ($commandes as $commande)
                <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm font-bold text-zinc-900 dark:text-white">Commande #{{ $commande->id }}</p>
                            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ optional($commande->date_commande)->format('d/m/Y H:i') }} ·
                                {{ $commande->commandeProduits->sum('quantite') }} article(s)
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                {{ $commande->statut }}
                            </span>
                            <a href="{{ route('client.commandes.show', $commande) }}" class="rounded-full border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 dark:border-zinc-700 dark:text-zinc-200">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-zinc-300 bg-white p-10 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400">
                    Vous n'avez pas encore passe de commande.
                </div>
            @endforelse
        </div>

        @if ($commandes->hasPages())
            <div class="rounded-3xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                {{ $commandes->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
