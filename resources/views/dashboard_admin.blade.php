@extends('layouts.app')

@section('content')
<div class="hero">
    <div>
        <span class="pill" style="background:rgba(255,255,255,.14); color:#fff7ec;">Panel Admin</span>
        <h1>Dashboard Alumni</h1>
        <p>Kelola import data, cari alumni dengan cepat, lalu lengkapi profil kontak dan pekerjaan langsung dari panel admin.</p>
    </div>
    <div class="hero-actions">
        <span class="btn ghost" style="cursor:default;">{{ number_format($data->total(), 0, ',', '.') }} data</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn ghost">Logout</button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="error">{{ $errors->first() }}</div>
@endif

<div class="stats">
    <div class="stat">
        <div class="stat-label">Total Alumni</div>
        <div class="stat-value">{{ number_format($data->total(), 0, ',', '.') }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">Coverage Dataset</div>
        <div class="stat-value">{{ number_format($profilingSummary['coverage_percent'], 4, ',', '.') }}%</div>
        <div class="muted" style="margin-top:8px;">{{ number_format($profilingSummary['found_total'], 0, ',', '.') }} item ditemukan</div>
    </div>
    <div class="stat">
        <div class="stat-label">Accuracy Dataset</div>
        <div class="stat-value">{{ number_format($profilingSummary['accuracy_percent'], 4, ',', '.') }}%</div>
        <div class="muted" style="margin-top:8px;">{{ number_format($profilingSummary['verified_total'], 0, ',', '.') }} item terverifikasi</div>
    </div>
</div>

<div class="inline-actions" style="margin-bottom:18px;">
    <a href="{{ route('admin.dashboard', ['filter' => 'has_linkedin', 'search' => $search ?: null]) }}" class="btn {{ $filter === 'has_linkedin' ? '' : 'secondary' }}">Punya LinkedIn</a>
    <a href="{{ route('admin.dashboard', ['filter' => 'missing_job', 'search' => $search ?: null]) }}" class="btn {{ $filter === 'missing_job' ? '' : 'secondary' }}">Belum Ada Data Kerja</a>
    <a href="{{ route('admin.dashboard', ['filter' => 'has_contact', 'search' => $search ?: null]) }}" class="btn {{ $filter === 'has_contact' ? '' : 'secondary' }}">Sudah Ada Kontak</a>
    @if($filter)
        <a href="{{ route('admin.dashboard', ['search' => $search ?: null]) }}" class="btn secondary">Hapus Filter</a>
    @endif
</div>

<div class="grid">
    <div class="card">
        <h3 style="margin-top:0;">Import CSV Alumni</h3>
        <p style="margin-bottom:14px;">Gunakan path file untuk CSV besar agar tidak terkena batas upload PHP dari browser.</p>
        <div class="error" style="margin-bottom:14px;">
            Upload langsung dari browser dibatasi PHP server. Limit saat ini kecil, jadi untuk file besar seperti data alumni gunakan metode path file.
        </div>
        <form method="POST" action="{{ route('admin.import') }}" style="margin-bottom:16px;">
            @csrf
            <div style="margin-bottom:8px; font-weight:600;">Metode yang disarankan untuk file besar</div>
            <div style="margin-bottom:12px;">
                <label>Path file CSV di komputer ini</label>
                <input type="text" name="csv_path" value="{{ old('csv_path') }}" placeholder="/Users/user/Downloads/Alumni 2000-2025.xlsx - Sheet1.csv">
            </div>
            <button type="submit">Import dari Path</button>
        </form>

        <form method="POST" action="{{ route('admin.import') }}" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom:8px; font-weight:600;">Upload file kecil</div>
            <div style="margin-bottom:12px;">
                <label>File CSV</label>
                <input type="file" name="csv_file" accept=".csv,text/csv">
            </div>
            <button type="submit">Upload CSV</button>
        </form>
    </div>
    <div class="card">
        <h3 style="margin-top:0;">Cari Alumni</h3>
        <p style="margin-bottom:14px;">Cari berdasarkan nama, NIM, email, nomor HP, atau tempat kerja untuk langsung membuka data yang dicari.</p>
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <div style="margin-bottom:12px;">
                <label>Nama, NIM, email, no HP, atau tempat kerja</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Contoh: Catur, 95620625, admin@example.com">
            </div>
            <input type="hidden" name="filter" value="{{ $filter }}">
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <button type="submit">Cari</button>
                <a href="{{ route('admin.dashboard') }}" class="btn secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="table-titlebar">
    <div>
        <h3 class="section-title">Daftar Alumni</h3>
        <p>{{ $search ? "Hasil pencarian untuk: {$search}" : 'Menampilkan data alumni yang telah diimport.' }}</p>
    </div>
    <span class="pill">{{ $data->count() }} baris di halaman ini</span>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIM</th>
                <th>Tahun Masuk</th>
                <th>Tanggal Lulus</th>
                <th>Fakultas</th>
                <th>Program Studi</th>
                <th>LinkedIn</th>
                <th>Pencarian Publik</th>
                <th>Kontak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td><strong>{{ $row->nama }}</strong></td>
                    <td>{{ $row->nim }}</td>
                    <td>{{ $row->tahun_masuk }}</td>
                    <td>{{ $row->tanggal_lulus }}</td>
                    <td>{{ $row->fakultas }}</td>
                    <td>{{ $row->program_studi }}</td>
                    @php($profilingQuery = $row->profilingQuery())
                    <td>
                        @if($row->linkedin)
                            <div class="stack" style="gap:8px; align-items:flex-start;">
                                <span class="pill" style="display:flex; justify-content:center; width:128px; padding:6px 10px; font-size:.82rem;">Ada</span>
                                <a href="{{ $row->linkedin }}" target="_blank" rel="noopener noreferrer" class="btn" style="width:128px; padding:10px 12px; font-size:.88rem; white-space:nowrap;">Buka</a>
                            </div>
                        @else
                            <div class="stack" style="gap:8px; align-items:flex-start;">
                                <span class="pill" style="display:flex; justify-content:center; width:128px; padding:6px 10px; font-size:.82rem; background:rgba(217,143,63,.12); color:#8a5b21;">Kosong</span>
                                <a href="{{ route('admin.alumni.search', [$row, 'linkedin']) }}" target="_blank" rel="noopener noreferrer" class="btn secondary" style="width:128px; padding:10px 12px; font-size:.88rem; white-space:nowrap;">Cari LinkedIn</a>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="stack" style="gap:8px; align-items:flex-start;">
                            <a href="{{ route('admin.alumni.search', [$row, 'google']) }}" target="_blank" rel="noopener noreferrer" class="btn secondary" style="width:140px; padding:10px 12px; font-size:.88rem; white-space:nowrap;">Cari Google</a>
                            <a href="{{ route('admin.alumni.search', [$row, 'linkedin']) }}" target="_blank" rel="noopener noreferrer" class="btn secondary" style="width:140px; padding:10px 12px; font-size:.88rem; white-space:nowrap;">Cari LinkedIn</a>
                            <a href="{{ route('admin.alumni.search', [$row, 'instagram']) }}" target="_blank" rel="noopener noreferrer" class="btn secondary" style="width:140px; padding:10px 12px; font-size:.88rem; white-space:nowrap;">Cari Instagram</a>
                            <a href="{{ route('admin.alumni.search', [$row, 'social']) }}" target="_blank" rel="noopener noreferrer" class="btn secondary" style="width:140px; padding:10px 12px; font-size:.88rem; white-space:nowrap;">Cari Sosmed</a>
                        </div>
                    </td>
                    <td>
                        <span class="pill" style="{{ $row->email || $row->no_hp || $row->tempat_kerja ? '' : 'background:rgba(217,143,63,.12); color:#8a5b21;' }}">
                            {{ $row->email || $row->no_hp || $row->tempat_kerja ? 'Sudah ada' : 'Belum ada' }}
                        </span>
                    </td>
                    <td><a href="{{ route('admin.alumni.show', $row) }}" class="btn">Detail</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination-bar">
    <div class="muted">
        Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} dari {{ $data->total() }} data
    </div>
    <div class="inline-actions">
        @if($data->onFirstPage())
            <span class="btn secondary" style="opacity:.6; cursor:not-allowed;">Sebelumnya</span>
        @else
            <a href="{{ $data->previousPageUrl() }}" class="btn secondary">Sebelumnya</a>
        @endif

        <span class="pill">Halaman {{ $data->currentPage() }} / {{ $data->lastPage() }}</span>

        @if($data->hasMorePages())
            <a href="{{ $data->nextPageUrl() }}" class="btn">Berikutnya</a>
        @else
            <span class="btn" style="opacity:.6; cursor:not-allowed;">Berikutnya</span>
        @endif
    </div>
</div>
@endsection
