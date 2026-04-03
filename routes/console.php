<?php

use App\Support\AlumniCsvImporter;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('alumni:import-csv {path}', function (string $path, AlumniCsvImporter $importer) {
    $result = $importer->import($path, function (int $processed): void {
        $this->info("Diproses: {$processed} baris");
    });

    $this->info("Selesai. Total baris diproses: {$result['processed']}");
    $this->info("Total data alumni saat ini: {$result['total']}");

    return self::SUCCESS;
})->purpose('Import data alumni dari file CSV');
