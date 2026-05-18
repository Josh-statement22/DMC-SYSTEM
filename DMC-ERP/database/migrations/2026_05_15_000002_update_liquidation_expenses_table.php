<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->foreignId('particular_id')->constrained('particulars')->onDelete('cascade')->after('expense_date');
        });
    }

    public function down(): void
    {
        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->dropForeign(['particular_id']);
            $table->dropColumn('particular_id');
        });

        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        });
    }
};
