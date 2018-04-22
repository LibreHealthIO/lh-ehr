<?php
/**
 *  
 *
 * Copyright (C) 2018 Naveen Muthusamy <kmnaveen101@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Naveen Muthusamy <kmnaveen101@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */
error_reporting(E_ALL);
require_once('../../interface/globals.php');
require_once('../../library/headers.inc.php');
require_once("../../library/sql.inc");

$library_array = array("bootstrap", "font-awesome", "jquery-ui", "jquery-min-3-1-1");
call_required_libraries($library_array);
?>
<style type="text/css">
	.forms {
		height: 20em;
		background-color: #F3F3F3;
		border-radius: 18px;

	}

</style>
<div class="col-xs-12" style="height: 10em; background-color: #404040;">
</div>

<div class="col-xs-12">
<br/><br/>
</div>

<div class="col-xs-12">
	<div class="col-xs-3">
		<div class="forms">
		</div>
		<br/>
		<div class="forms">
		</div>
	</div>

	<div class="col-xs-9" style="background-color: #B5B5B5; height: 40em; border-radius: 18px;">

	</div>

</div>


<div class="col-xs-12">
<br/><br/>
</div>

<div class="col-md-12">
		<div class="col-md-3">
			<div class="forms"></div>
		</div>
				<div class="col-md-3">
			<div class="forms"></div>
		</div>
				<div class="col-md-3">
			<div class="forms"></div>
		</div>
				<div class="col-md-3">
			<div class="forms"></div>
		</div>

</div>