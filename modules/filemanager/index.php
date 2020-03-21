<?php
/*
 * File Manager Interface
 *
 * Copyright (C) 2015 - 2017      Suncoast Connection
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */
require_once '../../interface/globals.php';
 $newURL = "$web_root/sites/" . $_SESSION['site_id'] . "/filemanager/index.php";
 header('Location: '.$newURL);
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