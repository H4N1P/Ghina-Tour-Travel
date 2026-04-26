@extends('components.layout.admin')
@section('title', 'Galeri')
@section('header', 'Galeri')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Galeri</h1>
        <a href="{{ route('admin.galeri.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Galeri
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @forelse($galleries as $g)
            <div
                class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 overflow-hidden group">
                <div class="relative h-40">
                    @if ($g->media_type === 'video')
                        <div
                            class="w-full h-full bg-gradient-to-br from-purple-600 to-blue-600 flex flex-col items-center justify-center gap-2">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                            <p class="text-white text-xs font-medium">Video Testimoni</p>
                        </div>
                    @elseif($g->path)
                        <img src="{{ asset('storage/' . $g->path) }}" alt="{{ $g->keterangan }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center">
                            <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-2 left-2 flex gap-1">
                        <span
                            class="px-1.5 py-0.5 text-xs font-bold rounded {{ $g->tipe === 'fasilitas' ? 'bg-blue-500 text-white' : 'bg-amber-500 text-black' }}">{{ ucfirst($g->tipe) }}</span>
                        @if ($g->sub_tipe)
                            <span
                                class="px-1.5 py-0.5 text-xs font-bold rounded bg-white/80 text-neutral-700">{{ ucfirst($g->sub_tipe) }}</span>
                        @endif
                    </div>
                </div>
                <div class="p-3">
                    <p class="text-xs font-medium text-neutral-700 dark:text-neutral-300 truncate">
                        {{ $g->keterangan ?? 'Tanpa keterangan' }}</p>
                    @if ($g->paket)
                        <p class="text-xs text-neutral-400 mt-0.5 truncate">{{ $g->paket->nama_paket }}</p>
                    @endif
                    <div class="flex gap-1 mt-2">
                        <a href="{{ route('admin.galeri.edit', $g->id) }}"
                            class="flex-1 text-center text-xs py-1 text-amber-600 bg-amber-50 hover:bg-amber-100 rounded font-medium transition-colors">Edit</a>
                        <form action="{{ route('admin.galeri.destroy', $g->id) }}" method="POST"
                            onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-xs py-1 px-2 text-red-600 bg-red-50 hover:bg-red-100 rounded font-medium transition-colors">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <svg class="w-16 h-16 mx-auto text-neutral-300 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-neutral-400">Belum ada item galeri</p>
            </div>
        @endforelse
    </div>

    @if ($galleries->hasPages())
        <div class="mt-6">{{ $galleries->links() }}</div>
    @endif
@endsection
