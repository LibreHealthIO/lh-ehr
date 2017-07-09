<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibreehrCalendarEventsTable extends Migration
{
    /**
     * Run the migrations.
     * Creates libreehr_calendar_events table. Earlier it was libreehr_postcalendar_events.
     * Stores the events in calendar. Fields will be updated later on. 
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('libreehr_calendar_events', function (Blueprint $table) {
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
        Schema::dropIfExists('libreehr_calendar_events');
    }
}
