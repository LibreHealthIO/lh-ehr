<?php
//require_once '../../../globals.php';
//require_once($GLOBALS['srcdir'].'/acl.inc');
?>
<html>
 
<head>  
<span class='title' visibility: hidden><?php echo 'File Manager'; ?></span> 
 	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<link href="assets/css/dropzone.css" type="text/css" rel="stylesheet" />
<link href="assets/css/styles.css" rel="stylesheet"/> 

<script src="assets/js/dropzone.js"></script>

 <h1> Drop your file in the box, or click in the box to browse for your files.</h1>
</head>
 
<body>
 

<form action="upload.php" class="dropzone"></form>

 	<div class="filemanager">

		<div class="search">
			<input type="search" placeholder="Find a file.." />
		</div>

		<div class="breadcrumbs"></div>

		<ul class="data"></ul>

		<div class="nothingfound">
			<div class="nofiles"></div>
			<span>No files here.</span>
		</div>

	</div>  
		<script src="assets/js/jquerycurrent.js"></script>
	<script src="assets/js/script.js"></script>
</body>
 
</html> 