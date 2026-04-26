<nav id="navbar" class="fixed inset-x-0 top-5 z-50 mx-auto w-full max-w-[1280px] px-14">
    <div class="nav-bg mx-auto flex h-[78px] items-center justify-between rounded-2xl px-7 transition-all duration-300">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img src="{{ asset('customer/assets/images/logos/logo.png') }}" alt="Logo"
                class="h-[50px] w-auto object-contain"
                onerror="this.onerror=null;this.src='';this.style.display='none';document.getElementById('lf').style.display='flex';" />
            <div id="lf"
                class="hidden h-10 w-10 items-center justify-center rounded-full font-black text-black text-lg"
                style="background:var(--gold);">G</div>
            <div>
                <div class="text-[16px] font-bold t leading-tight">Ghina Tour Travel</div>
                <div class="text-[10px] font-medium" style="color:var(--gold);">Serving With Love</div>
            </div>
        </a>

        <ul class="flex items-center gap-7 font-semibold text-[14px]">
            <li><a href="{{ route('home') }}" class="t hover:text-yellow-500 transition-colors">Beranda</a></li>
            <li><a href="{{ route('home') }}#tentang" class="t hover:text-yellow-500 transition-colors">Tentang Kami</a>
            </li>
            <li><a href="{{ route('home') }}#paket" class="t hover:text-yellow-500 transition-colors">Paket</a></li>
            <li><a href="{{ route('home') }}#galeri" class="t hover:text-yellow-500 transition-colors">Galeri</a></li>
        </ul>

        <label class="tgl" title="Ganti tema">
            <input type="checkbox" id="themeToggle" />
            <span class="sl">
                <span style="font-size:11px;z-index:1;">☀️</span>
                <span style="font-size:11px;z-index:1;">🌙</span>
            </span>
        </label>
    </div>
</nav>
