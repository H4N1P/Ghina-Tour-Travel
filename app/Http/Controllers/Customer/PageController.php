<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Foto;
use App\Models\Gallery;
use App\Models\Paket;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Menampilkan halaman utama dengan paket dan galeri terbaru.
     */
    public function index()
    {
        try {
            $pakets = Paket::with(['fasilitas', 'destinasis.galleries', 'fotos'])
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();

            $fotos = Gallery::whereNull('id_destinasi')
                ->whereNull('id_fasilitas')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        } catch (QueryException) {
            $pakets = collect();
            $fotos = collect();
        }

        return view('customer.index', compact('pakets', 'fotos'));
    }

    /**
     * Menampilkan daftar paket dan menerapkan pencarian nama paket atau destinasi.
     */
    public function packages(Request $request)
    {
        $query = trim((string) $request->input('q', ''));

        try {
            $pakets = Paket::with(['fasilitas', 'destinasis.galleries', 'fotos'])
                ->when($query !== '', function ($builder) use ($query) {
                    $builder->where(function ($searchQuery) use ($query) {
                        $searchQuery
                            ->where('nama_paket', 'like', "%{$query}%")
                            ->orWhereHas('destinasis', function ($destinationQuery) use ($query) {
                                $destinationQuery->where('nama_destinasi', 'like', "%{$query}%");
                            });
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(12)
                ->withQueryString();
        } catch (QueryException) {
            $pakets = new LengthAwarePaginator([], 0, 12);
            $pakets->withPath(route('packages'));
        }

        if ($request->ajax()) {
            return view('customer.partials.package-results', compact('pakets', 'query'));
        }

        return view('customer.packages', compact('pakets', 'query'));
    }

    /**
     * Menampilkan detail paket beserta rundown dan relasinya.
     */
    public function packageDetail($id)
    {
        $paket = Paket::with(['rundowns', 'fasilitas', 'destinasis.galleries', 'fotos'])
            ->findOrFail($id);

        return view('customer.package-detail', compact('paket'));
    }

    /**
     * Menampilkan seluruh dokumentasi galeri umum.
     */
    public function photos()
    {
        try {
            $fotos = Gallery::whereNull('id_destinasi')
                ->whereNull('id_fasilitas')
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        } catch (QueryException) {
            $fotos = new LengthAwarePaginator([], 0, 12);
            $fotos->withPath(route('photos'));
        }

        return view('customer.photos', compact('fotos'));
    }

    /**
     * Mengarahkan kata pencarian pelanggan ke halaman daftar paket.
     */
    public function search(Request $request)
    {
        return redirect()->route('packages', ['q' => $request->input('q')]);
    }
}
