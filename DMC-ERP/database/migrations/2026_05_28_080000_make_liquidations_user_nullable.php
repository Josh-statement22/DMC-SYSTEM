<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        // Drop existing foreign key, make column nullable, and re-add FK with ON DELETE SET NULL
        // Use raw statements to avoid requiring doctrine/dbal for column change
        DB::statement('ALTER TABLE `liquidations` DROP FOREIGN KEY `liquidations_user_id_foreign`');
        DB::statement('ALTER TABLE `liquidations` MODIFY `user_id` BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE `liquidations` ADD CONSTRAINT `liquidations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        // Revert to non-nullable with cascade on delete
        DB::statement('ALTER TABLE `liquidations` DROP FOREIGN KEY `liquidations_user_id_foreign`');
        DB::statement('ALTER TABLE `liquidations` MODIFY `user_id` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `liquidations` ADD CONSTRAINT `liquidations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE');
    }
};
