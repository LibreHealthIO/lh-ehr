<?php
/* Copyright © 2010 by Andrew Moore */
/* Licensing information appears at the end of this file. */

class Installer
{

  public function __construct( $cgi_variables )
  {
    // Installation variables
    $this->iuser                    = $cgi_variables['iuser'];
    $this->iuserpass                = $cgi_variables['iuserpass'];
    $this->iuname                   = $cgi_variables['iuname'];
    $this->iufname                  = $cgi_variables['iufname'];
    $this->igroup                   = $cgi_variables['igroup'];
    $this->server                   = $cgi_variables['server']; // mysql server (usually localhost)
    $this->loginhost                = $cgi_variables['loginhost']; // php/apache server (usually localhost)
    $this->port                     = $cgi_variables['port'];
    $this->root                     = $cgi_variables['root'];
    $this->rootpass                 = $cgi_variables['rootpass'];
    $this->login                    = $cgi_variables['login'];
    $this->pass                     = $cgi_variables['pass'];
    $this->dbname                   = $cgi_variables['dbname'];
    $this->collate                  = $cgi_variables['collate'];
    $this->site                     = $cgi_variables['site'];
    $this->source_site_id           = $cgi_variables['source_site_id'];
    $this->clone_database           = $cgi_variables['clone_database'];
    $this->no_root_db_access        = $cgi_variables['no_root_db_access']; // no root access to database. user/privileges pre-configured
    $this->development_translations = $cgi_variables['development_translations'];

    // Record name of sql access file
    $GLOBALS['OE_SITES_BASE'] = dirname(__FILE__) . '/../../sites';
    $GLOBALS['OE_SITE_DIR'] = $GLOBALS['OE_SITES_BASE'] . '/' . $this->site;
    $this->conffile  =  $GLOBALS['OE_SITE_DIR'] . '/sqlconf.php';

    // Record names of sql table files
    $this->main_sql = dirname(__FILE__) . '/../../sql/database.sql';
    $this->translation_sql = dirname(__FILE__) . '/../../modules/language_translations/currentLanguage_utf8.sql';
    $this->devel_translation_sql = "https://github.com/LibreHealthIO/lh-ehr-contribs/currentLanguage_utf8.sql";  //does not exist
    $this->cvx = dirname(__FILE__) . "/../../sql/cvx_codes.sql";
    $this->additional_users = dirname(__FILE__) . "/../../sql/official_additional_users.sql";
    $this->menu_def = dirname(__FILE__) . "/../../sql/menu_definitions.sql";  //REVIEW

    // Record name of php-gacl installation files
    $this->gaclSetupScript1 = dirname(__FILE__) . "/../../gacl/setup.php";
    $this->gaclSetupScript2 = dirname(__FILE__) . "/../../acl_setup.php";

    // Prepare the dumpfile list
    $this->initialize_dumpfile_list();

    // Entities to hold error and debug messages
    $this->error_message = '';
    $this->debug_message = '';

    // Entity to hold sql connection
    $this->dbh = false;
  }

  public function login_is_valid()
  {
    if ( ($this->login == '') || (! isset( $this->login )) ) {
      $this->error_message = "login is invalid: '$this->login'";
      return FALSE;
    }
    return TRUE;
  }

  public function iuser_is_valid()
  {
    if ( strpos($this->iuser, " ") ) {
      $this->error_message = "Initial user is invalid: '$this->iuser'";
      return FALSE;
    }
    return TRUE;
  }

  public function password_is_valid()
  {
    if ( $this->pass == "" || !isset($this->pass) ) {
      $this->error_message = "The password for the new database account is invalid: '$this->pass'";
      return FALSE;
    }
    return TRUE;
  }

  public function user_password_is_valid()
  {
    if ( $this->iuserpass == "" || !isset($this->iuserpass) ) {
      $this->error_message = "The password for the user is invalid: '$this->iuserpass'";
      return FALSE;
    }
    return TRUE;
  }

