@extends('components.layout.admin')
@section('title', 'Upload Foto Galeri')
@section('header', 'Upload Foto Galeri')

@section('content')
    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6">
        @csrf

        {{-- Upload Area --}}
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
            <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800 flex items-center gap-3">
                <a href="{{ route('admin.gallery.index') }}"
                    class="p-2 rounded-lg text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h3 class="text-lg font-semibold">Upload Foto</h3>
            </div>
            <div class="p-4 lg:p-6">
                {{-- Dropzone --}}
                <div id="dropzone"
                    class="flex flex-col items-center justify-center w-full h-48 border-2 border-neutral-300 dark:border-neutral-700 border-dashed rounded-xl cursor-pointer bg-neutral-50 dark:bg-neutral-800 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors"
                    onclick="document.getElementById('images').click()"
                    ondragover="event.preventDefault(); this.classList.add('border-amber-500','bg-amber-50')"
                    ondragleave="this.classList.remove('border-amber-500','bg-amber-50')" ondrop="handleDrop(event)">
                    <svg class="w-10 h-10 mb-3 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-300">Klik atau drag & drop foto di sini
                    </p>
                    <p class="text-xs text-neutral-400 mt-1">JPG, PNG, GIF, SVG — Maks. 2MB per file</p>
                </div>
                <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden"
                    onchange="previewImages(this)">

                @error('images')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror

                {{-- Preview Grid --}}
                <div id="preview-grid" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mt-4"></div>
            </div>
        </div>

        {{-- Relasi Opsional --}}
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
            <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800">
                <h3 class="text-lg font-semibold">Relasi (Opsional)</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Kaitkan foto dengan tempat atau fasilitas
                    tertentu</p>
            </div>
            <div class="p-4 lg:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Tempat
                        Wisata</label>
                    <select name="id_tempat"
                        class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 transition-colors">
                        <option value="">-- Tidak dikaitkan --</option>
                        @foreach ($pakets as $paket)
                            @foreach ($paket->tempats as $tempat)
                                <option value="{{ $tempat->id }}" {{ old('id_tempat') == $tempat->id ? 'selected' : '' }}>
                                    {{ $paket->nama_paket }} → {{ $tempat->nama_tempat }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('id_tempat')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Fasilitas</label>
                    <select name="id_fasilitas"
                        class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-amber-500 transition-colors">
                        <option value="">-- Tidak dikaitkan --</option>
                        @foreach ($pakets as $paket)
                            @foreach ($paket->fasilitas as $fas)
                                <option value="{{ $fas->id }}"
                                    {{ old('id_fasilitas') == $fas->id ? 'selected' : '' }}>
                                    {{ $paket->nama_paket }} → {{ $fas->nama_fasilitas }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('id_fasilitas')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.gallery.index') }}"
                class="px-5 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">Batal</a>
            <button type="submit"
                class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">
                Upload Foto
            </button>
        </div>
    </form>

    <script>
        function previewImages(input) {
            const grid = document.getElementById('preview-grid');
            grid.innerHTML = '';
            Array.from(input.files).forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className =
                        'relative aspect-square rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    grid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });

            // Update dropzone text
            if (input.files.length > 0) {
                document.getElementById('dropzone').innerHTML = `
                    <svg class="w-8 h-8 mb-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-sm font-medium text-amber-600">${input.files.length} foto dipilih</p>
                    <p class="text-xs text-neutral-400 mt-1">Klik untuk mengganti</p>
                `;
            }
        }

        function handleDrop(event) {
            event.preventDefault();
            event.currentTarget.classList.remove('border-amber-500', 'bg-amber-50');
            const input = document.getElementById('images');
            // Assign dropped files to input
            const dt = event.dataTransfer;
            // Use DataTransfer to set files on input
            Object.defineProperty(input, 'files', {
                value: dt.files,
                writable: true
            });
            previewImages({
                files: dt.files
            });
        }
    </script>
@endsection
