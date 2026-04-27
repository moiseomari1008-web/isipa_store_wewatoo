<?php

use App\Models\Panier;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $telephone = '';
    public string $adresse = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'telephone' => ['nullable', 'string', 'max:30'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $roleClient = Role::firstOrCreate(
            ['nom' => 'Client'],
            ['description' => 'Role attribue automatiquement aux clients de la boutique.']
        );

        $user = User::create([
            'nom_complet' => $validated['name'],
            'email' => $validated['email'],
            'mot_de_passe' => Hash::make($validated['password']),
            'telephone' => $validated['telephone'] ?: null,
            'adresse' => $validated['adresse'] ?: null,
            'id_role' => $roleClient->id,
        ]);

        Panier::firstOrCreate([
            'id_utilisateur' => $user->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('client.boutique', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Create an account" description="Enter your details below to create your account" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <div class="grid gap-2">
            <flux:input wire:model="name" id="name" label="{{ __('Name') }}" type="text" name="name" required autofocus autocomplete="name" placeholder="Full name" />
        </div>

        <!-- Email Address -->
        <div class="grid gap-2">
            <flux:input wire:model="email" id="email" label="{{ __('Email address') }}" type="email" name="email" required autocomplete="email" placeholder="email@example.com" />
        </div>

        <div class="grid gap-2">
            <flux:input wire:model="telephone" id="telephone" label="{{ __('Telephone') }}" type="text" name="telephone" autocomplete="tel" placeholder="+243..." />
        </div>

        <div class="grid gap-2">
            <flux:textarea wire:model="adresse" id="adresse" label="{{ __('Adresse') }}" name="adresse" rows="3" placeholder="Votre adresse complete"></flux:textarea>
        </div>

        <!-- Password -->
        <div class="grid gap-2">
            <flux:input
                wire:model="password"
                id="password"
                label="{{ __('Password') }}"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Password"
            />
        </div>

        <!-- Confirm Password -->
        <div class="grid gap-2">
            <flux:input
                wire:model="password_confirmation"
                id="password_confirmation"
                label="{{ __('Confirm password') }}"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Confirm password"
            />
        </div>

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        Already have an account?
        <x-text-link href="{{ route('login') }}">Log in</x-text-link>
    </div>
</div>
