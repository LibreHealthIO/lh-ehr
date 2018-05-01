<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormTrackAnythingTypesTable extends Migration
{
    /**
     * Run the migrations.
     * Creates form_track_anything_types table.
     * From UI, Select Patient -> Encounter -> Miscellaneous -> Track Anything -> Configure Tracks -> Create New Track. | Select Patient -> Encounter -> Patient/CLient -> Visit Forms -> Track Anything -> Configure Tracks -> Create New Track.
     * This stores newly created track.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('form_track_anything_types', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->string('name')->comment = "Name of Type.";
	    $table->text('description')->nullable()->comment = "Description of Type.";
	    $table->integer('parent', 0)->unsigned()->default(0)->comment = "Parent of any type. This happens in the case when you edit the type and enters a position of another type. Initially it will contain 0 as default value, indicating no parent.";
	    $table->integer('position', 0)->unsigned()->default(0)->comment = "At which position it should appear in the list.";
	    $table->boolean('active')->default(0)->comment = "Whether this type is enabled or not. Disable from Configure Track. 0 -> False | 1 -> True.";
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
        Schema::dropIfExists('form_track_anything_types');
    }
}
