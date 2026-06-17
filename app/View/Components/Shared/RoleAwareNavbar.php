<?php

namespace App\View\Components\Shared;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RoleAwareNavbar extends Component
{
    public string $role;

    public $labs;

    public $user;

    /**
     * Create a new component instance.
     */
    public function __construct($labs = [], $user = null, $role = null)
    {
        $this->labs = $labs;
        $this->user = $user;

        // If role not provided, detect from auth user
        $this->role = $role ?? (auth()->user()?->Role_User ?? 'Mahasiswa');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shared.role-aware-navbar', [
            'role' => $this->role,
            'labs' => $this->labs,
            'user' => $this->user,
            'routePrefix' => $this->getRoutePrefix(),
        ]);
    }

    /**
     * Get route prefix based on role
     */
    private function getRoutePrefix(): string
    {
        return match ($this->role) {
            'Peneliti Eksternal' => 'peneliti-eksternal',
            'Mahasiswa' => 'mahasiswa',
            'Dosen' => 'dosen',
            'Laboran' => 'laboran',
            'Kepala Laboratorium' => 'kepala-lab',
            'Safety Officer' => 'safety-officer',
            'Kaprodi' => 'kaprodi',
            'Admin' => 'admin',
            default => 'mahasiswa',
        };
    }
}
