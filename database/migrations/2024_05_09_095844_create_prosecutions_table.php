<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProsecutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prosecutions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('orderSheet_id')->nullable()->default(null);
            $table->string('subject')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('location')->nullable();
            $table->string('hints')->nullable();
            $table->unsignedBigInteger('court_id')->nullable();
            $table->tinyInteger('is_suomotu')->nullable();
            $table->tinyInteger('hasCriminal')->nullable();
            $table->string('case_status')->nullable();
            $table->string('prosecutor_details')->nullable();
            $table->string('prosecutor_name')->nullable();
            $table->string('witness1_name')->nullable();
            $table->string('witness1_custodian_name')->nullable();
            // $table->date('date_of_birth')->nullable();
            $table->string('witness1_mother_name')->nullable();
            $table->string('witness1_mobile_no')->nullable();
            $table->string('witness1_nationalid')->nullable();
            $table->string('witness1_address')->nullable();
            $table->string('witness1_imprint1')->nullable();
            $table->string('witness1_imprint2')->nullable();
            $table->string('witness2_name')->nullable();
            $table->string('witness2_custodian_name')->nullable();
            $table->string('witness2_mother_name')->nullable();
            $table->string('witness2_mobile_no')->nullable();
            $table->string('witness2_nationalid')->nullable();
            $table->string('witness2_address')->nullable();
            $table->string('witness2_imprint1')->nullable();
            $table->string('witness2_imprint2')->nullable();
            $table->unsignedBigInteger('requisition_id')->nullable();
            $table->tinyInteger('is_approved')->nullable();
            $table->tinyInteger('delete_status')->nullable();
            $table->string('case_no')->nullable();
            $table->unsignedBigInteger('divid')->nullable();
            $table->unsignedBigInteger('zillaId')->nullable();
            $table->string('location_type')->nullable();
            $table->unsignedBigInteger('upazilaid')->nullable();
            $table->integer('geo_metropolitan_id')->nullable();
            $table->integer('geo_citycorporation_id')->nullable();
            $table->integer('geo_thana_id')->nullable();
            $table->integer('geo_ward_id')->nullable();
            $table->unsignedBigInteger('prosecutor_id')->nullable();
            $table->string('witness1_age')->nullable();
            $table->string('witness2_age')->nullable();
            $table->integer('case_type1')->nullable();
            $table->integer('case_type2')->nullable();
            $table->integer('occurrence_type')->nullable();
            $table->string('occurrence_type_text')->nullable();
            $table->integer('no_criminal')->nullable()->comment('0= no criminal, and 1= criminal exists');
            $table->integer('is_altered')->nullable();
            $table->tinyInteger('is_sizeddispose')->nullable();
            $table->string('dispose_detail')->nullable();
            $table->string('jimmader_name')->nullable();
            $table->string('jimmader_address')->nullable();
            $table->string('jimmader_designation')->nullable();
            $table->integer('is_attached')->nullable();
            $table->string('user_idno')->nullable();
            $table->unsignedBigInteger('magistrate_id')->nullable();
            $table->string('location_type_old')->nullable();
            $table->string('created_by')->nullable();
            $table->string('update_by')->nullable();
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
        Schema::dropIfExists('prosecutions');
    }
}
