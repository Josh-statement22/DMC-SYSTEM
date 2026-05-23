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
        if (!Schema::hasTable('particulars')) {
            Schema::create('particulars', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $table->string('particular_name');
                $table->text('description')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('particulars', function (Blueprint $table) {
            if (!Schema::hasColumn('particulars', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('id')->constrained('categories')->cascadeOnDelete();
            }

            if (!Schema::hasColumn('particulars', 'particular_name')) {
                $table->string('particular_name')->after('category_id');
            }

            if (!Schema::hasColumn('particulars', 'description')) {
                $table->text('description')->nullable()->after('particular_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
