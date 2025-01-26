<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeizurelistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seizurelists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date');
            $table->string('stuff_description')->nullable();
            $table->string('stuff_weight')->nullable();
            $table->string('apx_value')->nullable();
            $table->string('hints')->nullable();
            $table->unsignedBigInteger('seizureitem_type_id')->nullable();
            $table->string('seizureitem_type_group')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('prosecution_id')->nullable();
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
        Schema::dropIfExists('seizurelists');
    }
}
