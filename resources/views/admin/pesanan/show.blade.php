@extends('components.layout.admin')
@section('title', 'Detail Pesanan')
@section('header', 'Detail Pesanan')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.pesanan.index') }}"
                        class="p-2 rounded-lg text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h3 class="text-lg font-bold">{{ $pesanan->invoice }}</h3>
                        <p class="text-sm text-neutral-400">{{ $pesanan->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                <span
                    class="inline-block px-3 py-1 rounded-full text-sm font-bold {{ $pesanan->status === 'selesai' ? 'bg-green-100 text-green-700' : ($pesanan->status === 'batal' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                    {{ ucfirst($pesanan->status ?? 'Pending') }}
                </span>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl p-4">
                        <p class="text-xs text-neutral-400 mb-1">Nama Pemesan</p>
                        <p class="font-semibold">{{ $pesanan->nama_pemesan }}</p>
                    </div>
                    <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl p-4">
                        <p class="text-xs text-neutral-400 mb-1">No. HP</p>
                        <p class="font-semibold">{{ $pesanan->no_hp }}</p>
                    </div>
                    <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl p-4">
                        <p class="text-xs text-neutral-400 mb-1">Tipe Pesanan</p>
                        <span
                            class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold {{ $pesanan->tipe_pesanan === 'custom' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($pesanan->tipe_pesanan ?? 'default') }}</span>
                    </div>
                    <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl p-4">
                        <p class="text-xs text-neutral-400 mb-1">Tanggal Acara</p>
                        <p class="font-semibold">
                            {{ $pesanan->tanggal_acara ? $pesanan->tanggal_acara->format('d M Y') : '-' }}</p>
                    </div>
                    <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl p-4">
                        <p class="text-xs text-neutral-400 mb-1">Paket / Destinasi</p>
                        <p class="font-semibold">{{ $pesanan->paket->nama_paket ?? ($pesanan->destinasi_custom ?? '-') }}
                        </p>
                    </div>
                    <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl p-4">
                        <p class="text-xs text-neutral-400 mb-1">Jumlah Orang</p>
                        <p class="font-semibold">{{ $pesanan->jumlah_orang }} orang</p>
                    </div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 space-y-2">
                    <div class="flex justify-between text-sm"><span class="text-neutral-500">Total Harga</span><span
                            class="font-semibold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span></div>
                    @if ($pesanan->diskon > 0)
                        <div class="flex justify-between text-sm"><span class="text-red-500">Diskon</span><span
                                class="font-semibold text-red-500">- Rp
                                {{ number_format($pesanan->diskon, 0, ',', '.') }}</span></div>
                        <div class="border-t border-green-200 pt-2 flex justify-between"><span
                                class="font-bold text-green-700">Total Dibayar</span><span
                                class="font-bold text-green-700 text-lg">Rp
                                {{ number_format($pesanan->total_harga - $pesanan->diskon, 0, ',', '.') }}</span></div>
                    @endif
                </div>
            </div>
            <div class="p-6 border-t border-neutral-200 dark:border-neutral-800 flex gap-3">
                <a href="{{ route('admin.pesanan.edit', $pesanan->id) }}"
                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">Edit</a>
                <form action="{{ route('admin.pesanan.destroy', $pesanan->id) }}" method="POST"
                    onsubmit="return confirm('Hapus pesanan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">Hapus</button>
                </form>
            </div>
        </div>
    </div>
@endsection
PHPEOF

# Quick edit view
cat > /home/claude/ghina-tour-travel/resources/views/admin/pesanan/edit.blade.php << 'PHPEOF' @extends('admin.layouts.app')
    @section('title', 'Edit Pesanan' ) @section('header', 'Edit Pesanan' ) @section('content') <form
    action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST" class="max-w-3xl space-y-6">
    @csrf @method('PUT')

    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
            <h3 class="text-lg font-semibold">Tipe Pesanan</h3>
        </div>
        <div class="p-4 lg:p-6">
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="tipe_pesanan" value="default"
                        {{ old('tipe_pesanan', $pesanan->tipe_pesanan) === 'default' ? 'checked' : '' }}
                        onchange="toggleTipe(this.value)">
                    <span class="text-sm font-medium">Paket Default</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="tipe_pesanan" value="custom"
                        {{ old('tipe_pesanan', $pesanan->tipe_pesanan) === 'custom' ? 'checked' : '' }}
                        onchange="toggleTipe(this.value)">
                    <span class="text-sm font-medium">Custom Destinasi</span>
                </label>
            </div>
        </div>
    </div>

    <div id="paket-section"
        class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 {{ old('tipe_pesanan', $pesanan->tipe_pesanan) === 'custom' ? 'hidden' : '' }}">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
            <h3 class="text-lg font-semibold text-amber-600">Pilih Paket</h3>
        </div>
        <div class="p-4 lg:p-6">
            <select name="id_paket"
                class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors">
                <option value="">-- Pilih Paket --</option>
                @foreach ($pakets as $pk)
                    <option value="{{ $pk->id }}"
                        {{ old('id_paket', $pesanan->id_paket) == $pk->id ? 'selected' : '' }}>{{ $pk->nama_paket }} —
                        Rp {{ number_format($pk->harga_paket, 0, ',', '.') }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="custom-section"
        class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 {{ old('tipe_pesanan', $pesanan->tipe_pesanan) !== 'custom' ? 'hidden' : '' }}">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
            <h3 class="text-lg font-semibold text-blue-600">Destinasi Custom</h3>
        </div>
        <div class="p-4 lg:p-6">
            <textarea name="destinasi_custom" rows="3"
                class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors resize-none">{{ old('destinasi_custom', $pesanan->destinasi_custom) }}</textarea>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
            <h3 class="text-lg font-semibold">Data Pemesan</h3>
        </div>
        <div class="p-4 lg:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Nama Perwakilan
                    <span class="text-red-500">*</span></label>
                <input type="text" name="nama_pemesan" value="{{ old('nama_pemesan', $pesanan->nama_pemesan) }}"
                    required
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">No. HP <span
                        class="text-red-500">*</span></label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $pesanan->no_hp) }}" required
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Tanggal Acara
                    <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_acara"
                    value="{{ old('tanggal_acara', $pesanan->tanggal_acara?->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Jumlah Orang <span
                        class="text-red-500">*</span></label>
                <input type="number" name="jumlah_orang" value="{{ old('jumlah_orang', $pesanan->jumlah_orang) }}"
                    required min="1"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors">
            </div>
        </div>
    </div>

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
                    <input type="number" name="total_harga" id="total_harga"
                        value="{{ old('total_harga', $pesanan->total_harga) }}" required min="0"
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors"
                        oninput="hitungAkhir()">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Diskon
                    (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500">Rp</span>
                    <input type="number" name="diskon" id="diskon"
                        value="{{ old('diskon', $pesanan->diskon) }}" min="0"
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors"
                        oninput="hitungAkhir()">
                </div>
            </div>
            <div class="sm:col-span-2">
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 flex items-center justify-between">
                    <span class="font-semibold text-green-700">Total Setelah Diskon</span>
                    <span id="total-akhir" class="text-xl font-bold text-green-700">Rp 0</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6">
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Status Pesanan</label>
            <select name="status"
                class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 focus:ring-2 focus:ring-amber-500 transition-colors">
                <option value="pending" {{ old('status', $pesanan->status) === 'pending' ? 'selected' : '' }}>Pending
                </option>
                <option value="konfirmasi" {{ old('status', $pesanan->status) === 'konfirmasi' ? 'selected' : '' }}>
                    Konfirmasi</option>
                <option value="selesai" {{ old('status', $pesanan->status) === 'selesai' ? 'selected' : '' }}>Selesai
                </option>
                <option value="batal" {{ old('status', $pesanan->status) === 'batal' ? 'selected' : '' }}>Batal
                </option>
            </select>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.pesanan.index') }}"
            class="px-5 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">Batal</a>
        <button type="submit"
            class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">Update
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
        hitungAkhir();
    </script>
@endsection
