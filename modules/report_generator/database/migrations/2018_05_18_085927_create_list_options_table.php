<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListOptionsTable extends Migration
{
    /**
     * Run the migrations.
     * This will create list_options.
     * TODO this migration file isn't complete. It lacks
     * the necessary foreign keys.
     *
     * @author Tigpezeghe Rodrige K. <tigrodrige@gmail.com> (2018)
     * @return void
     */
    public function up()
    {
        Schema::create('list_options', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
            $table->string('list_id', 31)->comment = "List Id.";
            $table->string('option_id', 31)->comment = "Id for list item.";
            $table->string('title', 255)->comment = "The title of the component.";
            $table->integer('seq', 0)->comment = "The order in which the components appear in the list.";
            $table->boolean('is_default')->default(0)->comment = "0 -> False, 1 -> True.";
            $table->float('option_value', 8, 2)->default(0)->comment = "";
            $table->string('mapping', 31)->comment = "";
            $table->string('notes', 255)->comment = "This stores the meaning or usefulness of the component.";
            $table->string('codes', 255)->comment = "";
            $table->boolean('toggle_setting_1')->default(0)->comment = "0 -> False, 1 -> True.";
            $table->boolean('toggle_setting_2')->default(0)->comment = "0 -> False, 1 -> True.";
            $table->boolean('activity')->default(1)->comment = "0 -> False, 1 -> True whether the component should be active or not.";
            $table->string('subtype', 31)->comment = "";
            
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
        Schema::dropIfExists('list_options');
    }
}
