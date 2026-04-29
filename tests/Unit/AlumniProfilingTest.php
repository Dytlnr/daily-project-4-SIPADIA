<?php

namespace Tests\Unit;

use App\Models\Alumni;
use PHPUnit\Framework\TestCase;

class AlumniProfilingTest extends TestCase
{
    public function test_it_builds_a_profiling_query_with_academic_context(): void
    {
        $alumni = new Alumni([
            'nama' => 'Budi Santoso',
            'program_studi' => 'Teknik Informatika',
            'fakultas' => 'Teknik',
        ]);

        $this->assertSame(
            'Budi Santoso Teknik Informatika Teknik Universitas Muhammadiyah Malang',
            $alumni->profilingQuery()
        );
    }

    public function test_it_counts_found_and_verified_items_using_the_lecturer_categories(): void
    {
        $alumni = new Alumni([
            'linkedin' => 'https://linkedin.com/in/budi',
            'email' => 'budi@example.com',
            'no_hp' => '08123456789',
            'tempat_kerja' => 'PT Maju Jaya',
            'status_kerja' => 'Swasta',
            'verified_items' => ['social_media', 'email', 'employment_status'],
        ]);

        $this->assertTrue(Alumni::hasProfileItem($alumni, 'social_media'));
        $this->assertTrue(Alumni::hasProfileItem($alumni, 'email'));
        $this->assertTrue(Alumni::hasProfileItem($alumni, 'phone'));
        $this->assertFalse(Alumni::hasProfileItem($alumni, 'work_address'));

        $this->assertTrue(Alumni::hasVerifiedProfileItem($alumni, 'social_media'));
        $this->assertFalse(Alumni::hasVerifiedProfileItem($alumni, 'phone'));

        $this->assertSame(5, $alumni->foundProfileItemsCount());
        $this->assertSame(3, $alumni->verifiedProfileItemsCount());
    }
}
