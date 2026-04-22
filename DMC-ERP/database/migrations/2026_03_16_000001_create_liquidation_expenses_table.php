<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liquidation_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('liquidation_id')->constrained()->cascadeOnDelete();
            $table->date('expense_date');
            $table->foreignId('particular_id')->constrained();
            $table->string('transaction_details')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidation_expenses');
    }
};
