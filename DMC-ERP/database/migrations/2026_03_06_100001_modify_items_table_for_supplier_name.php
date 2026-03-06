<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // Make project nullable
            $table->string('project')->nullable()->change();
            
            // Add supplier_name column
            $table->string('supplier_name')->nullable()->after('item_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('project')->nullable(false)->change();
            $table->dropColumn('supplier_name');
        });
    }
};
