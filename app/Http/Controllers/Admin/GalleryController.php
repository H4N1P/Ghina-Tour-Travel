<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // FIX: get() tidak menerima argumen — gunakan latest()->get() untuk semua data
        $galleries = Gallery::with(['tempat', 'fasilitas'])->latest()->get();
        return view('admin.gallery.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // FIX: relasi di model Paket bernama 'tempats' bukan 'tempat'
        $pakets = Paket::with(['tempats', 'fasilitas'])->latest()->get();
        return view('admin.gallery.create', compact('pakets'));
    }

    public function show(Gallery $id)
    {
        $id->load(['tempat', 'fasilitas']);
        return view('admin.gallery.show', compact('id'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // FIX: tambah validasi 'images' => 'array' agar array validation bekerja dengan benar
        $request->validate([
            'images'       => 'required|array',
            'images.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'id_fasilitas' => 'nullable|exists:fasilitas,id',
            'id_tempat'    => 'nullable|exists:tempats,id',
        ]);

        foreach ($request->file('images') as $image) {
            $path = $image->store('galleries', 'public');
            Gallery::create([
                'path'         => $path,
                'id_fasilitas' => $request->id_fasilitas,
                'id_tempat'    => $request->id_tempat,
            ]);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery berhasil ditambahkan');
    }


    /**
     * Remove the specified resource from storage.
     * FIX: hapus file dari disk storage sebelum delete record
     */
    public function destroy(Gallery $id)
    {
        // FIX: hapus file dari storage saat foto dihapus
        if (Storage::disk('public')->exists($id->path)) {
            Storage::disk('public')->delete($id->path);
        }

        $id->delete();
        return redirect()->route('admin.gallery.index')->with('success', 'Gallery berhasil dihapus');
    }
}
