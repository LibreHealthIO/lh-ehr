<?php
/**
 * 
 * Copyright (c) 2016 Sam Likins WSI-Services
 * Copyright (c) 2016 SunCoast Connection
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @package Librehealth EHR 
 * @author Sam Likins <sam.likins@wsi-services.com>
 * @link http://suncoastconnection.com
 * @link http://librehealth.io
 *  
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 * 
 */
if(!defined('MERGE_ENCOUNTERS') || MERGE_ENCOUNTERS !== true) {
	die('Not authorized');
}

?>
<html>
	<head>
		<title>Merge Encounters</title>
		<link type="text/css" rel="stylesheet" href="<?= pathToResource('css/KoGrid.css'); ?>">
		<link type="text/css" rel="stylesheet" href="<?= pathToResource('css/split-pane.css'); ?>">
		<link type="text/css" rel="stylesheet" href="<?= pathToResource('css/style.css', true); ?>">
		<script src="<?= pathToResource('js/jquery-1.11.0.min.js'); ?>"></script>
		<script src="<?= pathToResource('js/split-pane.js'); ?>"></script>
		<script src="<?= pathToResource('js/knockout-3.4.0.min.js'); ?>"></script>
		<script src="<?= pathToResource('js/koGrid-2.1.1.min.js'); ?>"></script>
		<script src="<?= pathToResource('js/MergeEncounters.js'); ?>"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('div.split-pane').splitPane();

				MergeEncounters.encounters.resizeGridEvent(
					'div#encounter-duplicates-grid',
					'body > div.split-pane'
				);
				MergeEncounters.encounters.resizeGridEvent(
					'div#encounter-duplicates-grid',
					'div#encounter-control > div.split-pane'
				);

				MergeEncounters.duplicates.loadList();

				ko.applyBindings(MergeEncounters);
			});
		</script>
	</head>
	<body>
		<div id="notices-box" data-bind="style: { display: notices.list().length == 0 ? 'none' : 'block' }, with: notices">
			<ul data-bind="foreach: list">
				<li>
					<button title="Close" data-bind="click: $parent.remove">&#215;</button>
					<div data-bind="html: $data"></div>
				</li>
			</ul>
		</div>
		<div class="split-pane fixed-left">
			<div id="encounter-list" class="split-pane-component" data-bind="with: duplicates">
				<div class="action-bar">
					<ul>
						<li><button title="Refresh" data-bind="click: loadList">&#8635;</button></li>
						<li>
							<span>Duplicates Count: </span>
							<span data-bind="text: list().length == 0 ? '&mdash;' : list().length"></span>
						</li>
					</ul>
				</div>
				<div>
					<div class="message-box" data-bind="style: { display: list().length == 0 ? 'block' : 'none' }">
						<span>Loading &hellip;</span>
					</div>
					<div id="encounter-list-table" data-bind="style: { display: list().length == 0 ? 'none' : 'block' }">
						<table rules="cols" frame="box" data-bind="with: table">
							<thead class="sortable">
								<tr data-bind="foreach: columnDefs">
									<th data-bind="css: { sortable: sortable }, click: $parent.clickSort.bind(property)">
										<span data-bind="text: header, css: state"></span>
									</th>
								</tr>
							</thead>
							<tbody class="clickable" data-bind="foreach: data()">
								<tr data-bind="css: { selected: pid == $parents[2].encounters.pid() && date == $parents[2].encounters.date() }, click: $parents[2].encounters.display">
									<td><span data-bind="text: pid"></span></td>
									<td><span data-bind="text: date"></span></td>
									<td><span data-bind="text: count"></span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div id="encounter-list-divider" class="split-pane-divider"></div>
			<div id="encounter-control" class="split-pane-component" data-bind="with: encounters">
				<div class="split-pane horizontal-percent">
					<div id="encounter-duplicates" class="split-pane-component">
						<div class="action-bar">
							<ul>
								<li><button title="Close" data-bind="disable: pid().length == 0, click: remove">&#215;</button></li>
								<li><button title="Refresh" data-bind="disable: pid().length == 0, click: load">&#8635;</button></li>
								<li>
									<span>PID: </span>
									<span data-bind="text: pid() || '&mdash;'"></span>
								</li>
								<li>
									<span>Date: </span>
									<span data-bind="text: date() || '&mdash;'"></span>
								</li>
								<li>
									<span>Encounters: </span>
									<span data-bind="text: list().length == 0 ? '&mdash;' : list().length"></span>
								</li>
								<li>
									<span>Forms: </span>
									<span data-bind="text: list().length == 0 ? '&mdash;' : forms.list().length"></span>
								</li>
								<li>
									<span>Billing: </span>
									<span data-bind="text: list().length == 0 ? '&mdash;' : billing.list().length"></span>
								</li>
								<li>
									<span>Documents: </span>
									<span data-bind="text: list().length == 0 ? '&mdash;' : documents.list().length"></span>
								</li>
							</ul>
						</div>
						<div class="nonScrollable">
							<div class="message-box" data-bind="style: { display: pid().length == 0 ? 'block' : 'none' }">
								<span>Please make a selection on the left.</span>
							</div>
							<div id="encounter-duplicates-grid" class="gridStyle" data-bind="koGrid: gridOptions, style: { display: pid().length == 0 ? 'none' : 'block' }">
							</div>
						</div>
					</div>
					<div class="split-pane-divider" id="encounter-duplicates-divider"></div>
					<div id="encounter-actions" class="split-pane-component" data-bind="with: actions">
						<div class="action-bar">
							<ul>
								<li><button title="Merge" data-bind="disable: list().length < 2 || typeof primaryEncounter() != 'object', click: merge">&#8651;</button></li>
								<li>
									<span>Primary ID: </span>
									<!-- ko if: list().length > 0 -->
									<select data-bind="optionsCaption: 'Select', value: primaryEncounter, optionsText: 'id', options: list().sort(function(left, right) { return left.id == right.id ? 0 : (left.id < right.id ? -1 : 1); })"></select>
									<!-- /ko -->
									<!-- ko ifnot: list().length > 0 -->
									<span data-bind="">&mdash;</span>
									<!-- /ko -->
								</li>
								<li>
									<span>Encounters: </span>
									<span data-bind="text: list().length > 0 ? list().length : '&mdash;'"></span>
								</li>
								<li>
									<span>Forms: </span>
									<span data-bind="text: list().length > 0 ? forms().length : '&mdash;'"></span>
								</li>
								<li>
									<span>Billing: </span>
									<span data-bind="text: list().length > 0 ? billing().length : '&mdash;'"></span>
								</li>
								<li>
									<span>Documents: </span>
									<span data-bind="text: list().length > 0 ? documents().length : '&mdash;'"></span>
								</li>
							</ul>
						</div>
						<div>
							<div class="message-box" data-bind="style: { display: list().length == 0 ? 'block' : 'none' }">
								<span>Select encounters above for merging.</span>
							</div>
							<div id="encounter-update" data-bind="style: { display: list().length == 0 ? 'none' : 'block' }">
								<table>
									<thead>
										<tr>
											<th>Field</th>
											<th>Data</th>
										</tr>
									</thead>
									<tbody data-bind="foreach: encounterUpdate">
										<tr>
											<th data-bind="text: displayName || field"></th>
											<td>
												<!-- ko ifnot: field == 'reason' -->
												<span data-bind="text: value"></span>
												<!-- /ko -->
												<!-- ko if: field == 'reason' -->
												<textarea data-bind="textInput: value"></textarea>
												<!-- /ko -->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>