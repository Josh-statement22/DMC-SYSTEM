<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cash_advance_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('cash_advance_requests', 'approved_by_name')) {
                $table->string('approved_by_name')->nullable()->after('reviewed_by');
            }

            if (! Schema::hasColumn('cash_advance_requests', 'sent_by_name')) {
                $table->string('sent_by_name')->nullable()->after('approved_by_name');
            }
        });

            DB::statement("\n            UPDATE cash_advance_requests AS car\n            LEFT JOIN users AS reviewers ON car.reviewed_by = reviewers.id\n            SET\n                car.approved_by_name = COALESCE(\n                    car.approved_by_name,\n                    CASE\n                        WHEN LOWER(COALESCE(car.status, '')) = 'approved' THEN reviewers.name\n                        ELSE NULL\n                    END\n                ),\n                car.sent_by_name = COALESCE(\n                    car.sent_by_name,\n                    CASE\n                        WHEN LOWER(COALESCE(car.status, '')) = 'approved' THEN reviewers.name\n                        ELSE NULL\n                    END\n                )\n            WHERE car.reviewed_by IS NOT NULL\n        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_advance_requests', function (Blueprint $table) {
            if (Schema::hasColumn('cash_advance_requests', 'sent_by_name')) {
                $table->dropColumn('sent_by_name');
            }

            if (Schema::hasColumn('cash_advance_requests', 'approved_by_name')) {
                $table->dropColumn('approved_by_name');
            }
        });
    }
};
