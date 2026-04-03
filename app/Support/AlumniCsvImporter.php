<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class AlumniCsvImporter
{
    public function import(string $path, ?callable $progressCallback = null): array
    {
        if (! is_file($path)) {
            throw new \InvalidArgumentException("File tidak ditemukan: {$path}");
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new \RuntimeException("Gagal membuka file: {$path}");
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            return [
                'processed' => 0,
                'total' => DB::table('alumni')->count(),
            ];
        }

        $normalize = static function (?string $value): ?string {
            $value = trim((string) $value);

            return $value === '' ? null : $value;
        };

        $now = now();
        $batch = [];
        $batchSize = 1000;
        $processed = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) !== count($header)) {
                continue;
            }

            $item = array_combine($header, $row);

            $nama = $normalize($item['Nama Lulusan'] ?? null);
            $nim = $normalize($item['NIM'] ?? null);
            $tahunMasuk = $normalize($item['Tahun Masuk'] ?? null);
            $tanggalLulus = $normalize($item['Tanggal Lulus'] ?? null);
            $fakultas = $normalize($item['Fakultas'] ?? null);
            $programStudi = $normalize($item['Program Studi'] ?? null);

            if ($nama === null) {
                continue;
            }

            $sourceHash = sha1(implode('|', [
                $nama,
                $nim,
                $tahunMasuk,
                $tanggalLulus,
                $fakultas,
                $programStudi,
            ]));

            $batch[] = [
                'user_id' => null,
                'nama' => $nama,
                'nim' => $nim,
                'tahun_masuk' => $tahunMasuk,
                'tanggal_lulus' => $tanggalLulus,
                'fakultas' => $fakultas,
                'program_studi' => $programStudi,
                'consent' => false,
                'source_hash' => $sourceHash,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($batch) >= $batchSize) {
                $this->upsertBatch($batch);
                $processed += count($batch);
                $batch = [];

                if ($progressCallback !== null) {
                    $progressCallback($processed);
                }
            }
        }

        fclose($handle);

        if ($batch !== []) {
            $this->upsertBatch($batch);
            $processed += count($batch);

            if ($progressCallback !== null) {
                $progressCallback($processed);
            }
        }

        return [
            'processed' => $processed,
            'total' => DB::table('alumni')->count(),
        ];
    }

    private function upsertBatch(array $batch): void
    {
        DB::table('alumni')->upsert(
            $batch,
            ['source_hash'],
            ['nama', 'nim', 'tahun_masuk', 'tanggal_lulus', 'fakultas', 'program_studi', 'updated_at']
        );
    }
}
