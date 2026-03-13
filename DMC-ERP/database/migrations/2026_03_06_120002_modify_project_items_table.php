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
        Schema::table('project_items', function (Blueprint $table) {
            // Add project_id column as the primary relationship
            $table->unsignedBigInteger('project_id')->after('id');
            
            // Add supplier_id column
            $table->unsignedBigInteger('supplier_id')->after('item_id');
            
            // Add unit_price column
            $table->decimal('unit_price', 10, 2)->after('quantity');
            
            // Add foreign key for project_id
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            
            // Add foreign key for supplier_id
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            
            // Drop the project_name column
            $table->dropColumn('project_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_items', function (Blueprint $table) {
            // Drop the foreign keys
            $table->dropForeign(['project_id']);
            $table->dropForeign(['supplier_id']);
            
            // Drop the new columns
            $table->dropColumn(['project_id', 'supplier_id', 'unit_price']);
            
            // Re-add project_name column
            $table->string('project_name')->after('id');
        });
    }
};
