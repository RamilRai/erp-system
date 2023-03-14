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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('organization_type');
            $table->string('organization_name', 100)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('contact_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('lead_by_name', 100)->nullable();
            $table->date('lead_date')->nullable();
            $table->enum('lead_status', ['Pending', 'Active', 'Cancelled'])->default('Pending');
            $table->enum('active_status', ['Y', 'N'])->default('Y');
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
        Schema::dropIfExists('leads');
    }
};
