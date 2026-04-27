@volt
<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Role;

new class extends Component {
    public string $search = '';
    public string $mode   = 'list';
    public ?int   $user_id = null;

    // Form
    public string $nom_complet = '';
    public string $email       = '';
    public string $telephone   = '';
    public string $adresse     = '';
    public ?int   $id_role     = null;
    public string $password    = '';

    #[Computed]
    public function users()
    {
        return User::with('role')
            ->where(function($q) {
                $q->where('nom_complet', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(12);
    }

    #[Computed]
    public function roles()
    {
        return Role::all();
    }

    public function edit($id)
    {
        $user = User::with('role')->findOrFail($id);
        $this->user_id    = $user->id;
        $this->nom_complet = $user->nom_complet;
        $this->email      = $user->email;
        $this->telephone  = $user->telephone ?? '';
        $this->adresse    = $user->adresse ?? '';
        $this->id_role    = $user->id_role;
        $this->mode       = 'edit';
    }

    public function update()
    {
        $this->validate([
            'nom_complet' => 'required|string|max:255',
            'email'       => 'required|email|unique:utilisateurs,email,' . $this->user_id,
            'telephone'   => 'nullable|string|max:30',
            'adresse'     => 'nullable|string|max:500',
            'id_role'     => 'required|exists:roles,id',
        ]);
        $user = User::findOrFail($this->user_id);
        $user->update([
            'nom_complet' => $this->nom_complet,
            'email'       => $this->email,
            'telephone'   => $this->telephone ?: null,
            'adresse'     => $this->adresse ?: null,
            'id_role'     => $this->id_role,
        ]);
        if ($this->password) {
            $user->update(['mot_de_passe' => \Hash::make($this->password)]);
        }
        session()->flash('success', "Utilisateur « {$user->nom_complet} » mis à jour.");
        $this->mode = 'list';
        $this->resetForm();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            return;
        }
        $name = $user->nom_complet;
        $user->delete();
        session()->flash('success', "Utilisateur « {$name} » supprimé.");
    }

    public function cancel()
    {
        $this->mode = 'list';
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['nom_complet','email','telephone','adresse','id_role','password','user_id']);
    }
}; ?>

<x-admin-layout title="Gestion des Utilisateurs" subtitle="Voir et modifier les comptes">

    @if (session('success'))
        <div style="background:#f0fdf4;border-left:4px solid #22c55e;color:#166534;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.875rem;">✅ {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div style="background:#fef2f2;border-left:4px solid #ef4444;color:#991b1b;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.875rem;">❌ {{ session('error') }}</div>
    @endif

    @if($mode === 'list')

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <input wire:model.live.debounce.400ms="search" id="search-users" type="text" placeholder="Rechercher par nom ou email..."
                style="padding:0.5rem 0.85rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;width:280px;">
            <span style="font-size:0.8rem;color:#64748b;">{{ $this->users->total() }} utilisateurs</span>
        </div>

        <div style="background:white;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.06);overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Nom</th>
                        <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Email</th>
                        <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Rôle</th>
                        <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Téléphone</th>
                        @if(auth()->user()->hasPermission('gerer_utilisateurs') || auth()->user()->isSuperAdmin())
                        <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->users as $user)
                    <tr style="border-top:1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                        <td style="padding:0.85rem 1.5rem;">
                            <div style="display:flex;align-items:center;gap:0.6rem;">
                                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#60a5fa,#a78bfa);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;color:white;flex-shrink:0;">
                                    {{ $user->initials() }}
                                </div>
                                <span style="font-size:0.875rem;font-weight:600;color:#0f172a;">{{ $user->nom_complet }}</span>
                            </div>
                        </td>
                        <td style="padding:0.85rem 1.5rem;font-size:0.875rem;color:#64748b;">{{ $user->email }}</td>
                        <td style="padding:0.85rem 1.5rem;">
                            @if($user->role)
                                <span style="background:#f1f5f9;color:#475569;font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:999px;border:1px solid #e2e8f0;">
                                    {{ $user->role->nom }}
                                </span>
                            @else
                                <span style="color:#94a3b8;font-size:0.8rem;">—</span>
                            @endif
                        </td>
                        <td style="padding:0.85rem 1.5rem;font-size:0.875rem;color:#64748b;">{{ $user->telephone ?? '—' }}</td>
                        @if(auth()->user()->hasPermission('gerer_utilisateurs') || auth()->user()->isSuperAdmin())
                        <td style="padding:0.85rem 1.5rem;">
                            <div style="display:flex;gap:0.5rem;">
                                <button wire:click="edit({{ $user->id }})" id="btn-edit-user-{{ $user->id }}"
                                    style="background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;font-size:0.75rem;font-weight:600;padding:4px 10px;border-radius:6px;cursor:pointer;">
                                    ✏️
                                </button>
                                @if($user->id !== auth()->id())
                                <button wire:click="delete({{ $user->id }})" onclick="return confirm('Supprimer cet utilisateur ?')" id="btn-del-user-{{ $user->id }}"
                                    style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;font-size:0.75rem;font-weight:600;padding:4px 10px;border-radius:6px;cursor:pointer;">
                                    🗑️
                                </button>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:3rem;text-align:center;color:#94a3b8;font-size:0.875rem;">Aucun utilisateur trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">{{ $this->users->links() }}</div>

    @else

        <div style="background:white;border-radius:12px;padding:2rem;max-width:600px;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin-bottom:1.5rem;">✏️ Modifier l'utilisateur</h3>
            <form wire:submit="update" style="display:flex;flex-direction:column;gap:1rem;">
                <div>
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Nom complet *</label>
                    <input wire:model="nom_complet" type="text" id="edit_user_name"
                        style="width:100%;padding:0.5rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;">
                    @error('nom_complet') <span style="color:#ef4444;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Email *</label>
                    <input wire:model="email" type="email" id="edit_user_email"
                        style="width:100%;padding:0.5rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;">
                    @error('email') <span style="color:#ef4444;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Téléphone</label>
                    <input wire:model="telephone" type="text" id="edit_user_telephone"
                        style="width:100%;padding:0.5rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;">
                </div>
                <div>
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Adresse</label>
                    <textarea wire:model="adresse" id="edit_user_adresse"
                        style="width:100%;padding:0.5rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;" rows="2"></textarea>
                </div>
                @if(auth()->user()->hasPermission('attribuer_roles') || auth()->user()->isSuperAdmin())
                <div>
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Rôle *</label>
                    <select wire:model="id_role" id="edit_user_role"
                        style="width:100%;padding:0.5rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;background:white;">
                        <option value="">-- Sélectionner --</option>
                        @foreach($this->roles as $role)
                            <option value="{{ $role->id }}">{{ $role->nom }}</option>
                        @endforeach
                    </select>
                    @error('id_role') <span style="color:#ef4444;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                @endif
                <div>
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Nouveau mot de passe (optionnel)</label>
                    <input wire:model="password" type="password" id="edit_user_password"
                        style="width:100%;padding:0.5rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;" placeholder="Laisser vide pour ne pas changer">
                </div>
                <div style="display:flex;gap:0.75rem;padding-top:0.5rem;">
                    <button type="submit" id="btn-save-user"
                        style="background:#2563eb;color:white;font-weight:600;font-size:0.875rem;padding:0.6rem 1.5rem;border:none;border-radius:8px;cursor:pointer;">
                        💾 Enregistrer
                    </button>
                    <button type="button" wire:click="cancel"
                        style="background:#f1f5f9;color:#475569;font-weight:600;font-size:0.875rem;padding:0.6rem 1.5rem;border:1px solid #e2e8f0;border-radius:8px;cursor:pointer;">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    @endif

</x-admin-layout>
@endvolt
