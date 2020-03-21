<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates categories table.
     * From UI, Administration -> Practice -> Documents -> 'Select Any Category' -> Add Category.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->string('name')->comment = "Name of category.";
	    /*$table->string('value')->nullable()->comment = "Value";*/ //This needs to be dropped no usage found.
	    $table->integer('parent', 0)->unsigned()->default(0)->comment = "Parent of sub directory. Category directory is root and hence it will have parent as 0.";
	    $table->integer('lft', 0)->unsigned()->default(0)->comment = "Left Subtree.";
	    $table->integer('rght', 0)->unsigned()->default(0)->comment = "Right Subtree";
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
        Schema::dropIfExists('categories');
    }
}
