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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('first_name', 50)->nullable();
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('permanent_address', 100)->nullable();
            $table->string('temporary_address', 100)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone_number')->nullable();
            $table->string('profile')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('dob_bs')->nullable();
            $table->date('dob_ad')->nullable();
            $table->string('blood_group')->nullable();
            $table->date('recruited_date_bs')->nullable();
            $table->date('recruited_date_ad')->nullable();
            $table->string('documents')->nullable();
            $table->integer('department_id')->nullable();
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
        Schema::dropIfExists('profiles');
    }
};
