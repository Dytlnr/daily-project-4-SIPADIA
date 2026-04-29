<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Alumni extends Model
{
    use HasFactory;

    private static ?bool $profilingVerificationColumnsAvailable = null;
    private static ?bool $searchTrackingColumnsAvailable = null;

    public const PROFILE_ITEMS = [
        'social_media',
        'email',
        'phone',
        'workplace',
        'work_address',
        'position',
        'employment_status',
        'work_social_media',
    ];

    protected $table = 'alumni';

    protected $fillable = [
        'user_id',
        'nama',
        'nim',
        'tahun_masuk',
        'tanggal_lulus',
        'fakultas',
        'program_studi',
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
        'consent',
        'source_hash',
        'verified_items',
        'verification_notes',
        'evidence_links',
        'search_links',
        'last_searched_at',
    ];

    protected $casts = [
        'consent' => 'boolean',
        'verified_items' => 'array',
        'search_links' => 'array',
        'last_searched_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function hasProfileItem(self $alumni, string $key): bool
    {
        return match ($key) {
            'social_media' => self::filled($alumni->linkedin)
                || self::filled($alumni->instagram)
                || self::filled($alumni->facebook)
                || self::filled($alumni->tiktok),
            'email' => self::filled($alumni->email),
            'phone' => self::filled($alumni->no_hp),
            'workplace' => self::filled($alumni->tempat_kerja),
            'work_address' => self::filled($alumni->alamat_kerja),
            'position' => self::filled($alumni->posisi),
            'employment_status' => self::filled($alumni->status_kerja),
            'work_social_media' => self::filled($alumni->sosmed_tempat_kerja),
            default => false,
        };
    }

    public static function hasVerifiedProfileItem(self $alumni, string $key): bool
    {
        if (! self::supportsProfilingVerification()) {
            return false;
        }

        return in_array($key, $alumni->verified_items ?? [], true);
    }

    public function profilingQuery(): string
    {
        return trim(implode(' ', array_filter([
            $this->nama,
            $this->program_studi,
            $this->fakultas,
            'Universitas Muhammadiyah Malang',
        ])));
    }

    public function linkedinSearchQuery(): string
    {
        return trim($this->nama . ' Universitas Muhammadiyah Malang');
    }

    public function searchUrl(string $platform): string
    {
        return match ($platform) {
            'google' => 'https://www.google.com/search?q=' . urlencode($this->profilingQuery()),
            'linkedin' => 'https://www.linkedin.com/search/results/all/?keywords=' . urlencode($this->linkedinSearchQuery()),
            'instagram' => 'https://www.google.com/search?q=' . urlencode($this->profilingQuery() . ' site:instagram.com'),
            'social' => 'https://www.google.com/search?q=' . urlencode($this->profilingQuery() . ' site:tiktok.com OR site:facebook.com'),
            default => 'https://www.google.com/search?q=' . urlencode($this->profilingQuery()),
        };
    }

    public function rememberSearch(string $platform): void
    {
        if (! self::supportsSearchTracking()) {
            return;
        }

        $links = $this->search_links ?? [];
        $links[$platform] = $this->searchUrl($platform);

        $this->forceFill([
            'search_links' => $links,
            'last_searched_at' => now(),
        ])->save();
    }

    public function rememberedSearchUrl(string $platform): ?string
    {
        return ($this->search_links ?? [])[$platform] ?? null;
    }

    public function foundProfileItemsCount(): int
    {
        return collect(self::PROFILE_ITEMS)
            ->filter(fn (string $key) => self::hasProfileItem($this, $key))
            ->count();
    }

    public function verifiedProfileItemsCount(): int
    {
        if (! self::supportsProfilingVerification()) {
            return 0;
        }

        return collect(self::PROFILE_ITEMS)
            ->filter(fn (string $key) => self::hasVerifiedProfileItem($this, $key))
            ->count();
    }

    public static function supportsProfilingVerification(): bool
    {
        if (self::$profilingVerificationColumnsAvailable !== null) {
            return self::$profilingVerificationColumnsAvailable;
        }

        self::$profilingVerificationColumnsAvailable = Schema::hasColumns('alumni', [
            'verified_items',
            'verification_notes',
            'evidence_links',
        ]);

        return self::$profilingVerificationColumnsAvailable;
    }

    public static function supportsSearchTracking(): bool
    {
        if (self::$searchTrackingColumnsAvailable !== null) {
            return self::$searchTrackingColumnsAvailable;
        }

        self::$searchTrackingColumnsAvailable = Schema::hasColumns('alumni', [
            'search_links',
            'last_searched_at',
        ]);

        return self::$searchTrackingColumnsAvailable;
    }

    private static function filled(?string $value): bool
    {
        return trim((string) $value) !== '';
    }
}
