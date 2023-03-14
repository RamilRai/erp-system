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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 100)->nullable();
            $table->string('owner_name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('address')->nullable();
            $table->string('mobile_number', 20)->nullable();
            $table->string('landline_number', 20)->nullable();
            $table->foreignId('service_id')->constrained('services', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('service_name', 100)->nullable();
            $table->string('domain_name', 100)->nullable();
            $table->string('company_website', 100)->nullable();
            $table->date('contracted_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->date('contract_end_date_ad')->nullable();
            $table->string('contracted_by', 100)->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
