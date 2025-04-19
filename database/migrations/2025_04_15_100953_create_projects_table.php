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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');

            // Project Timeline
            $table->integer('duration')->nullable(); // in months
            $table->date('commencement_date')->nullable();
            $table->date('completion_date')->nullable();

            // Financial Information
            $table->decimal('project_value', 15, 2)->default(0);
            $table->decimal('total_billed', 15, 2)->default(0);
            $table->decimal('remaining_unbilled', 15, 2)->default(0);
            $table->decimal('planned_percentage', 8, 2)->default(0);
            $table->decimal('actual_percentage', 8, 2)->default(0);
            $table->decimal('variance_percentage', 8, 2)->default(0);

            // Time Tracking
            $table->string('time_elapsed')->nullable();
            $table->integer('time_balance')->nullable();

            // Status Information
            $table->date('variation_status')->nullable();
            $table->decimal('variation_number', 15, 2)->default(0);
            $table->string('eot_status')->nullable();
            $table->string('ncrs_status')->nullable();

            // Current Invoice
            $table->string('current_invoice_status')->nullable();
            $table->decimal('current_invoice_value', 15, 2)->default(0);
            $table->string('current_invoice_month')->nullable();

            // Previous Invoice
            $table->string('previous_invoice_status')->nullable();
            $table->decimal('previous_invoice_value', 15, 2)->default(0);
            $table->string('previous_invoice_month')->nullable();

            // Expected Invoice
            $table->date('expected_invoice_date')->nullable();
            $table->string('expected_invoice_month')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
