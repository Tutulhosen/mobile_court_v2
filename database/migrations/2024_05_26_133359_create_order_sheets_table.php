<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_sheets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('prosecution_id')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('order_header')->nullable();
            $table->string('order_details')->nullable();
            $table->string('receipt_no')->nullable();
            $table->string('version')->nullable();
            $table->tinyInteger('delete_status')->nullable()->default(1);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

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
        Schema::dropIfExists('order_sheets');
    }
}
