@extends('layouts.app')

@section('content')
<div class="hero">
    <div>
        <span class="pill" style="background:rgba(255,255,255,.14); color:#fff7ec;">Profil Alumni</span>
        <h2>{{ $alumni->nama }}</h2>
        <p>Lengkapi data kontak, sosial media, dan informasi pekerjaan agar data alumni lebih mudah ditelusuri dan dikelola.</p>
    </div>
    <div class="hero-actions">
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <button type="submit" class="btn ghost">Kembali</button>
        </form>
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

<div class="detail-layout">
    <aside class="detail-meta">
        @php($profilingQuery = $alumni->profilingQuery())
        <div class="meta-row">
            <small>NIM</small>
            <strong>{{ $alumni->nim ?: '-' }}</strong>
        </div>
        <div class="meta-row">
            <small>Program Studi</small>
            <strong>{{ $alumni->program_studi ?: '-' }}</strong>
        </div>
        <div class="meta-row">
            <small>Fakultas</small>
            <strong>{{ $alumni->fakultas ?: '-' }}</strong>
        </div>
        <div class="meta-row">
            <small>Status Kontak</small>
            <strong>{{ $alumni->email || $alumni->no_hp || $alumni->tempat_kerja ? 'Sudah mulai diisi' : 'Masih kosong' }}</strong>
        </div>
        <div class="meta-row">
            <small>Progress Profiling</small>
            <strong>{{ $alumni->foundProfileItemsCount() }}/8 ditemukan</strong>
            <strong>{{ $alumni->verifiedProfileItemsCount() }}/8 terverifikasi</strong>
        </div>
        <div class="meta-row">
            <small>LinkedIn</small>
            @if($alumni->linkedin)
                <strong>Akun tersedia</strong>
                <div class="stack" style="margin-top:10px; gap:10px;">
                    <a href="{{ $alumni->linkedin }}" target="_blank" rel="noopener noreferrer" class="btn">Buka LinkedIn</a>
                    <a href="{{ route('admin.alumni.search', [$alumni, 'linkedin']) }}" target="_blank" rel="noopener noreferrer" class="btn secondary">Cari Lagi di LinkedIn</a>
                </div>
            @else
                <strong>Belum ada link</strong>
                <div style="margin-top:10px;">
                    <a href="{{ route('admin.alumni.search', [$alumni, 'linkedin']) }}" target="_blank" rel="noopener noreferrer" class="btn secondary">Cari LinkedIn</a>
                </div>
            @endif
        </div>
        <div class="meta-row">
            <small>Pencarian Publik</small>
            <div class="muted" style="margin-top:8px; font-size:.92rem;">Query: {{ $profilingQuery }}</div>
            <div class="stack" style="margin-top:10px; gap:10px;">
                <a href="{{ route('admin.alumni.search', [$alumni, 'google']) }}" target="_blank" rel="noopener noreferrer" class="btn secondary">Cari di Google</a>
                <a href="{{ route('admin.alumni.search', [$alumni, 'linkedin']) }}" target="_blank" rel="noopener noreferrer" class="btn secondary">Cari LinkedIn</a>
                <a href="{{ route('admin.alumni.search', [$alumni, 'instagram']) }}" target="_blank" rel="noopener noreferrer" class="btn secondary">Cari Instagram</a>
                <a href="{{ route('admin.alumni.search', [$alumni, 'social']) }}" target="_blank" rel="noopener noreferrer" class="btn secondary">Cari Sosmed</a>
            </div>
            @if($searchTrackingEnabled && $alumni->last_searched_at)
                <div class="muted" style="margin-top:10px; font-size:.92rem;">
                    Terakhir dicari: {{ $alumni->last_searched_at->format('d M Y H:i') }}
                </div>
            @endif
            @if($searchTrackingEnabled && $alumni->rememberedSearchUrl('google'))
                <div class="stack" style="margin-top:10px; gap:10px;">
                    <a href="{{ $alumni->rememberedSearchUrl('google') }}" target="_blank" rel="noopener noreferrer" class="btn secondary">Buka Google Terakhir</a>
                    @if($alumni->rememberedSearchUrl('linkedin'))
                        <a href="{{ $alumni->rememberedSearchUrl('linkedin') }}" target="_blank" rel="noopener noreferrer" class="btn secondary">Buka LinkedIn Terakhir</a>
                    @endif
                    @if($alumni->rememberedSearchUrl('instagram'))
                        <a href="{{ $alumni->rememberedSearchUrl('instagram') }}" target="_blank" rel="noopener noreferrer" class="btn secondary">Buka Instagram Terakhir</a>
                    @endif
                    @if($alumni->rememberedSearchUrl('social'))
                        <a href="{{ $alumni->rememberedSearchUrl('social') }}" target="_blank" rel="noopener noreferrer" class="btn secondary">Buka Sosmed Terakhir</a>
                    @endif
                </div>
            @endif
        </div>
    </aside>

    <section class="card">
        <h3 class="section-title">Lengkapi Data Alumni</h3>
        <p class="muted" style="margin:0 0 18px;">Perbarui data akademik, kontak, sosial media, dan pekerjaan dalam satu form.</p>

        <form method="POST" action="{{ route('admin.alumni.update', $alumni) }}">
            @csrf
            <div class="grid">
                <div>
                    <label>Nama</label>
                    <input type="text" name="nama" value="{{ old('nama', $alumni->nama) }}" required>
                </div>
                <div>
                    <label>NIM</label>
                    <input type="text" name="nim" value="{{ old('nim', $alumni->nim) }}">
                </div>
                <div>
                    <label>Tahun Masuk</label>
                    <input type="text" name="tahun_masuk" value="{{ old('tahun_masuk', $alumni->tahun_masuk) }}">
                </div>
                <div>
                    <label>Tanggal Lulus</label>
                    <input type="text" name="tanggal_lulus" value="{{ old('tanggal_lulus', $alumni->tanggal_lulus) }}">
                </div>
                <div>
                    <label>Fakultas</label>
                    <input type="text" name="fakultas" value="{{ old('fakultas', $alumni->fakultas) }}">
                </div>
                <div>
                    <label>Program Studi</label>
                    <input type="text" name="program_studi" value="{{ old('program_studi', $alumni->program_studi) }}">
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $alumni->email) }}">
                </div>
                <div>
                    <label>No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $alumni->no_hp) }}">
                </div>
                <div>
                    <label>LinkedIn</label>
                    <input type="url" name="linkedin" value="{{ old('linkedin', $alumni->linkedin) }}">
                    <div class="muted" style="margin-top:8px; font-size:.92rem;">
                        Bisa isi link profil setelah ditemukan dari tombol Cari LinkedIn.
                    </div>
                </div>
                <div>
                    <label>Instagram</label>
                    <input type="text" name="instagram" value="{{ old('instagram', $alumni->instagram) }}" placeholder="username_instagram">
                </div>
                <div>
                    <label>Facebook</label>
                    <input type="url" name="facebook" value="{{ old('facebook', $alumni->facebook) }}">
                </div>
                <div>
                    <label>TikTok</label>
                    <input type="url" name="tiktok" value="{{ old('tiktok', $alumni->tiktok) }}">
                </div>
                <div>
                    <label>Tempat Bekerja</label>
                    <input type="text" name="tempat_kerja" value="{{ old('tempat_kerja', $alumni->tempat_kerja) }}">
                </div>
                <div>
                    <label>Posisi</label>
                    <input type="text" name="posisi" value="{{ old('posisi', $alumni->posisi) }}">
                </div>
                <div>
                    <label>Status Kerja</label>
                    <select name="status_kerja">
                        <option value="">- Pilih -</option>
                        <option value="PNS" @selected(old('status_kerja', $alumni->status_kerja) === 'PNS')>PNS</option>
                        <option value="Swasta" @selected(old('status_kerja', $alumni->status_kerja) === 'Swasta')>Swasta</option>
                        <option value="Wirausaha" @selected(old('status_kerja', $alumni->status_kerja) === 'Wirausaha')>Wirausaha</option>
                    </select>
                </div>
                <div>
                    <label>Sosmed Tempat Kerja</label>
                    <input type="url" name="sosmed_tempat_kerja" value="{{ old('sosmed_tempat_kerja', $alumni->sosmed_tempat_kerja) }}">
                </div>
                <div class="full">
                    <label>Alamat Bekerja</label>
                    <textarea name="alamat_kerja">{{ old('alamat_kerja', $alumni->alamat_kerja) }}</textarea>
                </div>
                @if($verificationEnabled)
                    <div class="full">
                        <label>Checklist Verifikasi Accuracy</label>
                        <div class="grid" style="margin-top:10px;">
                            @foreach(\App\Support\AlumniProfilingMetrics::PROFILE_ITEMS as $key => $label)
                                <label style="display:flex; gap:10px; align-items:flex-start;">
                                    <input
                                        type="checkbox"
                                        name="verified_items[]"
                                        value="{{ $key }}"
                                        @checked(in_array($key, old('verified_items', $alumni->verified_items ?? []), true))
                                        style="width:auto; margin-top:4px;"
                                    >
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="muted" style="margin-top:8px; font-size:.92rem;">
                            Centang hanya jika item sudah dicek benar dari sosial media, website resmi, atau evidence lain.
                        </div>
                    </div>
                @else
                    <div class="full">
                        <div class="error" style="margin-bottom:0;">
                            Fitur verifikasi accuracy belum aktif di database. Jalankan migration terbaru agar checklist, evidence, dan catatan verifikasi bisa dipakai.
                        </div>
                    </div>
                @endif
                <div class="full">
                    <label>
                        <input type="checkbox" name="consent" value="1" @checked(old('consent', $alumni->consent)) style="width:auto;">
                        Data boleh digunakan untuk kepentingan akademik.
                    </label>
                </div>
                <div class="full">
                    <button type="submit">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection
