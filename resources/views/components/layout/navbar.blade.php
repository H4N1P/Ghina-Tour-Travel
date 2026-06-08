<nav id="navbar" class="fixed inset-x-0 top-0 z-50 w-full">
    <div class="nav-bg flex min-h-[68px] w-full items-center justify-between gap-2 px-3 py-2.5 transition-all duration-300 sm:min-h-[78px] sm:gap-3 sm:px-8 sm:py-3 lg:px-14">
        <a href="{{ route('home') }}" class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 lg:flex-none">
            <img src="{{ asset('customer/assets/images/logos/logo-transparent.png') }}" alt="Logo"
                class="h-10 w-auto shrink-0 object-contain sm:h-14"
                onerror="this.onerror=null;this.src='';this.style.display='none';document.getElementById('lf').style.display='flex';" />
            <div id="lf"
                class="hidden h-10 w-10 items-center justify-center rounded-full text-lg font-black text-black"
                style="background:var(--gold);">G</div>
            <div class="min-w-0">
                <div class="t truncate text-[14px] font-bold leading-tight sm:text-[16px]">Ghina Tour Travel</div>
                <div class="truncate text-[8px] font-medium sm:text-[10px]" style="color:var(--gold);">Serving With Love</div>
            </div>
        </a>

        <ul class="hidden items-center gap-7 text-[14px] font-semibold lg:flex">
            <li><a href="{{ route('home') }}" class="t flex min-h-11 items-center transition-colors hover:text-yellow-500">Beranda</a></li>
            <li><a href="{{ route('home') }}#tentang" class="t flex min-h-11 items-center transition-colors hover:text-yellow-500">Tentang Kami</a></li>
            <li><a href="{{ route('home') }}#paket" class="t flex min-h-11 items-center transition-colors hover:text-yellow-500">Paket</a></li>
            <li><a href="{{ route('home') }}#galeri" class="t flex min-h-11 items-center transition-colors hover:text-yellow-500">Galeri</a></li>
        </ul>

        <div class="flex shrink-0 items-center gap-2 sm:gap-3">
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
                   class="hidden items-center gap-1.5 rounded-lg px-4 py-2 text-[13px] font-semibold transition-all duration-200 lg:inline-flex"
                   style="background: var(--gold); color: #000;"
                   onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" class="hidden lg:block">
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
                   class="hidden items-center gap-1.5 rounded-lg px-5 py-2 text-[13px] font-semibold transition-all duration-200 lg:inline-flex"
                   style="background: var(--gold); color: #000;"
                   onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Login
                </a>
            @endauth

            <button type="button" id="mobileNavToggle"
                class="t inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-[var(--border)] bg-[var(--bg-card)] transition-colors hover:border-[var(--gold)] hover:text-[var(--gold-dark)] lg:hidden"
                aria-controls="mobileNavMenu" aria-expanded="false" aria-label="Buka menu navigasi">
                <svg data-mobile-nav-open class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg data-mobile-nav-close class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div id="mobileNavMenu" class="mobile-nav-menu hidden lg:hidden" aria-hidden="true">
        <div class="grid gap-1">
            <a href="{{ route('home') }}" class="mobile-nav-menu__link">Beranda</a>
            <a href="{{ route('home') }}#tentang" class="mobile-nav-menu__link">Tentang Kami</a>
            <a href="{{ route('home') }}#paket" class="mobile-nav-menu__link">Paket</a>
            <a href="{{ route('home') }}#galeri" class="mobile-nav-menu__link">Galeri</a>
        </div>

        <div class="mt-3 border-t border-[var(--border)] pt-3">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="mobile-nav-menu__account mobile-nav-menu__account--primary">
                    Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="mobile-nav-menu__account mobile-nav-menu__account--outline">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="mobile-nav-menu__account mobile-nav-menu__account--primary">
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>
