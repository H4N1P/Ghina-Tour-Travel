<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanans = Pesanan::latest()->paginate(10);
        return view('admin.pesanan.index', compact('pesanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pakets = Paket::with(['fasilitas', 'tempats'])->get();
        return view('admin.pesanan.create', compact('pakets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_paket'      => 'required|exists:pakets,id',
            'nama_pemesan'  => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
            'diskon'        => 'nullable|numeric|min:0|max:100',
            'total_harga'   => 'required|numeric|min:0',
            'tanggal_acara' => 'required|date',
            // FIX: tambah validasi jumlah_orang yang hilang dari store()
            'jumlah_orang'  => 'required|integer|min:1',
        ]);

        Pesanan::create([
            'id_paket'      => $request->id_paket,
            'nama_pemesan'  => $request->nama_pemesan,
            'no_hp'         => $request->no_hp,
            'diskon'        => $request->diskon ?? 0,
            // FIX: hitung total_harga konsisten — ambil dari request (sudah dihitung di frontend)
            'total_harga'   => $request->total_harga,
            'jumlah_orang'  => $request->jumlah_orang,
            'tanggal_acara' => $request->tanggal_acara,
            'invoice'       => 'INV-' . strtoupper(uniqid()),
            'status'        => 'pending',
        ]);

        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pesanan = Pesanan::with('paket')->findOrFail($id);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pesanan $pesanan)
    {
        // FIX: rename parameter dari $id ke $pesanan agar route model binding bekerja benar
        // Gunakan variable $id agar view edit.blade.php yang menggunakan $id tetap kompatibel
        $id = $pesanan;
        $pakets = Paket::with(['fasilitas', 'tempats'])->get();
        return view('admin.pesanan.edit', compact('id', 'pakets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_paket'      => 'required|exists:pakets,id',
            'nama_pemesan'  => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
            'diskon'        => 'nullable|numeric|min:0|max:100',
            'total_harga'   => 'required|numeric|min:0',
            'tanggal_acara' => 'required|date',
            'jumlah_orang'  => 'required|integer|min:1',
            'status'        => 'nullable|in:pending,batal,selesai',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        // FIX: gunakan only() bukan $request->all() agar tidak ada field berbahaya yang masuk (misal invoice)
        $pesanan->update($request->only([
            'id_paket',
            'nama_pemesan',
            'no_hp',
            'diskon',
            'total_harga',
            'jumlah_orang',
            'tanggal_acara',
            'status',
        ]));

        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pesanan $pesanan)
    {
        // FIX: rename $id ke $pesanan agar route model binding sesuai konvensi Laravel
        $pesanan->delete();
        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
