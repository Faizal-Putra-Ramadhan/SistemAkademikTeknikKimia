@props(['labs', 'user'])
@php
    // pastikan selalu collection
    $labs = collect($labs ?? []);

    // Ambil lab pertama
    $currentLab = $labs->first();

    // Jika ada param id, pilih lab sesuai id
    if (request()->route('id')) {
        $currentLab = $labs->firstWhere('id', request()->route('id')) ?? $currentLab;
    }

    // fallback kalau tetap null
    $currentLab = $currentLab ?? (object)[
        'id' => 0,
        'nama_lab' => 'Tidak ada lab'
    ];
@endphp

<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <div class="shrink-0">
            <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" class="size-8" />
          </div>
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              
            
             
              <x-dosen.nav-link href="{{ route('dosen.dashboard', $currentLab->id) }}" 
              :active="request()->routeIs('dosen.dashboard')">Dashboard</x-dosen.nav-link>

              <x-dosen.nav-link href="{{ route('dosen.pinjam-ruangan', $currentLab->id) }}" 
              :active="request()->routeIs('dosen.pinjam-ruangan')">Pinjam Ruangan</x-dosen.nav-link>

              <x-dosen.nav-link href="{{ route('dosen.pinjam-alat', $currentLab->id) }}" 
              :active="request()->routeIs('dosen.pinjam-alat')">Pinjam Alat</x-dosen.nav-link>

              <x-dosen.nav-link href="{{ route('dosen.pengumuman.index', $currentLab->id) }}" 
              :active="request()->routeIs('dosen.pengumuman.index')">Kelola Pengumuman</x-dosen.nav-link>

              <x-dosen.nav-link href="{{ route('dosen.risk-assessment.index', $currentLab->id) }}" 
              :active="request()->routeIs('dosen.risk-assessment.index')">Risk Assessment</x-dosen.nav-link>

            

        
              
           
            </div>
          </div>
        </div>
        <div class="hidden md:block">
          <div class="ml-4 flex items-center md:ml-6">
            
            <!-- Role Switcher Component (NEW!) -->
            <x-role-switcher :user="$user" />

            <button type="button" class="relative rounded-full p-1 text-gray-600 hover:text-gray-900 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
              <span class="absolute -inset-1.5"></span>
              <span class="sr-only">View notifications</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>

            <!-- Profile dropdown -->
            <el-dropdown class="relative ml-3">
              <button class="relative flex max-w-xs items-center rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                <span class="absolute -inset-1.5"></span>
                <span class="sr-only">Open user menu</span>
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-8 rounded-full outline -outline-offset-1 outline-gray-200" />
              </button>

              <el-menu anchor="bottom end" popover class="w-48 origin-top-right rounded-md bg-white py-1 outline-1 -outline-offset-1 outline-gray-200 shadow-lg transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in">
                <a href="{{ route('dosen.profil', $currentLab->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-hidden">Your profile</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-hidden">Settings</a>
                <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit"
        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
        Sign out
    </button>
</form>

                <!-- <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                  @csrf
                  <button type="submit" class="block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:outline-hidden">Sign out</button>
                  
                </form> -->
                
              </el-menu>
            </el-dropdown>
          </div>
        </div>
        <div class="-mr-2 flex md:hidden">
          <!-- Mobile menu button -->
          <button type="button" command="--toggle" commandfor="mobile-menu" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
            <span class="absolute -inset-0.5"></span>
            <span class="sr-only">Open main menu</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 in-aria-expanded:hidden">
              <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 not-in-aria-expanded:hidden">
              <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <el-disclosure id="mobile-menu" hidden class="block md:hidden">
      
      <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
        <!-- Current: "bg-gray-950/50 text-white", Default: "text-gray-300 hover:bg-white/5 hover:text-white" -->
       
        <x-dosen.nav-link-mob href="{{ route('dosen.dashboard', $currentLab->id) }}" 
        :active="request()->routeIs('dosen.dashboard')">Dashboard</x-dosen.nav-link-mob>

        <x-dosen.nav-link-mob href="{{ route('dosen.pinjam-ruangan', $currentLab->id) }}" 
        :active="request()->routeIs('dosen.pinjam-ruangan')">Pinjam Ruangan</x-dosen.nav-link-mob>

        <x-dosen.nav-link-mob href="{{ route('dosen.pinjam-alat', $currentLab->id) }}" 
        :active="request()->routeIs('dosen.pinjam-alat')">Pinjam Alat</x-dosen.nav-link-mob>

        <x-dosen.nav-link-mob href="{{ route('dosen.pengumuman.index', $currentLab->id) }}" 
        :active="request()->routeIs('dosen.pengumuman.index')">Kelola Pengumuman</x-dosen.nav-link-mob>

        <x-dosen.nav-link-mob href="{{ route('dosen.risk-assessment.index', $currentLab->id) }}" 
        :active="request()->routeIs('dosen.risk-assessment.index')">Risk Assessment</x-dosen.nav-link-mob>

      

      </div>
      <div class="border-t border-gray-200 pt-4 pb-3">
        <div class="flex items-center px-5">
          <div class="shrink-0">
            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-10 rounded-full outline -outline-offset-1 outline-gray-200" />
          </div>
          <div class="ml-3">
            <div class="text-base/5 font-medium text-gray-900">{{ $user->Nama }}</div>
            <div class="text-sm font-medium text-gray-600">{{ $user->Email }}</div>
          </div>
          <button type="button" class="relative ml-auto shrink-0 rounded-full p-1 text-gray-600 hover:text-gray-900 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
            <span class="absolute -inset-1.5"></span>
            <span class="sr-only">View notifications</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
              <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </div>
        <div class="mt-3 space-y-1 px-2">
          <x-role-switcher :user="$user" />
          <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">Your profile</a>
          <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">Settings</a>
          <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit"
        class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">
        Sign out
    </button>
</form>

          
        </div>
      </div>
    </el-disclosure>
  </nav>