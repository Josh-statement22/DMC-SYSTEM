<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cash_advance_requests') || Schema::hasColumn('cash_advance_requests', 'category_id')) {
            return;
        }

        Schema::table('cash_advance_requests', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('category')
                ->constrained('categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('cash_advance_requests') || ! Schema::hasColumn('cash_advance_requests', 'category_id')) {
            return;
        }

        Schema::table('cash_advance_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
