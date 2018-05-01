<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTfTagsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates tf_tags table.
     * From UI, Administration -> Tags.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('tf_tags', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincremnet";
	    $table->integer('created_by', 0)->unsigned()->comment = "User who created this tag. Foreign key to users table.";
	    $table->integer('updated_by', 0)->unsigned()->comment = "User who updated this tag. Foreign key to users table.";
	    $table->string('tag_name')->comment = "Name of tag.";
	    $table->string('tag_color', 7)->comment = "Color used to denote this tag on layout.";

	    /*Establishing Relationship*/
	    $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
	    $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
	    $table->index('tag_name'); //Indexing tag name.
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
        Schema::dropIfExists('tf_tags');
    }
}
