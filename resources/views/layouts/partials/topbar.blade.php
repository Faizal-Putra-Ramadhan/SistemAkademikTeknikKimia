{{-- 
    Topbar partial - top navigation bar
    Used by layouts/app.blade.php
--}}
@php
    use Illuminate\Support\Facades\Auth;
    use App\Http\Controllers\LabSwitchController;
    $currentUser = Auth::user();
    $role = $currentUser->Role_User ?? '';
    $pageTitle = $pageTitle ?? View::yieldContent('page-title', 'Dashboard');
    
    // Role switcher
    $linkedAccounts = method_exists($currentUser, 'getAllLinkedAccounts') ? $currentUser->getAllLinkedAccounts() : collect();
    $hasLinkedAccounts = $linkedAccounts->count() > 1;
    $isSwitched = session()->has('role_switch_id');
    
    // Lab switcher untuk Laboran
    $labsForLaboran = collect();
    $activeLab = null;
    if ($role === 'Laboran' && $currentUser) {
        $labsForLaboran = LabSwitchController::getLabsForLaboran($currentUser);
        $activeLab = LabSwitchController::getActiveLab($currentUser);
    }
@endphp

<header class="app-topbar">
    <div class="topbar-left">
        <button class="btn-sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
        </button>
        <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="topbar-right">
        {{-- Lab Switcher untuk Laboran --}}
        @if($role === 'Laboran' && $labsForLaboran->count() > 1)
            <div class="lab-switcher hidden-mobile" style="margin-right: 12px;">
                <select id="lab-switcher-select" 
                        onchange="switchLab(this.value)" 
                        style="padding: 6px 12px; border: 1px solid #e5e7eb; border-radius: 6px; background: white; font-size: 13px; cursor: pointer; min-width: 180px;"
                        title="Pilih Laboratorium">
                    @foreach($labsForLaboran as $lab)
                        <option value="{{ $lab->id }}" {{ $activeLab && $activeLab->id == $lab->id ? 'selected' : '' }}>
                            🧪 {{ $lab->Nama_Laboratorium }} ({{ $lab->floor }} - {{ ucfirst($lab->lab_type) }})
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        {{-- Role Switcher Component -- HIDDEN untuk Mahasiswa & Peneliti Eksternal --}}
        @if($role !== 'Mahasiswa' && $role !== 'Peneliti Eksternal')
            <div class="role-switcher-topbar">
                <x-role-switcher :user="$currentUser" />
            </div>
        @endif

        {{-- User Info --}}
        <div class="topbar-user">
            <div class="topbar-avatar">
                @if($currentUser->foto && file_exists(public_path('uploads/profile/' . $currentUser->foto)))
                    <img src="{{ asset('uploads/profile/' . $currentUser->foto) }}" alt="" style="width:34px;height:34px;border-radius:50%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($currentUser->Nama ?? 'U', 0, 1)) }}
                @endif
            </div>
            <div class="topbar-user-info">
                <div class="topbar-user-name">{{ $currentUser->Nama ?? 'User' }}</div>
                <div class="topbar-user-role">{{ $role }}</div>
            </div>
        </div>
    </div>
</header>

@if($role === 'Laboran' && $labsForLaboran->count() > 1)
<script>
    function switchLab(labId) {
        if (!labId) return;
        
        // Redirect ke route switch lab dengan POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("laboran.lab.switch", ":id") }}'.replace(':id', labId);
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endif
