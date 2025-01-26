<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_texts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('prosecution_id')->nullable();
            $table->string('order_type')->nullable()->comment('Three types: PUNISHMENT, RELEASE, REGULARCASE');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('order_texts');
    }
}
