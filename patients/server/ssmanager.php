<?php
$ignoreAuth = true;
// require_once(dirname(__file__) . './../../interface/globals.php');
require_once ( dirname( __FILE__ ) . "/../../library/log.inc" );
require_once ( dirname( __FILE__ ) . "/../../library/sql.inc" );
set_time_limit( 0 );
ob_implicit_flush();
// Release session lock to prevent freezing of other scripts
session_write_close();
ignore_user_abort( 1 );
 //server_logit( 1, "Server Manager Loaded" );

/*
while( true ){
 runCheck();
 sleep( 10 );
 }*/

function runCheck(){
	// print('check on server...............');
	if( ! socket_status( 'localhost', '6661', 'status' ) ){
		$cmd = 'php -q /var/www/libreehr/patients/server/ccda_server.php';
		server_logit( 1, "Execute ccda Server Start" );
		execInBackground( $cmd );
		sleep( 1 );
		server_logit( 1, "ccda Server Start Success" );
		return true;
	} else{
		// print( "Server is alive .....!" );
		server_logit( 1, "Service status check : Alive." );
		return true;
	}
}
function execInBackground( $cmd ){
	if( substr( php_uname(), 0, 7 ) == "Windows" ){
		$cmd = 'php -q  /xampp/htdocs/libreehr/patients/server/ccda_server.php';
		pclose( popen( "start cmd /K " . $cmd, "r" ) );
		// exec( $cmd );
	} else{
		$cmd = 'php -q /var/www/libreehr/patients/server/ccda_server.php';
		exec( $cmd . " > /dev/null &" );
	}
}
function server_logit( $success, $text, $pid = 0, $event = "ccda-service-manager" ){
	newEvent( $event, "server_manager", 0, $success, $text, $pid );
}
function socket_status( $ip, $port, $data ){
	$output = "";
	// print('mgr send to status check ..............' ."\r\n");
	$socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
	if( $socket === false ){
		// print( "Socket Creation Failed........" ."\r\n" );
		server_logit( 1, "Creation Socket Failed..maybe fluke restart service" );
		return false;
	}
	$result = socket_connect( $socket, $ip, $port );
	if( $result === false ){
		// print( "Socket Connect Failed..trying restart" ."\r\n" );
		socket_close( $socket );
		server_logit( 1, "Connect Socket Failed..attempting server start/restart-maybe first run" );
		return false;
	}

	$data = chr( 11 ) . $data . chr( 28 ) . "\r";
	//server_logit( 1, 'Send for server status.' );
	// print('sending keep alive...............' ."\r\n");
	$out = socket_write( $socket, $data, strlen( $data ) );

	do{
		$line = "";
		$line = socket_read( $socket, 1024, PHP_NORMAL_READ );
		$output .= $line;
	} while( $line != "\r" );

	$output = substr( trim( $output ), 0, strlen( $output ) - 3 );
	socket_close( $socket );
	// print('recv keep alive....: '.$output ."\r\n" );
	//server_logit( 1, 'Server is alive.' );
	//if( $output == 'statusok' ) return true;
return true;
	// if( $socket ) socket_close( $socket );
}