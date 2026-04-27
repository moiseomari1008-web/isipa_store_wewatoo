@volt
<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $nom_site         = 'ISIPA Store';
    public string $description_site = 'Boutique officielle de l\'ISIPA';
    public string $email_contact    = 'contact@isipa.cd';
    public string $telephone        = '+243 000 000 000';
    public string $adresse_site     = 'Kinshasa, RDC';
    public string $devise           = 'FCFA';
    public bool   $maintenance      = false;
    public bool   $inscriptions     = true;

    public function mount()
    {
        // Charger depuis la config ou la base de données si disponible
        $this->nom_site = config('app.name', 'ISIPA Store');
    }

    public function sauvegarder()
    {
        $this->validate([
            'nom_site'         => 'required|string|max:100',
            'description_site' => 'nullable|string|max:500',
            'email_contact'    => 'nullable|email|max:100',
            'telephone'        => 'nullable|string|max:30',
            'adresse_site'     => 'nullable|string|max:300',
            'devise'           => 'required|string|max:10',
        ]);

        // En production : enregistrer en base ou fichier .env via Artisan
        session()->flash('success', 'Paramètres sauvegardés avec succès.');
    }
}; ?>

<x-admin-layout title="Paramètres du Site" subtitle="Configuration générale de la plateforme">

    @if(session('success'))
        <div style="background:#f0fdf4;border-left:4px solid #22c55e;color:#166534;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.875rem;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div style="max-width:800px;display:flex;flex-direction:column;gap:1.5rem;">

        {{-- Informations générales --}}
        <div style="background:white;border-radius:12px;padding:1.75rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin-bottom:1.25rem;border-bottom:1px solid #f1f5f9;padding-bottom:0.75rem;">
                🏪 Informations Générales
            </h3>
            <form wire:submit="sauvegarder" style="display:flex;flex-direction:column;gap:1rem;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Nom du site *</label>
                        <input wire:model="nom_site" type="text" id="site_name"
                            style="width:100%;padding:0.55rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                        @error('nom_site') <span style="color:#ef4444;font-size:0.75rem;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Devise</label>
                        <select wire:model="devise" id="site_devise"
                            style="width:100%;padding:0.55rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;background:white;">
                            <option value="FCFA">FCFA (Franc CFA)</option>
                            <option value="USD">USD (Dollar)</option>
                            <option value="EUR">EUR (Euro)</option>
                            <option value="CDF">CDF (Franc Congolais)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Description</label>
                    <textarea wire:model="description_site" id="site_description"
                        style="width:100%;padding:0.55rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;" rows="3"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Email de contact</label>
                        <input wire:model="email_contact" type="email" id="site_email"
                            style="width:100%;padding:0.55rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Téléphone</label>
                        <input wire:model="telephone" type="text" id="site_telephone"
                            style="width:100%;padding:0.55rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                    </div>
                </div>
                <div>
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Adresse</label>
                    <input wire:model="adresse_site" type="text" id="site_adresse"
                        style="width:100%;padding:0.55rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                </div>
                <button type="submit" id="btn-save-params"
                    style="background:linear-gradient(135deg,#2563eb,#7c3aed);color:white;font-weight:600;font-size:0.875rem;padding:0.65rem 1.5rem;border:none;border-radius:8px;cursor:pointer;width:fit-content;">
                    💾 Sauvegarder les paramètres
                </button>
            </form>
        </div>

        {{-- Options système --}}
        <div style="background:white;border-radius:12px;padding:1.75rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin-bottom:1.25rem;border-bottom:1px solid #f1f5f9;padding-bottom:0.75rem;">
                ⚙️ Options Système
            </h3>
            <div style="display:flex;flex-direction:column;gap:1rem;">

                <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem;background:#f8fafc;border-radius:10px;border:1px solid #f1f5f9;">
                    <div>
                        <p style="font-weight:600;color:#0f172a;font-size:0.9rem;">🔧 Mode Maintenance</p>
                        <p style="color:#64748b;font-size:0.8rem;margin-top:2px;">Le site sera inaccessible aux visiteurs</p>
                    </div>
                    <label style="position:relative;display:inline-flex;align-items:center;cursor:pointer;">
                        <input type="checkbox" wire:model.live="maintenance" id="toggle-maintenance" style="sr-only:true;opacity:0;position:absolute;">
                        <div style="
                            width:44px;height:24px;border-radius:12px;transition:background 0.2s;
                            background:{{ $maintenance ? '#dc2626' : '#d1d5db' }};
                            display:flex;align-items:center;padding:2px;
                        ">
                            <div style="width:20px;height:20px;background:white;border-radius:50%;transition:transform 0.2s;transform:{{ $maintenance ? 'translateX(20px)' : 'translateX(0)' }};box-shadow:0 1px 3px rgba(0,0,0,0.2);"></div>
                        </div>
                    </label>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem;background:#f8fafc;border-radius:10px;border:1px solid #f1f5f9;">
                    <div>
                        <p style="font-weight:600;color:#0f172a;font-size:0.9rem;">📝 Inscriptions ouvertes</p>
                        <p style="color:#64748b;font-size:0.8rem;margin-top:2px;">Permettre aux nouveaux clients de s'inscrire</p>
                    </div>
                    <label style="position:relative;display:inline-flex;align-items:center;cursor:pointer;">
                        <input type="checkbox" wire:model.live="inscriptions" id="toggle-inscriptions" style="opacity:0;position:absolute;">
                        <div style="
                            width:44px;height:24px;border-radius:12px;transition:background 0.2s;
                            background:{{ $inscriptions ? '#22c55e' : '#d1d5db' }};
                            display:flex;align-items:center;padding:2px;
                        ">
                            <div style="width:20px;height:20px;background:white;border-radius:50%;transition:transform 0.2s;transform:{{ $inscriptions ? 'translateX(20px)' : 'translateX(0)' }};box-shadow:0 1px 3px rgba(0,0,0,0.2);"></div>
                        </div>
                    </label>
                </div>

            </div>
        </div>

        {{-- Infos système --}}
        <div style="background:white;border-radius:12px;padding:1.75rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin-bottom:1.25rem;border-bottom:1px solid #f1f5f9;padding-bottom:0.75rem;">
                📋 Informations Système
            </h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;font-size:0.85rem;">
                @foreach([
                    ['Laravel', app()->version()],
                    ['PHP', phpversion()],
                    ['Environnement', app()->environment()],
                    ['Base de données', config('database.default')],
                    ['Timezone', config('app.timezone')],
                    ['Locale', config('app.locale')],
                ] as [$label, $val])
                <div style="display:flex;justify-content:space-between;padding:0.6rem 1rem;background:#f8fafc;border-radius:8px;">
                    <span style="color:#64748b;font-weight:500;">{{ $label }}</span>
                    <span style="font-weight:700;color:#0f172a;font-family:monospace;">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</x-admin-layout>
@endvolt
