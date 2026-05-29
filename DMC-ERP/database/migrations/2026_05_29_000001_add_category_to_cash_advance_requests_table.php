<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cash_advance_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('cash_advance_requests', 'category')) {
                $table->string('category')->nullable()->after('purpose');
                $table->index('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_advance_requests', function (Blueprint $table) {
            if (Schema::hasColumn('cash_advance_requests', 'category')) {
                $table->dropIndex(['category']);
                $table->dropColumn('category');
            }
        });
    }
};
