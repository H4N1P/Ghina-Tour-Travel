@extends('components.layout.customer')

@section('title', 'Semua Paket - Ghina Tour Travel')
@section('description', 'Lihat pilihan paket wisata Ghina Tour Travel untuk perjalanan rombongan, sekolah, instansi, dan
    keluarga.')
@section('content')
    <main class="mx-auto max-w-7xl px-4 pb-12 pt-32 sm:px-6 sm:pb-14 sm:pt-36 lg:px-16">
        <div class="mb-8 flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <h1 class="text-3xl font-extrabold leading-tight text-[#202638] dark:text-white sm:text-[44px]">
                Semua Paket
            </h1>

            <form id="package-search-form" action="{{ route('packages') }}" method="GET"
                class="package-live-search" role="search">
                <span class="package-live-search__icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.25">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.2-5.2m1.7-5.3a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                    </svg>
                </span>
                <input id="package-search-input" type="search" name="q" value="{{ $query ?? '' }}"
                    placeholder="Cari nama paket atau destinasi..." autocomplete="off"
                    aria-label="Cari nama paket atau destinasi">
                <span id="package-search-loading" class="package-live-search__loading" aria-hidden="true"></span>
            </form>
        </div>

        <div id="package-results" aria-live="polite">
            @include('customer.partials.package-results', ['pakets' => $pakets, 'query' => $query])
        </div>
    </main>
@endsection

@section('extra_scripts')
    <script>
        // Mengaktifkan pencarian paket real-time dan pagination tanpa memuat ulang halaman.
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('package-search-form');
            const input = document.getElementById('package-search-input');
            const results = document.getElementById('package-results');
            const loading = document.getElementById('package-search-loading');
            if (!form || !input || !results) return;

            let debounceTimer;
            let activeRequest;

            // Memperbarui URL halaman sesuai kata pencarian dan halaman hasil aktif.
            function syncUrl(url) {
                const nextUrl = new URL(url, window.location.origin);
                if (nextUrl.searchParams.get('page') === '1') {
                    nextUrl.searchParams.delete('page');
                }
                window.history.replaceState({}, '', `${nextUrl.pathname}${nextUrl.search}${nextUrl.hash}`);
            }

            // Mengambil partial hasil paket terbaru dari server.
            async function loadResults(url) {
                activeRequest?.abort();
                const request = new AbortController();
                activeRequest = request;
                results.setAttribute('aria-busy', 'true');
                loading?.classList.add('is-visible');

                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        signal: request.signal,
                    });

                    if (!response.ok) throw new Error('Gagal memuat hasil pencarian.');

                    results.innerHTML = await response.text();
                    syncUrl(url);
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        window.location.assign(url);
                    }
                } finally {
                    if (activeRequest === request) {
                        results.removeAttribute('aria-busy');
                        loading?.classList.remove('is-visible');
                    }
                }
            }

            // Menjalankan pencarian setelah pengguna berhenti mengetik selama 300 milidetik.
            input.addEventListener('input', function() {
                window.clearTimeout(debounceTimer);
                debounceTimer = window.setTimeout(() => {
                    const url = new URL(form.action);
                    const query = input.value.trim();
                    if (query) url.searchParams.set('q', query);
                    loadResults(url);
                }, 300);
            });

            // Mempertahankan fallback form GET sambil menggunakan AJAX ketika JavaScript aktif.
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                window.clearTimeout(debounceTimer);
                const url = new URL(form.action);
                const query = input.value.trim();
                if (query) url.searchParams.set('q', query);
                loadResults(url);
            });

            // Memuat halaman hasil pagination melalui AJAX.
            results.addEventListener('click', function(event) {
                const link = event.target.closest('.ui-pagination a');
                if (!link) return;

                event.preventDefault();
                loadResults(link.href);
            });
        });
    </script>
@endsection
