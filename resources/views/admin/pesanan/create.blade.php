@extends('components.layout.admin')
@section('title', 'Tambah Pesanan')
@section('header', 'Tambah Pesanan')

@section('content')
    <form action="{{ route('admin.pesanan.store') }}" method="POST" class="max-w-3xl space-y-6">
        @csrf

        {{-- Tipe Pesanan --}}
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
            <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
                <h3 class="text-lg font-semibold">Tipe Pesanan</h3>
            </div>
            <div class="p-4 lg:p-6">
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="tipe_pesanan" value="default" class="text-amber-500"
                            {{ old('tipe_pesanan', 'default') === 'default' ? 'checked' : '' }}
                            onchange="toggleTipe(this.value)">
                        <span class="text-sm font-medium">Paket Default</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="tipe_pesanan" value="custom" class="text-amber-500"
                            {{ old('tipe_pesanan') === 'custom' ? 'checked' : '' }} onchange="toggleTipe(this.value)">
                        <span class="text-sm font-medium">Custom Destinasi</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Pilih Paket (Default) --}}
        <div id="paket-section"
            class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
            <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
                <h3 class="text-lg font-semibold text-amber-600">Pilih Paket</h3>
            </div>
            <div class="p-4 lg:p-6">
                <select name="id_paket"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 transition-colors">
                    <option value="">-- Pilih Paket --</option>
                    @foreach ($pakets as $pk)
                        <option value="{{ $pk->id }}" {{ old('id_paket') == $pk->id ? 'selected' : '' }}>
                            {{ $pk->nama_paket }} — Rp {{ number_format($pk->harga_paket, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Custom Destinasi --}}
        <div id="custom-section"
            class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 hidden">
            <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
                <h3 class="text-lg font-semibold text-blue-600">Destinasi Custom</h3>
            </div>
            <div class="p-4 lg:p-6">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Deskripsi
                    Destinasi</label>
                <textarea name="destinasi_custom" rows="3"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 transition-colors resize-none"
                    placeholder="Contoh: Bali 3D2N, Gunung Bromo, dll...">{{ old('destinasi_custom') }}</textarea>
            </div>
        </div>

        {{-- Data Pemesan --}}
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
            <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
                <h3 class="text-lg font-semibold">Data Pemesan</h3>
            </div>
            <div class="p-4 lg:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Nama Perwakilan
                        <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pemesan" value="{{ old('nama_pemesan') }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors"
                        placeholder="Nama perwakilan rombongan">
                    @error('nama_pemesan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">No. HP <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors"
                        placeholder="08xx-xxxx-xxxx">
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Tanggal Acara <span
                            class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_acara" value="{{ old('tanggal_acara') }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors">
                    @error('tanggal_acara')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Jumlah Orang <span
                            class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_orang" value="{{ old('jumlah_orang', 1) }}" required min="1"
                        class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors">
                    @error('jumlah_orang')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Harga & Diskon --}}
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
            <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
                <h3 class="text-lg font-semibold">Harga & Diskon</h3>
            </div>
            <div class="p-4 lg:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Total Harga (Rp)
                        <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500">Rp</span>
                        <input type="number" name="total_harga" id="total_harga" value="{{ old('total_harga') }}" required
                            min="0" step="1000"
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors"
                            placeholder="0" oninput="hitungAkhir()">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Diskon (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500">Rp</span>
                        <input type="number" name="diskon" id="diskon" value="{{ old('diskon', 0) }}" min="0"
                            step="1000"
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors"
                            placeholder="0" oninput="hitungAkhir()">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 flex items-center justify-between">
                        <span class="font-semibold text-green-700 dark:text-green-400">Total Setelah Diskon</span>
                        <span id="total-akhir" class="text-xl font-bold text-green-700 dark:text-green-400">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
            <div class="p-4 lg:p-6">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Status Pesanan</label>
                <select name="status"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors">
                    <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="konfirmasi" {{ old('status') === 'konfirmasi' ? 'selected' : '' }}>Konfirmasi</option>
                    <option value="selesai" {{ old('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ old('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.pesanan.index') }}"
                class="px-5 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">Batal</a>
            <button type="submit"
                class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">Simpan
                Pesanan</button>
        </div>
    </form>

    <script>
        function toggleTipe(val) {
            document.getElementById('paket-section').classList.toggle('hidden', val === 'custom');
            document.getElementById('custom-section').classList.toggle('hidden', val !== 'custom');
        }

        function hitungAkhir() {
            const total = parseFloat(document.getElementById('total_harga').value) || 0;
            const diskon = parseFloat(document.getElementById('diskon').value) || 0;
            const akhir = Math.max(0, total - diskon);
            document.getElementById('total-akhir').textContent = 'Rp ' + akhir.toLocaleString('id-ID');
        }
        // Init
        const initialTipe = '{{ old('tipe_pesanan', 'default') }}';
        if (initialTipe === 'custom') toggleTipe('custom');
        hitungAkhir();
    </script>
@endsection
