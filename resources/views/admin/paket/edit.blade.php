@extends('components.layout.admin')
@section('title', 'Edit Paket Tour')
@section('header', 'Edit Paket Tour')

@section('content')
<form action="{{ route('admin.paket.update', $paket->id) }}" method="POST" class="max-w-4xl space-y-6">
    @csrf
    @method('PUT')

    {{-- ── Informasi Paket ── --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800 flex items-center gap-3">
            <a href="{{ route('admin.paket.show', $paket->id) }}"
                class="p-2 rounded-lg text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h3 class="text-lg font-semibold">Informasi Paket</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-0.5">Nama, harga, durasi, dan catatan paket</p>
            </div>
        </div>
        <div class="p-4 lg:p-6 space-y-4">

            <div>
                <label for="nama_paket" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Nama Paket <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_paket" name="nama_paket"
                    value="{{ old('nama_paket', $paket->nama_paket) }}" required
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                @error('nama_paket')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="harga_paket" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Harga / Pax (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 text-sm">Rp</span>
                        <input type="number" id="harga_paket" name="harga_paket"
                            value="{{ old('harga_paket', $paket->harga_paket) }}" required min="0" step="1000"
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                    </div>
                    @error('harga_paket')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="durasi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Durasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="durasi" name="durasi"
                        value="{{ old('durasi', $paket->durasi) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                    @error('durasi')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="note" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Catatan / Note
                </label>
                <textarea id="note" name="note" rows="3"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors resize-none">{{ old('note', $paket->note) }}</textarea>
            </div>
        </div>
    </div>

    {{-- ── Rundown Detail ── --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-purple-600 dark:text-purple-400">Rundown Detail (Harian)</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-0.5">Jadwal harian kegiatan/acara paket tour</p>
            </div>
            <button type="button" onclick="addRundownField()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-colors border border-purple-200 dark:border-purple-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Rundown
            </button>
        </div>
        <div class="p-4 lg:p-6">
            <div id="rundowns-container" class="space-y-4">
                {{-- Populated via JS from existing data --}}
            </div>
        </div>
    </div>

    {{-- ── Tempat Wisata ── --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400">Tempat Wisata</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-0.5">Daftar destinasi yang akan dikunjungi</p>
            </div>
            <button type="button" onclick="addTempatField()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors border border-blue-200 dark:border-blue-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Tempat
            </button>
        </div>
        <div class="p-4 lg:p-6">
            <div id="tempats-container" class="space-y-3">
                {{-- Populated via JS from existing data --}}
            </div>
        </div>
    </div>

    {{-- ── Fasilitas ── --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-green-600 dark:text-green-400">Fasilitas</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-0.5">Konsumsi, akomodasi, dan transportasi</p>
            </div>
            <button type="button" onclick="addFasilitasField()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors border border-green-200 dark:border-green-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Fasilitas
            </button>
        </div>
        <div class="p-4 lg:p-6">
            <div id="fasilitas-container" class="space-y-3">
                {{-- Populated via JS from existing data --}}
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3 pb-4">
        <a href="{{ route('admin.paket.show', $paket->id) }}"
            class="px-5 py-2.5 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-colors">
            Batal
        </a>
        <button type="submit"
            class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg transition-colors">
            Simpan Perubahan
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
// ── Existing data from server ──────────────────────────────────
const existingRundowns = @json($paket->rundowns->map(fn($r) => ['id' => $r->id, 'waktu' => $r->waktu, 'acara' => $r->acara, 'deskripsi' => $r->deskripsi]));
const existingTempats = @json($paket->tempats->map(fn($t) => ['id' => $t->id, 'nama_tempat' => $t->nama_tempat]));
const existingFasilitas = @json($paket->fasilitas->map(fn($f) => ['id' => $f->id, 'nama_fasilitas' => $f->nama_fasilitas, 'tipe_fasilitas' => $f->tipe_fasilitas]));

// ── Rundown helpers ─────────────────────────────────────────────
function addRundownField(id = '', waktu = '', acara = '', deskripsi = '') {
    const container = document.getElementById('rundowns-container');
    const index = container.querySelectorAll('.field-row').length;
    const div = document.createElement('div');
    div.className = 'field-row grid grid-cols-1 md:grid-cols-12 gap-4 items-start p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800/50';
    div.innerHTML = `
        <input type="hidden" name="rundowns[${index}][id]" value="${id}">
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Waktu / Hari</label>
            <input type="text" name="rundowns[${index}][waktu]" value="${waktu}"
                class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-sm"
                placeholder="Contoh: Hari 1 / 08.00">
        </div>
        <div class="md:col-span-4">
            <label class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Acara / Kegiatan</label>
            <input type="text" name="rundowns[${index}][acara]" value="${acara}"
                class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-sm"
                placeholder="Contoh: Kedatangan di Bandara">
        </div>
        <div class="md:col-span-4">
            <label class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Deskripsi</label>
            <input type="text" name="rundowns[${index}][deskripsi]" value="${deskripsi}"
                class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-sm"
                placeholder="Contoh: Penjemputan di gate kedatangan...">
        </div>
        <div class="md:col-span-1 flex items-end">
            <button type="button" onclick="removeRundownRow(this)"
                class="w-full p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors flex-shrink-0 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>`;
    container.appendChild(div);
}

function removeRundownRow(btn) {
    const container = document.getElementById('rundowns-container');
    btn.closest('.field-row').remove();
    if (container.querySelectorAll('.field-row').length === 0) {
        const p = document.createElement('p');
        p.id = 'rundowns-empty';
        p.className = 'text-sm text-neutral-400 italic';
        p.innerHTML = 'Belum ada rundown. Klik &quot;+ Tambah Rundown&quot; untuk menambahkan.';
        container.appendChild(p);
    }
}

// ── Tempat helpers ─────────────────────────────────────────────
function addTempatField(id = '', value = '') {
    const container = document.getElementById('tempats-container');
    const index = container.querySelectorAll('.field-row').length;
    const div = document.createElement('div');
    div.className = 'field-row flex gap-3 items-center';
    div.innerHTML = `
        <span class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold flex items-center justify-center flex-shrink-0">${index + 1}</span>
        ${id ? `<input type="hidden" name="tempats[${index}][id]" value="${id}">` : ''}
        <input type="text" name="tempats[${index}][nama_tempat]" value="${escHtml(value)}"
            class="flex-1 px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
            placeholder="Nama destinasi (contoh: Mekkah, Madinah)">
        <button type="button" onclick="removeRow(this,'tempats-container')"
            class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>`;
    container.appendChild(div);
    renumberRows('tempats-container');
}

// ── Fasilitas helpers ──────────────────────────────────────────
function addFasilitasField(id = '', nama = '', tipe = 'konsumsi') {
    const container = document.getElementById('fasilitas-container');
    const index = container.querySelectorAll('.field-row').length;
    const div = document.createElement('div');
    div.className = 'field-row flex gap-3 items-center';
    div.innerHTML = `
        ${id ? `<input type="hidden" name="fasilitas[${index}][id]" value="${id}">` : ''}
        <input type="text" name="fasilitas[${index}][nama_fasilitas]" value="${escHtml(nama)}"
            class="flex-1 px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
            placeholder="Nama fasilitas (contoh: Hotel Bintang 5)">
        <select name="fasilitas[${index}][tipe_fasilitas]"
            class="w-44 px-3 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 text-sm">
            <option value="konsumsi" ${tipe==='konsumsi'?'selected':''}>🍽 Konsumsi</option>
            <option value="akomodasi" ${tipe==='akomodasi'?'selected':''}>🏨 Akomodasi</option>
            <option value="transportasi" ${tipe==='transportasi'?'selected':''}>🚌 Transportasi</option>
        </select>
        <button type="button" onclick="removeRow(this,'fasilitas-container')"
            class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>`;
    container.appendChild(div);
}

function removeRow(btn, containerId) {
    btn.closest('.field-row').remove();
    renumberRows(containerId);
}

function renumberRows(containerId) {
    document.querySelectorAll(`#${containerId} .field-row`).forEach((row, i) => {
        row.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${i}]`);
        });
        const badge = row.querySelector('.rounded-full');
        if (badge) badge.textContent = i + 1;
    });
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// ── Populate existing data on DOM ready ────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    existingRundowns.forEach(r => addRundownField(r.id, r.waktu, r.acara, r.deskripsi));
    existingTempats.forEach(t => addTempatField(t.id, t.nama_tempat));
    existingFasilitas.forEach(f => addFasilitasField(f.id, f.nama_fasilitas, f.tipe_fasilitas));
});
</script>
@endpush
