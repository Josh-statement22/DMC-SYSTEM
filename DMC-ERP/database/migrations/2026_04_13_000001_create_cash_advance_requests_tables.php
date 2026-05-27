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
        if (! Schema::hasTable('cash_advance_requests')) {
            Schema::create('cash_advance_requests', function (Blueprint $table) {
                $table->id();
                $table->string('reference_no')->nullable()->unique();
                $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
                $table->decimal('requested_amount', 12, 2);
                $table->decimal('approved_amount', 12, 2)->nullable();
                $table->text('purpose');
                $table->date('request_date');
                $table->date('date_needed');
                $table->string('status')->default('pending');
                $table->text('accounting_remarks')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamp('released_at')->nullable();
                $table->date('liquidation_due_date')->nullable();
                $table->timestamps();

                $table->index(['status', 'request_date']);
                $table->index(['requester_id', 'status']);
                $table->index('released_at');
            });
        }

        if (! Schema::hasTable('cash_advance_request_attachments')) {
            Schema::create('cash_advance_request_attachments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cash_advance_request_id')->constrained('cash_advance_requests')->cascadeOnDelete();
                $table->string('original_name');
                $table->string('file_path');
                $table->string('mime_type', 120)->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('cash_advance_request_audits')) {
            Schema::create('cash_advance_request_audits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cash_advance_request_id')->constrained('cash_advance_requests')->cascadeOnDelete();
                $table->string('action', 60);
                $table->string('old_status')->nullable();
                $table->string('new_status')->nullable();
                $table->text('remarks')->nullable();
                $table->foreignId('acted_by')->nullable()->constrained('users')->nullOnDelete();
                $table->json('meta')->nullable();
                $table->timestamp('acted_at')->nullable();
                $table->timestamps();

                $table->index(['cash_advance_request_id', 'acted_at'], 'idx_cash_adv_req_acted_at');
                $table->index(['action', 'acted_at'], 'idx_action_acted_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_advance_request_audits');
        Schema::dropIfExists('cash_advance_request_attachments');
        Schema::dropIfExists('cash_advance_requests');
    }
};
