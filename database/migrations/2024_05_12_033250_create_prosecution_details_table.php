<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProsecutionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prosecution_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prosecution_id')->nullable();
            $table->unsignedBigInteger('criminal_id')->nullable();
            $table->tinyInteger('delete_status')->nullable()->default(1);
            $table->string('updated_by')->nullable();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prosecution_details');
    }
}