  public function root_database_connection()
  {
    $this->dbh = $this->connect_to_database( $this->server, $this->root, $this->rootpass, $this->port );
    if ( $this->dbh ) {
            if (! $this->set_sql_strict()) {
                $this->error_message = 'unable to set strict sql setting';
                return false;
            }

            return true;
    } else {
      $this->error_message = 'unable to connect to database as root';
            return false;
    }
  }

  public function user_database_connection()
  {
    $this->dbh = $this->connect_to_database( $this->server, $this->login, $this->pass, $this->port, $this->dbname );
    if ( ! $this->dbh ) {
      $this->error_message = "unable to connect to database as user: '$this->login'";
            return false;
        }

        if (! $this->set_sql_strict()) {
            $this->error_message = 'unable to set strict sql setting';
            return false;
    }
    if ( ! $this->set_collation() ) {
      return FALSE;
    }
    if ( ! mysqli_select_db($this->dbh, $this->dbname) ) {
      $this->error_message = "unable to select database: '$this->dbname'";
      return FALSE;
    }
    return TRUE;
  }

  public function create_database() {
    $sql = "create database $this->dbname";
    if ($this->collate) {
      $sql .= " character set utf8 collate $this->collate";
      $this->set_collation();
    }

    return $this->execute_sql($sql);
  }

  public function drop_database() {
   $sql = "drop database if exists $this->dbname";
   return $this->execute_sql($sql);
  }

  public function grant_privileges() {
    return $this->execute_sql( "GRANT ALL PRIVILEGES ON $this->dbname.* TO '$this->login'@'$this->loginhost' IDENTIFIED BY '$this->pass'" );
  }

  public function disconnect() {
    return mysqli_close($this->dbh);
  }

  /**
   * This method creates any dumpfiles necessary.
   * This is actually only done if we're cloning an existing site
   * and we need to dump their database into a file.
   * @return bool indicating success
   */
  public function create_dumpfiles() {
    return $this->dumpSourceDatabase();
  }

  public function load_dumpfiles() {
    $sql_results = ''; // information string which is returned
    foreach ($this->dumpfiles as $filename => $title) {
        $sql_results_temp = '';
        $sql_results_temp = $this->load_file($filename,$title);
        if ($sql_results_temp == FALSE) return FALSE;
        $sql_results .= $sql_results_temp;
    }
    return $sql_results;
  }

  public function load_file($filename,$title) {
    $sql_results = ''; // information string which is returned
    $sql_results .= "Creating $title tables...\n";
    $fd = fopen($filename, 'r');
    if ($fd == FALSE) {
      $this->error_message = "ERROR.  Could not open dumpfile '$filename'.\n";
      return FALSE;
    }
    $query = "";
    $line = "";
    while (!feof ($fd)){
            $line = fgets($fd,1024);
            $line = rtrim($line);
            if (substr($line,0,2) == "--") // Kill comments
                    continue;
            if (substr($line,0,1) == "#") // Kill comments
                    continue;
            if ($line == "")
                    continue;
            $query = $query.$line;          // Check for full query
            $chr = substr($query,strlen($query)-1,1);
            if ($chr == ";") { // valid query, execute
                    $query = rtrim($query,";");
                    $query_status=$this->execute_sql( $query );
                    if($query_status==false)
                    {
                        echo $this->error_message;
                    }
                    $query = "";
            }
    }
    $sql_results .= "OK<br>\n";
    fclose($fd);
    return $sql_results;
  }

  public function add_version_info() {
    include dirname(__FILE__) . "/../../version.php";
    if ($this->execute_sql("UPDATE version SET v_major = '$v_major', v_minor = '$v_minor', v_patch = '$v_patch', v_realpatch = '$v_realpatch', v_tag = '$v_tag', v_database = '$v_database', v_acl = '$v_acl'") == FALSE) {
      $this->error_message = "ERROR. Unable insert version information into database\n" .
        "<p>".mysqli_error($this->dbh)." (#".mysqli_errno($this->dbh).")\n";
      return FALSE;
    }
    return TRUE;
  }

