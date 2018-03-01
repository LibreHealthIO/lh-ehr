// Class to represent a record in the Safety Types catalog data
function SafetyType() {
	this.Id = ko.observable();
	this.Label = ko.observable();
	this.Priority = ko.observable();
	this.Disabled = ko.observable();
}

// Class to represent a record in the Current Services Types catalog data
function CurrentServicesType() {
	this.Id = null;
	this.Label = null;
	this.Priority = null;
	this.Disabled = null;
}

// Class to represent a record in the Functional Types catalog data
function FunctionalType() {
	this.Id = ko.observable();
	this.Label = ko.observable();
	this.Priority = ko.observable();
	this.Disabled = ko.observable();
	this.Description = ko.observable();
	this.ControlName = ko.computed(function() {
		return 'functionalNeed-' + this.Id();
	}.bind(this));
}