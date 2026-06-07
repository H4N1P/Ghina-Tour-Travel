@extends('components.layout.customer')

@section('title', $paket->nama_paket . ' — Ghina Tour Travel')
@section('description',
    Str::limit(
    strip_tags(
    $paket->displayNote() ??
    'Paket wisata dari Ghina Tour Travel dengan harga fleksibel dan
    layanan terpercaya.',
    ),
    155,
    ))
@section('og_type', 'product')

@section('extra_styles')
    <style>
        .detail-hero {
            background: var(--bg-section);
        }

        .detail-hero__blur {
            filter: blur(22px);
            opacity: .72;
            transform: scale(1.12);
        }

        .placeholder-panel {
            background: var(--bg-section);
            border: 1px solid var(--border);
        }

        .fas-box,
        .harga-box {
            background: var(--bg-card);
            border: 1px solid var(--border);
        }

        .facility-price-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 32px;
            width: 100%;
        }

        .facility-panel {
            min-width: 0;
            overflow: hidden;
        }

        .facility-carousel {
            position: relative;
            min-width: 0;
        }

        .facility-carousel__track {
            --facility-visible-slides: 1;
            --facility-gap: 18px;
            display: flex;
            gap: var(--facility-gap);
            max-width: 100%;
            overflow-x: auto;
            padding: 4px 0 16px;
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
        }

        .facility-carousel__track::-webkit-scrollbar {
            display: none;
        }

        .facility-slide {
            flex: 0 0 calc((100% - (var(--facility-gap) * (var(--facility-visible-slides) - 1))) / var(--facility-visible-slides));
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--bg-card);
            scroll-snap-align: start;
        }

        .facility-slide__media {
            height: 190px;
            overflow: hidden;
            background: var(--bg-section);
        }

        .facility-slide__media img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .facility-slide:hover .facility-slide__media img {
            transform: scale(1.04);
        }

        .facility-carousel__button--prev {
            left: 8px;
        }

        .facility-carousel__button--next {
            right: 8px;
        }

        .package-detail-action {
            display: flex;
            min-height: 76px;
            align-items: center;
            gap: 14px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--bg-section);
            padding: 16px;
            color: var(--text);
        }

        .package-detail-action__icon {
            display: flex;
            height: 42px;
            width: 42px;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: var(--gold);
            color: #111827;
        }

        .rundown-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
        }

        .tempat-card {
            position: relative;
            height: 180px;
            border-radius: 12px;
            overflow: hidden;
            background: var(--bg-section);
            border: 1px solid var(--border);
        }

        .tempat-card__label {
            position: absolute;
            inset: auto 0 0 0;
            padding: 12px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
            color: #fff;
            font-weight: 600;
            font-size: 14px;
        }

        .placeholder-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: #9ca3af;
        }

        .placeholder-icon svg {
            width: 44px;
            height: 44px;
        }

        @media (min-width: 640px) {
            .facility-carousel__track {
                --facility-visible-slides: 2;
            }
        }

        @media (min-width: 1024px) {
            .facility-price-layout {
                grid-template-columns: minmax(0, 1fr) 300px;
            }

            .facility-carousel__track {
                --facility-visible-slides: 3;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $displayNote = $paket->displayNote();
        $maxPax = $paket->maxPaxFromNote();
        $whatsappNumber = \App\Models\CompanyProfile::whatsappLinkNumber($companyProfile?->whatsapp);
    @endphp

    <!-- HERO / HEADER PAKET -->
    <div class="detail-hero relative mt-[72px] h-[320px] w-full overflow-hidden sm:h-[380px] lg:h-[420px]">
        @if ($paket->image)
            @php
                $packageImage = Str::startsWith($paket->image, 'http')
                    ? $paket->image
                    : asset('storage/' . $paket->image);
            @endphp
            <img src="{{ $packageImage }}" alt="" aria-hidden="true"
                class="detail-hero__blur absolute inset-0 h-full w-full max-w-full object-cover" />
            <div class="absolute inset-0 bg-black/35"></div>
            <img src="{{ $packageImage }}" alt="{{ $paket->nama_paket }}"
                class="absolute inset-0 h-full w-full max-w-full object-contain" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-black/20"></div>
        @else
            <div class="placeholder-icon absolute inset-0">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2ZM8.5 11.5l2.5 3 3.5-4.5 4.5 6H5l3.5-4.5Z" />
                </svg>
            </div>
            <div class="absolute inset-0" style="background:rgba(255,255,255,.35);"></div>
        @endif
        <div class="absolute inset-0 flex flex-col justify-end gap-2 px-4 pb-9 text-center sm:px-8 sm:pb-12">
            <h1 class="text-3xl font-bold sm:text-[38px] {{ $paket->image ? 'text-white' : 't' }}">
                {{ $paket->nama_paket }}
            </h1>
            <p class="{{ $paket->image ? 'text-gray-200' : 'tm' }} text-base">Paket Wisata</p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-14">

        <!-- Destinasi Wisata Images -->
        @if ($paket->destinasis && $paket->destinasis->count() > 0)
            <div class="mb-10">
                <h2 class="text-[22px] font-bold t mb-4">Destinasi Wisata</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
                    @foreach ($paket->destinasis as $tempat)
                        @php
                            $destSrc = $tempat->image
                                ? (Str::startsWith($tempat->image, 'http')
                                    ? $tempat->image
                                    : asset('storage/' . $tempat->image))
                                : null;
                        @endphp
                        <div class="tempat-card cursor-pointer group"
                            @if ($destSrc) onclick="openLightbox('{{ $destSrc }}', '{{ $tempat->nama_destinasi }}')" @endif>
                            @if ($tempat->image)
                                <img src="{{ $destSrc }}" alt="{{ $tempat->nama_destinasi }}"
                                    class="absolute inset-0 h-full w-full max-w-full object-cover" loading="lazy" />
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all"></div>
                            @else
                                <div class="placeholder-icon">
                                    <svg fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="tempat-card__label">{{ $tempat->nama_destinasi }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Description -->
        @if ($displayNote)
            <div class="mb-10">
                <h2 class="text-[22px] font-bold t mb-4">Deskripsi Paket</h2>
                <div class="p-6 rounded-2xl" style="background:var(--bg-card);border:1px solid var(--border);">
                    <p class="tm leading-7">{{ $displayNote }}</p>
                </div>
            </div>
        @endif

        <!-- Rundown Detail (visible to admin only) -->
        @auth
            @if ($paket->rundowns && $paket->rundowns->count() > 0)
                <div class="mb-10">
                    <h2 class="text-[22px] font-bold t mb-4">Rundown Perjalanan</h2>
                    <div class="space-y-4">
                        @foreach ($paket->rundowns as $index => $rundown)
                            <div class="rundown-card">
                                <div class="flex items-start gap-4">
                                    <span
                                        class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-black font-bold text-sm"
                                        style="background:var(--gold);">{{ $index + 1 }}</span>
                                    <div class="flex-1">
                                        <p class="font-semibold text-[15px]" style="color:var(--gold-dark);">
                                            {{ $rundown->waktu }}</p>
                                        <h4 class="font-bold text-[16px] t mt-1 mb-2">{{ $rundown->acara }}</h4>
                                        @if ($rundown->deskripsi)
                                            <p class="tm text-sm leading-7">{{ $rundown->deskripsi }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endauth

        <!-- Fasilitas + Harga -->
        <div class="facility-price-layout">

            <!-- Fasilitas -->
            <div class="facility-panel fas-box rounded-2xl p-5 sm:p-7">
                @php
                    $facilitySlides = collect();

                    foreach ($paket->fasilitas ?? collect() as $facility) {
                        if ($facility->image) {
                            $facilitySlides->push([
                                'src' => Str::startsWith($facility->image, 'http')
                                    ? $facility->image
                                    : asset('storage/' . $facility->image),
                                'name' => $facility->nama_fasilitas,
                                'type' => $facility->tipe_fasilitas,
                                'caption' => $facility->nama_fasilitas,
                            ]);
                        }

                        foreach ($facility->galleries->where('type', 'image') as $gallery) {
                            $facilitySlides->push([
                                'src' => Str::startsWith($gallery->path, 'http')
                                    ? $gallery->path
                                    : asset('storage/' . $gallery->path),
                                'name' => $facility->nama_fasilitas,
                                'type' => $facility->tipe_fasilitas,
                                'caption' => $gallery->keterangan ?: $facility->nama_fasilitas,
                            ]);
                        }

                        if (!$facility->image && $facility->galleries->where('type', 'image')->isEmpty()) {
                            $facilitySlides->push([
                                'src' => null,
                                'name' => $facility->nama_fasilitas,
                                'type' => $facility->tipe_fasilitas,
                                'caption' => $facility->nama_fasilitas,
                            ]);
                        }
                    }
                @endphp

                <div class="mb-5 flex items-center justify-between gap-4">
                    <h2 class="text-[22px] font-bold t">Fasilitas</h2>
                    <p class="tm text-xs">Geser untuk melihat foto lainnya</p>
                </div>

                <div class="facility-carousel" data-public-carousel data-carousel-item=".facility-slide">
                    @if ($facilitySlides->isNotEmpty())
                        <button type="button" class="public-carousel__btn facility-carousel__button--prev"
                            data-carousel-prev aria-label="Foto fasilitas sebelumnya">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button type="button" class="public-carousel__btn facility-carousel__button--next"
                            data-carousel-next aria-label="Foto fasilitas berikutnya">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    @endif

                    <div class="facility-carousel__track" data-carousel-track>
                        @forelse ($facilitySlides as $slide)
                            <article class="facility-slide">
                                <button type="button" class="facility-slide__media block w-full"
                                    @if ($slide['src']) onclick="openLightbox('{{ $slide['src'] }}', @js($slide['caption']))" @endif>
                                    @if ($slide['src'])
                                        <img src="{{ $slide['src'] }}" alt="{{ $slide['caption'] }}" loading="lazy" />
                                    @else
                                        <span class="placeholder-icon" aria-label="Foto fasilitas belum tersedia">
                                            <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path
                                                    d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2ZM8.5 11.5l2.5 3 3.5-4.5 4.5 6H5l3.5-4.5Z" />
                                            </svg>
                                        </span>
                                    @endif
                                </button>
                                <div class="p-4">
                                    <p class="t font-bold">{{ $slide['name'] }}</p>
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-wide"
                                        style="color:var(--gold-dark);">{{ $slide['type'] }}</p>
                                    <p class="tm mt-2 text-sm leading-6">{{ $slide['caption'] }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="placeholder-panel w-full rounded-xl p-6 text-center">
                                <p class="tm text-sm">Informasi fasilitas belum tersedia.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('photos') }}"
                        class="package-detail-action transition hover:-translate-y-0.5 hover:shadow-md">
                        <span class="package-detail-action__icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7h3l2-2h8l2 2h3v12H3V7Zm9 3a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z" />
                            </svg>
                        </span>
                        <span>
                            <strong class="block">Dokumentasi</strong>
                            <span class="tm mt-1 block text-xs">Lihat semua galeri perjalanan</span>
                        </span>
                    </a>

                    <div class="package-detail-action" aria-label="Banner Custom">
                        <span class="package-detail-action__icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 13V8l12-4v13L4 13Zm0 0v4m12-7 4 2-4 2M7 14l2 5" />
                            </svg>
                        </span>
                        <span>
                            <strong class="block">Banner Custom</strong>
                            <span class="tm mt-1 block text-xs">Banner perjalanan rombongan</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Harga & CTA -->
            <div class="w-full space-y-4">
                <div class="harga-box rounded-2xl p-6 space-y-3">
                    <p class="tm text-sm">Harga Paket Tour</p>
                    <p class="text-[26px] font-bold t">
                        Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}<span
                            class="text-base font-normal tm">/pax</span>
                    </p>
                    @if ($maxPax)
                        <p class="tm text-xs">Maks. {{ $maxPax }} pax/slot</p>
                    @endif
                    <p class="text-xs font-bold" style="color:#dc2626;">NB : HARGA SEWAKTU WAKTU BISA BERUBAH</p>

                    <hr style="border-color:var(--border);" />

                    <div class="space-y-2 text-sm tm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" style="color:var(--gold);" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                            </svg>
                            <span>{{ $paket->durasi ?? '1 Hari' }}</span>
                        </div>
                    </div>
                </div>

                <a href="https://wa.me/{{ $whatsappNumber }}?text=Halo%20Ghina%20Tour%20Travel,%20saya%20tertarik%20dengan%20paket%20{{ urlencode($paket->nama_paket) }}."
                    target="_blank" class="btn btn-gold w-full justify-center text-[15px] py-4">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    Pesan via WhatsApp
                </a>
                <a href="{{ route('packages') }}" class="btn w-full justify-center text-[14px] py-3"
                    style="border:1px solid var(--border);background:transparent;color:var(--text);">
                    ← Kembali ke Semua Paket
                </a>
            </div>
        </div>
    </main>
@endsection
