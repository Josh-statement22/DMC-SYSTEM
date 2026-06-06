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

        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement("
                UPDATE cash_advance_requests
                SET
                    approved_by_name = COALESCE(
                        approved_by_name,
                        CASE
                            WHEN LOWER(COALESCE(status, '')) = 'approved'
                            THEN (SELECT users.name FROM users WHERE users.id = cash_advance_requests.reviewed_by)
                            ELSE NULL
                        END
                    ),
                    sent_by_name = COALESCE(
                        sent_by_name,
                        CASE
                            WHEN LOWER(COALESCE(status, '')) = 'approved'
                            THEN (SELECT users.name FROM users WHERE users.id = cash_advance_requests.reviewed_by)
                            ELSE NULL
                        END
                    )
                WHERE reviewed_by IS NOT NULL
            ");
        } else {
            DB::statement("
                UPDATE cash_advance_requests AS car
                LEFT JOIN users AS reviewers ON car.reviewed_by = reviewers.id
                SET
                    car.approved_by_name = COALESCE(
                        car.approved_by_name,
                        CASE
                            WHEN LOWER(COALESCE(car.status, '')) = 'approved' THEN reviewers.name
                            ELSE NULL
                        END
                    ),
                    car.sent_by_name = COALESCE(
                        car.sent_by_name,
                        CASE
                            WHEN LOWER(COALESCE(car.status, '')) = 'approved' THEN reviewers.name
                            ELSE NULL
                        END
                    )
                WHERE car.reviewed_by IS NOT NULL
            ");
        }
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
