/**
 * Author: Sam Likins <sam.likins@wsi-services.com>
 * Copyright: Copyright (c) 2016, WSI-Services
 *
 * License: http://opensource.org/licenses/gpl-3.0.html
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

var MergeEncounters = new MergeEncounters();


// Application View Model

function MergeEncounters() {
	var self = this;

	this.notices = new Notices();

	this.duplicates = new Duplicates(this);

	this.encounters = new Encounters(this);
}


// Application Notices

function Notices() {
	var self = this;

	this.list = ko.observableArray([]);

	this.add = function(message) {
		self.list.push(message);
	}

	this.remove = function(alert) {
		self.list.remove(alert);
	}

	this.clear = function() {
		self.list([]);
	}
}


// Duplicates List

function Duplicates(parent) {
	var self = this;

	this.parent = function() { return parent };

	this.list = ko.observableArray();

	this.table = new sortableTable({
		data: this.list,

		columnDefs: [
			{ property: 'pid',		header: 'PID',		sortable: true, type: 'number',	state: ko.observable() },
			{ property: 'date',		header: 'Date',		sortable: true, type: 'date',	state: ko.observable() },
			{ property: 'count',	header: 'Count',	sortable: true, type: 'number',	state: ko.observable() }
		]
	});

	this.loadList = function() {
		self.list([]);

		$.ajax({
			type: 'POST',
			url: '?service=json-duplicates',
			dataType: 'json',
			success: function(response, status, xHR) {
				if(typeof response === 'object') {
					if(!response.hasOwnProperty('status') ||
						!response.hasOwnProperty('errors') ||
						!response.hasOwnProperty('data')
					) {
						self.parent().notices.add('AJAX JSON call failed.')
					} else {
						if(response.errors.length > 0) {
							response.errors.forEach(function(error) {
								self.parent().notices.add('Error: ' + response + '.');
							});
						} else if(response.data.length == 0) {
							self.parent().notices.add('No duplicates found.')
						} else if(
							!response.data[0].hasOwnProperty('pid') ||
							!response.data[0].hasOwnProperty('date') ||
							!response.data[0].hasOwnProperty('count')
						) {
							self.parent().notices.add('Duplicates list has malformed data.')
						} else {
							self.list(response.data);
						}
					}
				} else if(response === '') {
					self.parent().notices.add('Duplicates list failed to load.');
				} else {
					self.parent().notices.add('Failed: ' + response + '.');
				}
			},
			error: function(xHR, status, error) {
				self.parent().notices.add('Status: ' + status + ', Error: ' + error);
			},
		});
	}
}


// Sortable Table

function sortableTable(options) {
	var self = this;

	this.data = options.data;

	this.columnDefs = options.columnDefs;

	this.ascending = 'ascending';
	this.descending = 'descending';

	this.clickSort = function(column) {
		self.clearColumnStates(column);

		if(column.state() === '' || column.state() === self.ascending) {
			column.state(self.descending);
		} else {
			column.state(self.ascending);
		}

		switch(column.type) {
			case 'date':
				self.dateSort(column);
				break;
			case 'number':
				self.numberSort(column);
				break;
			case 'string':
			default:
				self.stringSort(column);
				break;
		}
	}

	self.clearColumnStates = function(selectedColumn) {
		var otherColumns = self.columnDefs.filter(function(col) {
			return col != selectedColumn;
		});

		for(var i = 0; i < otherColumns.length; i++) {
			otherColumns[i].state('');
		}
	};

	self.dateSort = function(column) {
		self.data(self.data().sort(function(left, right) {
			if(column.state() === self.ascending) {
				return new Date(left[column.property]) - new Date(right[column.property]);
			} else {
				return new Date(right[column.property]) - new Date(left[column.property]);
			}
		}));
	};

	self.numberSort = function(column) {
		self.data(self.data().sort(function(left, right) {
			var dataLeft = left[column.property],
				dataRight = right[column.property];

			if(column.state() === self.ascending) {
				return dataLeft - dataRight;
			} else {
				return dataRight - dataLeft;
			}
		}));
	};

	self.stringSort = function(column) {
		self.data(self.data().sort(function(left, right) {
			var dataLeft = left[column.property].toLowerCase(),
				dataRight = right[column.property].toLowerCase();

			if(dataLeft < dataRight) {
				return (column.state() === self.ascending) ? -1 : 1;
			} else if(dataLeft > dataRight) {
				return (column.state() === self.ascending) ? 1 : -1;
			} else {
				return 0
			}
		}));
	};
}


// Duplicate Encounter List

function Encounters(parent) {
	var self = this;

	this.parent = function() { return parent };

	this.pid = ko.observable('');
	this.date = ko.observable('');

	this.list = ko.observableArray();

	this.forms = new EncountersForms();
	this.billing = new EncountersBilling();
	this.documents = new EncountersDocuments();

	this.actions = new EncountersActions(this);

	var gridTemplates = {
		highlightRow:	'<div data-bind="foreach: $grid.visibleColumns, attr: { class: (typeof $userViewModel.actions.primaryEncounter() == \'object\' && $userViewModel.actions.primaryEncounter().id == getProperty(\'id\') ? \'primaryEncounter\' : \'\') }"><div data-bind="attr: { \'class\': cellClass() + \' kgCell col\' + $index() }, kgCell: $data"></div></div>',
		tooltipCount:	'<div data-bind="attr: { class: \'kgCellText colt\' + $index(), title: $parent.entity[$data.field].join(\', \') }, text: $parent.entity[$data.field].length"></div>'
	};

	this.gridOptions = {
		data: this.list,
		selectedItems: self.actions.list,

		multiSelect: true,
		canSelectRows: true,
		displaySelectionCheckbox: true,

		showGroupPanel: false,
		footerVisible: false,

		rowTemplate: gridTemplates.highlightRow,

		columnDefs: [
			{ field: 'Forms',				displayName: 'Forms',				width: 60,		cellTemplate: gridTemplates.tooltipCount },
			{ field: 'Billing',				displayName: 'Billing',				width: 60,		cellTemplate: gridTemplates.tooltipCount },
			{ field: 'Documents',			displayName: 'Docs',				width: 60,		cellTemplate: gridTemplates.tooltipCount },
			{ field: 'id',					displayName: 'ID',					width: 70},
			{ field: 'date',				displayName: 'Date',				width: 155 },
			{ field: 'reason',				displayName: 'Reason',				width: 300 },
			{ field: 'facility',			displayName: 'Service Location',	width: 200 },
			{ field: 'pid',					displayName: 'PID',					width: 70 },
			{ field: 'encounter',			displayName: 'Encounter',			width: 100 },
			{ field: 'onset_date',			visible: false },
			{ field: 'sensitivity',			visible: false },
			{ field: 'facility_id',			visible: false },
			{ field: 'billing_note',		displayName: 'Billing Note',		width: 225 },
			{ field: 'pc_catid',			visible: false },
			{ field: 'last_level_billed',	displayName: 'Last Level Billed',	width: 120 },
			{ field: 'last_level_closed',	visible: false },
			{ field: 'last_stmt_date',		displayName: 'Last Stmt Date',		width: 155 },
			{ field: 'stmt_count',			visible: false },
			{ field: 'provider_id',			visible: false },
			{ field: 'supervisor_id',		visible: false },
			{ field: 'invoice_refno',		visible: false },
			{ field: 'referral_source',		visible: false },
			{ field: 'billing_facility',	visible: false },
			{ field: 'external_id',			visible: false },
		]
	};

	this.display = function(encounter) {
		self.pid(encounter.pid);
		self.date(encounter.date);

		self.load();
	}

	this.remove = function() {
		self.pid('');
		self.date('');

		self.clear();
	}

	this.clear = function() {
		self.list([]);
		self.billing.remove();
		self.documents.remove();
		self.forms.remove();

		self.actions.remove();
	}

	this.load = function() {
		self.clear();

		$.ajax({
			type: 'POST',
			url: '?service=json-encounters',
			dataType: 'json',
			data: {
				pid: self.pid(),
				date: self.date()
			},
			success: function(response, status, xHR) {
				if(typeof response === 'object') {
					if(!response.hasOwnProperty('status') ||
						!response.hasOwnProperty('errors') ||
						!response.hasOwnProperty('data')
					) {
						self.parent().notices.add('AJAX JSON call failed.')
					} else {
						if(response.errors.length > 0) {
							response.errors.forEach(function(error) {
								self.parent().notices.add('Error: ' + response + '.');
							});
						} else if(response.data.length == 0) {
							self.parent().notices.add('No Encounters found.')
						} else if(
							!response.data.hasOwnProperty('encounters') ||
							!response.data.hasOwnProperty('documents') ||
							!response.data.hasOwnProperty('billing') ||
							!response.data.hasOwnProperty('forms')
						) {
							self.parent().notices.add('Encounters list has malformed data.')
						} else {
							self.initialize(response.data);
						}
					}
				} else if(response === '') {
					self.parent().notices.add('Encounters failed to load.');
				} else {
					self.parent().notices.add('Failed: ' + response + '.');
				}
			},
			error: function(xHR, status, error) {
				self.parent().notices.add('Status: ' + status + ', Error: ' + error);
			},
		});
	}

	this.initialize = function(data) {
		self.forms.load(data.forms);
		self.billing.load(data.billing);
		self.documents.load(data.documents);

		data.encounters.forEach(function(encounter) {
			encounter['Forms'] = self.forms.stubs(encounter['encounter']);

			encounter['Billing'] = self.billing.stubs(encounter['encounter']);

			encounter['Documents'] = self.documents.stubs(encounter['encounter']);

			self.list.push(encounter);
		});

		this.gridResize();
	}

	// this.gridResize = function() {}

	this.resizeGridEvent = function(gridSelector, paneSelector) {
		if(paneSelector) {
			$(paneSelector).on('dividerdragstart', function(event, data) {
				if($(gridSelector).css('display') != 'none') {
					$(gridSelector).hide();

					$(paneSelector).one('dividerdragend', function(event, data) {
						$(gridSelector).show();

						self.gridResize();
					});
				}
			});
		}

		self.gridResize = function() {
			// Resize window to get KoGrid to display
			$(gridSelector).trigger('resize');

			// Fix headers bar Width
			$(gridSelector + ' .kgHeaderContainer').width(
				$(gridSelector + ' .kgTopPanel').width()
			);
		}
	}
}


// Duplicate Encounter Forms

function EncountersForms() {
	var self = this;

	this.list = ko.observableArray();

	this.remove = function() {
		self.list([]);
	}

	this.load = function(data) {
		self.list(data);
	}

	this.stubs = function(encounter) {
		var stubs = [];

		self.list().forEach(function(item) {
			if(item['encounter'] == encounter) {
				stubs.push(item.form_name);
			}
		})

		return stubs;
	}
}


// Duplicate Encounter Billing

function EncountersBilling() {
	var self = this;

	this.list = ko.observableArray();

	this.remove = function() {
		self.list([]);
	}

	this.load = function(data) {
		self.list(data);
	}

	this.stubs = function(encounter) {
		var stubs = [];

		self.list().forEach(function(item) {
			if(item['encounter'] == encounter) {
				stubs.push(item.code_type + ':' + item.code);
			}
		})

		return stubs;
	}
}


// Duplicate Encounter Documents

function EncountersDocuments() {
	var self = this;

	this.list = ko.observableArray();

	this.remove = function() {
		self.list([]);
	}

	this.load = function(data) {
		self.list(data);
	}

	this.stubs = function(encounter) {
		var stubs = [];

		self.list().forEach(function(item) {
			if(item['encounter_id'] == encounter) {
				stubs.push(item.url.substring(item.url.lastIndexOf('/') + 1));
			}
		})

		return stubs;
	}
}


// Duplicate Encounter Actions

function EncountersActions(parent) {
	var self = this;

	this.parent = function() { return parent };

	this.list = ko.observableArray();

	this.primaryEncounter = ko.observable();

	this.primaryEncounter.extend({ notify: 'always' });
	this.primaryEncounter.subscribe(function(newValue) {
		if(typeof newValue == 'object') {
			self.encounterUpdate().forEach(function(item) {
				item.value(newValue[item.field]);
			});
		}
	});

	this.encounterUpdate = ko.observableArray([
		{ field: 'id',					displayName: 'ID',					value: ko.observable() },
		{ field: 'date',				displayName: 'Date',				value: ko.observable() },
		{ field: 'reason',				displayName: 'Reason',				value: ko.observable() },
		{ field: 'facility',			displayName: 'Service Location',	value: ko.observable() },
		{ field: 'pid',					displayName: 'PID',					value: ko.observable() },
		{ field: 'encounter',			displayName: 'Encounter',			value: ko.observable() },
		{ field: 'onset_date',			displayName: null,					value: ko.observable() },
		{ field: 'sensitivity',			displayName: null,					value: ko.observable() },
		{ field: 'facility_id',			displayName: null,					value: ko.observable() },
		{ field: 'billing_note',		displayName: 'Billing Note',		value: ko.observable() },
		{ field: 'pc_catid',			displayName: null,					value: ko.observable() },
		{ field: 'last_level_billed',	displayName: 'Last Level Billed',	value: ko.observable() },
		{ field: 'last_level_closed',	displayName: null,					value: ko.observable() },
		{ field: 'last_stmt_date',		displayName: 'Last Stmt Date',		value: ko.observable() },
		{ field: 'stmt_count',			displayName: null,					value: ko.observable() },
		{ field: 'provider_id',			displayName: null,					value: ko.observable() },
		{ field: 'supervisor_id',		displayName: null,					value: ko.observable() },
		{ field: 'invoice_refno',		displayName: null,					value: ko.observable() },
		{ field: 'referral_source',		displayName: null,					value: ko.observable() },
		{ field: 'billing_facility',	displayName: null,					value: ko.observable() },
		{ field: 'external_id',			displayName: null,					value: ko.observable() }
	]);

	this.encounterIds = ko.computed(function() {
		return self.list().map(function(encounter) {return encounter.encounter;});
	});

	this.forms = ko.computed(function() {
		return parent.forms.list().filter(function(item) {
			return self.encounterIds().indexOf(item.encounter) > -1;
		});
	});

	this.billing = ko.computed(function() {
		return parent.billing.list().filter(function(item) {
			return self.encounterIds().indexOf(item.encounter) > -1;
		});
	});

	this.documents = ko.computed(function() {
		return parent.documents.list().filter(function(item) {
			return self.encounterIds().indexOf(item.encounter_id) > -1;
		});
	});

	this.remove = function() {
		self.list([]);
		self.primaryEncounter('');
		self.encounterUpdate().forEach(function(item) {
			item.value(null);
		});
	}

	this.merge = function() {
		if(self.list().length < 2) {
			self.parent().parent().notices.add('You need to select a minimum of 2 encoutners to merge.')
		} else if(typeof self.primaryEncounter() != 'object') {
			self.parent().parent().notices.add('You need to select a primary encoutner to merge into.')
		} else {
			if(confirm('Performing this action will update and remove records in the database.  Proceed with the merge?')) {
				var mergeData = {
					primary: {},
					ids: [],
					encounters: []
				};

				self.encounterUpdate().forEach(function(field) {
					mergeData.primary[field.field] = field.value();
				});

				self.list().forEach(function(encounter) {
					if(encounter.id !== mergeData.primary.id) {
						mergeData.ids.push(encounter.id);
						mergeData.encounters.push(encounter.encounter);
					}
				});

				console.log(mergeData);

				$.ajax({
					type: 'POST',
					url: '?service=json-merge',
					dataType: 'json',
					data: mergeData,
					success: function(response, status, xHR) {
						if(typeof response === 'object') {
							if(!response.hasOwnProperty('status') ||
								!response.hasOwnProperty('errors') ||
								!response.hasOwnProperty('data')
							) {
								self.parent().parent().notices.add('AJAX JSON call failed.')
							} else {
								if(response.errors.length > 0) {
									response.errors.forEach(function(error) {
										self.parent().parent().notices.add('Error: ' + response + '.');
									});
								} else if(response.data.length == 0) {
									self.parent().parent().notices.add('No merge results returned.')
								} else if(
									!response.data.hasOwnProperty('Primary Encounter Updated') ||
									!response.data.hasOwnProperty('Secondary Encounters Deleted') ||
									!response.data.hasOwnProperty('Secondary Form Encounters Deleted') ||
									!response.data.hasOwnProperty('Secondary Forms Update') ||
									!response.data.hasOwnProperty('Secondary Billing Update') ||
									!response.data.hasOwnProperty('Secondary Documents Update')
								) {
									self.parent().parent().notices.add('Merge results has malformed data.')
								} else {
									self.displayMergeResults(response.data);
								}
							}
						} else if(response === '') {
							self.parent().parent().notices.add('Encounters failed to merge.');
						} else {
							self.parent().parent().notices.add('Failed: ' + response + '.');
						}
					},
					error: function(xHR, status, error) {
						self.parent().parent().notices.add('Status: ' + status + ', Error: ' + error);
					},
				});
			}
		}
	}

	this.displayMergeResults = function(results) {
		var notice = [];

		for(var item in results) {
			if(results.hasOwnProperty(item)) {
				notice.push(item + ': ' + results[item]);
			}
		}

		self.parent().parent().notices.add(notice.join('<br>'));

		self.parent().load();
		self.parent().parent().duplicates.loadList();
	}

}
