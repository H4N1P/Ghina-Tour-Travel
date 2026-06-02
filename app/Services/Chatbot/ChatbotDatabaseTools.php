<?php

namespace App\Services\Chatbot;

use App\Models\CompanyProfile;
use App\Models\Paket;
use App\Models\Pesanan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ChatbotDatabaseTools
{
    /** @var array<int, string> */
    private const ALLOWED_TOOLS = [
        'get_company_profile',
        'search_tour_packages',
        'get_package_detail',
        'search_orders',
    ];

    public function declarations(): array
    {
        return [
            [
                'name' => 'get_company_profile',
                'description' => 'Read Ghina Tour Travel company profile and official contact data. Use for address, WhatsApp, email, Instagram, about company, vision, mission, booking contact, and admin contact questions.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => (object) [],
                ],
            ],
            [
                'name' => 'search_tour_packages',
                'description' => 'Read tour package summaries from the database. Use for package lists, recommendations, destinations, facilities, price comparisons, cheapest package, most expensive package, or popular packages. For generic questions such as cheapest/recommendation/list all, leave query empty and set sort_by.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'query' => [
                            'type' => 'string',
                            'description' => 'Specific package name, destination name, facility name, or travel keyword from the customer request. Leave empty for generic package list, cheapest package, or recommendation.',
                        ],
                        'max_price' => [
                            'type' => 'number',
                            'description' => 'Maximum package price in Indonesian Rupiah when the customer mentions a budget.',
                        ],
                        'sort_by' => [
                            'type' => 'string',
                            'description' => 'Sort result by customer intent.',
                            'enum' => ['cheapest', 'expensive', 'popular', 'newest'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'get_package_detail',
                'description' => 'Read complete detail for one package, including price, duration, destinations, facilities, rundown, and notes. Use only when a package id is known from search_tour_packages or customer context.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'package_id' => [
                            'type' => 'number',
                            'description' => 'Package id returned by search_tour_packages.',
                        ],
                    ],
                    'required' => ['package_id'],
                ],
            ],
            [
                'name' => 'search_orders',
                'description' => 'Read a customer order status from the database. Use only when the customer asks about booking/order/invoice/status and provides an invoice number or their own phone number. If neither identifier exists, ask the customer for invoice or phone number instead of calling this tool.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'customer_phone' => [
                            'type' => 'string',
                            'description' => 'Customer phone number provided by the customer. Use only for that customer, not for listing all orders.',
                        ],
                        'invoice' => [
                            'type' => 'string',
                            'description' => 'Invoice number provided by the customer. Match exactly.',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function isAllowed(string $name): bool
    {
        return in_array($name, self::ALLOWED_TOOLS, true);
    }

    public function execute(string $name, array $arguments): array
    {
        return match ($name) {
            'get_company_profile' => $this->companyProfile(),
            'search_tour_packages' => $this->searchTourPackages($arguments),
            'get_package_detail' => $this->packageDetail((int) ($arguments['package_id'] ?? 0)),
            'search_orders' => $this->searchOrders($arguments),
            default => [
                'ok' => false,
                'message' => 'Akses data tersebut tidak tersedia untuk customer service.',
            ],
        };
    }

    private function companyProfile(): array
    {
        $profile = CompanyProfile::query()->latest('id')->first();

        return [
            'ok' => (bool) $profile,
            'company' => $profile ? [
                'name' => 'Ghina Tour Travel',
                'about' => $profile->about,
                'vision_mission' => $profile->vision_mission,
                'whatsapp' => $profile->whatsapp,
                'email' => $profile->email,
                'address' => $profile->address,
                'instagram' => $profile->instagram,
            ] : null,
            'message' => $profile ? null : 'Profil perusahaan belum tersedia di database.',
        ];
    }

    private function searchTourPackages(array $arguments): array
    {
        $query = trim((string) ($arguments['query'] ?? ''));
        $maxPrice = $this->positiveNumber($arguments['max_price'] ?? null);
        $sortBy = $this->validSort((string) ($arguments['sort_by'] ?? 'cheapest'));

        $pakets = Paket::query()
            ->with(['destinasis', 'fasilitas'])
            ->withCount('pesanans')
            ->when($query !== '', fn (Builder $builder) => $this->applyPackageSearch($builder, $query))
            ->when($maxPrice !== null, fn (Builder $builder) => $builder->where('harga_paket', '<=', $maxPrice))
            ->tap(fn (Builder $builder) => $this->applyPackageSort($builder, $sortBy))
            ->limit(8)
            ->get();

        return [
            'ok' => true,
            'query' => $query,
            'sort_by' => $sortBy,
            'max_price' => $maxPrice,
            'count' => $pakets->count(),
            'packages' => $pakets->map(fn (Paket $paket) => $this->packageSummary($paket))->values(),
            'message' => $pakets->isEmpty() ? 'Tidak ada paket yang cocok dengan kriteria tersebut.' : null,
        ];
    }

    private function packageDetail(int $packageId): array
    {
        $paket = Paket::query()
            ->with(['destinasis', 'fasilitas', 'rundowns'])
            ->find($packageId);

        return [
            'ok' => (bool) $paket,
            'package' => $paket ? [
                ...$this->packageSummary($paket),
                'rundown' => $paket->rundowns
                    ->sortBy('id')
                    ->map(fn ($rundown) => [
                        'time' => $rundown->waktu,
                        'activity' => $rundown->acara,
                        'description' => $rundown->deskripsi,
                    ])
                    ->values(),
            ] : null,
            'message' => $paket ? null : 'Paket tidak ditemukan di database.',
        ];
    }

    private function searchOrders(array $arguments): array
    {
        $phone = $this->digitsOnly((string) ($arguments['customer_phone'] ?? ''));
        $invoice = trim((string) ($arguments['invoice'] ?? ''));

        if ($invoice === '' && $phone === '') {
            return [
                'ok' => false,
                'message' => 'Nomor invoice atau nomor HP diperlukan untuk mengecek status pesanan.',
                'count' => 0,
                'orders' => [],
            ];
        }

        if ($invoice === '' && mb_strlen($phone) < 8) {
            return [
                'ok' => false,
                'message' => 'Nomor HP terlalu pendek. Minta pelanggan mengirim nomor HP lengkap atau nomor invoice.',
                'count' => 0,
                'orders' => [],
            ];
        }

        $orders = $this->orderQuery($invoice, $phone)
            ->latest()
            ->limit(6)
            ->get();

        if ($invoice !== '' && $phone !== '') {
            $orders = $orders->filter(
                fn (Pesanan $pesanan) => str_contains($this->digitsOnly($pesanan->no_hp), $phone)
            )->values();
        }

        return [
            'ok' => true,
            'count' => $orders->count(),
            'orders' => $orders->map(fn (Pesanan $pesanan) => $this->orderSummary($pesanan))->values(),
            'message' => $orders->isEmpty()
                ? 'Pesanan tidak ditemukan untuk identifier tersebut.'
                : null,
        ];
    }

    private function applyPackageSearch(Builder $builder, string $query): void
    {
        $builder->where(function (Builder $nested) use ($query) {
            $nested
                ->where('nama_paket', 'like', "%{$query}%")
                ->orWhere('note', 'like', "%{$query}%")
                ->orWhereHas('destinasis', fn (Builder $destinasi) => $destinasi->where('nama_destinasi', 'like', "%{$query}%"))
                ->orWhereHas('fasilitas', fn (Builder $fasilitas) => $fasilitas->where('nama_fasilitas', 'like', "%{$query}%"));
        });
    }

    private function applyPackageSort(Builder $builder, string $sortBy): void
    {
        match ($sortBy) {
            'expensive' => $builder->orderByDesc('harga_paket'),
            'popular' => $builder->orderByDesc('pesanans_count')->orderBy('harga_paket'),
            'newest' => $builder->latest('id'),
            default => $builder->orderBy('harga_paket'),
        };
    }

    private function orderQuery(string $invoice, string $phone): Builder
    {
        return Pesanan::query()
            ->with('paket')
            ->when($invoice !== '', fn (Builder $builder) => $builder->where('invoice', $invoice))
            ->when($invoice === '' && $phone !== '', fn (Builder $builder) => $builder->where('no_hp', 'like', "%{$phone}%"));
    }

    private function packageSummary(Paket $paket): array
    {
        return [
            'id' => $paket->id,
            'name' => $paket->nama_paket,
            'duration' => $paket->durasi,
            'price' => (float) $paket->harga_paket,
            'destinations' => $paket->destinasis->pluck('nama_destinasi')->values(),
            'facilities' => $paket->fasilitas
                ->map(fn ($fasilitas) => [
                    'type' => $fasilitas->tipe_fasilitas,
                    'name' => $fasilitas->nama_fasilitas,
                ])
                ->values(),
            'note' => $paket->note,
            'detail_url' => route('package.detail', $paket->id),
        ];
    }

    private function orderSummary(Pesanan $pesanan): array
    {
        return [
            'invoice' => $pesanan->invoice,
            'customer_name' => $pesanan->nama_pemesan,
            'package' => $pesanan->is_custom ? 'Paket custom' : $pesanan->paket?->nama_paket,
            'event_date' => optional($pesanan->tanggal_acara)->toDateString() ?: (string) $pesanan->tanggal_acara,
            'people_count' => $pesanan->jumlah_orang,
            'total_price' => (float) $pesanan->total_harga,
            'status' => $pesanan->status ?: 'Menunggu Konfirmasi',
            'custom_places' => $pesanan->is_custom ? $this->arrayValues($pesanan->custom_places) : [],
            'custom_facilities' => $pesanan->is_custom ? $this->arrayValues($pesanan->custom_fasilitas) : [],
        ];
    }

    private function validSort(string $sortBy): string
    {
        return in_array($sortBy, ['cheapest', 'expensive', 'popular', 'newest'], true)
            ? $sortBy
            : 'cheapest';
    }

    private function positiveNumber(mixed $value): ?float
    {
        if (!is_numeric($value)) {
            return null;
        }

        $number = (float) $value;

        return $number > 0 ? $number : null;
    }

    private function digitsOnly(string $value): string
    {
        return implode('', array_filter(str_split($value), fn (string $char) => ctype_digit($char)));
    }

    private function arrayValues(mixed $value): array
    {
        if ($value instanceof Collection) {
            return $value->values()->all();
        }

        return is_array($value) ? array_values($value) : [];
    }
}
