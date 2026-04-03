<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Support\AlumniCsvImporter;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $filter = trim((string) $request->query('filter', ''));

        $data = Alumni::query()
            ->with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%")
                        ->orWhere('tempat_kerja', 'like', "%{$search}%");
                });
            })
            ->when($filter === 'has_linkedin', function ($query) {
                $query->whereNotNull('linkedin')->where('linkedin', '!=', '');
            })
            ->when($filter === 'missing_job', function ($query) {
                $query->where(function ($inner) {
                    $inner->whereNull('tempat_kerja')
                        ->orWhere('tempat_kerja', '')
                        ->orWhereNull('posisi')
                        ->orWhere('posisi', '');
                });
            })
            ->when($filter === 'has_contact', function ($query) {
                $query->where(function ($inner) {
                    $inner->where(function ($contact) {
                        $contact->whereNotNull('email')->where('email', '!=', '');
                    })->orWhere(function ($contact) {
                        $contact->whereNotNull('no_hp')->where('no_hp', '!=', '');
                    });
                });
            })
            ->orderBy('nama')
            ->paginate(25)
            ->withQueryString();

        return view('dashboard_admin', compact('data', 'search', 'filter'));
    }

    public function import(Request $request, AlumniCsvImporter $importer)
    {
        $validated = $request->validate([
            'csv_file' => ['nullable', 'file', 'mimes:csv,txt', 'required_without:csv_path'],
            'csv_path' => ['nullable', 'string', 'required_without:csv_file'],
        ]);

        set_time_limit(0);

        $path = $request->file('csv_file')?->getRealPath() ?? trim((string) ($validated['csv_path'] ?? ''));

        $result = $importer->import($path);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', "Import selesai. {$result['processed']} baris diproses, total alumni {$result['total']}.");
    }

    public function show(Alumni $alumni)
    {
        return view('admin_alumni_detail', compact('alumni'));
    }

    public function update(Request $request, Alumni $alumni)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nim' => ['nullable', 'string', 'max:50'],
            'tahun_masuk' => ['nullable', 'string', 'max:20'],
            'tanggal_lulus' => ['nullable', 'string', 'max:50'],
            'fakultas' => ['nullable', 'string', 'max:255'],
            'program_studi' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'linkedin' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
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

        $alumni->update($data);

        return redirect()
            ->route('admin.alumni.show', $alumni)
            ->with('success', 'Data alumni berhasil diperbarui.');
    }
}
