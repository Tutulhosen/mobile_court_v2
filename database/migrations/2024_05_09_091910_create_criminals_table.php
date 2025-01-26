<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCriminalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criminals', function (Blueprint $table) {

            // `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
            // `name_bng` varchar(100) NOT NULL,
            // `custodian_name` varchar(100) NOT NULL,
            // `custodian_type` varchar(25) DEFAULT NULL,
            // `mother_name` varchar(100) DEFAULT NULL,
            // `age` varchar(100) NOT NULL,
            // `gender` varchar(25) DEFAULT NULL,
            // `occupation` varchar(100) DEFAULT NULL,
            // `present_address` varchar(250) NOT NULL,
            // `permanent_address` varchar(250) DEFAULT NULL,
            // `do_address` tinyint DEFAULT '1',
            // `national_id` varchar(100) DEFAULT NULL,
            // `date_of_birth` date DEFAULT NULL,
            // `profession` varchar(100) DEFAULT NULL,
            // `phone_no` varchar(100) DEFAULT NULL,
            // `mobile_no` varchar(100) DEFAULT NULL,
            // `created_by` varchar(100) NOT NULL,

            // `created_date` datetime NOT NULL,

            // `update_by` varchar(100) DEFAULT NULL,

            // `update_date` datetime DEFAULT NULL,

            // `delete_status` tinyint(1) NOT NULL DEFAULT '1',
            // `email` varchar(100) DEFAULT NULL,

            // `divid` bigint DEFAULT NULL,
            // `zillaId` bigint DEFAULT NULL,
            // `location_type` varchar(50) DEFAULT NULL,
            // `upazilaid` bigint DEFAULT NULL,
            // `geo_metropolitan_id` int DEFAULT NULL,
            // `geo_citycorporation_id` int DEFAULT NULL,
            // `geo_thana_id` int DEFAULT NULL,
            // `geo_ward_id` int DEFAULT NULL,
            // `imprint1` longtext,
            // `imprint2` longtext,
            // `organization_name` varchar(250) DEFAULT NULL,
            // `trade_no` varchar(250) DEFAULT NULL,
            $table->id();
            $table->string('name')->nullable();
            $table->string('name_bng')->nullable();
            $table->string('name_eng')->nullable();
            $table->string('custodian_name')->nullable();
            $table->string('custodian_type')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('occupation')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('do_address')->nullable();
            $table->string('national_id')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('profession')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('delete_status')->nullable();
            $table->string('email')->nullable();
            $table->string('divid')->nullable();
            $table->integer('zillaId')->nullable();
            $table->string('location_type')->nullable();
            $table->integer('upazilaid')->nullable();
            $table->integer('geo_metropolitan_id')->nullable();
            $table->integer('geo_citycorporation_id')->nullable();
            $table->integer('geo_thana_id')->nullable();
            $table->integer('geo_ward_id')->nullable();
            $table->string('imprint1')->nullable();
            $table->string('imprint2')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('trade_no')->nullable();

            // $table->foreign('zillaId')->references('id')->on('geo_districts');


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
        Schema::dropIfExists('criminals');
    }
}
