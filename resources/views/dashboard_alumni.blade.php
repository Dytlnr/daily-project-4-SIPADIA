@extends('layouts.app')

@section('content')
<div class="topbar">
    <div>
        <h1>Form Alumni</h1>
        <p>Lengkapi data alumni Anda</p>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="secondary">Logout</button>
    </form>
</div>

@if(session('success'))
    <div class="success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('alumni.update') }}">
    @csrf
    <div class="grid">
        <div>
            <label>Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $alumni->nama) }}" required>
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
            <label>Tempat Kerja</label>
            <input type="text" name="tempat_kerja" value="{{ old('tempat_kerja', $alumni->tempat_kerja) }}">
        </div>
        <div class="full">
            <label>Alamat Kerja</label>
            <textarea name="alamat_kerja">{{ old('alamat_kerja', $alumni->alamat_kerja) }}</textarea>
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
            <label>
                <input type="checkbox" name="consent" value="1" @checked(old('consent', $alumni->consent)) style="width:auto;">
                Saya menyetujui data ini digunakan untuk kepentingan akademik.
            </label>
        </div>
        <div class="full">
            <button type="submit">Simpan</button>
        </div>
    </div>
</form>
@endsection
