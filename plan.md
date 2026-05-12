Implementation Plan — UI Polish & Bug Fix Round 2

    ## Problem Statement

    Plan.md sebelumnya sudah selesai dari sisi arsitektur, tapi muncul 7+ issue UI/UX yang perlu diperbaiki:
    - Placeholder foto kosong masih pakai background hitam/gelap (tidak clean).
    - Stats card di Beranda masih menabrak section Tentang Kami.
    - Text tidak kontras di beberapa tempat saat dark/light mode.
    - Halaman Detail Paket (`/package/{id}`) tidak bisa dibuka (error).
    - Menu Company Profile di admin tidak bisa dibuka (error routing).
    - Tombol Chatbot berjejer dengan WhatsApp di kanan bawah.
    - Toggle dark mode admin belum sama persis dengan customer.
    - Form admin hardcoded warna gelap sehingga jelek dan tidak terbaca di light mode.

    ## Requirements (Konfirmasi User)

    - **R1** — Placeholder/fallback foto kosong: buat clean (abu muda/light neutral), jangan pakai gradient hitam/orange gelap.
    - **R2** — Stats card di Beranda tidak boleh menabrak section Tentang Kami di semua viewport.
    - **R3** — Semua text harus kontras jelas, menyesuaikan tema terpilih (dark/light).
    - **R4** — Detail paket (`/package/{id}`) harus bisa dibuka tanpa error.
    - **R5** — Chatbot tidak berjejer dengan WhatsApp. Layout: WhatsApp tetap stack vertikal di kanan bawah, chatbot pindah ke kiri bawah (tidak menabrak WA).
    - **R6** — Admin dark mode toggle harus interaktif sama persis dengan customer (knob bergeser).
    - **R7** — Menu Company Profile di admin harus bisa dibuka.
    - **R8** — Admin form di light mode harus mengikuti desain (background putih, text gelap). Saat ini form hardcoded `bg-neutral-900` sehingga tidak terlihat dan jelek
    di light mode.

    ## Background (Akar Masalah yang Sudah Ditemukan)

    1. **Stats card tabrakan** — Section hero `h-[720px]` + stats absolute `-bottom-16` (menonjol 64px ke bawah) butuh padding ≥ `pt-48` untuk desktop; solusi lebih
    robust: keluarkan stats card dari absolute, pakai `-mt-16` di section berikutnya.
    2. **Detail paket error** — `package-detail.blade.php` mengakses `$paket->rundowns->isNotEmpty()`, tapi kemungkinan Paket model tidak punya relasi `rundowns()` (hanya
    field `rundown` string). Eager loading `'rundowns'` di `PageController::packageDetail` gagal → exception.
    3. **Company Profile error** — `Route::resource('company-profile', ...)` butuh `{company_profile}` parameter. Di `show.blade.php` `route('admin.company-profile.edit')`
    dipanggil tanpa ID → `UrlGenerationException: Missing required parameter`.
    4. **Placeholder gelap** — `customer/index.blade.php` galeri kosong pakai `from-amber-500 to-orange-700`, `.package-card__placeholder` pakai gradient `#242424`,
    `package-detail.blade.php` pakai hardcoded `#78350f`/`#7c3f00`/`#c97a1a`.
    5. **Form admin gelap di light mode** — Banyak view admin pakai class Tailwind `bg-neutral-900`, `bg-neutral-800`, `text-neutral-300` sebagai warna default (bukan
    `dark:` prefix), sehingga tetap gelap walau di light mode.
    6. **Chatbot vs WhatsApp** — `.chatbot-trigger` di `right: 92px; bottom: 30px` dan `.wa-float` di `right: 24px; bottom: 24px` — keduanya di kanan dan saling dekat.
    7. **Toggle admin** — Struktur HTML/CSS masih sedikit beda dengan customer (`.tgl`). Perlu samakan persis.

    ## Task Breakdown

    ### Task 1: Fix Company Profile routing & menu

    - **Objective:** Menu Company Profile dapat dibuka tanpa error.
    - **Implementation:**
        - Ganti `Route::resource('company-profile', ...)` di `routes/web.php` dengan explicit routes tanpa parameter:
            ```php
            Route::get('company-profile', [CompanyProfileController::class, 'show'])->name('company-profile.show');
            Route::get('company-profile/edit', [CompanyProfileController::class, 'edit'])->name('company-profile.edit');
            Route::put('company-profile', [CompanyProfileController::class, 'update'])->name('company-profile.update');
            ```
        - Update sidebar link `route('admin.company-profile.show', 1)` → `route('admin.company-profile.show')`.
        - Update semua pemanggilan `route('admin.company-profile.edit')` agar konsisten tanpa parameter.
    - **Test:** klik menu Company Profile dari sidebar → halaman terbuka; klik Edit → form edit terbuka; submit update → berhasil redirect.
    - **Demo:** Menu Company Profile dapat diklik, halaman tampil, dan tombol Edit/Update berfungsi.

    ### Task 2: Fix detail paket — relation `rundowns`

    - **Objective:** `/package/{id}` bisa dibuka tanpa exception.
    - **Implementation:**
        - Baca `app/Models/Paket.php` — verifikasi ada/tidaknya relasi `rundowns()`.
        - Jika tidak ada & tabel `rundowns` tidak ada, hapus `rundowns` dari eager loading di `PageController::packageDetail` dan hapus block `@if
    ($paket->rundowns->isNotEmpty())` dari Blade; gunakan hanya field `rundown` (string).
        - Jika relasi seharusnya ada tapi belum didefinisikan & tabel sudah ada, tambahkan `public function rundowns() { return $this->hasMany(Rundown::class, 'paket_id');
    }` di Paket model.
    - **Test:** Buka `/package/{id}` dengan paket valid dan paket tanpa rundown.
    - **Demo:** Halaman detail paket tampil lengkap tanpa error.

    ### Task 3: Fix placeholder foto kosong jadi clean

    - **Objective:** Tidak ada background hitam/orange gelap untuk placeholder foto.
    - **Implementation:**
        - Di `resources/css/customer.css`: ganti `.package-card__placeholder` dari gradient `#242424` menjadi `background: var(--bg-section); display: flex; align-items:
    center; justify-content: center;` + ikon SVG placeholder di tengah (color `#9ca3af`).
        - Di `customer/index.blade.php` galeri: ganti `bg-gradient-to-br from-amber-500 to-orange-700` → background netral via CSS variable + ikon.
        - Di `customer/package-detail.blade.php`: ganti hardcoded gradient `#78350f`/`#7c3f00`/`#c97a1a` dengan `var(--bg-card)` + border `var(--border)`.
    - **Test:** Buat paket tanpa foto; buka halaman dengan paket/foto kosong.
    - **Demo:** Semua placeholder tampil clean dengan warna netral yang respect tema.

    ### Task 4: Fix stats card tabrakan dengan section Tentang Kami

    - **Objective:** Stats card tidak pernah overlap dengan konten di bawahnya, di semua viewport (mobile, tablet, desktop).
    - **Implementation:**
        - Opsi A: Ubah section `#tentang` dari `pt-40` → `pt-48 lg:pt-56`.
        - Opsi B (lebih robust, direkomendasikan): Keluarkan stats card dari `absolute -bottom-16`. Jadikan stats card block normal di dalam container baru yang posisinya
    `translate-y: -50%` atau pakai negative margin `-mt-16` pada section Tentang, dan beri `pt-20` biar tidak ketimpa.
    - **Test:** Resize browser dari 375px → 1920px; pastikan stats card full terlihat dan tidak menabrak section berikutnya.
    - **Demo:** Stats card selalu terlihat penuh di semua ukuran layar, tidak menabrak konten di bawah.

    ### Task 5: Pindahkan chatbot ke kiri, WhatsApp tetap di kanan

    - **Objective:** Chatbot dan WhatsApp tidak berjejer, tidak saling menabrak.
    - **Implementation:**
        - Di `resources/css/chatbot.css`:
            - `.chatbot-trigger`: ganti `right: 92px` → `left: 24px`. Tetap `bottom: 24-30px`.
            - `.chatbot-container`: ganti `right: 28px` → `left: 28px; right: auto`.
            - Media query mobile: pastikan chatbot tetap di kiri, tidak pindah ke kanan.
        - Di `resources/css/customer.css` `.wa-float`: tetap `right: 24px; bottom: 24px`.
        - Verifikasi di admin layout: chatbot admin juga pindah ke kiri.
    - **Test:** Buka customer & admin pada desktop dan mobile; scroll, buka/tutup chatbot.
    - **Demo:** Chatbot muncul di kiri bawah, WhatsApp di kanan bawah; keduanya tidak tabrakan di semua ukuran layar.

    ### Task 6: Samakan toggle admin dengan customer

    - **Objective:** Toggle dark mode admin visual-nya sama dengan customer (`.tgl` style, knob bergeser mulus).
    - **Implementation:**
        - Di `components/layout/admin.blade.php`, ganti markup toggle dengan struktur identik customer:
            ```html
            <label class="tgl" title="Ganti tema">
                <input type="checkbox" id="adminThemeToggle" />
                <span class="sl">
                    <span style="font-size:11px;z-index:1;">☀️</span>
                    <span style="font-size:11px;z-index:1;">🌙</span>
                </span>
            </label>
            ```
        - Pindahkan / duplikasi CSS `.tgl` dari `customer.css` ke `admin.css` (atau ekstrak ke `shared.css` yang di-import keduanya) agar style `.tgl`, `.tgl input`, `.tgl
    .sl`, `.tgl .sl::after`, `[data-theme="dark"] .tgl .sl::after` tersedia di admin.
        - Hapus style `.admin-theme-toggle`, `.admin-theme-toggle__track`, `.admin-theme-toggle__icon` yang lama.
        - Update admin theme JS agar listen `change` event di `#adminThemeToggle` (checkbox). Konsisten dengan customer.
    - **Test:** Toggle di customer dan admin — perbandingan visual harus sama.
    - **Demo:** Toggle di admin dan customer tampil identik (warna gold track, knob putih bergeser) dan berfungsi.

    ### Task 7: Audit & fix semua text contrast di dark/light mode

    - **Objective:** Semua text terlihat jelas di kedua tema.
    - **Implementation:**
        - Grep seluruh `resources/views` untuk class `text-black`, `text-white`, `text-neutral-*`, `text-gray-*` yang dipakai tanpa pair `dark:` (hardcoded).
        - Untuk customer views: ganti dengan utility class `t` / `tm` yang sudah pakai CSS var `--text` / `--text-muted`.
        - Untuk admin views: ganti dengan `style="color:var(--admin-text)"` / `var(--admin-muted)`, atau utility class aware dark mode.
        - Prioritas halaman: Dashboard, tabel paket, detail pesanan, company profile (show/edit), form create/edit.
    - **Test:** Toggle light ↔ dark di semua halaman, spot-check kontras (≥ 4.5:1).
    - **Demo:** Semua text terbaca jelas di kedua tema.

    ### Task 8: Fix admin form di light mode sesuai desain

    - **Objective:** Form admin tampil putih bersih di light mode, tidak gelap/jelek.
    - **Implementation:**
        - Grep views admin yang pakai `bg-neutral-900`, `bg-neutral-800`, `bg-gray-900`, `bg-gray-800` sebagai default (bukan di balik `dark:`).
        - Ganti strategi:
            - Card/panel: pakai `style="background:var(--admin-card);border:1px solid var(--admin-border);"` atau class utility custom.
            - Text: `style="color:var(--admin-text)"` / `var(--admin-muted)`.
            - Input/textarea/select: background `var(--admin-card)` / putih di light, warna gelap di dark; border `var(--admin-border)`; text `var(--admin-text)`.
        - Tambahkan utility class di `admin.css`:
            ```css
            .admin-card { background: var(--admin-card); border: 1px solid var(--admin-border); color: var(--admin-text); }
            .admin-input { background: var(--admin-card); color: var(--admin-text); border: 1px solid var(--admin-border); }
            .admin-input:focus { outline: none; border-color: var(--admin-orange); }
            .admin-label { color: var(--admin-text); font-weight: 600; }
            .admin-muted { color: var(--admin-muted); }
            ```
        - Prioritas file: `admin/paket/create.blade.php`, `admin/paket/edit.blade.php`, `admin/paket/show.blade.php`, `admin/pesanan/create.blade.php`,
    `admin/pesanan/edit.blade.php`, `admin/pesanan/create-custom.blade.php`, `admin/pesanan/edit-custom.blade.php`, `admin/pesanan/show.blade.php`,
    `admin/company-profile/edit.blade.php`, `admin/company-profile/show.blade.php`, `admin/gallery/create.blade.php`, `admin/gallery/show.blade.php`,
    `admin/paket/index.blade.php`, `admin/pesanan/index.blade.php`, `admin/gallery/index.blade.php`, `admin/index.blade.php`.
    - **Test:** Buka semua form admin di light mode → putih bersih; toggle ke dark → gelap elegan; keduanya terbaca.
    - **Demo:** Form admin konsisten dengan tema terpilih, mudah dibaca di kedua mode.

    ### Task 9: Tulis ulang `plan.md`

    - **Objective:** File `plan.md` merefleksikan plan baru ini (bukan plan lama).
    - **Implementation:** Overwrite `plan.md` dengan seluruh konten implementation plan ini (Problem Statement, Requirements, Background, Task Breakdown, Test Plan).
    - **Demo:** Isi `plan.md` menampilkan plan terbaru.

    ### Task 10: Build, test, dan verifikasi

    - **Objective:** Pastikan semua perubahan ter-build dan tidak ada regresi.
    - **Implementation:**
        - Jalankan `npm run build`.
        - Jalankan `php artisan route:list` untuk verifikasi route company-profile.
        - Manual check:
            - Customer: Beranda → Semua Paket → Detail Paket → Semua Foto (toggle dark/light di tiap halaman).
            - Admin login → Dashboard → Paket (list, create, edit, show) → Pesanan (list, create, edit, show, custom) → Galeri (list, create, show) → Company Profile
    (show, edit).
            - Toggle dark/light di semua halaman admin & customer.
            - Ukur jarak stats card ke section bawah di berbagai viewport.
            - Chatbot vs WA tidak tabrakan.
    - **Demo:** Semua halaman ter-load tanpa error; toggle dark/light mulus di admin & customer; tidak ada text yang sulit dibaca; placeholder bersih; chatbot/WA terpisah.

    ## Test Plan

    - `npm run build` sukses tanpa error.
    - `php artisan route:list` menampilkan route `admin.company-profile.show/edit/update` tanpa parameter.
    - Manual test customer:
        - Beranda: stats card tidak tabrakan dengan Tentang Kami, placeholder galeri clean, toggle dark/light text tetap kontras.
        - Semua Paket: package card placeholder clean jika tidak ada foto, search bar berfungsi.
        - Detail Paket: tidak error, placeholder destinasi clean.
        - Semua Foto: galeri tampil, toggle tema text kontras.
    - Manual test admin:
        - Login berhasil.
        - Dashboard, Paket, Pesanan, Galeri, Company Profile semua bisa dibuka.
        - Form create/edit di light mode: background putih, text gelap, input terbaca.
        - Form create/edit di dark mode: background gelap, text terang, input terbaca.
        - Toggle dark/light admin visual identik dengan customer.
    - Chatbot di kiri bawah, WhatsApp di kanan bawah; buka/tutup chatbot tidak overlap dengan WA di desktop dan mobile.

    ## Assumptions

    - User setuju semua perubahan di atas.
    - Backend logic (controller, model, migration) tidak perlu perubahan besar kecuali route company-profile dan kemungkinan Paket::rundowns relation.
    - Tidak ada breaking change pada struktur database.
    - Tema dark mode admin menggunakan toggle identik customer; local storage key bisa dipisah (`admin-theme` vs `theme`) atau disatukan (`theme`) — akan disatukan jika
    memungkinkan untuk konsistensi.
