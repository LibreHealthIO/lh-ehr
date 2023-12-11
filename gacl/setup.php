<?php

$config_file = dirname(__FILE__).'/gacl.ini.php';

require_once(dirname(__FILE__).'/admin/gacl_admin.inc.php');
require_once(ADODB_DIR .'/adodb-xmlschema.inc.php');

// check if user is authenticated before displaying setup info
require_once('../interface/globals.php');

$db_table_prefix = $gacl->_db_table_prefix;
$db_type = $gacl->_db_type;
$db_name = $gacl->_db_name;
$db_host = $gacl->_db_host;
$db_user = $gacl->_db_user;
$db_password = $gacl->_db_password;

$failed = 0;
echo '<h4 class="librehealth-color">Configuration...</h4>';
echo '
            <p>driver = <span class="blueblack">'.$db_type.',</span></p>
            <p>host = <span class="blueblack">'.$db_host.',</span></p>
            <p>user = <span class="blueblack">'.$db_user.',</span></p>
            <p>database = <span class="blueblack">'.$db_name.',</span></p>
            <p>table prefix = '.$db_table_prefix.'</p>
            
	';


function echo_success($text) {
	echo '<p><span class="green"><b>Success!</b></span> '.$text."</p>";
}

function echo_failed($text) {
	global $failed;
	echo '<p><span class="red"><b>Failed!</b></span> '.$text."</p>";
	$failed++;
}

function echo_normal($text) {
	echo "<p>".$text."</p>";
}

/*
 * Test database connection
 */
echo '<p class="clearfix"></p>';
echo '<h4 class="librehealth-color">Testing database connection...</h4>';

if (is_object($db->_connectionID)) {
    echo_success('<span class="blueblack">'.$db_type.'</span> database on <span class="blueblack">' .$db_host.'</span>');
} else {
	echo_failed('<b>ERROR</b> connecting to database,<br/>
			are you sure you specified the proper host, user name, password, and database in <b>admin/gacl_admin.inc.php</b>?<br/>
			Did you create the database, and give read/write permissions to &quot;<b>'.$db_user.'</b>&quot; already?');
	exit;
}

/*
 * Do database specific stuff.
 */
echo "<p class='clearfix'></p>";
echo "<h4 class='librehealth-color'>Testing database type...</h4>";

switch ( $db_type ) {
	case ($db_type == "mysql" OR $db_type == "mysqlt" OR $db_type == "maxsql" OR $db_type == "mysqli" ):
		echo_success("Compatible database type <span class='blueblack'>".$db_type."</span> detected!");
		echo_normal("Making sure database <span class='blueblack'>".$db_name."</span> exists...");

		$databases = $db->GetCol("show databases");

		if (in_array($db_name, $databases) ) {
			echo_success("Good database  <span class='blueblack'>".$db_name."</span> already exists!");
		} else {
			echo_normal("Database <span class='blueblack'>".$db_name."</span> does not exist!");
			echo_normal("Lets try to create it...");

			if (!$db->Execute("create database $db_name") ) {
				echo_failed("Database  <span class='blueblack'>".$db_name."</span>  could not be created, please do so manually.");
			} else {
				echo_success("Good, database  <span class='blueblack'>".$db_name."</span>  has been created!!");

				//Reconnect. Hrmm, this is kinda weird.
				$db->Connect($db_host, $db_user, $db_password, $db_name);
			}
		}

		break;
	case ( $db_type == "postgres8" OR $db_type == "postgres7" ):
		echo_success("Compatible database type \"<b>$db_type</b>\" detected!");

		echo_normal("Making sure database \"<b>$db_name</b>\" exists...");

		$databases = $db->GetCol("select datname from pg_database");

		if (in_array($db_name, $databases) ) {
			echo_success("Good, database \"<b>$db_name</b>\" already exists!");
		} else {
			echo_normal("Database \"<b>$db_name</b>\" does not exist!");
			echo_normal("Lets try to create it...");

			if (!$db->Execute("create database $db_name") ) {
				echo_failed("Database \"<b>$db_name</b>\" could not be created, please do so manually.");
			} else {
				echo_success("Good, database \"<b>$db_name</b>\" has been created!!");

				//Reconnect. Hrmm, this is kinda weird.
				$db->Connect($db_host, $db_user, $db_password, $db_name);
			}
		}

		break;

	case "oci8-po":
		echo_success("Compatible database type \"<b>$db_type</b>\" detected!");

		echo_normal("Making sure database \"<b>$db_name</b>\" exists...");

		$databases = $db->GetCol("select '$db_name' from dual");

		if (in_array($db_name, $databases) ) {
				echo_success("Good, database \"<b>$db_name</b>\" already exists!");
		} else {
				echo_normal("Database \"<b>$db_name</b>\" does not exist!");
				echo_normal("Lets try to create it...");

				if (!$db->Execute("create database $db_name") ) {
						echo_failed("Database \"<b>$db_name</b>\" could not be created, please do so manually.");
				} else {
						echo_success("Good, database \"<b>$db_name</b>\" has been created!!");

						//Reconnect. Hrmm, this is kinda weird.
						$db->Connect($db_host, $db_user, $db_password, $db_name);
				}
		}

		break;
		
	case "mssql":
		echo_success("Compatible database type \"<b>$db_type</b>\" detected!");

		echo_normal("Making sure database \"<b>$db_name</b>\" exists...");

		$databases = $db->GetCol("select CATALOG_NAME from INFORMATION_SCHEMA.SCHEMATA");

		if (in_array($db_name, $databases) ) {
				echo_success("Good, database \"<b>$db_name</b>\" already exists!");
		} else {
				echo_normal("Database \"<b>$db_name</b>\" does not exist!");
				echo_normal("Lets try to create it...");

				if (!$db->Execute("create database $db_name") ) {
						echo_failed("Database \"<b>$db_name</b>\" could not be created, please do so manually.");
				} else {
						echo_success("Good, database \"<b>$db_name</b>\" has been created!!");

						//Reconnect. Hrmm, this is kinda weird.
						$db->Connect($db_host, $db_user, $db_password, $db_name);
				}
		}

		break;
		
	default:
		echo_normal("Sorry, <b>setup.php</b> currently does not fully support \"<b>$db_type</b>\" databases.
					<br>I'm assuming you've already created the database \"$db_name\", attempting to create tables.
					<br> Please email <b>$author_email</b> code to detect if a database is created or not so full support for \"<b>$db_type</b>\" can be added.");
}


/*
 * Attempt to create tables
 */
// Create the schema object and build the query array.
$schema = new adoSchema($db);
$schema->SetPrefix($db_table_prefix, FALSE); //set $underscore == FALSE

// Build the SQL array
$schema->ParseSchema(dirname(__FILE__).'/schema.xml');

// maybe display this if $gacl->debug is true?
if ($gacl->_debug) {
	print "Here's the SQL to do the build:<br />\n<code>";
	print $schema->getSQL('html');
	print "</code>\n";
	// exit;
}

// Execute the SQL on the database
#ADODB's xmlschema is being lame, continue on error.
$schema->ContinueOnError(TRUE);
$result = $schema->ExecuteSchema();
echo "<p class='clearfix'></p>";

if ($result != 2) {
  echo_failed('Failed creating tables. Please enable DEBUG mode (set it to TRUE in $gacl_options near top of admin/gacl_admin.inc.php) to see the error and try again. You will most likely need to delete any tables already created.');
}

if ( $failed <= 0 ) {
	echo_success('First Step of Access Control Installation Successful!!!');
} else {
	echo_failed('Please fix the above errors and try again.');
}
?>
