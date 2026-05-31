<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('liquidation_expenses')
            || ! Schema::hasColumn('liquidation_expenses', 'cash_advance_request_id')) {
            return;
        }

        DB::statement("
            UPDATE liquidation_expenses AS le
            JOIN (
                SELECT matched.liquidation_id, matched.expense_date, MAX(matched.cash_advance_request_id) AS cash_advance_request_id
                FROM (
                    SELECT legacy.liquidation_id, legacy.expense_date, car.id AS cash_advance_request_id
                    FROM (
                        SELECT
                            le2.liquidation_id,
                            le2.expense_date,
                            l.user_id,
                            ROUND(SUM(le2.amount), 2) AS breakdown_total
                        FROM liquidation_expenses AS le2
                        JOIN liquidations AS l ON l.id = le2.liquidation_id
                        WHERE le2.cash_advance_request_id IS NULL
                        GROUP BY le2.liquidation_id, le2.expense_date, l.user_id
                    ) AS legacy
                    JOIN cash_advance_requests AS car
                        ON car.requester_id = legacy.user_id
                        AND car.request_date = legacy.expense_date
                        AND ROUND(COALESCE(car.approved_amount, car.requested_amount, 0), 2) = legacy.breakdown_total
                ) AS matched
                GROUP BY matched.liquidation_id, matched.expense_date
                HAVING COUNT(*) = 1
            ) AS resolved
                ON resolved.liquidation_id = le.liquidation_id
                AND resolved.expense_date = le.expense_date
            SET le.cash_advance_request_id = resolved.cash_advance_request_id,
                le.updated_at = CURRENT_TIMESTAMP
            WHERE le.cash_advance_request_id IS NULL
        ");
    }

    public function down(): void
    {
        // Intentionally no-op: the backfill only records an inferred parent link.
    }
};
