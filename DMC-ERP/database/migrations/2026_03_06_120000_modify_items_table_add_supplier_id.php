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
            // Foreign key relationship already exists, so just drop unnecessary columns
            
            // Drop the supplier_name column
            $table->dropColumn('supplier_name');
            
            // Drop the project column (not in requirements)
            if (Schema::hasColumn('items', 'project')) {
                $table->dropColumn('project');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Re-add supplier_name column
            $table->string('supplier_name')->nullable()->after('item_description');
            
            // Re-add project column
            $table->string('project')->nullable()->after('id');
        });
    }
};
