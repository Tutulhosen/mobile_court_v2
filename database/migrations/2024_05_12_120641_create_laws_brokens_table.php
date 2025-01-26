<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawsBrokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laws_brokens', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('prosecution_id')->nullable();
            $table->bigInteger('law_id')->nullable();
            $table->bigInteger('section_id')->nullable();
            $table->string('Description')->nullable();
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
        Schema::dropIfExists('laws_brokens');
    }
}
