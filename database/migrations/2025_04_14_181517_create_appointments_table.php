<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('appointment_date');
            $table->integer('duration_minutes')->default(30);
            $table->enum('meeting_type', ['in-person', 'virtual'])->default('in-person');
            $table->string('meeting_link')->nullable();
            $table->string('status')->default('pending');
            $table->text('description')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->boolean('is_rescheduled')->default(false);
            $table->foreignId('rescheduled_from_id')->nullable()->constrained('appointments')->onDelete('set null');
            $table->dateTime('suggested_date')->nullable();
            $table->text('suggestion_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
