@props(['user'])

@php
    // HIDDEN untuk Mahasiswa - langsung return
    if ($user->Role_User === 'Mahasiswa') {
        return;
    }
    
    // Linked accounts (akun berbeda yang terhubung)
    $linkedAccounts = $user->getAllLinkedAccounts();
    $hasLinkedAccounts = $linkedAccounts->count() > 1;

    // Multi-role dalam satu akun (roles di user_roles)
    $roleNames = $user->roleNames ?? [$user->Role_User];
    $activeRole = $user->primaryRole()?->name ?? $user->Role_User;
    $hasMultipleRoles = count($roleNames) > 1;

    $isSwitched = session()->has('role_switch_id');

    // Warna & ikon per role (hex untuk inline style - Tailwind dynamic class tidak reliable)
    $roleConfigs = [
        'Dosen' => ['bg' => '#3b82f6', 'light' => '#93c5fd', 'icon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
        'Mahasiswa' => ['bg' => '#10b981', 'light' => '#6ee7b7', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        'Laboran' => ['bg' => '#f97316', 'light' => '#fdba74', 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.673.337a4 4 0 01-2.506.327l-1.623-.27a2 2 0 01-1.183-.7l-1.18-1.18a2 2 0 00-1.505-.583l-1.233.056c-.506.022-.883.473-.834.977l.08 1.4a2 2 0 00.99 1.568l2.03 1.1a2 2 0 001.634.027l2.856-1.16a2 2 0 011.156 0l2.623 1.076a2 2 0 001.565-.034l2.721-1.36a2 2 0 00.658-2.618l-1.159-2.033z'],
        'Kepala Laboratorium' => ['bg' => '#a855f7', 'light' => '#c084fc', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        'Safety Officer' => ['bg' => '#f43f5e', 'light' => '#fda4af', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
        'Admin' => ['bg' => '#6366f1', 'light' => '#a5b4fc', 'icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'],
        'Kaprodi' => ['bg' => '#ec4899', 'light' => '#f9a8d4', 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222'],
        'Peneliti Eksternal' => ['bg' => '#06b6d4', 'light' => '#67e8f9', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9'],
    ];
    $defaultConfig = ['bg' => '#64748b', 'light' => '#94a3b8', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'];
    $activeConfig = $roleConfigs[$activeRole] ?? $defaultConfig;
@endphp

<div class="relative inline-block text-left ml-2" x-data="{ open: false }">
    {{-- Trigger Button --}}
    <button
        @click="open = !open"
        type="button"
        class="group flex items-center gap-2.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm transition-all duration-200 hover:border-slate-300 hover:bg-slate-50 hover:shadow-sm active:scale-[0.97]"
    >
        {{-- Role Icon with colored background --}}
        <span
            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md text-white"
            style="background: {{ $activeConfig['bg'] }};"
        >
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $activeConfig['icon'] }}" />
            </svg>
        </span>

        {{-- Label & Role Name --}}
        <div class="min-w-0 leading-tight">
            <span class="block text-[10px] font-medium uppercase tracking-wide text-slate-400">Switch Role</span>
            <span class="block max-w-[120px] truncate text-[13px] font-semibold text-slate-700">{{ $activeRole }}</span>
        </div>

        {{-- Impersonating badge --}}
        @if($isSwitched)
            <span class="rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold leading-none text-amber-700">Switch</span>
        @endif

        {{-- Chevron --}}
        <svg class="h-4 w-4 shrink-0 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
        </svg>
    </button>

    {{-- Dropdown Panel --}}
    <div
        x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-xl border border-slate-200/80 bg-white shadow-lg"
        style="display: none; padding: 16px;"
    >
        {{-- Active Role Header --}}
        <div class="flex items-center gap-3 pb-3" style="border-bottom: 1px solid #f1f5f9; margin-bottom: 12px;">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-white"
                style="background: {{ $activeConfig['bg'] }};"
            >
                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $activeConfig['icon'] }}" />
                </svg>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Peran Aktif</p>
                <p class="truncate text-sm font-bold text-slate-800">{{ $activeRole }}</p>
            </div>
            @if($isSwitched)
                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-700">Switched</span>
            @endif
        </div>

        <div class="max-h-[55vh] overflow-y-auto">
            @if($hasMultipleRoles)
                <div style="margin-bottom: 6px;">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Ganti Peran</p>
                </div>

                @foreach($roleNames as $roleName)
                    @php
                        $isActive = $roleName === $activeRole;
                        $rc = $roleConfigs[$roleName] ?? $defaultConfig;
                    @endphp
                    <form method="POST" action="{{ route('role.switch-role') }}" style="margin-bottom: 4px;">
                        @csrf
                        <input type="hidden" name="role" value="{{ $roleName }}">
                        <button
                            type="submit"
                            {{ $isActive ? 'disabled' : '' }}
                            class="group flex w-full items-center gap-3 rounded-lg p-2 text-left text-sm transition-colors {{ $isActive ? 'cursor-default bg-slate-50' : 'hover:bg-slate-50' }}"
                        >
                            <span
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                style="background: {{ $rc['bg'] }}15; color: {{ $rc['bg'] }};"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $rc['icon'] }}" />
                                </svg>
                            </span>
                            <span class="min-w-0 flex-1 truncate font-medium text-slate-700">{{ $roleName }}</span>
                            @if($isActive)
                                <span
                                    class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-white"
                                    style="background: {{ $rc['bg'] }};"
                                >
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            @else
                                <svg class="h-4 w-4 shrink-0 text-slate-300 transition-colors group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            @endif
                        </button>
                    </form>
                @endforeach
            @endif

            @if($hasLinkedAccounts)
                @if($hasMultipleRoles)
                    <div style="border-top: 1px solid #f1f5f9; margin: 10px 0;"></div>
                @endif

                <div style="margin-bottom: 6px;">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Akun Terhubung</p>
                </div>

                @foreach($linkedAccounts as $linkedAccount)
                    @if($linkedAccount->id !== $user->id)
                        @php $lc = $roleConfigs[$linkedAccount->Role_User] ?? $defaultConfig; @endphp
                        <form method="POST" action="{{ route('role.switch', $linkedAccount->id) }}">
                            @csrf
                            <button type="submit" class="group flex w-full items-center gap-3 rounded-lg p-2 text-left text-sm transition-colors hover:bg-slate-50">
                                <span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full"
                                    style="background: {{ $lc['bg'] }}15; color: {{ $lc['bg'] }};"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $lc['icon'] }}" />
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <span class="block truncate font-medium text-slate-700">{{ $linkedAccount->Role_User }}</span>
                                    <span class="block truncate text-[11px] text-slate-400">{{ $linkedAccount->Email }}</span>
                                </div>
                                <svg class="h-4 w-4 shrink-0 text-slate-300 transition-colors group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </form>
                    @endif
                @endforeach
            @endif

            @if($isSwitched)
                <div style="border-top: 1px solid #f1f5f9; margin-top: 10px; padding-top: 10px;">
                    <form method="POST" action="{{ route('role.switch-back') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg py-2.5 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Akun Utama
                        </button>
                    </form>
                </div>
            @endif

            @if(!$hasMultipleRoles && !$hasLinkedAccounts && !$isSwitched)
                <div class="px-4 py-4 text-center">
                    <p class="text-sm text-slate-500">Anda hanya memiliki satu peran.</p>
                </div>
            @endif
        </div>
    </div>
</div>
