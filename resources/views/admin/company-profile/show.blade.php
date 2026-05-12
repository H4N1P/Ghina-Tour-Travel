@extends('components.layout.admin')

@section('title', 'Company Profile')
@section('header', 'Company Profile')

@section('content')
    <div class="max-w-3xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Company Profile</h1>
            <a href="{{ route('admin.company-profile.edit') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-amber-500 text-black hover:bg-amber-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                </svg>
                Edit
            </a>
        </div>

        <div class="bg-admin-card rounded-xl border border-admin-border overflow-hidden">
            {{-- About --}}
            <div class="p-6 border-b border-admin-border">
                <label class="text-xs font-semibold text-admin-muted uppercase tracking-wider">Tentang Perusahaan</label>
                <p class="mt-2 text-sm text-admin-text leading-relaxed">
                    {{ $companyProfile->about ?? '-' }}
                </p>
            </div>

            {{-- Vision & Mission --}}
            <div class="p-6 border-b border-admin-border">
                <label class="text-xs font-semibold text-admin-muted uppercase tracking-wider">Visi & Misi</label>
                <p class="mt-2 text-sm text-admin-text leading-relaxed whitespace-pre-line">{{ $companyProfile->vision_mission ?? '-' }}</p>
            </div>

            {{-- Contact Info --}}
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs font-semibold text-admin-muted uppercase tracking-wider">Alamat</label>
                    <p class="mt-2 text-sm text-admin-text">{{ $companyProfile->address ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-admin-muted uppercase tracking-wider">WhatsApp</label>
                    <p class="mt-2 text-sm text-admin-text">{{ $companyProfile->whatsapp ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-admin-muted uppercase tracking-wider">Email</label>
                    <p class="mt-2 text-sm text-admin-text">{{ $companyProfile->email ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-admin-muted uppercase tracking-wider">Instagram</label>
                    <p class="mt-2 text-sm text-admin-text">{{ $companyProfile->instagram ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
