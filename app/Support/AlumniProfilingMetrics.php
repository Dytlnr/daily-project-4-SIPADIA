<?php

namespace App\Support;

use App\Models\Alumni;
use Illuminate\Database\Eloquent\Builder;

class AlumniProfilingMetrics
{
    public const PROFILE_ITEMS = [
        'social_media' => 'Alamat sosial media',
        'email' => 'Email',
        'phone' => 'No HP',
        'workplace' => 'Tempat bekerja',
        'work_address' => 'Alamat bekerja',
        'position' => 'Posisi',
        'employment_status' => 'PNS / Swasta / Wirausaha',
        'work_social_media' => 'Sosial media tempat bekerja',
    ];

    public function summarize(Builder $query): array
    {
        $totalRecords = (clone $query)->count();
        $foundCounts = array_fill_keys(array_keys(self::PROFILE_ITEMS), 0);
        $verifiedCounts = array_fill_keys(array_keys(self::PROFILE_ITEMS), 0);
        $selectColumns = [
            'id',
            'email',
            'no_hp',
            'linkedin',
            'instagram',
            'facebook',
            'tiktok',
            'tempat_kerja',
            'alamat_kerja',
            'posisi',
            'status_kerja',
            'sosmed_tempat_kerja',
        ];

        if (Alumni::supportsProfilingVerification()) {
            $selectColumns[] = 'verified_items';
        }

        (clone $query)
            ->select($selectColumns)
            ->orderBy('id')
            ->chunkById(1000, function ($rows) use (&$foundCounts, &$verifiedCounts): void {
                foreach ($rows as $alumni) {
                    foreach (array_keys(self::PROFILE_ITEMS) as $key) {
                        if (Alumni::hasProfileItem($alumni, $key)) {
                            $foundCounts[$key]++;
                        }

                        if (Alumni::hasVerifiedProfileItem($alumni, $key)) {
                            $verifiedCounts[$key]++;
                        }
                    }
                }
            });

        $itemCount = count(self::PROFILE_ITEMS);
        $maxPoints = $totalRecords * $itemCount;
        $foundTotal = array_sum($foundCounts);
        $verifiedTotal = array_sum($verifiedCounts);

        return [
            'total_records' => $totalRecords,
            'item_count' => $itemCount,
            'found_total' => $foundTotal,
            'verified_total' => $verifiedTotal,
            'coverage_percent' => $maxPoints > 0 ? round(($foundTotal / $maxPoints) * 100, 2) : 0.0,
            'accuracy_percent' => $maxPoints > 0 ? round(($verifiedTotal / $maxPoints) * 100, 2) : 0.0,
            'items' => collect(self::PROFILE_ITEMS)->map(function ($label, $key) use ($totalRecords, $foundCounts, $verifiedCounts) {
                return [
                    'key' => $key,
                    'label' => $label,
                    'found' => $foundCounts[$key],
                    'verified' => $verifiedCounts[$key],
                    'coverage_percent' => $totalRecords > 0 ? round(($foundCounts[$key] / $totalRecords) * 100, 2) : 0.0,
                    'accuracy_percent' => $totalRecords > 0 ? round(($verifiedCounts[$key] / $totalRecords) * 100, 2) : 0.0,
                ];
            })->values()->all(),
        ];
    }
}
