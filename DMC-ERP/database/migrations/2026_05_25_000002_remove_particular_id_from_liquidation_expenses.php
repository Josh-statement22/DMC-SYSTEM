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
        Schema::table('liquidation_expenses', function (Blueprint $table) {
            // Drop the column (handles case where foreign key doesn't exist)
            if (Schema::hasColumn('liquidation_expenses', 'particular_id')) {
                $table->dropColumn('particular_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('particular_id')->nullable()->after('category_id');
            $table->foreign('particular_id')->references('id')->on('particulars')->onDelete('cascade');
        });
    }
};
