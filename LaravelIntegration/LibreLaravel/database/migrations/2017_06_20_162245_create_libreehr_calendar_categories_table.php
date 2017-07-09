<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibreehrCalendarCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates libreehr_calendar_categories table. Earlier it was libreehr_postcalendar_categories.
     * Stores the category. It's fields will be updated later on.
     * @author Priyanhsu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('libreehr_calendar_categories', function (Blueprint $table) {
            $table->increments('id');
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
        Schema::dropIfExists('libreehr_calendar_categories');
    }
}
