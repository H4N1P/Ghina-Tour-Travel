<nav id="navbar" class="fixed inset-x-0 top-0 z-50 w-full">
    <div class="nav-bg flex min-h-[68px] w-full items-center justify-between gap-3 px-4 py-3 transition-all duration-300 sm:min-h-[78px] sm:px-8 lg:px-14">
        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
            <img src="{{ asset('customer/assets/images/logos/logo-transparent.png') }}" alt="Logo"
                class="h-12 w-auto max-w-full object-contain sm:h-14"
                onerror="this.onerror=null;this.src='';this.style.display='none';document.getElementById('lf').style.display='flex';" />
            <div id="lf"
                class="hidden h-10 w-10 items-center justify-center rounded-full text-lg font-black text-black"
                style="background:var(--gold);">G</div>
            <div class="min-w-0">
                <div class="t truncate text-[16px] font-bold leading-tight">Ghina Tour Travel</div>
                <div class="truncate text-[10px] font-medium" style="color:var(--gold);">Serving With Love</div>
            </div>
        </a>

        <ul class="hidden items-center gap-7 text-[14px] font-semibold lg:flex">
            <li><a href="{{ route('home') }}" class="t flex min-h-11 items-center transition-colors hover:text-yellow-500">Beranda</a></li>
            <li><a href="{{ route('home') }}#tentang" class="t flex min-h-11 items-center transition-colors hover:text-yellow-500">Tentang Kami</a></li>
            <li><a href="{{ route('home') }}#paket" class="t flex min-h-11 items-center transition-colors hover:text-yellow-500">Paket</a></li>
            <li><a href="{{ route('home') }}#galeri" class="t flex min-h-11 items-center transition-colors hover:text-yellow-500">Galeri</a></li>
        </ul>

        <div class="flex items-center gap-3">
            <label class="tgl" title="Ganti tema">
                <input type="checkbox" id="themeToggle" />
                <span class="sl">
                    <svg class="tgl__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <circle cx="12" cy="12" r="4" />
                        <path d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32 1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
                    </svg>
                    <svg class="tgl__icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M21 14.2A8.5 8.5 0 0 1 9.8 3a7 7 0 1 0 11.2 11.2Z" />
                    </svg>
                </span>
            </label>

            @auth
                <a href="{{ route('admin.dashboard') }}"
                   class="hidden items-center gap-1.5 rounded-lg px-4 py-2 text-[13px] font-semibold transition-all duration-200 sm:inline-flex"
                   style="background: var(--gold); color: #000;"
                   onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-lg border px-4 py-2 text-[13px] font-semibold transition-all duration-200"
                            style="border-color: var(--gold); color: var(--gold);"
                            onmouseover="this.style.background='var(--gold)';this.style.color='#000'"
                            onmouseout="this.style.background='transparent';this.style.color='var(--gold)'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   id="btn-login"
                   class="hidden items-center gap-1.5 rounded-lg px-5 py-2 text-[13px] font-semibold transition-all duration-200 sm:inline-flex"
                   style="background: var(--gold); color: #000;"
                   onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>
