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
        Schema::create('extra_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_no')->nullable();
            $table->string('task_title')->nullable();
            $table->foreignId('project_id')->constrained('project_management', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('task_type')->nullable();
            $table->timestamp('task_created_date_ad')->nullable();
            $table->timestamp('task_created_date_bs')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('task_status')->nullable();
            $table->longText('task_description')->nullable();
            $table->timestamp('task_completed_date_ad')->nullable();
            $table->timestamp('task_completed_date_bs')->nullable();
            $table->string('documents')->nullable();
            $table->longText('remarks')->nullable();
            $table->timestamp('task_verified_date_ad')->nullable();
            $table->timestamp('task_verified_date_bs')->nullable();
            $table->tinyInteger('achieved_point')->nullable();
            $table->longText('supervisor_response')->nullable();
            $table->integer('verified_by')->nullable();
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
        Schema::dropIfExists('extra_tasks');
    }
};
