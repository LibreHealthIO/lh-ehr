// Class to represent signature information
function Signatures(assessment) {
	var self = this;

	this.CaseManager = ko.observable();
	this.Supervisor = ko.observable();

	this.IsCaseManger = ko.computed(function() {
		return this.CaseManagerId() == this.ViewModel.User();
	}.bind(assessment));
	this.IsSupervisor = ko.computed(function() {
		return this.CaseManagerSupervisorId() == this.ViewModel.User();
	}.bind(assessment));
	this.ExistSupervisor = ko.computed(function() {
		return this.CaseManagerSupervisorId() !== '';
	}.bind(assessment));

	this.DisplayCaseManager = ko.computed(function() {
		return self.CaseManager() == null && self.IsCaseManger();
	});
	this.DisplaySupervisor = ko.computed(function() {
		return self.Supervisor() == null && self.IsSupervisor() && self.CaseManager() 
	});
	this.DisplayRevert = ko.computed(function() {
		return self.CaseManager() !== null
			&& (self.IsSupervisor()
				|| (!self.ExistSupervisor()
					&& self.IsCaseManger()
				)
			);
	});
	this.Display = ko.computed(function() {
		return self.IsCaseManger() || self.IsSupervisor();
	});
	this.Visible = ko.observable(false);
	this.ManagerNoteOff = function() {
		this.ManagerNoteVisible(false);
	}.bind(assessment);
	this.VisibleToggle = function() {
		self.Visible(!self.Visible());
		if(self.Visible())
			self.ManagerNoteOff();
	};
};

// Class to represent a record in the Sources grid
function SourceRecord() {
	var self = this;
	this.Id = ko.observable();
	this.Type = ko.observable();
	this.Date = ko.observable();
	this.Links = ko.observableArray();
	this.LinksActive = ko.computed(function() {
		return ko.utils.arrayFilter(
			self.Links(),
			function(link) { return !link._destroy; }
		);
	});
	this.LinkExists = function(linkType) {
		return (ko.utils.arrayFilter(
			self.Links(),
			function(link) { return link.Field === linkType; }
		).length > 0);
	};
	this.LinksAdd = function(data) {
		if(!self.LinkExists(data.Field)) {
			link = new SourceLinkRecord;
			link.Id(data.Id);
			link.Field(data.Field);
			self.Links.push(link);
		}
	};
	this.LinksRemove = function(link) {
		if(link.Id() === undefined)
			self.Links.remove(link);
		else
			self.Links.destroy(link);
	};
	this.OptionsText = ko.computed(function() {
		return self.Type() + ' (' + self.Date() + ')';
	});
}

// Class to represent a record in the SourcesLinks grid
function SourceLinkRecord() {
	this.Id = ko.observable();
	this.Field = ko.observable();
}

// Class to represent a grid of field specific Sources
function SourcesLinkedControl(sources, type) {
	var self = this;

	this.SourcesCore = sources;
	this.Type = type;

	this.Display = ko.observable(false);
	this.DisplaySet = function(value) {
		self.Display(value);
	};
	this.Selected = ko.observable();

	this.SourceCheckForLink = function(source) {
		var linkExists = false;
		source.Links().forEach(function(link) {
			if(link.Field() === self.Type
				&& !link._destroy
			)
				linkExists = true;
		});
		return linkExists;
	};

	this.SourcesAvailable = ko.computed(function() {
		return ko.utils.arrayFilter(
			self.SourcesCore(),
			function(source) {
				return !self.SourceCheckForLink(source);
			}
		);
	});
	this.Sources = ko.computed(function() {
		return ko.utils.arrayFilter(
			self.SourcesCore(),
			function(source) {
				return self.SourceCheckForLink(source);
			}
		);
	});
	this.Add = function() {
		self.Selected().LinksAdd({
			Id: null,
			Field: self.Type
		});
		self.Selected(null);
		self.Display(false);
	};
	this.Remove = function(data) {
		data.LinksActive().forEach(function(link) {
			if(link.Field() === self.Type)
				data.LinksRemove(link);
		});
	};
}

// Class to represent a record in the Treatment History grid
function TreatmentRecord() {
	this.Id = ko.observable();
	this.Provider = ko.observable();
	this.Dates = ko.observable();
	this.Type = ko.observable();
}

// Class to represent a record in the Medical History grid
function MedicalRecord() {
	this.Id = ko.observable();
	this.Type = ko.observable();
	this.Provider = ko.observable();
	this.LastExam = ko.observable();
	this.Findings = ko.observable();
	this.KnownConditions = ko.observable();
}

// Class to represent a record in the Medication grids
function MedicationRecord() {
	this.Id = ko.observable();
	this.Date = ko.observable();
	this.Title = ko.observable();
	this.BeginDate = ko.observable();
	this.EndDate = ko.observable();
	this.Activity = ko.observable();
}

// Class to represent a record in the Educational History grid
function EducationalRecord() {
	this.Id = ko.observable();
	this.School = ko.observable();
	this.Grades = ko.observable();
	this.Type = ko.observable();
	this.Plan = ko.observable();
	this.Performance = ko.observable();
	this.Behavior = ko.observable();
}

// Class to represent a record in the Substance Abuse grid
function SubstanceAbuseRecord() {
	this.Id = ko.observable();
	this.Substance = ko.observable();
	this.Frequency = ko.observable();
}

// Class to represent a record in the Current Services grid
function CurrentServicesRecord() {
	var self = this;
	this.Id = ko.observable();
	this.Provider = ko.observable();
	this.DateBegin = ko.observable();
	this.Type = ko.observable();
	this.TypeOther = ko.observable();
	this.Effectiveness = ko.observable();
	this.TypeToggle = function() {
		self.Type(self.Type() === '0' ? null : '0');
	};
}

// Class to represent a record in the Safety grid
function SafetyRecord() {
	var self = this;
	this.Id = ko.observable();
	this.TypeId = ko.observable();
	this.Type = ko.observable();
	this.Selected = ko.observable();
	this.Selected.subscribe(function (newValue) {
		if(newValue === false && self.Id() > 0) {
			self._change = true;
		} else if("_change" in self || self.Id() === null) {
			delete self._change;
		}
	});
}

// Class to represent a record in the Functional grid
function FunctionalRecord() {
	var self = this;
	this.Id = ko.observable();
	this.TypeId = ko.observable();
	this.ServiceNeeds = ko.observable();
	this.Willingness = ko.observable();
	this.NeedSummary = ko.observable();
	this.ActivityRecommendations = ko.observable();
	this.WillingEnable = ko.computed(function() {
		return self.ServiceNeeds() === 'no';
	});
	this.ServiceNeeds.subscribe(function() {
		if(self.ServiceNeeds() === 'no')
			self.Willingness(false);
	});
	this.Type = ko.observable();
}
