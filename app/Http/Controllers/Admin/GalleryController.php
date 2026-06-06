<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Paket;
use App\Traits\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    use ImageCompressor;

    /**
     * Menampilkan galeri sesuai kategori yang dipilih.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'semua');

        $query = Gallery::with(['destinasi.paket', 'fasilitas.paket'])->latest();

        switch ($filter) {
            case 'destinasi':
                $query->whereNotNull('id_destinasi');
                break;
            case 'fasilitas':
                $query->whereNotNull('id_fasilitas');
                break;
            case 'dokumentasi':
                $query->whereNull('id_destinasi')->whereNull('id_fasilitas');
                break;
            // Nilai 'semua' tidak menambahkan filter.
        }

        $galleries = $query->get();
        return view('admin.gallery.index', compact('galleries', 'filter'));
    }

    /**
     * Menampilkan formulir penambahan media galeri.
     */
    public function create()
    {
        // Memuat relasi destinasi dan fasilitas untuk pilihan formulir.
        $pakets = Paket::with(['destinasis', 'fasilitas'])->latest()->get();
        return view('admin.gallery.create', compact('pakets'));
    }

    /**
     * Menampilkan detail satu media galeri.
     */
    public function show(Gallery $gallery)
    {
        $gallery->load(['destinasi', 'fasilitas']);
        return view('admin.gallery.show', compact('gallery'));
    }

    /**
     * Mengembalikan destinasi dan fasilitas paket untuk kebutuhan formulir dinamis.
     */
    public function getRelationsByPaket(Request $request)
    {
        $paket = Paket::with(['destinasis', 'fasilitas'])->find($request->paket_id);

        if (!$paket) {
            return response()->json(['destinasis' => [], 'fasilitas' => []]);
        }

        return response()->json([
            'destinasis' => $paket->destinasis->map(fn($t) => ['id' => $t->id, 'nama' => $t->nama_destinasi]),
            'fasilitas' => $paket->fasilitas->map(fn($f) => ['id' => $f->id, 'nama' => $f->nama_fasilitas]),
        ]);
    }

    /**
     * Memvalidasi dan menyimpan gambar atau video baru ke galeri.
     */
    public function store(Request $request)
    {
        $request->validate([
            'media'        => 'required|array',
            'media.*'      => 'file|mimes:jpeg,png,jpg,gif,svg,webp,mp4,mov,avi|max:51200', // 50MB max
            'id_fasilitas' => 'nullable|exists:fasilitas,id',
            'id_destinasi' => 'nullable|exists:destinasis,id',
        ]);

        foreach ($request->file('media') as $file) {
            $mime = $file->getMimeType();
            $type = str_starts_with($mime, 'video/') ? 'video' : 'image';

            // Mengompres gambar dengan GD tanpa library eksternal.
            if ($type === 'image') {
                $path = $this->compressAndStoreImage($file, 'galleries');
            } else {
                // Menyimpan video langsung tanpa FFMpeg.
                $path = $file->store('galleries', 'public');
            }

            Gallery::create([
                'path'         => $path,
                'type'         => $type,
                'id_fasilitas' => $request->id_fasilitas,
                'id_destinasi' => $request->id_destinasi,
            ]);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Media berhasil ditambahkan');
    }


    /**
     * Menghapus media galeri beserta file fisiknya.
     */
    public function destroy(Gallery $gallery)
    {
        // Menghapus file dari storage sebelum record galeri dihapus.
        if (Storage::disk('public')->exists($gallery->path)) {
            Storage::disk('public')->delete($gallery->path);
        }

        $gallery->delete();
        return redirect()->route('admin.gallery.index')->with('success', 'Media berhasil dihapus');
    }
}
