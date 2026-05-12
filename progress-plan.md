# Implementation Plan — UI Polish & Bug Fix Round 2 (Lanjutan dari Task 5)

    ## Context

    Plan utama ada di `plan.md` (Task 1–10). Status saat eksekusi dimulai:
    - Task 1 (Company Profile routes) — ✅ selesai (`routes/web.php` sudah explicit routes)
    - Task 2 (Paket::rundowns relation) — ✅ selesai (`app/Models/Paket.php` sudah ada `rundowns()`)
    - Task 3 (Placeholder clean) — ✅ selesai
    - Task 4 (Stats card tabrakan) — ✅ selesai
    - Task 9 (Rewrite plan.md) — ✅ selesai
    - **Task 5, 6, 7, 8, 10 — belum, ini yang harus dikerjakan**
    - **Task 11 — TASK BARU, tambah logo di form auth**

    Plan revisi berdasarkan diskusi dengan user:
    - Task 5: tetap dikerjakan (1=a)
    - Task 7: audit terbatas halaman admin prioritas saja (2=b)
    - Task 8: **REVISI** — gunakan Tailwind v4 semantic tokens (`@theme`) + fix `@custom-variant dark` (3=a, tapi direvisi menjadi pendekatan Tailwind-native yang lebih idiomatic daripada utility
    class custom)
    - Task 11: NEW — tambahkan logo Ghina di form login, forgot password, reset password

    ## Constraints (wajib dipatuhi)

    Sesuai `prd.md`:
    - **Tidak mengubah** logic controller, model, migrasi, seeder.
    - **Tidak mengubah** struktur database atau fitur CRUD.
    - **Tidak mengubah** fitur chatbot, auth flow, atau company profile logic.
    - Figma admin (`figma_designs/admin/*.png`) = source-of-truth untuk warna & layout admin **light mode**. Primary design adalah light mode; dark mode = mirror palette yang sudah ada di
    `[data-theme="dark"]` block di `admin.css`.
    - Palette Figma sudah match dengan `:root { --admin-* }` di `admin.css`:
      - bg `#f3f4f6`, card `#ffffff`, text `#1f2637`, muted `#667085`, border `#d7dce5`, orange `#ff7a1a`, gold `#f5b000`.

    ---

    ## Task 5 — Pindahkan chatbot ke kiri, WhatsApp tetap di kanan

    **Objective:** Chatbot dan WhatsApp tidak overlap.

    **Implementation:**
    - Edit `resources/css/chatbot.css`:
      - `.chatbot-trigger { right: 92px; bottom: 30px; ... }` → ganti `right: 92px` → `left: 24px` (hapus `right`)
      - `.chatbot-container { right: 28px; bottom: 102px; ... }` → ganti `right: 28px` → `left: 28px; right: auto`
      - Media query `@media (max-width: 640px)`:
        - `.chatbot-trigger { right: 90px; bottom: 22px; }` → `left: 16px; bottom: 22px`
        - `.chatbot-container { right: 16px; bottom: 92px; ... }` → `left: 16px; right: auto; bottom: 92px; ...`
    - `resources/css/customer.css` `.wa-float` TETAP `right: 24px; bottom: 24px`. Tidak ubah.
    - Tidak ubah blade file (positioning murni CSS).

    **Verify:** buka browser, chatbot di kiri bawah, WA di kanan bawah; keduanya tidak overlap di desktop & mobile.

    ---

    ## Task 6 — Samakan toggle admin dengan customer

    **Objective:** Toggle admin visual & behavior identik customer.

    **Implementation:**

    1. Edit `resources/views/components/layout/admin.blade.php`:
       - Ganti markup toggle (cari `class="admin-theme-toggle"`):
         ```html
         <label class="tgl" title="Ganti tema">
             <input type="checkbox" id="adminThemeToggle" />
             <span class="sl">
                 <span style="font-size:11px;z-index:1;">☀️</span>
                 <span style="font-size:11px;z-index:1;">🌙</span>
             </span>
         </label>
         ```
       - Di inline script bagian bawah, update:
         ```js
         applyTheme(localStorage.getItem('theme') === 'dark');   // ganti key 'admin-theme' → 'theme'
         ...
         toggle?.addEventListener('change', function() {
             const isDark = toggle.checked;
             localStorage.setItem('theme', isDark ? 'dark' : 'light');   // ganti
             applyTheme(isDark);
         });
         ```
       - Ini menyinkronkan state dark/light antara admin & customer (1 key `theme`).

    2. Edit `resources/css/admin.css`:
       - **Hapus** block CSS: `.admin-theme-toggle`, `.admin-theme-toggle input`, `.admin-theme-toggle__track`, `.admin-theme-toggle__track::after`, `.admin-theme-toggle__icon`,
    `[data-theme="dark"] .admin-theme-toggle__track::after`.
       - **Tambahkan** (copy dari `customer.css` baris 81–113 — style `.tgl`):
         ```css
         .tgl {
           position: relative;
           width: 96px;
           height: 48px;
           cursor: pointer;
           flex-shrink: 0;
         }
         .tgl input { opacity: 0; width: 0; height: 0; }
         .tgl .sl {
           position: absolute;
           inset: 0;
           border-radius: 999px;
           background: var(--gold, #f5b000);
           display: flex;
           align-items: center;
           justify-content: space-between;
           padding: 0 14px;
           color: #fff;
         }
         .tgl .sl::after {
           content: "";
           position: absolute;
           width: 36px;
           height: 36px;
           top: 6px;
           left: 6px;
           border-radius: 50%;
           background: #fff;
           box-shadow: 0 5px 14px rgba(0,0,0,.18);
           transition: transform .25s ease;
         }
         [data-theme="dark"] .tgl .sl::after { transform: translateX(48px); }
         ```
       - Catatan: gunakan `var(--gold, #f5b000)`. Jika `--gold` tidak ada di admin scope, fallback `#f5b000` bekerja (match `--admin-gold`).

    **Verify:** buka sisi customer & admin, toggle theme. Keduanya harus: visual identik (gold pill, white knob yang slide), state tersimpan sama (key `theme`), transisi mulus.

    ---

    ## Task 7 — Audit text contrast halaman admin prioritas

    **Objective:** Text tidak tenggelam di light/dark mode pada halaman prioritas admin.

    **Target file (halaman prioritas saja):**
    - `resources/views/admin/index.blade.php` (Dashboard)
    - `resources/views/admin/paket/index.blade.php`
    - `resources/views/admin/paket/show.blade.php`
    - `resources/views/admin/paket/create.blade.php`
    - `resources/views/admin/paket/edit.blade.php`
    - `resources/views/admin/pesanan/index.blade.php`
    - `resources/views/admin/pesanan/show.blade.php`
    - `resources/views/admin/pesanan/create.blade.php`
    - `resources/views/admin/pesanan/edit.blade.php`
    - `resources/views/admin/pesanan/create-custom.blade.php`
    - `resources/views/admin/pesanan/edit-custom.blade.php`
    - `resources/views/admin/gallery/index.blade.php`
    - `resources/views/admin/gallery/create.blade.php`
    - `resources/views/admin/gallery/show.blade.php`
    - `resources/views/admin/company-profile/show.blade.php`
    - `resources/views/admin/company-profile/edit.blade.php`

    **Implementation:**
    - Scan tiap file untuk pattern yang tidak punya pair `dark:`:
      - `text-black`, `text-white` → ganti dengan pair sesuai context.
      - `text-neutral-400 italic` (empty state) → tambahkan `dark:text-neutral-500`, atau ganti ke utility semantic (lihat Task 8).
      - `text-gray-500` tanpa `dark:` → tambahkan `dark:text-gray-400`.
      - `bg-neutral-800 flex items-center justify-center` (di `gallery/index.blade.php` line 23) → tambahkan `dark:bg-neutral-800` dan ganti default ke `bg-neutral-100` atau `bg-admin-bg` (Task
    8).
    - Fokus: pastikan **label, heading, paragraph, empty state, helper text, badge** punya warna eksplisit untuk kedua tema.
    - Jangan ubah accent colors yang sengaja konstan: `text-red-500` (error), `text-blue-600`, `text-green-600`, `text-purple-600`, `text-amber-500` — semua ini sudah punya pair `dark:` di file
    yang saya periksa.

    **Catatan:** Task 8 akan melakukan refactor besar di file yang sama. Jalankan Task 8 DULU, baru Task 7 sebagai pass audit tambahan untuk tangkap yang terlewat.

    **Urutan eksekusi: Task 8 DULU, lalu Task 7.**

    ---

    ## Task 8 (REVISI) — Fix admin form light mode pakai Tailwind v4 semantic tokens

    **Objective:** Form admin light mode tampil putih bersih sesuai Figma, dark mode tetap elegan; text tidak tenggelam di manapun.

    **Root cause fix:**

    1. Edit `resources/css/app.css`, tambahkan di atas `@theme`:
       ```css
       @import 'tailwindcss';

       @custom-variant dark (&:where(.dark, .dark *));

       @source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
       @source '../../storage/framework/views/*.php';
       @source '../**/*.blade.php';
       @source '../**/*.js';

       @theme {
           --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
               'Segoe UI Symbol', 'Noto Color Emoji';
       }
       ```
       Ini membuat `dark:` variant Tailwind mengikuti class `.dark` di `<html>` (yang sudah ditoggle oleh JS admin & customer). Tanpa ini, Tailwind v4 default pakai `prefers-color-scheme` media
    query — sumber masalah utama.

    2. Edit `resources/css/admin.css`, tambahkan block `@theme` di bagian atas file (setelah `*, body` dan sebelum `:root`), atau di bawah `:root`/`[data-theme="dark"]` — pilih posisi setelah
    `[data-theme="dark"]` block:
       ```css
       @theme {
         --color-admin-bg: #f3f4f6;
         --color-admin-card: #ffffff;
         --color-admin-text: #1f2637;
         --color-admin-muted: #667085;
         --color-admin-border: #d7dce5;
         --color-admin-orange: #ff7a1a;
         --color-admin-gold: #f5b000;
       }
       ```

       ⚠️ **Penting**: `@theme` tidak bisa langsung pakai `var(--admin-bg)` (Tailwind v4 butuh nilai statis saat build). JADI: gunakan nilai hex eksplisit di `@theme` dan biarkan runtime switching
    via `[data-theme="dark"]` di kelas utility yang dihasilkan.

       **Alternatif (lebih baik)**: pakai nilai statis untuk kedua mode dengan cara override di `[data-theme="dark"]` via selector `.dark`:
       ```css
       @theme {
         --color-admin-bg: #f3f4f6;
         --color-admin-card: #ffffff;
         --color-admin-text: #1f2637;
         --color-admin-muted: #667085;
         --color-admin-border: #d7dce5;
         --color-admin-orange: #ff7a1a;
         --color-admin-gold: #f5b000;
       }

       .dark {
         --color-admin-bg: #111318;
         --color-admin-card: #1b1f27;
         --color-admin-text: #f5f7fb;
         --color-admin-muted: #a8b0bf;
         --color-admin-border: #303744;
       }
       ```
       Tailwind v4 mendukung override CSS var di scope child. `bg-admin-card` jadi utility yang otomatis switch saat `.dark` aktif.

       Bersihkan juga existing `:root { --admin-* }` dan `[data-theme="dark"] { --admin-* }` — gabungkan agar tidak duplikasi. Jadi end-state `admin.css` bagian atas:
       ```css
       *, body {
         font-family: "Poppins", "Plus Jakarta Sans", ...;
       }

       @theme {
         --color-admin-bg: #f3f4f6;
         --color-admin-card: #ffffff;
         --color-admin-text: #1f2637;
         --color-admin-muted: #667085;
         --color-admin-border: #d7dce5;
         --color-admin-orange: #ff7a1a;
         --color-admin-gold: #f5b000;
       }

       :root {
         --admin-bg: #f3f4f6;
         --admin-card: #ffffff;
         --admin-text: #1f2637;
         --admin-muted: #667085;
         --admin-border: #d7dce5;
         --admin-orange: #ff7a1a;
         --admin-orange-soft: #fff4eb;
         --admin-gold: #f5b000;
         --admin-shadow: 0 18px 44px rgba(31, 41, 55, .12);
       }

       [data-theme="dark"] {
         --admin-bg: #111318;
         --admin-card: #1b1f27;
         --admin-text: #f5f7fb;
         --admin-muted: #a8b0bf;
         --admin-border: #303744;
         --admin-orange-soft: rgba(255, 122, 26, .14);
         --admin-shadow: 0 18px 44px rgba(0, 0, 0, .28);
       }

       .dark {
         --color-admin-bg: #111318;
         --color-admin-card: #1b1f27;
         --color-admin-text: #f5f7fb;
         --color-admin-muted: #a8b0bf;
         --color-admin-border: #303744;
       }
       ```
       Dengan ini: utility `bg-admin-card`, `text-admin-text`, `text-admin-muted`, `border-admin-border`, `ring-admin-orange`, dll otomatis tersedia dan switch saat `.dark` aktif.

    **Refactor form admin** (di file prioritas Task 7):

    Pattern replacement:
    - `bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800` → `bg-admin-card rounded-xl border border-admin-border`
    - `bg-neutral-50 dark:bg-neutral-800` → `bg-admin-bg`
    - `bg-neutral-50 dark:bg-neutral-800/50` → `bg-admin-bg/50`
    - `text-neutral-700 dark:text-neutral-300` (label) → `text-admin-text`
    - `text-neutral-900 dark:text-neutral-100` (input text) → `text-admin-text`
    - `text-neutral-500 dark:text-neutral-400` (muted/helper) → `text-admin-muted`
    - `text-neutral-400 italic` (empty state) → `text-admin-muted italic`
    - `border-neutral-300 dark:border-neutral-700` (input border) → `border-admin-border`
    - `border-neutral-200 dark:border-neutral-800` (card border) → `border-admin-border`
    - `bg-neutral-100 dark:bg-neutral-800` (upload drop zone bg) → `bg-admin-bg`
    - `text-neutral-400` (icon muted) → `text-admin-muted`

    **Yang TIDAK diubah:**
    - Accent colors (purple/blue/green untuk section headings, red untuk error, amber untuk button primary) — biarkan sudah punya pair `dark:`.
    - `focus:ring-amber-500` / `focus:border-amber-500` — biarkan.
    - Button primary `bg-amber-500 hover:bg-amber-600 text-white` — biarkan.

    **Urutan eksekusi dalam Task 8:**
    1. Fix `@custom-variant dark` di `app.css`
    2. Add `@theme` + `.dark` override di `admin.css`
    3. Refactor 16 file form admin (list di Task 7)
    4. (Task 7) Pass audit akhir untuk tangkap pair yang terlewat

    ---

    ## Task 11 (NEW) — Tambah logo Ghina di form auth

    **Objective:** Login, Lupa Password, Reset Password menampilkan logo Ghina sesuai Figma.

    **Figma reference:**
    - `figma_designs/admin/Admin Login.png`
    - `figma_designs/admin/Admin Forgot Password.png`
    - `figma_designs/admin/Admin Konfirmasi Password.png`

    Logo tampil di antara brand title "**Ghina** Tour Travel" (line 1) dan heading halaman (line 2: "Admin Login" / "Lupa Password" / "Password Baru"). Logo bulat berdiameter sekitar 84px.

    **Implementation:**

    Untuk tiap file (`resources/views/login.blade.php`, `resources/views/forgot-password.blade.php`, `resources/views/reset-password.blade.php`):

    1. Di `<style>` block, tambahkan CSS `.brand-logo`:
       ```css
       .brand-logo {
         display: flex;
         justify-content: center;
         margin: 1.25rem 0 1rem;
       }
       .brand-logo img {
         width: 84px;
         height: 84px;
         object-fit: contain;
       }
       ```

    2. Di HTML body, setelah `div.brand` (brand title) dan sebelum heading halaman, tambahkan:
       ```html
       <div class="brand-logo">
         <img src="{{ asset('customer/assets/images/logos/logo.png') }}" alt="Ghina Tour Travel">
       </div>
       ```

    3. Baca dulu tiap file lengkap untuk konfirmasi posisi exact. Struktur mungkin:
       ```html
       <div class="card">
         <div class="brand">Ghina Tour Travel</div>
         {{-- TAMBAH LOGO DI SINI --}}
         <h1>Admin Login</h1>
         ...
       </div>
       ```

    4. Jika `<div class="brand">` sudah berisi logo inline (periksa dulu), skip atau sesuaikan. Berdasarkan grep sebelumnya, kemungkinan belum ada logo di auth forms.

    **Verify:** buka `/login`, `/forgot-password`, `/reset-password/{token}` — logo muncul di posisi yang sama seperti Figma.

    ---

    ## Task 10 — Build, test, verifikasi

    **Objective:** Semua perubahan ter-build, tidak ada regresi.

    **Implementation:**
    1. Jalankan `npm run build` — harus sukses tanpa error. Jika ada warning CSS tentang utility class baru (`bg-admin-card` dll), pastikan semua tercompile. Jika gagal, cek syntax `@theme` /
    `@custom-variant` di `app.css` dan `admin.css`.
    2. Jalankan `php artisan route:list --name=admin.company-profile` — harus tampil 3 routes (show/edit/update) tanpa `{company_profile}` parameter (Task 1 sudah done, ini verifikasi).
    3. Laporkan ke user:
       - File yang diubah (list lengkap)
       - Build status
       - Route list output
       - Catatan manual test yang perlu dilakukan user:
         - Customer: Beranda (stats card, placeholder, toggle), Semua Paket, Detail Paket, Semua Foto
         - Admin: Login (logo muncul), forgot/reset password (logo muncul), Dashboard, Paket CRUD, Pesanan CRUD + custom, Galeri CRUD, Company Profile show/edit
         - Toggle dark/light di admin & customer — state sinkron (local storage `theme`)
         - Chatbot di kiri bawah, WhatsApp di kanan bawah, tidak overlap
         - Text tidak tenggelam di light mode form admin

    **Catatan:** karena agent tidak bisa buka browser, manual test di browser diserahkan ke user. Agent hanya jalankan build & route check.

    ---

    ## Demo (hasil akhir untuk user)

    Setelah semua task selesai:
    - Chatbot di kiri bawah, WhatsApp di kanan bawah — tidak overlap.
    - Toggle admin identik customer (gold pill, white knob slide), state tersinkron via localStorage.
    - Form admin light mode tampil persis Figma: bg putih, text dark slate, input putih dengan border halus. Dark mode tetap elegan, text kontras.
    - Logo Ghina muncul di form login, forgot password, reset password sesuai Figma.
    - Tidak ada text tenggelam di manapun.
    - Company Profile menu dapat dibuka tanpa error (Task 1 done).
    - Detail Paket dapat dibuka tanpa error (Task 2 done).
    - Stats card beranda tidak tabrakan dengan Tentang Kami (Task 4 done).
    - Placeholder foto kosong clean (Task 3 done).
    - `npm run build` sukses, route list OK.

    ---

    ## Urutan eksekusi (rekomendasi)

    1. **Task 5** — CSS chatbot (simple, isolated)
    2. **Task 8** — root fix `@custom-variant dark` + `@theme` tokens di `app.css` & `admin.css` (foundation)
    3. **Task 8** — refactor 16 file form admin (banyak edit, paling lama)
    4. **Task 7** — audit pass untuk tangkap hardcoded text color yang terlewat
    5. **Task 6** — toggle admin (sedikit edit blade + CSS)
    6. **Task 11** — logo di 3 form auth (edit kecil)
    7. **Task 10** — build & verify
    8. Report ke user
