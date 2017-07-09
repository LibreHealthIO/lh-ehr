<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     * Create users table.
     * This will store the information from Administartion->Users->Add User.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
            $table->string('fname')->comment = "User First Name. Required.";
	    $table->string('mname')->nullable()->comment = "User Middle Name. Null allowed.";
	    $table->string('lname')->comment = "User Last Name. Required.";
	    $table->string('federalTaxId')->unique()->comment = "Federal Tax ID.";
	    $table->string('federalDrugId')->unique()->comment = "Federal Drug ID. DEA Number";
	    $table->integer('see_auth')->comment = "See Authorization. 0 -> None | 1 -> All | 2 -> Only Mine";
	    $table->integer('npi')->comment = "National Provider Identifier.";
	    $table->string('suffix')->comment = "User Suffix";
	    $table->string('taxonomy')->comment = "Taxonomy";
	    $table->string('calendar_ui')->comment = "Calendar Preference. viz Outlook, Original and Fancy";
	    $table->text('info')->nullable()->comment = "Information About User.";
	    $table->string('newcrop_user_role')->comment = "Role of created user. Like admin, nurse, doctor, etc.";
	    $table->string('access_control')->comment = "Acccess Control of user. Viz Accounting, Administrators, Clinicians, etc.";
	    $table->boolean('inCalendar')->default(1)->comment = "If user wants calendar or not. 0 -> No | 1 -> Yes ";
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
        Schema::dropIfExists('users');
    }
}
