@extends('layouts.app')

@section('content')
<div class="login-shell">
    <section class="hero" style="min-height:520px; align-items:flex-end;">
        <div>
            <span class="pill" style="background:rgba(255,255,255,.14); color:#fff7ec;">Portal Tracer Study</span>
            <h1>Sistem Alumni yang lebih tertata, mudah dicari, dan siap diupdate.</h1>
            <p>Kelola riwayat alumni, lakukan pencarian cepat, dan simpan informasi kontak serta pekerjaan dalam satu panel yang rapi.</p>

            <div class="feature-list">
                <div class="feature-item">Import data alumni dari CSV dan rapikan pencarian berdasarkan nama atau NIM.</div>
                <div class="feature-item">Lengkapi profil alumni dengan email, sosial media, pekerjaan, dan status kerja.</div>
                <div class="feature-item">Dashboard admin disusun agar nyaman dipakai untuk data alumni dalam jumlah besar.</div>
            </div>
        </div>
    </section>

    <section class="card" style="padding:28px;">
        <span class="pill">Masuk ke Sistem</span>
        <h2 style="margin:14px 0 8px; font-size:1.65rem;">Login Sistem Alumni</h2>
        <p class="muted" style="margin:0 0 20px;">Gunakan akun admin untuk mengelola data atau akun alumni untuk memperbarui profil pribadi.</p>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="stack">
            @csrf
            <div>
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

    </section>
</div>
@endsection
