<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCriminalConfessionLawsbrokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criminal_confession_lawsbrokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('CriminalConfessionID')->nullable();
            $table->unsignedBigInteger('LawsBrokenID')->nullable();
            $table->string('Confessed')->nullable();
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
        Schema::dropIfExists('criminal_confession_lawsbrokens');
    }
}
