<?php
	$ignoreAuth = true;
	// Remove time limit so script doesn't time out
	set_time_limit( 0 );
	session_write_close();
	ignore_user_abort( 1 );
	// require_once(dirname(__file__) . './../../interface/globals.php');
	require_once ( dirname( __FILE__ ) . "/../../library/log.inc" );
	require_once ( dirname( __FILE__ ) . '/../lib/document_parse.class.php' );
	register_shutdown_function( 'shutdown' );
	$host = 'localhost';
	$port = '6661'; // keep- hard coded port in ccm code??...
	$null = NULL;
	server_logit( 1, "Ccda Server Service loaded " );

	$socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );

	socket_set_option( $socket, SOL_SOCKET, SO_REUSEADDR, 1 );
	socket_bind( $socket, 0, $port );
	socket_listen( $socket );
	// add the listning socket to the list
	$clients = array (
			$socket
	);

	// scary endless loop
	while( true ){
		// manage multiple connections - add handler for multi docs
		$changed = $clients;
		socket_select( $changed, $null, $null, 0, 10 );
		if( in_array( $socket, $changed ) ){
			$socket_new = socket_accept( $socket );
			$clients[] = $socket_new;
			// get ip address of connected socket
			socket_getpeername( $socket_new, $ip );
			$found_socket = array_search( $socket, $changed );
			unset( $changed[$found_socket] );
		}
		// loop through all connected sockets
		foreach( $changed as $changed_socket ){
			$received_text = '';
			$rtn = '';
			// check for any incoming data
			$received_text = receive( $changed_socket );
			$response_text = substr( trim( $received_text ), 0, - 1 );
			// build from oemr xml data -- need add a doc type param ie 1-12 ccd - transfer
			if( strlen( $response_text ) > 80 ){
				server_logit( 1, "Parse session started-new session" );
				$rtn = buildCcda( $response_text ) . "\r";
				send_message( $rtn . chr( 28 ) . "\r\r" );
			}

			$buf = false; // @socket_read($changed_socket, 1024, PHP_NORMAL_READ);// looking for keep alives-dont use yet
			              // delete client he'll reconnect when needed - next doc
			if( $buf === false ){
				// check disconnected client he's disconnected automatically in socket_get routine
				$found_socket = array_search( $changed_socket, $clients );
				socket_getpeername( $changed_socket, $ip );
				unset( $clients[$found_socket] );
				socket_close( $changed_socket );
				// notify service manager
				if( strlen( $rtn ) > 80 ) server_logit( 1, "Parse session completed-destroy session" );
			}
		}
	}
	// close the listening socket and die ...........
	socket_close( $socket );
	server_logit( 1, "Server died!" );
	exit( 1 );

	// ----------------------------------- Functions ------need management----------------------//
	function send_message( $msg ){
		global $clients;
		foreach( $clients as $changed_socket ){
			$l = strlen( $msg );
			@socket_write( $changed_socket, $msg, $l );
		}
		if( strlen( "$msg" ) > 80 ) server_logit( 1, "Sent completed document", '', "Document transfer:" . strlen( "$msg" ) );
		return true;
	}
	// ----------- switch to below soon
	function safeWrite( &$sock, $msg ){
		$msg = "$msg";
		$length = strlen( $msg );
		while( true ){
			$sent = socket_write( $sock, $msg, $length );
			if( $sent === false ){return false;}
			if( $sent < $length ){
				$msg = substr( $msg, $sent );
				$length -= $sent;
				// print ( "Message truncated: Resending: $msg" ) ;
			} else{
				return true;
			}
		}
		return false;
	}
	function receive( $socket ){
		// !
		// socket select changed socket-from polling -- lets get data
		// !
		$timeout = 3; // set a timeout
		/* important */
		$socket_recv_return_values['no_data_received'] = false;
		$socket_recv_return_values['client_disconnected'] = 0;

		$start = time();
		$received_data = null;
		$received_bytes = null;
		socket_set_nonblock( $socket );
		socket_clear_error();
		while( ( $t_out = ( ( time() - $start ) >= $timeout ) ) === false and ( $read = @socket_recv( $socket, $buf, 25000, 0 ) ) >= 1 ){
			$received_data = ( isset( $received_data ) ) ? $received_data . $buf : $buf;
			$received_bytes = ( isset( $received_bytes ) ) ? $received_bytes + $read : $read;
			usleep(500000);
          //server_logit( 1, "Service Bytes Read= ".$read );
		}
		$last_error = socket_last_error( $socket );
		socket_set_block( $socket );
	//server_logit( 1, "Service error:".socket_strerror( $last_error ) );
		if( $t_out === true ){
          	server_logit( 1, "service TIMEOUT error:" );
			throw new Exception( 'timeout after ' . ( ( ! $received_bytes ) ? 0 : $received_bytes ) . ' bytes', 0 ); // error code here
		} elseif( $last_error !== false and $last_error !== 0 and $last_error !== 11){
          	server_logit( 1, "service recv error:".socket_strerror( $last_error ).$last_error );
			throw new Exception( socket_strerror( $last_error ), $last_error );
		} else{
			if( $read === $socket_recv_return_values['no_data_received'] ){
				// client returned NO DATA
				if( $received_bytes < 1 ){
					// client is connected but sent NO DATA ?
					server_logit( 1, "service no bytes error" );
					throw new Exception( 'client crashed', false ); // recover
				} else{
					// client gave us DATA
					$tmp = substr( trim( $received_data ), 0, - 1 );
					if( $tmp == 'status' ){
						send_message( "statusok" . "\r\r\r" );
						server_logit( 1, "service replying with keep alive" );
						return '';
					}
					return $received_data;
				}
			} elseif( $read === $socket_recv_return_values['client_disconnected'] ){
				// client disconnected
				if( $received_bytes < 1 ){
					// client disconnected before/without sending any bytes//
					server_logit( 1, "Client Disconnected error" );
				} else{
					// give disconnect-exception above
					return $received_data;
				}
			}
		}
	}
	function server_logit( $success, $text, $pid = 0, $event = "ccda-service" ){
		newEvent( $event, "ccda_server-service", 0, $success, $text, $pid );
	}
	function shutdown(){
		server_logit( 1, "Alert- Service unwanted exit " );
	}

	?>