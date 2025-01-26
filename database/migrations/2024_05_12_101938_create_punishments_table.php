<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePunishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('punishments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('prosecution_id');
            $table->bigInteger('criminal_id');
            $table->string('order_type')->nullable()->comment('Three types: PUNISHMENT, RELEASE, REGULARCASE');
            $table->string('fine_in_word')->nullable();
            $table->integer('fine')->nullable();
            $table->string('receipt_no')->nullable();
            $table->string('exe_jail_type')->nullable()->default('2');
            $table->string('warrent_duration')->nullable();
            $table->string('warrent_detail')->nullable();
            $table->string('rep_warrent_duration')->nullable();
            $table->string('rep_warrent_detail')->nullable();
            $table->string('total_warrant')->nullable();
            $table->string('total_fine')->nullable();
            $table->integer('warrent_type')->nullable();
            $table->integer('rep_warrent_type')->nullable();
            $table->string('warrent_type_text')->nullable();
            $table->string('responsibleAdalotAddress')->nullable();
            $table->string('punishmentJailName')->nullable();
            $table->bigInteger('order_text_id')->nullable();
            $table->bigInteger('punishmentJailID')->nullable();
            $table->string('rep_warrent_type_text')->nullable();
            $table->longText('orderJustificationText')->nullable();
            $table->integer('responsibleThanaID')->nullable();
            $table->string('responsibleDepartmentName')->nullable();
            $table->string('regular_case_type_name')->nullable();
            $table->longText('order_detail')->nullable();
            $table->integer('laws_broken_id')->nullable();
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
        Schema::dropIfExists('punishments');
    }
}
