@extends('components.layout.admin')
@section('title', 'Detail Pesanan')
@section('header', 'Detail Pesanan')

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 overflow-hidden">
            {{-- Header --}}
            <div class="p-4 lg:p-6 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.pesanan.index') }}"
                        class="p-2 rounded-lg text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h3 class="text-lg font-semibold">Detail Pesanan</h3>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 font-mono">{{ $pesanan->invoice }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                        {{ $pesanan->status === 'selesai' ? 'bg-green-100 text-green-700' :
                           ($pesanan->status === 'batal'   ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($pesanan->status ?? 'pending') }}
                    </span>
                    <a href="{{ route('admin.pesanan.edit', $pesanan->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="p-4 lg:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl p-4">
                    <p class="text-xs text-neutral-500 font-medium uppercase tracking-wider mb-3">Data Pemesan</p>
                    <table class="w-full text-sm">
                        <tr><td class="text-neutral-500 py-1 pr-4 w-32">Nama</td><td class="font-medium">{{ $pesanan->nama_pemesan }}</td></tr>
                        <tr><td class="text-neutral-500 py-1 pr-4">No. HP</td><td>{{ $pesanan->no_hp }}</td></tr>
                        <tr><td class="text-neutral-500 py-1 pr-4">Tanggal Acara</td><td>{{ \Carbon\Carbon::parse($pesanan->tanggal_acara)->format('d F Y') }}</td></tr>
                        <tr><td class="text-neutral-500 py-1 pr-4">Jumlah Orang</td><td>{{ $pesanan->jumlah_orang ?? '-' }} orang</td></tr>
                    </table>
                </div>

                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl p-4">
                    <p class="text-xs text-neutral-500 font-medium uppercase tracking-wider mb-3">Rincian Harga</p>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="text-neutral-500 py-1 pr-4 w-32">Paket</td>
                            <td class="font-medium">{{ $pesanan->paket->nama_paket ?? '-' }}</td>
                        </tr>
                        @if($pesanan->paket && $pesanan->jumlah_orang)
                        <tr>
                            <td class="text-neutral-500 py-1 pr-4">Subtotal</td>
                            <td>Rp {{ number_format($pesanan->paket->harga_paket * $pesanan->jumlah_orang, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-neutral-500 py-1 pr-4">Diskon</td>
                            <td class="text-red-500">{{ $pesanan->diskon ?? 0 }}%</td>
                        </tr>
                        <tr class="border-t border-neutral-200 dark:border-neutral-700">
                            <td class="text-neutral-700 dark:text-neutral-300 py-2 pr-4 font-semibold">Total</td>
                            <td class="font-bold text-green-600 text-lg">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="px-4 lg:px-6 pb-6 flex gap-2 justify-end border-t border-neutral-100 dark:border-neutral-800 pt-4">
                <form action="{{ route('admin.pesanan.destroy', $pesanan->id) }}" method="POST"
                    onsubmit="return confirm('Yakin hapus pesanan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                        Hapus Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
