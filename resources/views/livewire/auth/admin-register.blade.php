<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name     = '';
    public string $email    = '';
    public string $telephone = '';
    public string $adresse  = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int   $role_id  = null;
    public string $code_secret = '';

    // Code secret admin (à changer en production!)
    const ADMIN_SECRET = 'ISIPA2025ADMIN';

    public function adminRoles()
    {
        return Role::whereIn('nom', ['Super Admin', 'Admin Articles', 'Admin Utilisateurs'])->get();
    }

    public function registerAdmin(): void
    {
        $validated = $this->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'telephone' => ['nullable', 'string', 'max:30'],
            'adresse'   => ['nullable', 'string', 'max:500'],
            'password'  => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role_id'   => ['required', 'exists:roles,id'],
            'code_secret' => ['required', 'string'],
        ]);

        if ($validated['code_secret'] !== self::ADMIN_SECRET) {
            $this->addError('code_secret', 'Code secret invalide. Contactez le Super Administrateur.');
            return;
        }

        $role = Role::findOrFail($validated['role_id']);
        // Seuls les rôles admin autorisés
        if (!in_array($role->nom, ['Super Admin', 'Admin Articles', 'Admin Utilisateurs'])) {
            $this->addError('role_id', 'Rôle non autorisé.');
            return;
        }

        $user = User::create([
            'nom_complet'  => $validated['name'],
            'email'        => $validated['email'],
            'mot_de_passe' => Hash::make($validated['password']),
            'telephone'    => $validated['telephone'] ?: null,
            'adresse'      => $validated['adresse'] ?: null,
            'id_role'      => $validated['role_id'],
        ]);

        event(new Registered($user));
        Auth::login($user);

        $this->redirect(route('admin.dashboard', absolute: false), navigate: true);
    }
};
?>

<div class="flex flex-col gap-6">
    <x-auth-header
        title="Inscription Administrateur"
        description="Créez un compte administrateur avec votre rôle spécifique"
    />

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="registerAdmin" class="flex flex-col gap-5">

        {{-- Nom complet --}}
        <div class="grid gap-2">
            <flux:input
                wire:model="name"
                id="admin_name"
                label="Nom complet *"
                type="text"
                name="name"
                required
                autofocus
                placeholder="Prénom et Nom"
            />
        </div>

        {{-- Email --}}
        <div class="grid gap-2">
            <flux:input
                wire:model="email"
                id="admin_email"
                label="Adresse email *"
                type="email"
                name="email"
                required
                placeholder="admin@isipa.cd"
            />
        </div>

        {{-- Telephone --}}
        <div class="grid gap-2">
            <flux:input
                wire:model="telephone"
                id="admin_telephone"
                label="Téléphone"
                type="text"
                name="telephone"
                placeholder="+243 000 000 000"
            />
        </div>

        {{-- Adresse --}}
        <div class="grid gap-2">
            <flux:textarea
                wire:model="adresse"
                id="admin_adresse"
                label="Adresse"
                name="adresse"
                rows="2"
                placeholder="Adresse complète"
            />
        </div>

        {{-- Sélection du Rôle --}}
        <div class="grid gap-2">
            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Rôle Administrateur *
            </label>
            <select
                wire:model="role_id"
                id="admin_role_id"
                name="role_id"
                required
                class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="">-- Sélectionner votre rôle --</option>
                @foreach($this->adminRoles() as $role)
                    <option value="{{ $role->id }}">
                        {{ $role->nom }}
                        @if($role->nom === 'Super Admin') — Tous les droits
                        @elseif($role->nom === 'Admin Articles') — Produits & Commandes
                        @elseif($role->nom === 'Admin Utilisateurs') — Utilisateurs & Rôles
                        @endif
                    </option>
                @endforeach
            </select>
            @error('role_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Description du rôle sélectionné --}}
        <div x-data="{
            roleId: @entangle('role_id'),
            roles: {
                '': '',
                'Super Admin': '👑 Accès complet : publication, rôles, produits, commandes, livraisons, paiements, utilisateurs, paramètres, boutique.',
                'Admin Articles': '📦 Accès : tableau de bord, produits, catégories, commandes, paiements, livraisons, boutique.',
                'Admin Utilisateurs': '👥 Accès : tableau de bord, utilisateurs, attribution des rôles, commandes, boutique.'
            }
        }">
            @foreach($this->adminRoles() as $role)
                <div
                    x-show="roleId == '{{ $role->id }}'"
                    class="p-3 rounded-lg text-sm
                        {{ $role->nom === 'Super Admin' ? 'bg-purple-50 border border-purple-200 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300' : '' }}
                        {{ $role->nom === 'Admin Articles' ? 'bg-blue-50 border border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300' : '' }}
                        {{ $role->nom === 'Admin Utilisateurs' ? 'bg-green-50 border border-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300' : '' }}
                    "
                >
                    <strong>{{ $role->nom }}</strong> — {{ $role->description }}
                </div>
            @endforeach
        </div>

        {{-- Mot de passe --}}
        <div class="grid gap-2">
            <flux:input
                wire:model="password"
                id="admin_password"
                label="Mot de passe *"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Mot de passe sécurisé"
            />
        </div>

        {{-- Confirmation --}}
        <div class="grid gap-2">
            <flux:input
                wire:model="password_confirmation"
                id="admin_password_confirmation"
                label="Confirmer le mot de passe *"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Répéter le mot de passe"
            />
        </div>

        {{-- Code secret --}}
        <div class="grid gap-2">
            <flux:input
                wire:model="code_secret"
                id="admin_code_secret"
                label="Code secret administrateur *"
                type="password"
                name="code_secret"
                required
                placeholder="Code fourni par le Super Admin"
            />
            @error('code_secret')
                <span class="text-red-500 text-sm font-medium">⚠️ {{ $message }}</span>
            @enderror
            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                Ce code est requis pour créer un compte administrateur. Contactez votre Super Administrateur pour l'obtenir.
            </p>
        </div>

        <flux:button type="submit" variant="primary" class="w-full" id="btn_admin_register">
            🔐 Créer mon compte administrateur
        </flux:button>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        Vous avez déjà un compte ?
        <x-text-link href="{{ route('login') }}">Se connecter</x-text-link>
    </div>
    <div class="text-center text-sm text-zinc-600 dark:text-zinc-400">
        Pas un administrateur ?
        <x-text-link href="{{ route('register') }}">Inscription client</x-text-link>
    </div>
</div>
