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
            $table->string('company_name')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('landline_number')->nullable();
            $table->foreignId('service_id')->constrained('services', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('service_name')->nullable();
            $table->string('domain_name')->nullable();
            $table->string('company_website')->nullable();
            $table->date('contracted_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->date('contract_end_date_ad')->nullable();
            $table->string('contracted_by')->nullable();
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
