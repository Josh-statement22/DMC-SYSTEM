<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->enum('transaction_type', ['debit', 'credit'])->default('debit')->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
        });
    }
};
