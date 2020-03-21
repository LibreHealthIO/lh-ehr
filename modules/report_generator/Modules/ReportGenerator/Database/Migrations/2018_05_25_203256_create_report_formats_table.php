<?php
/**
 * This file creates report formats for the report-generator.
 * These report formats are close to system static reports, but this one if for the report generator module.
 *
 * TODO Link this table to users table in order to keep track of user adding or editing a component.
 * @author Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportFormatsTable extends Migration
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
        Schema::connection('mysql_report_generator')->create('report_formats', function (Blueprint $table) {
            $table->increments('id')->comment = "This will identify each component with a unique integer.";
            $table->string('user', 255)->default('default')->comment = "The user who created the report format. This will be 'default' for components that come with the module.";
            $table->string('title', 255)->comment = "This is the report name e.g Patient List.";
            $table->text('description')->comment = "This describes the report format briefly.";
            //$table->string('option_ids')->comment = "This stores the option ids of components that constitute this report format";
            $table->integer('system_feature_id')->unsigned()->comment = "The system feature under which the report belongs.";
            $table->foreign('system_feature_id')->references('id')->on('system_features')->onDelete('cascade');
            //$table->string('draggable_components_id', 1000)->comment = "This stores the id of all the components that belong to this report format";


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
        Schema::dropIfExists('report_formats');
    }
}