  public function add_initial_user() {
    if ($this->execute_sql("INSERT INTO `groups` (id, name, user) VALUES (1,'$this->igroup','$this->iuser')") == FALSE) {
      $this->error_message = "ERROR. Unable to add initial user group\n" .
        "<p>".mysqli_error($this->dbh)." (#".mysqli_errno($this->dbh).")\n";
      return FALSE;
    }
    $password_hash = "NoLongerUsed";  // This is the value to insert into the password column in the "users" table. password details are now being stored in users_secure instead.
    $salt=oemr_password_salt();     // Uses the functions defined in library/authentication/password_hashing.php
    $hash=oemr_password_hash($this->iuserpass,$salt);
    if ($this->execute_sql("INSERT INTO users (id, username, password, authorized, lname, fname, facility_id, calendar, cal_ui) VALUES (1,'$this->iuser','$password_hash',1,'$this->iuname','$this->iufname',3,1,3)") == FALSE) {
      $this->error_message = "ERROR. Unable to add initial user\n" .
        "<p>".mysqli_error($this->dbh)." (#".mysqli_errno($this->dbh).")\n";
      return FALSE;

    }

    // Create the new style login credentials with blowfish and salt
    if ($this->execute_sql("INSERT INTO users_secure (id, username, password, salt) VALUES (1,'$this->iuser','$hash','$salt')") == FALSE) {
      $this->error_message = "ERROR. Unable to add initial user login credentials\n" .
        "<p>".mysqli_error($this->dbh)." (#".mysqli_errno($this->dbh).")\n";
      return FALSE;
    }
    // Add the official libreehr users (services)
    if ($this->load_file($this->additional_users,"Additional Official Users") == FALSE) return FALSE;

    return TRUE;
  }

  /**
   * Create site directory if it is missing.
   * @global string $GLOBALS['OE_SITE_DIR'] contains the name of the site directory to create
   * @return name of the site directory or False
   */
  public function create_site_directory() {
    if (!file_exists($GLOBALS['OE_SITE_DIR'])) {
      $source_directory      = $GLOBALS['OE_SITES_BASE'] . "/" . $this->source_site_id;
      $destination_directory = $GLOBALS['OE_SITE_DIR'];
      if ( ! $this->recurse_copy( $source_directory, $destination_directory ) ) {
        $this->error_message = "unable to copy directory: '$source_directory' to '$destination_directory'. " . $this->error_message;
        return False;
      }
    }
    return True;
  }

