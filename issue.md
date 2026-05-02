# Plan: Enhancement Galeri, UI Admin, dan Company Profile

**Deskripsi:**
Issue ini mencakup beberapa pembaruan fungsionalitas dan antarmuka pengguna, baik di sisi Admin maupun Customer. Pekerjaan difokuskan pada integrasi media (foto/video) yang lebih baik pada paket wisata, penyelarasan tema antarmuka (Dark/Light mode), kontrol visibilitas data spesifik (Rundown), serta penambahan modul manajemen Company Profile.

---

## 1. Manajemen Galeri & Media (Foto/Video)
**Tujuan:** Mengembangkan fitur galeri admin untuk mendukung unggahan foto dan video terkompresi yang terelasi langsung dengan Paket, Destinasi, dan Fasilitas.

**High-Level Task:**
*   **Backend (Upload & Kompresi):** 
    *   Implementasikan logika kompresi otomatis untuk unggahan foto dan video pada modul Galeri.
    *   Simpan data media ke dalam tabel `galleries`.
*   **Admin UI (Form Tambah Galeri):**
    *   Buat dropdown/opsi relasi saat *upload*: pilih penempatan media (Paket, Destinasi Tempat, atau Fasilitas).
    *   *Rules:* Jika memilih Destinasi atau Fasilitas, *user* wajib memilih/filter Paket-nya terlebih dahulu. (Catatan: Foto pada fasilitas bersifat opsional).
*   **Customer UI (Tampilan Media):**
    *   Tampilkan *thumbnail* Paket di halaman Home (Section Paket) dan halaman "Semua Paket".
    *   Pada halaman Detail Paket, integrasikan media yang terhubung: jadikan gambar latar (*background*), serta tampilkan gambar Destinasi dan Fasilitas (jika ada).
    *   Tampilkan *thumbnail* galeri di halaman Home (Section Galeri) dan buat halaman khusus "Semua Galeri".

## 2. Implementasi Tema Dark/Light Mode (Admin)
**Tujuan:** Membawa fitur *toggle* tema yang sudah ada di sisi Customer ke Dashboard Admin.

**High-Level Task:**
*   Integrasikan *script* atau *state management* (contoh: Tailwind dark mode class, local storage) yang digunakan di UI Customer ke dalam layout Admin.
*   Pastikan *toggle switch* tema tersedia dan berfungsi dengan baik di seluruh halaman Dashboard Admin.

## 3. Penyesuaian Data Paket (Rundown)
**Tujuan:** Menampilkan informasi Rundown di tabel Admin dan membatasi akses melihat Rundown di halaman Customer hanya untuk Admin.

**High-Level Task:**
*   **Admin - Index Paket:** Tambahkan kolom untuk menampilkan data (atau ringkasan) `rundown` pada tabel *list* Paket di Dashboard Admin.
*   **Customer - Detail Paket:** 
    *   Tambahkan komponen *Rundown* di halaman detail paket customer. Letakkan bersebelahan (dalam 1 *card*/komponen yang sama) dengan daftar *Fasilitas*.
    *   Berikan kondisional (`if auth/session admin`): Rundown **hanya** boleh di-*render* atau dilihat jika pengguna yang mengakses halaman tersebut sedang *login* sebagai Admin. Pelanggan biasa tidak boleh melihatnya.

## 4. Manajemen Company Profile (Admin)
**Tujuan:** Membuat modul CRUD/Edit sederhana untuk profil perusahaan di Dashboard Admin.

**High-Level Task:**
*   Gunakan model `CompanyProfile.php` dan buat/perbarui `CompanyProfileController.php`.
*   Buat halaman di Admin Dashboard untuk mengedit data profil perusahaan (About, Vision & Mission, Kontak, Social Media) yang diambil dari tabel `company_profiles`.
