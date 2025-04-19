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
        Schema::create('customers_details', function (Blueprint $table) {
            $table->id(); // auto-increment primary key
            $table->string('travel_from');
            $table->string('travel_to');
            $table->string('destination');
            $table->string('relationship')->nullable();
            $table->integer('adults')->default(0);
            $table->integer('children')->default(0);
            $table->timestamps(); // adds created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers_details');
    }
};