              /**UNDO EXTRA COMMENT SECTIONS AFTER THIS HAS BEEN FIXED FOR THE .env FILE ISSUES.
              * Create .env file for the report generator.
              * Write database credentials to .env file.
              * These credentials are used to create
              * 1. the report generator database.
              * 2. run database migration, and
              * 3. populate database(seeding)
              * @param db_name Database name
              * @param db_host Database hostname
              * @param db_port Database port number
              * @param db_username Database username
              * @param db_password Database user's password
              * @return void
              *
              * @author 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
              *
              * @TODO
              * 1. Add main EHR database credentials to .env file after database upgrade is complete.
              * 2. Work on the report generator upon upgrade i.e if anything changes during upgrade.
              * 3. Give the user to change the database name and other during installation of EHR.
              * 4. Put these database credentials in an array before passing it to methods here.
               !!!!!!COMMENTED OUT COMMENT
              private function setup_env_file($db_credentials) {
                  $dot_env_file_path = dirname(__FILE__) . '/../../modules/report_generator'; // expected .env file path
                  $dot_env_file = $dot_env_file_path.'/.env';
            
                  // 1. Check if file EXISTS, else create file.
                  if(file_exists($dot_env_file)){
                     // 2. If file exists, check if the application key and databases' credentials have been specified.
                     if(exec('grep '.escapeshellarg('DB_REPORT_GENERATOR_CONNECTION ').$dot_env_file)) {
                         // 3. If they have been specified, then update with recent database credentials in parameters to this function. DON'T CHNGE APP_KEY
                         $this->write_to_env_file($dot_env_file, $db_credentials, TRUE); // Update database credentials.
                     }
                     else{
                         // 4. Else, generate application key and write the databases' credentials in the .env file.
                         $this->generate_application_key();  // Generate application key.
                         $this->write_to_env_file($dot_env_file, $db_credentials); // Write database credentials.
                     }
                  }
                  else {
                      $this->create_dot_env_file($dot_env_file);  // Create file.
                      $this->generate_application_key();  // Generate application key.
                      $this->write_to_env_file($dot_env_file, $db_credentials ); // Write database credentials.
            
                  }
            
                  // 5. If file is successfully created and written to, call the 'php artisan make:database' command.
                  exec('cd '.escapeshellarg($dot_env_file_path)); // move to the report generator directory first!
                  exec('php artisan make:database'); // run 'php artisan make:database' command here.
                  exec('cd ../../library/classes'); // Go back to the installer directory.
                  // The command above, 'php artisan make:database';
                  //    1. creates report generateor database called 'librereportgenerator'.
                  //    2. runs laravel-module's database migration command, (programmatically).
                  //    3. runs laravel-module's database seeds, (programmatically).
            
              }
            
              /**
              * Create laravel .env file for specifying various application variables.
              * @param dot_env_file path to .env file. Includes file's name
              * @param db_name Database name
              * @param db_host Database hostname
              * @param db_port Database port number
              * @param db_username Database username
              * @param db_password Database user's password
              * @param update whether to update .env or not
              * @return Boolean
              *
              * @author 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
              //RESTORE COMMENT HERE AFTER FIX
              private function write_to_env_file($dot_env_file, $db_credentials, $update = FALSE){
            
                  if($update){ // Just delete the existing file and recreate it.
                      unlink($dot_env_file);
                      $this->create_dot_env_file($dot_env_file);
                  }
            
                  $env_file_open = @fopen($dot_env_file, 'w'); // Open .env file for writing
                  if ( !$env_file_open ) { // If .env file doesn't open
                      $this->error_message = 'Unable to open'.$dot_env_file.' file for writing';
                      return FALSE;
                  }
            
                  $string_connection_1 ='DB_REPORT_GENERATOR_CONNECTION=mysql_report_generator';
                  $string_connection_2 = 'DB_LIBREEHR_CONNECTION=mysql_libreehr';
            
                  $errors = 0;   //fmg: variable keeps running track of any errors
            
                  fwrite($env_file_open,$string_connection_1) or $errors++;
                  fwrite($env_file_open,"DB_REPORT_GENERATOR_HOST=$db_credentials['db_host']\n") or $errors++;
                  fwrite($env_file_open,"DB_REPORT_GENERATOR_PORT=$db_credentials['db_port']\n") or $errors++;
                  fwrite($env_file_open,"DB_REPORT_GENERATOR_DATABASE=$db_credentials['db_name']\n") or $errors++;
                  fwrite($env_file_open,"DB_REPORT_GENERATOR_USERNAME=$db_credentials['db_username']\n") or $errors++;
                  fwrite($env_file_open,"DB_REPORT_GENERATOR_PASSWORD=$db_credentials['db_password']\n\n") or $errors++;
            
                  fwrite($env_file_open,$string_connection_2) or $errors++;
                  fwrite($env_file_open,"DB_LIBREEHR_HOST=$db_credentials['db_host']\n") or $errors++;
                  fwrite($env_file_open,"DB_LIBREEHR_PORT=$db_credentials['db_port']\n") or $errors++;
                  fwrite($env_file_open,"DB_LIBREEHR_DATABASE=$db_credentials['db_name']\n") or $errors++;
                  fwrite($env_file_open,"DB_LIBREEHR_USERNAME=$db_credentials['db_username']\n") or $errors++;
                  fwrite($env_file_open,"DB_LIBREEHR_PASSWORD=$db_credentials['db_password']\n\n") or $errors++;
            
                  fclose($env_file_open) or $errors++;
            
                  // Report errors when writing this file.
                  if ($errors != 0) {
                    $this->error_message = "ERROR. Couldn't write $errors lines to config file '$dot_env_file'.\n";
                    return FALSE;
                  }
            
                  return TRUE;
              }
            
              /**
              * Create laravel .env file for specifying various application variables.
              * @param dot_env_file
              * @return Boolean
              *
              * @author 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
              /RESTORE COMMENT HERE AFTER FIX
              private function create_dot_env_file($dot_env_file){
                  if(@touch($dot_env_file)) {
                      // write initial .env variables, enabling key generation command to Work
                      $initial_string = '
                          APP_NAME=Report_Generator
                          APP_ENV=local
                          APP_KEY=
                          APP_DEBUG=true
                          APP_LOG_LEVEL=debug
                          APP_URL=http://localhost
                      ';
            
                      $env_file_open = @fopen($dot_env_file, 'w'); // Open .env file for writing
                      if ( !$env_file_open ) { // If .env file doesn't open
                          $this->error_message = 'Unable to open .'$dot_env_file'. file for writing';
                          return FALSE;
                      }
            
                      fwrite($env_file_open, $initial_string);
                      fclose($env_file_open);
            
                      return TRUE;
                  }
                  else {
                      $this->error_message = 'Failed to create .env file for report generator';
                      return FALSE;
                  }
              }
            
              /**
              * Generate laravel application key for report generator.
              * This key will be used for the whole laravel app.
              * @param
              * @return
              *
              * @author 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
              //RESTORE COMMENT HERE AFTER FIX
              private function generate_application_key(){
                  exec('cd '.escapeshellarg($dot_env_file_path)); // move to the report generator directory first!
                  exec('php artisan key:generate'); // Generate application key command. This writes the application key in .env file's APP_KEY constant.
                  exec('cd ../../library/classes'); // Go back to the installer directory.
              }

REMOVE THIS LINE AFTER FIX*/  


