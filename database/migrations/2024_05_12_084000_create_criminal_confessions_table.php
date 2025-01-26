<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCriminalConfessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criminal_confessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('prosecution_id')->nullable();
            $table->unsignedBigInteger('criminal_id')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('criminal_confessions');
    }
}
