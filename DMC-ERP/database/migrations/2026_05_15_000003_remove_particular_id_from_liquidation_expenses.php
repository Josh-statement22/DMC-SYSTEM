<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kept as a no-op because the application still stores expenses by particular_id.
    }

    public function down(): void
    {
        // No-op.
    }
};
