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
        Schema::create('task_management', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->nullable();
            $table->string('task_title', 100)->nullable();
            $table->foreignId('project_id')->constrained('project_management', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('task_type')->nullable();
            $table->date('task_start_date')->nullable();
            $table->date('task_start_date_ad')->nullable();
            $table->date('task_end_date')->nullable();
            $table->date('task_end_date_ad')->nullable();
            $table->string('estimated_hour', 100)->nullable();
            $table->string('priority')->nullable();
            $table->json('assigned_to')->nullable();
            $table->longText('task_description')->nullable();
            $table->tinyInteger('task_point')->nullable();
            $table->string('task_status')->nullable();
            $table->string('task_started_date_and_time')->nullable();
            $table->string('task_started_date_and_time_ad')->nullable();
            $table->string('task_completed_date_and_time')->nullable();
            $table->string('task_completed_date_and_time_ad')->nullable();
            $table->tinyInteger('achieved_point')->nullable();
            $table->integer('verified_by')->nullable();
            $table->date('verified_date')->nullable();
            $table->date('verified_date_ad')->nullable();
            $table->enum('status', ['Y', 'N'])->default('Y');
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
        Schema::dropIfExists('task_management');
    }
};