  public function write_configuration_file() {
    @touch($this->conffile); // php bug
    $fd = @fopen($this->conffile, 'w');
    if ( ! $fd ) {
      $this->error_message = 'unable to open configuration file for writing: ' . $this->conffile;
      return False;
    }
    $string = '<?php
//  LibreEHR
//  MySQL Config

';

    $it_died = 0;   //fmg: variable keeps running track of any errors

    fwrite($fd,$string) or $it_died++;
    fwrite($fd,"\$host\t= '$this->server';\n") or $it_died++;
    fwrite($fd,"\$port\t= '$this->port';\n") or $it_died++;
    fwrite($fd,"\$login\t= '$this->login';\n") or $it_died++;
    fwrite($fd,"\$pass\t= '$this->pass';\n") or $it_died++;
    fwrite($fd,"\$dbase\t= '$this->dbname';\n\n") or $it_died++;
    fwrite($fd,"global \$disable_utf8_flag;\n") or $it_died++;
    fwrite($fd,"\$disable_utf8_flag = false;\n") or $it_died++;

$string = '
$sqlconf = array();
global $sqlconf;
$sqlconf["host"]= $host;
$sqlconf["port"] = $port;
$sqlconf["login"] = $login;
$sqlconf["pass"] = $pass;
$sqlconf["dbase"] = $dbase;
/////////WARNING!/////////
//Setting $config to = 0//
// will break this site //
//and cause SETUP to run//
$config = 1; /////////////
//////////////////////////
//////////////////////////
//////////////////////////
?>
';
?><?php // done just for coloring

    fwrite($fd,$string) or $it_died++;
    fclose($fd) or $it_died++;

    //it's rather irresponsible to not report errors when writing this file.
    if ($it_died != 0) {
      $this->error_message = "ERROR. Couldn't write $it_died lines to config file '$this->conffile'.\n";
      return FALSE;
    }
/*  REMOVE THIS COMMENT AFTER REPORT GENERATOR FIX.
    // These variablesa are used to setup the report generator database.
    $db_credentials = array();
    $db_credentials['db_name'] = $this->dbname;
    $db_credentials['db_host'] = $this->server;
    $db_credentials['db_port'] = $this->port;
    $db_credentials['db_username'] = $this->login;
    $db_credentials['db_password'] = $this->pass;

    $this->setup_env_file($db_credentials); // Setup Laravel's .env file for use in Report generator.
*/
    return TRUE;
  }

