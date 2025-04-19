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
    Schema::create('passengers', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('customer_id');
        $table->string('passengers')->nullable();
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->string('mobile_number', 20)->nullable();
        $table->string('email')->nullable();
        $table->string('gender', 50)->nullable();
        $table->date('dob')->nullable();
        $table->date('anniversary')->nullable();
        $table->string('pan_number', 20)->nullable();
        $table->string('passport_number', 20)->nullable();
        $table->string('passport_issue_city', 100)->nullable();
        $table->string('passport_issue_country', 100)->nullable();
        $table->date('passport_issue_date')->nullable();
        $table->date('passport_expiry_date')->nullable();
        $table->string('passport_front', 500)->nullable();
        $table->string('passport_back', 500)->nullable();
        $table->string('pan_card', 500)->nullable();
        $table->timestamps(); // Handles created_at and updated_at
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passengerdetails');
    }
};
