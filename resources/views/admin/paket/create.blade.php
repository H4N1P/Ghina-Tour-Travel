<script>
    function addField(containerId, placeholder, name) {
        const container = document.getElementById(containerId);
        const wrapper = document.createElement('div');
        wrapper.className = 'dynamic-field flex items-center gap-2';
        wrapper.innerHTML = `
            <input type="text" name="${name}[]" placeholder="${placeholder}"
                class="flex-1 px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
            <button type="button" onclick="removeField(this)" class="p-2 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        container.appendChild(wrapper);
    }

    function removeField(btn) {
        btn.closest('.dynamic-field').remove();
    }
</script>

@extends('admin.layouts.app')

@section('title', 'Tambah Paket Tour')
@section('header', 'Tambah Paket Tour')

@section('content')
<form action="{{ route('admin.paket.store') }}" method="POST" class="space-y-6">
    @csrf

    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
            <h3 class="text-lg font-semibold">Informasi Paket</h3>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Nama paket dan harga</p>
        </div>
        <div class="p-4 lg:p-6 space-y-4">
            <div>
                <label for="nama_paket" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Nama Paket <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_paket" name="nama_paket" value="{{ old('nama_paket') }}" required
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                    placeholder="Contoh: Paket Umroh Premium 9 Hari">
                @error('nama_paket')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="harga_paket" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Harga Paket (Rp) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500">Rp</span>
                    <input type="number" id="harga_paket" name="harga_paket" value="{{ old('harga_paket') }}" required min="0" step="1000"
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                        placeholder="0">
                </div>
                @error('harga_paket')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="note" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Catatan / Deskripsi
                </label>
                <textarea id="note" name="note" rows="3"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors resize-none"
                    placeholder="Tambahkan deskripsi atau catatan tambahan...">{{ old('note') }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400">Tempat Wisata</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Daftar tempat yang dikunjungi</p>
                </div>
                <button type="button" onclick="addField('tempats-container', 'Nama tempat wisata', 'tempats')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah
                </button>
            </div>
        </div>
        <div class="p-4 lg:p-6">
            <div id="tempats-container" class="space-y-2"></div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-green-600 dark:text-green-400">Konsumsi</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Fasilitas makan & minum</p>
                </div>
                <button type="button" onclick="addField('konsumsis-container', 'Contoh: Makan 3x sehari', 'konsumsis')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah
                </button>
            </div>
        </div>
        <div class="p-4 lg:p-6">
            <div id="konsumsis-container" class="space-y-2"></div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-purple-600 dark:text-purple-400">Akomodasi</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Penginapan & hotel</p>
                </div>
                <button type="button" onclick="addField('akomodasis-container', 'Contoh: Hotel Bintang 4', 'akomodasis')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah
                </button>
            </div>
        </div>
        <div class="p-4 lg:p-6">
            <div id="akomodasis-container" class="space-y-2"></div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
        <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-orange-600 dark:text-orange-400">Transportasi</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Kendaraan & perjalanan</p>
                </div>
                <button type="button" onclick="addField('transportasis-container', 'Contoh: Bus AC Premium', 'transportasis')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/20 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah
                </button>
            </div>
        </div>
        <div class="p-4 lg:p-6">
            <div id="transportasis-container" class="space-y-2"></div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.paket.index') }}" class="px-5 py-2.5 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-colors">
            Batal
        </a>
        <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">
            Simpan Paket
        </button>
    </div>
</form>
@endsection
