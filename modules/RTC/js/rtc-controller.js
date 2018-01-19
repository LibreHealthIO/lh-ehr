/* WebRTC PubNub Controller
 * Author: Kevin Gleason
 * Date: July 15, 2015
 * Description: A wrapper library for the PubNub WebRTC SDK to make simple video
 *              functions a breeze to implement.
 *
 * TODO: make getVideoElement a native non-jQuery function
 *
 */

(function(){
	
	
var CONTROLLER = window.CONTROLLER = function(phone, serverFunc){
	if (!window.phone) window.phone = phone;
	var ctrlChan  = controlChannel(phone.number());
	var pubnub    = phone.pubnub;
	var userArray = [];
	subscribe();
	
	var CONTROLLER = function(){};
	
	// Get the control version of a users channel
	function controlChannel(number){
		return number + "-ctrl";
	}
	
	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	// Setup Phone and Session callbacks.
	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	var readycb   = function(){};
	var unablecb  = function(){};
    var receivecb = function(session){};
    var videotogglecb = function(session, isEnabled){};
    var audiotogglecb = function(session, isEnabled){};
    
    CONTROLLER.ready   = function(cb) { readycb   = cb };
    CONTROLLER.unable  = function(cb) { unablecb  = cb };
    CONTROLLER.receive = function(cb) { receivecb = cb };
    CONTROLLER.videoToggled = function(cb) { videotogglecb = cb };
    CONTROLLER.audioToggled = function(cb) { audiotogglecb = cb };
	
	phone.ready(function(){ readycb() });
	phone.unable(function(){ unablecb() });
	phone.receive(function(session){
		manage_users(session);
		receivecb(session);
	});
	
	/* Require some boolean form of authentication to accept a call
	var authcb    = function(){};
	CONTROLLER.answerCall = function(session, auth, cb){
		auth(acceptCall(session, cb), session);
	}
	
	function acceptCall(session, cb){ // Return function bound to session that needs a boolean.
		return function(accept) {
			if (accept) cb(session);
			else phone.hangup(session.number); 
		}
	}*/
	
	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	// Setup broadcasting, your screen to all.
	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	var streamreceivecb = function(m){}; 
	var streamprescb    = function(m){};
	var stream_name = "";
	
	
	CONTROLLER.streamPresence = function(cb){ streamprescb    = cb; }
	CONTROLLER.streamReceive  = function(cb){ streamreceivecb = cb; }
	
	function broadcast(vid){
	    var video = document.createElement('video');
        video.src    = URL.createObjectURL(phone.mystream);
        video.volume = 0.0;
        video.play();
	    video.setAttribute( 'autoplay', 'autoplay' );
	    video.setAttribute( 'data-number', phone.number() );
	    vid.style.cssText ="-moz-transform: scale(-1, 1); \
						 	-webkit-transform: scale(-1, 1); -o-transform: scale(-1, 1); \
							transform: scale(-1, 1); filter: FlipH;";
		vid.appendChild(video);
    };
    
    function stream_subscribe(name){
	    var ch = (name ? name : phone.number()) + "-stream";
	    pubnub.subscribe({
            channel    : ch,
            message    : streamreceivecb,
            presence   : streamprescb,
            connect    : function() { stream_name = ch; console.log("Streaming channel " + ch); }
        });
    }
    
    CONTROLLER.stream = function(){
	    stream_subscribe();
    }
    
    CONTROLLER.joinStream = function(name){
	    stream_subscribe(name);
	    publishCtrl(controlChannel(name), "userJoin", phone.number());
    }
    
    CONTROLLER.leaveStream = function(name){
	    var ch = (name ? name : phone.number()) + "-stream";
	    pubnub.unsubscribe({
            channel    : ch,
        });
    }
    
    CONTROLLER.send = function( message, number ) {
        if (phone.oneway) return stream_message(message);
        phone.send(message, number);
    };
    
    function stream_message(message){
	    if (!stream_name) return; // Not in a stream
		pubnub.publish({ 
			channel: stream_name,
			message: msg,
			callback : function(m){console.log(m)}
		});
    }
    
    
    // Give it a div and it will set up the thumbnail image
    CONTROLLER.addLocalStream = function(streamHolder){
	    broadcast(streamHolder);
    };
	
	CONTROLLER.dial = function(number, servers){ // Authenticate here??
		if (!servers && serverFunc) servers=serverFunc();
		var session = phone.dial(number, servers); // Dial Number
		if (!session) return; // No Duplicate Dialing Allowed
	};
	
	CONTROLLER.hangup = function(number){
		if (number) {
			if (phone.oneway) CONTROLLER.leaveStream(number);
			phone.hangup(number);
			return publishCtrl(controlChannel(number), "userLeave", phone.number())
		} 
		if (phone.oneway) CONTROLLER.leaveStream();
		phone.hangup();
		for (var i=0; i < userArray.length; i++) {
			var cChan = controlChannel(userArray[i].number);
			publishCtrl(cChan, "userLeave", phone.number());
		}
	};
	
	CONTROLLER.toggleAudio = function(){
		var audio = false;
		var audioTracks = window.phone.mystream.getAudioTracks();
		for (var i = 0, l = audioTracks.length; i < l; i++) {
			audioTracks[i].enabled = !audioTracks[i].enabled;
			audio = audioTracks[i].enabled;
		}
		publishCtrlAll("userAudio", {user : phone.number(), audio:audio}); // Stream false if paused
		return audio;
	};
	
	CONTROLLER.toggleVideo = function(){
		var video = false;
		var videoTracks = window.phone.mystream.getVideoTracks();
		for (var i = 0, l = videoTracks.length; i < l; i++) {
			videoTracks[i].enabled = !videoTracks[i].enabled;
			video = videoTracks[i].enabled;
		}
		publishCtrlAll("userVideo", {user : phone.number(), video:video}); // Stream false if paused
		return video;
	};
	
	CONTROLLER.isOnline = function(number, cb){
		pubnub.here_now({
			channel : number,
			callback : function(m){
				console.log(m);  // TODO Comment out
				cb(m.occupancy != 0);
			}
		});
	};
	
	CONTROLLER.isStreaming = function(number, cb){
		CONTROLLER.isOnline(number+"-stream", cb);
	};
	
	CONTROLLER.getVideoElement = function(number){
		return $('*[data-number="'+number+'"]');
	}
	
	function manage_users(session){
		if (session.number == phone.number()) return; 	// Do nothing if it is self.
		var idx = findWithAttr(userArray, "number", session.number); // Find session by number
		if (session.closed){
			if (idx != -1) userArray.splice(idx, 1)[0]; // User leaving
		} else {  				// New User added to stream/group
			if (idx == -1) {  	// Tell everyone in array of new user first, then add to array. 
				if (!phone.oneway) publishCtrlAll("userJoin", session.number);
				userArray.push(session);
			}
		}
		userArray = userArray.filter(function(s){ return !s.closed; }); // Clean to only open talks
		// console.log(userArray);
	}
	
	function add_to_stream(number){
		if (serverFunc) phone.dial(number, serverFunc());
		else phone.dial(number);
	}
	
	function add_to_group(number){
		var session = serverFunc ? phone.dial(number, serverFunc()) : phone.dial(number); // Dial Number
		if (!session) return; 	// No Dupelicate Dialing Allowed
	}
	
	function publishCtrlAll(type, data){
		for (var i=0; i < userArray.length; i++) {
			var cChan = controlChannel(userArray[i].number);
			publishCtrl(cChan, type, data);
		}
	}
	
	function publishCtrl(ch, type, data){
		// console.log("Pub to " + ch);
		var msg = {type: type, data: data};
		pubnub.publish({ 
			channel: ch,
			message: msg,
			callback : function(m){console.log(m)}
		});
	}
	
	function subscribe(){
		pubnub.subscribe({
            channel    : ctrlChan,
            message    : receive,
            connect    : function() {} // console.log("Subscribed to " + ctrlChan); }
        });
	}
	
	function receive(m){
		switch(m.type) {
		case "userCall":
			callAuth(m.data);
			break;
		case "userJoin":
			if (phone.oneway){ add_to_stream(m.data); }// JOIN STREAM HERE!
			else add_to_group(m.data);
			break;
		case "userLeave":
			var idx = findWithAttr(userArray, "number", m.data);
			if (idx != -1) userArray.splice(idx, 1)[0];
			break;
		case "userVideo":
			var idx = findWithAttr(userArray, "number", m.data.user);
			var vidEnabled = m.data.video;
			if (idx != -1) videotogglecb(userArray[idx], vidEnabled);
			break;
		case "userAudio":
			var idx = findWithAttr(userArray, "number", m.data.user);
			var audEnabled = m.data.audio;
			if (idx != -1) audiotogglecb(userArray[idx], audEnabled);
			break;
		}
		// console.log(m);
	}
	
	function findWithAttr(array, attr, value) {
	    for(var i = 0; i < array.length; i += 1) {
	        if(array[i][attr] === value) {
	            return i;
	        }
	    }
	    return -1;
	}
	
	return CONTROLLER;
}

})();
