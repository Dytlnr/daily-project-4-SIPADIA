<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->json('search_links')->nullable()->after('evidence_links');
            $table->timestamp('last_searched_at')->nullable()->after('search_links');
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn(['search_links', 'last_searched_at']);
        });
    }
};