  public function insert_globals() {
    function xl($s) { return $s; }
    require(dirname(__FILE__) . '/../globals.inc.php');
    foreach ($GLOBALS_METADATA as $grpname => $grparr) {
      foreach ($grparr as $fldid => $fldarr) {
        list($fldname, $fldtype, $flddef, $flddesc) = $fldarr;
        if (is_array($fldtype) || substr($fldtype, 0, 2) !== 'm_') {
          $res = $this->execute_sql("SELECT count(*) AS count FROM globals WHERE gl_name = '$fldid'");
          $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
          if (empty($row['count'])) {
            $this->execute_sql("INSERT INTO globals ( gl_name, gl_index, gl_value ) " .
                           "VALUES ( '$fldid', '0', '$flddef' )");
          }
        }
      }
    }
    return TRUE;
  }

  public function install_gacl()
  {
    $install_results_1 = $this->get_require_contents($this->gaclSetupScript1);
    if (! $install_results_1 ) {
      $this->error_message = "install_gacl failed: unable to require gacl script 1";
      return FALSE;
    }

    $install_results_2 = $this->get_require_contents($this->gaclSetupScript2);
    if (! $install_results_2 ) {
      $this->error_message = "install_gacl failed: unable to require gacl script 2";
      return FALSE;
    }
    $this->debug_message .= $install_results_1 . $install_results_2;
    return TRUE;
  }

  public function quick_install() {
    // Validation of LibreEHR user settings
    //   (applicable if not cloning from another database)
    if (empty($this->clone_database)) {
      if ( ! $this->login_is_valid() ) {
        return False;
      }
      if ( ! $this->iuser_is_valid() ) {
        return False;
      }
      if ( ! $this->user_password_is_valid() ) {
        return False;
      }
    }
    // Validation of mysql database password
    if ( ! $this->password_is_valid() ) {
      return False;
    }
    if (! $this->no_root_db_access) {
      // Connect to mysql via root user
      if (! $this->root_database_connection() ) {
        return False;
      }
      // Create the dumpfile
      //   (applicable if cloning from another database)
      if (! empty($this->clone_database)) {
        if ( ! $this->create_dumpfiles() ) {
          return False;
        }
      }
      // Create the site directory
      //   (applicable if mirroring another local site)
      if ( ! empty($this->source_site_id) ) {
        if ( ! $this->create_site_directory() ) {
          return False;
        }
      }
      $this->disconnect();
      if (! $this->user_database_connection()) {
        // Re-connect to mysql via root user
        if (! $this->root_database_connection() ) {
          return False;
        }
        // Create the mysql database
        if ( ! $this->create_database()) {
          return False;
        }
        // Grant user privileges to the mysql database
        if ( ! $this->grant_privileges() ) {
          return False;
        }
      }
      $this->disconnect();
    }
    // Connect to mysql via created user
    if ( ! $this->user_database_connection() ) {
      return False;
    }
    // Build the database
    if ( ! $this->load_dumpfiles() ) {
      return False;
    }
    // Write the sql configuration file
    if ( ! $this->write_configuration_file() ) {
      return False;
    }
    // Load the version information, globals settings,
    // initial user, and set up gacl access controls.
    //  (applicable if not cloning from another database)
    if (empty($this->clone_database)) {
      if ( ! $this->add_version_info() ) {
        return False;
      }
      if ( ! $this->insert_globals() ) {
        return False;
      }
      if ( ! $this->add_initial_user() ) {
        return False;
      }
      if ( ! $this->install_gacl()) {
        return False;
      }
    }

    return True;
  }

  private function execute_sql( $sql ) {
    $this->error_message = '';
    if ( ! $this->dbh ) {
      $this->user_database_connection();
    }
    $results = mysqli_query($this->dbh, $sql);
    if ( $results ) {
      return $results;
    } else {
      $this->error_message = "unable to execute SQL: '$sql' due to: " . mysqli_error($this->dbh);
      return False;
    }
  }

  private function connect_to_database( $server, $user, $password, $port, $dbname='' )
  {
    if ($server == "localhost")
      $dbh = mysqli_connect($server, $user, $password, $dbname);
    else
      $dbh = mysqli_connect($server, $user, $password, $dbname, $port);
    return $dbh;
  }

