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
        if (!Schema::hasColumn('projects', 'project_date')) {
            Schema::table('projects', function (Blueprint $table) {
                // Add project_date column after project_name
                $table->date('project_date')->nullable()->after('project_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('projects', 'project_date')) {
            Schema::table('projects', function (Blueprint $table) {
                // Drop project_date column
                $table->dropColumn('project_date');
            });
        }
    }
};
