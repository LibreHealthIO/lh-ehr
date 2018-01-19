<html>
<head>
	<title>WebRTC Embed Demo</title>
</head>
<body style="text-align: center">
	<div id="embed"></div>
	<h1 id="stream-name"></h1>
</body>
<script src="https://cdn.pubnub.com/pubnub-3.7.14.min.js"></script>
<script type="text/javascript">

(function(){

var urlargs     = urlparams();
var embed_box   = document.getElementById('embed');
var stream_name = document.getElementById('stream-name');

// Handle error if stream is not in urlargs.
if (!('stream' in urlargs)) {
	handleNoStream();
    return;
}
var stream = urlargs.stream;
var width  = 500;
var height = 500;

if ('width'  in urlargs) width = urlargs.width;
if ('height' in urlargs) height = urlargs.height;

stream_name.innerHTML = "Currently Watching: " + stream;
genEmbed(width,height);


function genEmbed(w,h){
	var url = "https://kevingleason.me/SimpleRTC/embed.html?stream=" + stream;
	var embed    = document.createElement('iframe');
	embed.src    = url;
	embed.width  = w;
	embed.height = h;
	embed.setAttribute("frameborder", 0);
	embed_box.innerHTML = embed.outerHTML; // For a preview
}

// Get URL params
function urlparams() {
    var params = {};
    if (location.href.indexOf('?') < 0) return params;
    PUBNUB.each(
        location.href.split('?')[1].split('&'),
        function(data) { var d = data.split('='); params[d[0]] = d[1]; }
    );
    return params;
}

function handleNoStream(){
	embed_box.innerHTML="ERROR! Stream not found in URL params.";
}
	
}());
	
</script>

<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new
		Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-46933211-3', 'auto');
	ga('send', 'pageview');

</script>

</html>
