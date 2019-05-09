<?php
/**
 * This file creates a join or junction table between Report formats and Draggable components.
 * This table is used to handle the many-to-many relationship between both tables.
 *
 * @author Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDraggableComponentReportFormatTable extends Migration
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
        Schema::connection('mysql_report_generator')->create('draggable_component_report_format', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary key.";
            $table->integer('draggable_component_id')->unsigned()->comment = "Used to access the draggable_components.";
            $table->foreign('draggable_component_id')->references('id')->on('draggable_components')->onDelete('cascade');
            $table->integer('report_format_id')->unsigned()->comment = "Used to access the report_formats.";
            $table->foreign('report_format_id')->references('id')->on('report_formats')->onDelete('cascade');

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
        Schema::dropIfExists('draggable_component_report_format');
    }
}
