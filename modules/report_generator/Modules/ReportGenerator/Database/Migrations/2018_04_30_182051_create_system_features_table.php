<?php
/**
 * This file creates a table for system features, e.g Clients, Financial, Insurance.
 * This table is used to handle the many-to-many relationship between both tables.
 *
 * @author Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemFeaturesTable extends Migration
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
        Schema::connection('mysql_report_generator')->create('system_features', function (Blueprint $table) {
            $table->increments('id')->comment = "This will identify each system feature with a unique integer.";
            $table->string('name')->comment = "The name of the system feature.";
            $table->text('description')->comment = "This describes the system feature briefly.";
            $table->string('user', 255)->default('default')->comment = "The user who created the feature. This will be 'default' for features that come with the module.";

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
        Schema::dropIfExists('system_features');
    }
}
