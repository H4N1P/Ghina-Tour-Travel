@php
    // Ambil data company profile dari database, fallback ke nilai default jika belum ada
    $companyProfile = $companyProfile ?? \App\Models\CompanyProfile::first();
@endphp

<footer class="mt-20 w-full px-14 py-16 text-white">
    <div class="mx-auto grid max-w-[1280px] grid-cols-4 gap-10">
        <div>
            <div class="mb-4 flex items-center gap-3">
                <img src="{{ asset('customer/assets/images/logos/logo.png') }}" alt="Logo" class="h-[40px] w-auto" />
                <div>
                    <div class="font-bold">Ghina Tour Travel</div>
                    <div class="text-[10px]" style="color:var(--gold);">Serving With Love</div>
                </div>
            </div>
            <p class="text-sm leading-6 text-gray-400">
                {{ $companyProfile->about ?? 'Biro perjalanan wisata terpercaya. Melayani perjalanan rombongan dengan harga sesuai anggaran Anda.' }}
            </p>
        </div>

        <div>
            <h4 class="mb-4 font-bold">Tautan</h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-yellow-500 transition-colors">Beranda</a></li>
                <li><a href="{{ route('packages') }}" class="hover:text-yellow-500 transition-colors">Paket Wisata</a>
                </li>
                <li><a href="{{ route('photos') }}" class="hover:text-yellow-500 transition-colors">Galeri</a></li>
            </ul>
        </div>

        <div>
            <h4 class="mb-4 font-bold">Layanan</h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li>Paket Wisata</li>
                <li>Transportasi</li>
                <li>Akomodasi</li>
                <li>Konsumsi</li>
            </ul>
        </div>

        <div>
            <h4 class="mb-4 font-bold">Kontak</h4>
            <ul class="space-y-2 text-sm text-gray-400">
                @if ($companyProfile?->address)
                    <li>📍 {{ $companyProfile->address }}</li>
                @else
                    <li>📍 Jl. Wisata No. 123, Jakarta</li>
                @endif

                @if ($companyProfile?->whatsapp)
                    <li>
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $companyProfile->whatsapp) }}"
                            target="_blank" class="hover:text-yellow-500 transition-colors">
                            📞 {{ $companyProfile->whatsapp }}
                        </a>
                    </li>
                @else
                    <li>📞 +62 812-3456-7890</li>
                @endif

                @if ($companyProfile?->email)
                    <li>
                        <a href="mailto:{{ $companyProfile->email }}" class="hover:text-yellow-500 transition-colors">
                            ✉️ {{ $companyProfile->email }}
                        </a>
                    </li>
                @else
                    <li>✉️ info@ghinatour.com</li>
                @endif

                @if ($companyProfile?->instagram)
                    <li>
                        <a href="https://instagram.com/{{ ltrim($companyProfile->instagram, '@') }}" target="_blank"
                            class="hover:text-yellow-500 transition-colors">
                            📸 {{ $companyProfile->instagram }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="mx-auto mt-12 max-w-[1280px] border-t border-gray-700 pt-6 text-center text-sm text-gray-400">
        <p>&copy; {{ date('Y') }} PT Ghina Tour Travel. All rights reserved.</p>
    </div>
</footer>