    private function set_sql_strict()
    {
        // Turn off STRICT SQL
        return $this->execute_sql("SET sql_mode = ''");
    }

  private function set_collation()
  {
   if ($this->collate) {
     return $this->execute_sql("SET NAMES 'utf8'");
   }
   return TRUE;
  }

  /**
   * innitialize $this->dumpfiles, an array of the dumpfiles that will
   * be loaded into the database, including the correct translation
   * dumpfile.
   * The keys are the paths of the dumpfiles, and the values are the titles
   * @return array
   */
  private function initialize_dumpfile_list() {
    if ( $this->clone_database ) {
      $this->dumpfiles = array( $this->get_backup_filename() => 'clone database' );
    } else {
      $dumpfiles = array( $this->main_sql => 'Main' );
      if (! empty($this->development_translations)) {
        // Use the online development translation set
        $dumpfiles[ $this->devel_translation_sql ] = "Online Development Language Translations (utf8)";
      }
      else {
        // Use the local translation set
        $dumpfiles[ $this->translation_sql ] = "Language Translation (utf8)";
      }

      // Load CVX codes if present
      if (file_exists( $this->cvx )) {
        $dumpfiles[ $this->cvx ] = "CVX Immunization Codes";
      }
      // Load Menu Definitions if present
      if (file_exists( $this->menu_def))
      {
          $dumpfiles[ $this->menu_def] = "Menu Definitions";
      }
      $this->dumpfiles = $dumpfiles;
    }
    return $this->dumpfiles;
  }

  // http://www.php.net/manual/en/function.include.php
  private function get_require_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        require $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
  }

  /**
   *
   * Directory copy logic borrowed from a user comment at
   * http://www.php.net/manual/en/function.copy.php
   * @param string $src name of the directory to copy
   * @param string $dst name of the destination to copy to
   * @return bool indicating success
   */
  private function recurse_copy($src, $dst) {
    $dir = opendir($src);
    if ( ! @mkdir($dst) ) {
      $this->error_message = "unable to create directory: '$dst'";
      return False;
    }
    while(false !== ($file = readdir($dir))) {
      if ($file != '.' && $file != '..') {
        if (is_dir($src . '/' . $file)) {
          $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
        }
        else {
          copy($src . '/' . $file, $dst . '/' . $file);
        }
      }
    }
    closedir($dir);
    return True;
  }

  /**
   *
   * dump a site's database to a temporary file.
   * @param string $source_site_id the site_id of the site to dump
   * @return filename of the backup
   */
  private function dumpSourceDatabase() {
    global $OE_SITES_BASE;
    $source_site_id = $this->source_site_id;

    include("$OE_SITES_BASE/$source_site_id/sqlconf.php");

    if (empty($config)) die("Source site $source_site_id has not been set up!");

    $backup_file = $this->get_backup_filename();
    $cmd = "mysqldump -u " . escapeshellarg($login) .
      " -p" . escapeshellarg($pass) .
      " --opt --quote-names -r $backup_file " .
      //" --opt --skip-extended-insert --quote-names -r $backup_file " .  Comment out above line and enable this to have really slow DB duplication.
      escapeshellarg($dbase);

    $tmp0 = exec($cmd, $tmp1=array(), $tmp2);
    if ($tmp2) die("Error $tmp2 running \"$cmd\": $tmp0 " . implode(' ', $tmp1));

    return $backup_file;
  }

  /**
   * @return filename of the source backup database for cloning
   */
  private function get_backup_filename() {
    if (stristr(PHP_OS, 'WIN')) {
      $backup_file = 'C:/windows/temp/setup_dump.sql';
    }
    else {
      $backup_file = '/tmp/setup_dump.sql';
    }
    return $backup_file;
  }

}

/*
This file is free software: you can redistribute it and/or modify it under the
terms of the GNU General Public License as publish by the Free Software
Foundation.

This file is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU Gneral Public License for more details.

You should have received a copy of the GNU General Public Licence along with
this file.  If not see <http://www.gnu.org/licenses/>.
*/
?>
