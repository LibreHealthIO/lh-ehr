<?php
/**
 * Administration of users who have access to the updater
 *
 *
 * Copyright (C) 2018 Naveen Muthusamy <kmnaveen101@gmail.com>
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Naveen Muthusamy <kmnaveen101@gmail.com>
 * @link    http://librehealth.io
 */
if($_SESSION['authUser'] == "admin") {
	echo "&nbsp;&nbsp;<h4><i class='fa fa-users'></i> ".xlt("Updater Administration")."</h4><br/>";
	//only admin can add, edit, delete users who can update the application
	$query = sqlQ("SELECT * FROM `users` WHERE authorized != '1'");
	echo "<table class='table table-striped'>";
	echo "<th>username</th>";
	echo "<th>Authorized</th>";
	echo "<th>Action</th>";
	while($array = sqlFetchArray($query)) {
		$authUserId = $array['id'];
		$sql = sqlQ("SELECT * FROM `updater_users` WHERE authUserId='$id'");
		$rows = sqlNumRows($sql);
		if ($rows == 1) {
			$authorised = "yes";
			$class = "color: green";
			$action = "i class='fa fa-trash'></i> Remove User";
			$action_class = "color: red";
		}
		else {
			$authorised = "no";
			$class = "color: red";
			$action = "<i class='fa fa-plus'></i> Add User";
			$action_class = "color: green";
			//it means he is not authorised
		}
		$arr = sqlFetchArray($sql);
		$username = $array['username'];
		echo "<tr>
				<td>$username</td>
				<td><b style='$class'>$authorised</b></td>
				<td class='btn' style='$action_class'>$action</td>
			</tr>";

	}
	echo "</table>";
}
else {
	//dont show addition form
}



?>