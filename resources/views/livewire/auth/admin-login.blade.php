<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();
        if (!$user->isAdmin()) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => "Accès refusé. Espace sécurisé réservé aux administrateurs.",
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirect(route('admin.dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Connexion Administrateur" description="Entrez vos identifiants pour accéder à l'espace sécurisé" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input wire:model="email" label="Adresse Email" type="email" name="email" required autofocus autocomplete="email" placeholder="admin@isipa.cd" />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                label="Mot de passe"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Mot de passe"
            />
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" label="Se souvenir de moi" />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full bg-blue-600 hover:bg-blue-700">Connexion Sécurisée</flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        Nouveau collaborateur ?
        <x-text-link href="{{ route('admin.register') }}">Créer un compte Admin</x-text-link>
    </div>
    <div class="text-center text-sm text-zinc-600 dark:text-zinc-400">
        Retourner à l'espace client ?
        <x-text-link href="{{ route('login') }}">Connexion Client</x-text-link>
    </div>
</div>
