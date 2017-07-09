<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFacilityLinksTable extends Migration
{
    /**
     * Run the migrations.
     * Creates user_facility_links table.
     * This links facility with user and vice-versa i.e, many to many relationship.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('user_facility_links', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('userID', 0)->unsigned()->comment = "Foreign key to users table.";
	    $table->integer('facilityId', 0)->unsigned()->comment = "Foreign key to facilities table.";
	    $table->boolean('isDefault', 0)->default(0)->comment = "Is the current facilty default? Note that it must be updated if any edit in UI.";
	    $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
	    $table->foreign('facilityId')->references('id')->on('facilities')->onDelete('cascade');
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
        Schema::dropIfExists('user_facility_links');
    }
}
