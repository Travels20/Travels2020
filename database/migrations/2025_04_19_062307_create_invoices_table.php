<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // auto-increment primary key
            $table->string('customer_name');
            $table->text('customer_address');
            $table->string('customer_gst_no', 50)->nullable(); // Nullable field
            $table->date('travel_from');
            $table->date('travel_to');
            $table->string('destination');
            $table->integer('num_adults');
            $table->integer('num_children');
            $table->decimal('adults_cost', 10, 2);
            $table->decimal('child_cost', 10, 2);
            $table->decimal('service_cost', 10, 2);
            $table->text('notes')->nullable(); // Nullable field
            $table->decimal('service_gst', 5, 2);
            $table->string('office_gst_no', 50);
            $table->string('office_pan_no', 50);
            // $table->timestamps(); // Adds created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
