<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumniController extends Controller
{
    public function dashboard()
    {
        $alumni = Alumni::firstOrCreate(
            ['user_id' => Auth::id()],
            ['nama' => Auth::user()->name]
        );

        return view('dashboard_alumni', compact('alumni'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'linkedin' => ['nullable', 'url'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'url'],
            'tiktok' => ['nullable', 'url'],
            'tempat_kerja' => ['nullable', 'string', 'max:255'],
            'alamat_kerja' => ['nullable', 'string'],
            'posisi' => ['nullable', 'string', 'max:255'],
            'status_kerja' => ['nullable', 'in:PNS,Swasta,Wirausaha'],
            'sosmed_tempat_kerja' => ['nullable', 'url'],
            'consent' => ['nullable'],
        ]);

        $data['consent'] = $request->has('consent');

        $alumni = Alumni::where('user_id', Auth::id())->firstOrFail();
        $alumni->update($data);

        return back()->with('success', 'Data berhasil disimpan.');
    }
}
