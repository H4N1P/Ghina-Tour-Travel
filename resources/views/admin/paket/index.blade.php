@extends('admin.layouts.app')

@section('title', 'Kelola Paket Tour')
@section('header', 'Kelola Paket Tour')

@section('content')
<div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800">
    <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold">Daftar Paket Tour</h3>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Kelola semua paket tour yang tersedia</p>
        </div>
        <a href="{{ route('admin.paket.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Paket
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                <tr>
                    <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">No</th>
                    <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Nama Paket</th>
                    <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Harga</th>
                    <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Komponen</th>
                    <th class="px-4 lg:px-6 py-3 text-right text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                @forelse($pakets as $index => $paket)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition-colors">
                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                        {{ $pakets->firstItem() + $index }}
                    </td>
                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ $paket->nama_paket }}</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate max-w-xs">{{ Str::limit($paket->note, 30) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">
                        Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}
                    </td>
                    <td class="px-4 lg:px-6 py-4">
                        <div class="flex flex-wrap gap-1.5">
                            @if($paket->tempats->count() > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $paket->tempats->count() }} Tempat
                                </span>
                            @endif
                            @if($paket->konsumsis->count() > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    {{ $paket->konsumsis->count() }} Konsumsi
                                </span>
                            @endif
                            @if($paket->akomodasis->count() > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                    {{ $paket->akomodasis->count() }} Akomodasi
                                </span>
                            @endif
                            @if($paket->transportasis->count() > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                    {{ $paket->transportasis->count() }} Transport
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.paket.show', $paket->id) }}" class="p-2 rounded-lg text-neutral-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors" title="Lihat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.paket.edit', $paket->id) }}" class="p-2 rounded-lg text-neutral-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.paket.destroy', $paket->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus paket ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg text-neutral-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <p class="text-neutral-500 dark:text-neutral-400 font-medium">Belum ada paket tour</p>
                            <p class="text-sm text-neutral-400 dark:text-neutral-500 mt-1">Tambahkan paket tour pertama Anda</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pakets->hasPages())
    <div class="px-4 lg:px-6 py-4 border-t border-neutral-200 dark:border-neutral-800">
        {{ $pakets->links() }}
    </div>
    @endif
</div>
@endsection
