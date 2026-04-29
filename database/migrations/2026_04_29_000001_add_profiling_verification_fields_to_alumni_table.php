<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->json('verified_items')->nullable()->after('source_hash');
            $table->text('verification_notes')->nullable()->after('verified_items');
            $table->text('evidence_links')->nullable()->after('verification_notes');
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn(['verified_items', 'verification_notes', 'evidence_links']);
        });
    }
};
