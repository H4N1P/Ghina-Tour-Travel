# UI Enhancements: Customer Paket Slideshow & Admin Views Refactoring

## 📌 Deskripsi Tugas

Issue ini bertujuan untuk melakukan peningkatan antarmuka pengguna (UI) pada dua bagian utama aplikasi:

1. Memperbarui tampilan daftar paket wisata pada halaman _customer_ menjadi bentuk _slideshow_ (karosel).
2. Memperbaiki dan menyeragamkan seluruh tampilan panel Admin agar sesuai dengan _mockup_ atau referensi desain.

---

## 📋 High-Level Implementation Plan

### 1. Implementasi Slideshow pada Customer Index (Section Paket)

- **Lokasi Target:** Halaman utama customer / section yang menampilkan daftar paket wisata (`resources/views/customer/`).
- **Rencana Tindakan:**
    - Ubah struktur tata letak (grid/list) yang menampilkan daftar paket menjadi bentuk _slideshow_.
    - Integrasikan pustaka _slideshow_ (contoh: Swiper.js, Splide, atau _carousel default_ bawaan UI framework jika sudah tersedia di _project_).
    - Pastikan _slideshow_ memiliki navigasi yang intuitif (tombol _prev/next_ dan indikator titik/dots).
    - Buat tampilan _slideshow_ _fully responsive_ (menampilkan jumlah kartu/paket yang sesuai berdasarkan lebar layar perangkat: _mobile_, _tablet_, _desktop_).

### 2. Refactoring dan Perbaikan Tampilan Admin

- **Lokasi Target:** Seluruh _file view_ di dalam direktori `resources/views/admin/` beserta sub-direktorinya.
- **Referensi Desain:** Folder `UI/Admin/`, dan komponen dari folder `UI/Component`
- **Rencana Tindakan:**
    - **Analisis Referensi:** Tinjau terlebih dahulu _file_ desain atau kode referensi yang ada di dalam folder `UI/Admin/` dan `UI/Component` untuk memahami _style guide_ (warna, tipografi, struktur tabel, form, _sidebar_, dll).
    - **Standardisasi Layout Utama:** Terapkan standar desain tersebut pada _layout_ utama (induk) admin.
    - **Penerapan Bertahap:** Terapkan gaya visual yang baru pada halaman-halaman spesifik (Dashboard, Manajemen Paket, Manajemen Pesanan, dll).
    - **Konsistensi UI:** Pastikan komponen-komponen yang sering digunakan seperti tombol, _modal_ CRUD, form input, dan tabel data memiliki desain yang konsisten di seluruh halaman admin.

---

## 🔄 Langkah Pengerjaan yang Disarankan (Action Items)

- [ ] Mengerjakan integrasi dan fungsionalitas _slideshow_ untuk daftar paket di sisi _customer_.
- [ ] Memastikan _slideshow_ responsif dan bekerja dengan baik pada berbagai perangkat.
- [ ] Melakukan pengecekan referensi desain di `UI/Admin/` dan `UI/Component`.
- [ ] Memperbaiki _layout_ utama admin (header, sidebar, footer).
- [ ] Memperbaiki antarmuka untuk fitur CRUD di setiap entitas admin (Paket, Pesanan, Galeri, dll) agar sesuai dengan referensi.
- [ ] Uji coba (_testing_) visual pada halaman _customer_ maupun _admin_ untuk memastikan tidak ada UI yang '_broken_'.

\*email admin : [admin@example.com] password : [password/password123]
