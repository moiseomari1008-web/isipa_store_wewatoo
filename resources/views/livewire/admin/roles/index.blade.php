@volt
<?php

use Livewire\Volt\Component;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Attribution;

new class extends Component {
    public string $mode = 'list';
    public ?int $role_id = null;

    // Form
    public string $nom = '';
    public string $description = '';
    public array  $selected_permissions = [];

    #[Computed]
    public function roles()
    {
        return Role::with('permissions')->get();
    }

    #[Computed]
    public function toutes_permissions()
    {
        return Permission::all()->groupBy(function($p) {
            $parts = explode('_', $p->nom);
            return ucfirst($parts[count($parts)-1] ?? 'global');
        });
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->role_id = $role->id;
        $this->nom = $role->nom;
        $this->description = $role->description ?? '';
        $this->selected_permissions = $role->permissions->pluck('id')->toArray();
        $this->mode = 'edit';
    }

    public function update()
    {
        $this->validate([
            'nom'         => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $role = Role::findOrFail($this->role_id);
        $role->update(['nom' => $this->nom, 'description' => $this->description]);

        // Resync permissions
        Attribution::where('id_role', $role->id)->delete();
        foreach ($this->selected_permissions as $permId) {
            Attribution::create(['id_role' => $role->id, 'id_permission' => $permId]);
        }

        session()->flash('success', "Rôle « {$role->nom} » mis à jour.");
        $this->mode = 'list';
        $this->reset(['nom', 'description', 'selected_permissions', 'role_id']);
    }

    public function cancel()
    {
        $this->mode = 'list';
        $this->reset(['nom', 'description', 'selected_permissions', 'role_id']);
    }
}; ?>

<x-admin-layout title="Rôles & Permissions" subtitle="Gérer les droits d'accès">

    @if (session('success'))
        <div style="background:#f0fdf4;border-left:4px solid #22c55e;color:#166534;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.875rem;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if($mode === 'list')

        <div style="background:white;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.06);overflow:hidden;">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">Liste des Rôles</h3>
                <span style="font-size:0.8rem;color:#64748b;">{{ $this->roles->count() }} rôles</span>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.8rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Rôle</th>
                            <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.8rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Description</th>
                            <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.8rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Permissions</th>
                            <th style="padding:0.75rem 1.5rem;text-align:left;font-size:0.8rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->roles as $role)
                        <tr style="border-top:1px solid #f1f5f9;">
                            <td style="padding:1rem 1.5rem;">
                                <span style="
                                    background: {{ $role->nom === 'Super Admin' ? '#f3e8ff' : ($role->nom === 'Admin Articles' ? '#dbeafe' : ($role->nom === 'Admin Utilisateurs' ? '#dcfce7' : '#f1f5f9')) }};
                                    color: {{ $role->nom === 'Super Admin' ? '#7e22ce' : ($role->nom === 'Admin Articles' ? '#1d4ed8' : ($role->nom === 'Admin Utilisateurs' ? '#15803d' : '#475569')) }};
                                    font-size:0.8rem;font-weight:600;padding:4px 12px;border-radius:999px;
                                ">
                                    {{ $role->nom === 'Super Admin' ? '👑' : ($role->nom === 'Admin Articles' ? '📦' : ($role->nom === 'Admin Utilisateurs' ? '👥' : '🔵')) }}
                                    {{ $role->nom }}
                                </span>
                            </td>
                            <td style="padding:1rem 1.5rem;font-size:0.875rem;color:#64748b;">{{ $role->description ?? '—' }}</td>
                            <td style="padding:1rem 1.5rem;">
                                <div style="display:flex;flex-wrap:wrap;gap:4px;max-width:400px;">
                                    @foreach($role->permissions as $perm)
                                        <span style="background:#f1f5f9;color:#475569;font-size:0.7rem;padding:2px 8px;border-radius:999px;border:1px solid #e2e8f0;">
                                            {{ str_replace('_', ' ', $perm->nom) }}
                                        </span>
                                    @endforeach
                                    @if($role->permissions->isEmpty())
                                        <span style="color:#94a3b8;font-size:0.8rem;">Aucune</span>
                                    @endif
                                </div>
                            </td>
                            <td style="padding:1rem 1.5rem;">
                                @if(auth()->user()->isSuperAdmin())
                                <button wire:click="edit({{ $role->id }})" id="btn-edit-role-{{ $role->id }}"
                                    style="background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;font-size:0.8rem;font-weight:600;padding:5px 12px;border-radius:6px;cursor:pointer;">
                                    ✏️ Modifier
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else

        {{-- Edit role --}}
        <div style="background:white;border-radius:12px;padding:2rem;max-width:700px;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin-bottom:1.5rem;">✏️ Modifier le Rôle : {{ $nom }}</h3>

            <form wire:submit="update" style="display:flex;flex-direction:column;gap:1.25rem;">
                <div>
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Nom du rôle</label>
                    <input wire:model="nom" type="text" id="edit_role_name"
                        style="width:100%;padding:0.5rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;"
                        @if(!auth()->user()->isSuperAdmin()) disabled @endif
                    >
                    @error('nom') <span style="color:#ef4444;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Description</label>
                    <textarea wire:model="description" id="edit_role_desc"
                        style="width:100%;padding:0.5rem 0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;" rows="2"></textarea>
                </div>

                <div>
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:0.75rem;">
                        Permissions attribuées
                    </label>
                    @foreach($this->toutes_permissions as $groupe => $perms)
                        <div style="margin-bottom:1rem;">
                            <p style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.4rem;">
                                {{ $groupe }}
                            </p>
                            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                                @foreach($perms as $perm)
                                <label style="display:flex;align-items:center;gap:6px;font-size:0.8rem;cursor:pointer;background:#f8fafc;padding:5px 10px;border-radius:6px;border:1px solid #e2e8f0;">
                                    <input type="checkbox" wire:model="selected_permissions" value="{{ $perm->id }}" id="perm_{{ $perm->id }}">
                                    {{ str_replace('_', ' ', $perm->nom) }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="display:flex;gap:0.75rem;">
                    <button type="submit" id="btn-save-role"
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
