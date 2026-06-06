<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\Paket;
use App\Models\Rundown;
use App\Models\Destinasi;
use App\Traits\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaketController extends Controller
{
    use ImageCompressor;

    /**
     * Menampilkan daftar paket wisata beserta relasi utamanya.
     */
    public function index()
    {
        $pakets = Paket::with(['fasilitas', 'destinasis.galleries', 'rundowns'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.paket.index', compact('pakets'));
    }

    /**
     * Menampilkan formulir penambahan paket wisata.
     */
    public function create()
    {
        return view('admin.paket.create');
    }

    /**
     * Memvalidasi dan menyimpan paket baru beserta destinasi, fasilitas, dan rundown.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_paket'      => 'required|string|max:255',
            'harga_paket'     => 'required|numeric',
            'durasi'          => 'required|string|max:255',
            'note'            => 'nullable|string',

            'image'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            // Validasi rundown bertingkat.
            'rundowns'                => 'nullable|array',
            'rundowns.*.waktu'        => 'required|string|max:255',
            'rundowns.*.acara'        => 'required|string|max:255',
            'rundowns.*.deskripsi'    => 'nullable|string',

            // Validasi destinasi bertingkat.
            'destinasis'                  => 'nullable|array',
            'destinasis.*.nama_destinasi' => 'required|string|max:255',
            'destinasis.*.image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            // Validasi fasilitas bertingkat.
            'fasilitas'                => 'nullable|array',
            'fasilitas.*.nama_fasilitas' => 'required|string',
            'fasilitas.*.tipe_fasilitas' => 'required|string|in:konsumsi,akomodasi,transportasi',
            'fasilitas.*.image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $paketData = $request->only(['nama_paket', 'harga_paket', 'durasi', 'note']);
            
            if ($request->hasFile('image')) {
                $paketData['image'] = $this->compressAndStoreImage($request->file('image'), 'images/paket');
            }

            $paket = Paket::create($paketData);

            // Menyimpan destinasi paket.
            if ($request->has('destinasis')) {
                foreach ($request->destinasis as $index => $destinasiData) {
                    $payload = ['nama_destinasi' => $destinasiData['nama_destinasi']];
                    
                    if ($request->hasFile("destinasis.{$index}.image")) {
                        $payload['image'] = $this->compressAndStoreImage($request->file("destinasis.{$index}.image"), 'images/destinasi');
                    }
                    
                    $paket->destinasis()->create($payload);
                }
            }

            // Menyimpan fasilitas paket.
            if ($request->has('fasilitas')) {
                foreach ($request->fasilitas as $index => $fasilitasData) {
                    $payload = [
                        'nama_fasilitas' => $fasilitasData['nama_fasilitas'],
                        'tipe_fasilitas' => $fasilitasData['tipe_fasilitas'],
                    ];
                    
                    if ($request->hasFile("fasilitas.{$index}.image")) {
                        $payload['image'] = $this->compressAndStoreImage($request->file("fasilitas.{$index}.image"), 'images/fasilitas');
                    }

                    $paket->fasilitas()->create($payload);
                }
            }

            // Menyimpan rundown paket.
            if ($request->has('rundowns')) {
                foreach ($request->rundowns as $rundown) {
                    $paket->rundowns()->create([
                        'waktu'     => $rundown['waktu'],
                        'acara'     => $rundown['acara'],
                        'deskripsi' => $rundown['deskripsi'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.paket.index')
                ->with('success', 'Paket berhasil ditambahkan beserta foto-fotonya');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('failed', 'Gagal menambahkan paket: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail satu paket wisata.
     */
    public function show(Paket $paket)
    {
        $paket->load(['fasilitas']);
        return view('admin.paket.show', compact('paket'));
    }

    /**
     * Menampilkan formulir perubahan paket wisata.
     */
    public function edit(Paket $paket)
    {
        $paket->load(['fasilitas']);
        return view('admin.paket.edit', compact('paket'));
    }

    /**
     * Memvalidasi dan menyimpan perubahan paket beserta relasinya.
     */
    public function update(Request $request, Paket $paket)
    {
        $request->validate([
            'nama_paket'      => 'required|string|max:255',
            'harga_paket'     => 'required|numeric',
            'durasi'          => 'required|string|max:255',
            'note'            => 'nullable|string',

            'image'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            // Validasi rundown bertingkat.
            'rundowns'                => 'nullable|array',
            'rundowns.*.id'           => 'nullable|exists:rundowns,id',
            'rundowns.*.waktu'        => 'required|string|max:255',
            'rundowns.*.acara'        => 'required|string|max:255',
            'rundowns.*.deskripsi'    => 'nullable|string',

            // Validasi destinasi bertingkat.
            'destinasis'                  => 'nullable|array',
            'destinasis.*.id'             => 'nullable|exists:destinasis,id',
            'destinasis.*.nama_destinasi' => 'required|string|max:255',
            'destinasis.*.image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            // Validasi fasilitas bertingkat.
            'fasilitas'                => 'nullable|array',
            'fasilitas.*.id'             => 'nullable|exists:fasilitas,id',
            'fasilitas.*.nama_fasilitas' => 'required|string',
            'fasilitas.*.tipe_fasilitas' => 'required|string|in:konsumsi,akomodasi,transportasi',
            'fasilitas.*.image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $paketData = $request->only(['nama_paket', 'harga_paket', 'durasi', 'note']);
        
        if ($request->hasFile('image')) {
            // Menghapus gambar lama sebelum menyimpan gambar pengganti.
            if ($paket->image && Storage::disk('public')->exists($paket->image)) {
                Storage::disk('public')->delete($paket->image);
            }
            $paketData['image'] = $this->compressAndStoreImage($request->file('image'), 'images/paket');
        }

        $paket->update($paketData);

        // Menyinkronkan destinasi paket.
        $this->syncChildWithImages($paket, 'destinasis', $request->destinasis ?? [], Destinasi::class, $request);
    
        // Menyinkronkan fasilitas paket.
        $this->syncChildWithImages($paket, 'fasilitas', $request->fasilitas ?? [], Fasilitas::class, $request);

        // Menyinkronkan rundown paket.
        $this->syncRundowns($paket, $request->rundowns ?? []);

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil diperbarui');
    }

    /**
     * Menghapus paket serta file gambar terkait dari penyimpanan.
     */
    public function destroy(Paket $paket)
    {
        // Menghapus seluruh file gambar yang terkait dengan paket.
        if ($paket->image && Storage::disk('public')->exists($paket->image)) {
            Storage::disk('public')->delete($paket->image);
        }

        foreach ($paket->destinasis as $destinasi) {
            if ($destinasi->image && Storage::disk('public')->exists($destinasi->image)) {
                Storage::disk('public')->delete($destinasi->image);
            }
            foreach ($destinasi->galleries as $foto) {
                if (Storage::disk('public')->exists($foto->path)) {
                    Storage::disk('public')->delete($foto->path);
                }
            }
        }
        
        foreach ($paket->fasilitas as $f) {
            if ($f->image && Storage::disk('public')->exists($f->image)) {
                Storage::disk('public')->delete($f->image);
            }
        }

        $paket->delete();

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil dihapus');
    }

    /**
     * Menyinkronkan data destinasi atau fasilitas beserta gambar yang diunggah.
     */
    private function syncChildWithImages(
        Paket $paket,
        string $relation,
        array $incomingData,
        string $modelClass,
        Request $request
    ) {
        if (empty($incomingData)) {
            $paket->{$relation}()->delete();
            return;
        }
    
        $incomingIds = collect($incomingData)
            ->pluck('id')
            ->filter()
            ->all();
    
        $existingChilds = $paket->{$relation}()->whereNotIn('id', $incomingIds)->get();
        foreach($existingChilds as $child) {
            if ($child->image && Storage::disk('public')->exists($child->image)) {
                Storage::disk('public')->delete($child->image);
            }
        }
    
        $paket->{$relation}()
            ->whereNotIn('id', $incomingIds)
            ->delete();
    
        foreach ($incomingData as $index => $item) {
    
            if ($relation === 'destinasis') {
                $payload = [
                    'nama_destinasi' => $item['nama_destinasi'],
                ];
            } else {
                $payload = [
                    'nama_fasilitas' => $item['nama_fasilitas'],
                    'tipe_fasilitas' => $item['tipe_fasilitas'],
                ];
            }

            // Memproses gambar baru jika tersedia.
            if ($request->hasFile("{$relation}.{$index}.image")) {
                $payload['image'] = $this->compressAndStoreImage($request->file("{$relation}.{$index}.image"), "images/" . rtrim($relation, 's'));
                
                // Menghapus gambar lama saat data diperbarui.
                if (!empty($item['id'])) {
                    $oldChild = $modelClass::find($item['id']);
                    if ($oldChild && $oldChild->image && Storage::disk('public')->exists($oldChild->image)) {
                        Storage::disk('public')->delete($oldChild->image);
                    }
                }
            }
    
            if (!empty($item['id'])) {
                $modelClass::where('id', $item['id'])
                    ->where('id_paket', $paket->id)
                    ->update($payload);
            } else {
                $paket->{$relation}()->create($payload);
            }
        }
    }

    /**
     * Menyinkronkan rundown masuk dengan data rundown paket yang tersimpan.
     */
    private function syncRundowns(Paket $paket, array $incomingData)
    {
        if (empty($incomingData)) {
            $paket->rundowns()->delete();
            return;
        }

        $incomingIds = collect($incomingData)
            ->pluck('id')
            ->filter()
            ->all();

        $paket->rundowns()
            ->whereNotIn('id', $incomingIds)
            ->delete();

        foreach ($incomingData as $item) {
            $payload = [
                'waktu'     => $item['waktu'],
                'acara'     => $item['acara'],
                'deskripsi' => $item['deskripsi'] ?? null,
            ];

            if (!empty($item['id'])) {
                Rundown::where('id', $item['id'])
                    ->where('id_paket', $paket->id)
                    ->update($payload);
            } else {
                $paket->rundowns()->create($payload);
            }
        }
    }
}
