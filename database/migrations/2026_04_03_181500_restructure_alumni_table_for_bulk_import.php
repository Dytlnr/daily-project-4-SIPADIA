<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_import_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama');
            $table->string('nim')->nullable();
            $table->string('tahun_masuk')->nullable();
            $table->string('tanggal_lulus')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('tempat_kerja')->nullable();
            $table->text('alamat_kerja')->nullable();
            $table->string('posisi')->nullable();
            $table->enum('status_kerja', ['PNS', 'Swasta', 'Wirausaha'])->nullable();
            $table->string('sosmed_tempat_kerja')->nullable();
            $table->boolean('consent')->default(false);
            $table->string('source_hash')->nullable()->unique();
            $table->timestamps();
        });

        DB::table('alumni')->orderBy('id')->chunk(500, function ($rows): void {
            $payload = [];

            foreach ($rows as $row) {
                $payload[] = [
                    'id' => $row->id,
                    'user_id' => $row->user_id,
                    'nama' => $row->nama,
                    'nim' => null,
                    'tahun_masuk' => null,
                    'tanggal_lulus' => null,
                    'fakultas' => null,
                    'program_studi' => null,
                    'email' => $row->email,
                    'no_hp' => $row->no_hp,
                    'linkedin' => $row->linkedin,
                    'instagram' => $row->instagram,
                    'facebook' => $row->facebook,
                    'tiktok' => $row->tiktok,
                    'tempat_kerja' => $row->tempat_kerja,
                    'alamat_kerja' => $row->alamat_kerja,
                    'posisi' => $row->posisi,
                    'status_kerja' => $row->status_kerja,
                    'sosmed_tempat_kerja' => $row->sosmed_tempat_kerja,
                    'consent' => $row->consent,
                    'source_hash' => null,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ];
            }

            DB::table('alumni_import_temp')->insert($payload);
        });

        Schema::drop('alumni');
        Schema::rename('alumni_import_temp', 'alumni');
    }

    public function down(): void
    {
        Schema::create('alumni_legacy_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('tempat_kerja')->nullable();
            $table->text('alamat_kerja')->nullable();
            $table->string('posisi')->nullable();
            $table->enum('status_kerja', ['PNS', 'Swasta', 'Wirausaha'])->nullable();
            $table->string('sosmed_tempat_kerja')->nullable();
            $table->boolean('consent')->default(false);
            $table->timestamps();
        });

        DB::table('alumni')
            ->whereNotNull('user_id')
            ->orderBy('id')
            ->chunk(500, function ($rows): void {
                $payload = [];

                foreach ($rows as $row) {
                    $payload[] = [
                        'id' => $row->id,
                        'user_id' => $row->user_id,
                        'nama' => $row->nama,
                        'email' => $row->email,
                        'no_hp' => $row->no_hp,
                        'linkedin' => $row->linkedin,
                        'instagram' => $row->instagram,
                        'facebook' => $row->facebook,
                        'tiktok' => $row->tiktok,
                        'tempat_kerja' => $row->tempat_kerja,
                        'alamat_kerja' => $row->alamat_kerja,
                        'posisi' => $row->posisi,
                        'status_kerja' => $row->status_kerja,
                        'sosmed_tempat_kerja' => $row->sosmed_tempat_kerja,
                        'consent' => $row->consent,
                        'created_at' => $row->created_at,
                        'updated_at' => $row->updated_at,
                    ];
                }

                DB::table('alumni_legacy_temp')->insert($payload);
            });

        Schema::drop('alumni');
        Schema::rename('alumni_legacy_temp', 'alumni');
    }
};
