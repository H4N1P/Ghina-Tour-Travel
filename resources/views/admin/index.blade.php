@extends('components.layout.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    <style>
        *,
        body {
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --gold: #D4A017;
            --gold-dark: #b8860b;
            --orange: #FF9357;
        }

        [data-theme="light"] {
            --bg: #F2F3F7;
            --bg-card: #FFFFFF;
            --text: #1a1a1a;
            --text-muted: #6b7280;
            --border: #E5E7EB;
            --table-head: #F9FAFB;
            --table-border: #F3F4F6;
            --shadow-card: 0 2px 8px rgba(0, 0, 0, .06);
            --sidebar-active-bg: #FFF4EB;
        }

        [data-theme="dark"] {
            --bg: #0f1117;
            --bg-card: #1e2130;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --border: #2d3348;
            --table-head: #1a1f2e;
            --table-border: #2a3045;
            --shadow-card: 0 2px 12px rgba(0, 0, 0, .3);
            --sidebar-active-bg: rgba(212, 160, 23, .12);
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow-card);
            padding: 22px 26px;
            display: flex;
            align-items: center;
            gap: 18px;
            transition: background .3s, border-color .3s;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0 0 4px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
            line-height: 1;
            margin: 0;
        }

        .adm-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .adm-tbl {
            width: 100%;
            border-collapse: collapse;
        }

        .adm-tbl thead th {
            background: var(--table-head);
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: 13px 18px;
            text-align: left;
            border-bottom: 1px solid var(--table-border);
        }

        .adm-tbl tbody td {
            padding: 14px 18px;
            font-size: 14px;
            color: var(--text);
            border-bottom: 1px solid var(--table-border);
        }

        .adm-tbl tbody tr:last-child td {
            border-bottom: none;
        }

        .adm-tbl tbody tr:hover {
            background: var(--sidebar-active-bg);
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-pending {
            background: #FFF4EB;
            color: var(--orange);
        }

        .badge-selesai {
            background: #ECFDF5;
            color: #10b981;
        }

        .badge-batal {
            background: #FEF2F2;
            color: #ef4444;
        }

        .badge-proses {
            background: #EFF6FF;
            color: #2563eb;
        }

        [data-theme="dark"] .badge-pending {
            background: rgba(255, 147, 87, .15);
            color: #ff9357;
        }

        [data-theme="dark"] .badge-selesai {
            background: rgba(16, 185, 129, .15);
            color: #34d399;
        }

        [data-theme="dark"] .badge-batal {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
        }

        [data-theme="dark"] .badge-proses {
            background: rgba(37, 99, 235, .15);
            color: #60a5fa;
        }

        .btn-view {
            background: transparent;
            color: #8b5cf6;
            border: none;
            cursor: pointer;
            padding: 5px;
            border-radius: 6px;
            transition: background .15s;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .btn-view:hover {
            background: rgba(139, 92, 246, .1);
        }

        .empty-state {
            text-align: center;
            padding: 48px 0;
            color: var(--text-muted);
            font-size: 14px;
        }
    </style>

    <h1 style="font-size:26px;font-weight:700;color:var(--text);margin:0 0 24px;">Dashboard</h1>

    {{-- ── Stat Cards ── --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:32px;">

        {{-- Total Paket --}}
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFF4EB;">
                <svg style="width:24px;height:24px;color:var(--orange);" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="stat-label">Total Paket</p>
                <p class="stat-value">{{ $totalPaket }}</p>
            </div>
        </div>

        {{-- Total Pesanan --}}
        <div class="stat-card">
            <div class="stat-icon" style="background:#ECFDF5;">
                <svg style="width:24px;height:24px;color:#10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="stat-label">Total Pesanan</p>
                <p class="stat-value">{{ $orders->count() }}</p>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFFBEB;">
                <svg style="width:24px;height:24px;color:var(--gold);" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="stat-label">Revenue (Selesai)</p>
                <p class="stat-value" style="font-size:20px;margin-top:4px;">
                    Rp {{ number_format($revenue, 0, ',', '.') }}
                </p>
            </div>
        </div>

    </div>

    {{-- ── Tabel Pesanan Terbaru ── --}}
    <div class="adm-card">
        <div
            style="padding:18px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:16px;font-weight:700;color:var(--text);margin:0;">Pesanan Terbaru</h2>
            <a href="{{ route('admin.pesanan.index') }}"
                style="font-size:13px;color:var(--gold);font-weight:600;text-decoration:none;">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="adm-tbl">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Pemesan</th>
                        <th>No. HP</th>
                        <th>Paket</th>
                        <th>Jumlah Orang</th>
                        <th>Tanggal Tour</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th style="width:80px;text-align:center;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $i => $p)
                        <tr>
                            <td style="color:var(--text-muted);font-size:13px;">{{ $i + 1 }}</td>
                            <td style="font-weight:500;">{{ $p->nama_pemesan }}</td>
                            <td style="color:var(--text-muted);">{{ $p->no_hp }}</td>
                            <td>{{ $p->paket->nama_paket ?? '-' }}</td>
                            <td>{{ $p->jumlah_orang }} orang</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_acara)->format('d M Y') }}</td>
                            <td style="font-weight:600;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $badgeClass = match ($p->status) {
                                        'selesai' => 'badge-selesai',
                                        'batal' => 'badge-batal',
                                        'proses' => 'badge-proses',
                                        default => 'badge-pending',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($p->status) }}</span>
                            </td>
                            <td style="text-align:center;">
                                <a href="{{ route('admin.pesanan.show', $p->id) }}" class="btn-view" title="Lihat Detail">
                                    <svg style="width:17px;height:17px;" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-state">Belum ada pesanan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
