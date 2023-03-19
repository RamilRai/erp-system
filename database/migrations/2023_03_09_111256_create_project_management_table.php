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
        Schema::create('project_management', function (Blueprint $table) {
            $table->id();
            $table->string('project_name')->nullable();
            $table->string('pdf')->nullable();
            $table->string('project_type')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->string('project_time_duration')->nullable();
            $table->date('start_date_bs')->nullable();
            $table->date('start_date_ad')->nullable();
            $table->date('end_date_bs')->nullable();
            $table->date('end_date_ad')->nullable();
            $table->foreignId('project_lead_by')->nullable();
            $table->json('assign_team_members')->nullable();
            $table->string('project_status')->nullable();
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
        Schema::dropIfExists('project_management');
    }
};
