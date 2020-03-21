<?php
/**
 * This file creates draggable_components for the report-generator.
 * This is close to the list_options table, but this one if for the report generator module.
 *
 * TODO Link this table to users table in order to keep track of user adding or editing a component.
 * @author Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDraggableComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @author Tigpezeghe Rodrige K. <tigrodrige@gmail.com> (2018)
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_report_generator')->create('draggable_components', function (Blueprint $table) {
            $table->increments('id')->comment = "This will identify each component with a unique integer.";
            $table->string('option_id', 255)->comment = "All draggable components have an ID.";
            $table->boolean('is_default')->default(1)->comment = "0 -> False, 1 -> True.";
            $table->string('user', 255)->default('default')->comment = "The user who created the component. This will be 'default' for components that come with the module.";
            $table->string('title', 255)->comment = "This is the text on the component that end users see.";
            $table->integer('order', 0)->comment = "The order in which components appear in the list.";
            $table->boolean('active')->default(1)->comment = "0 -> False, 1 -> True whether the component should be active or not.";
            $table->string('note', 255)->comment = "This stores the fields that the component relates to in the database.";
            $table->boolean('toggle_sort')->default(0)->comment = "0 -> False (Descending), 1 -> True (Ascending).";
            $table->boolean('toggle_display')->default(0)->comment = "0 -> False, 1 -> True. Display field if checked (1), and no if unchecked (0).";

            $table->timestamps();

            // $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('draggable_components');
    }
}
