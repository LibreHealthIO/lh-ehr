function Assessment(ViewModel) {
	var self = this;

	this.dateOffset = function(date, offsetYears, offsetMonth, offsetDate) {
		date = new Date(date) || new Date();
	    offsetYears = offsetYears || 0;
	    offsetMonth = offsetMonth || 0;
	    offsetDate = offsetDate || 0;
		return new Date(date.getFullYear() + offsetYears, date.getMonth() + offsetMonth, date.getDate() + offsetDate);
	};
	this.dateString = function(date) {
		date = new Date(date) || new Date();
		// YYYY-MM-DD
		return date.getFullYear() + '-' +
			('0' + date.getMonth()).slice(-2) + '-' +
			('0' + date.getDate()).slice(-2);
	};
	this.dateTimeString = function(date) {
		date = new Date(date) || new Date();
		// YYYY-MM-DD HH:MM:ss
		return date.getFullYear() + '-' +
			('0' + date.getMonth()).slice(-2) + '-' +
			('0' + date.getDate()).slice(-2) + ' ' +
			('0' + date.getHours()).slice(-2) + ':' +
			('0' + date.getMinutes()).slice(-2) + ':' +
			('0' + date.getSeconds()).slice(-2);
	};

	// ViewModel
	this.ViewModel = ViewModel;

	// Assessment Id
	this.Id = ko.observable();

	// Assessment Type
	this.Type = ko.observable();
	this.TypeDisplay = ko.computed(function() {
		return self.Type === 'UPDATE' ? 'Annual Update' : 'Initial';
	});

	// Assessment Form Information
	this.CaseManagerId = ko.observable();
	this.CaseManagerName = ko.observable();
	this.CaseManagerSupervisorId = ko.observable();
	this.CaseManagerSupervisorName = ko.observable();
	this.ClientId = ko.observable();
	this.ClientName = ko.observable();
	this.ClientBirth = ko.observable();
	this.MedicaidId = ko.observable();
	this.AdmitDate = ko.observable();
	this.HomeVisitDates = ko.observable();
	
	// wkr20170531
	this.TreatmentHistoryNotes = ko.observable();
	this.MedicationsCurrentNotes = ko.observable();
	this.MedicationsPastNotes = ko.observable();
	this.EducationalHistoryNotes = ko.observable();
	this.SubstanceAbuseNotes = ko.observable();
	this.CurrentCircumstances = ko.observable();

	this.ManagerNote = ko.observable();
	this.ManagerNoteVisible = ko.observable(false);
	this.ManagerNoteVisibleToggle = function() {
		self.ManagerNoteVisible(!self.ManagerNoteVisible());
		if(self.ManagerNoteVisible())
			self.Signatures.Visible(false);
	};

	this.Signatures = new Signatures(this);

	this.Encounter = {
		ReportDate: ko.observable(),
		ProblemReason: ko.observable()
	};

	// Assessment Sources of Information
	this.Sources = ko.observableArray();
	this.SourcesActive = ko.computed(function() {
		return ko.utils.arrayFilter(
			self.Sources(),
			function(source) { return !source._destroy; }
		);
	});
	this.SourcesAdd = function(data) {
		source = new SourceRecord;
		source.Id(data.Id);
		source.Type(data.Type);
		source.Date(data.Date);
		data.Links.forEach(function(data) {
			source.LinksAdd(data);
		});
		self.Sources.push(source);
	};
	this.SourcesAddNew = function() {
		self.Sources.push(new SourceRecord);
	};
	this.SourcesRemove = function(source) {
		if(source.Id() === undefined)
			self.Sources.remove(source);
		else
			self.Sources.destroy(source);
	};

	// Assessment Personal and Family History
	this.FamilyHistory = ko.observable();
	this.FamilyHistorySources = new SourcesLinkedControl(this.SourcesActive, 'familyHistory');

	// Assessment Treatment History
	this.TreatmentHistory = ko.observableArray();
	this.TreatmentHistorySources = new SourcesLinkedControl(this.SourcesActive, 'treatmentHistory');
	this.TreatmentHistoryActive = function() {
		return ko.utils.arrayFilter(
			self.TreatmentHistory(),
			function(treatment) { return !treatment._destroy; }
		);
	};
	this.TreatmentHistoryAdd = function(data) {
		treatment = new TreatmentRecord;
		treatment.Id(data.Id);
		treatment.Provider(data.Provider);
		treatment.Dates(data.Dates);
		treatment.Type(data.Type);
		self.TreatmentHistory.push(treatment);
	};
	this.TreatmentHistoryAddNew = function() {
		self.TreatmentHistory.push(new TreatmentRecord);
	};
	this.TreatmentHistoryRemove = function(treatment) {
		if(treatment.Id() === undefined)
			self.TreatmentHistory.remove(treatment);
		else
			self.TreatmentHistory.destroy(treatment);
	};

	// Assessment Medical History
	this.MedicalHistory = ko.observableArray();
	this.MedicalHistoryNotes = ko.observable();
	this.MedicalHistoryActive = function() {
		return ko.utils.arrayFilter(
			self.MedicalHistory(),
			function(medical) { return !medical._destroy; }
		);
	};
	this.MedicalHistoryAdd = function(data) {
		medical = new MedicalRecord;
		medical.Id(data.Id);
		medical.Type(data.Type);
		medical.Provider(data.Provider);
		medical.LastExam(data.LastExam);
		medical.Findings(data.Findings);
		medical.KnownConditions(data.KnownConditions);
		self.MedicalHistory.push(medical);
	};
	this.MedicalHistoryAddNew = function() {
		self.MedicalHistory.push(new MedicalRecord);
	};
	this.MedicalHistoryRemove = function(medical) {
		if(medical.Id() === undefined)
			self.MedicalHistory.remove(medical);
		else
			self.MedicalHistory.destroy(medical);
	};
	this.MedicalHistorySources = new SourcesLinkedControl(this.SourcesActive, 'medicalHistory');

	// Assessment Medications
	this.Medications = ko.observableArray();
	this.MedicationsAdd = function(data) {
		medication = new MedicationRecord();
		medication.Id(data.Id);
		medication.Date(data.Date);
		medication.Title(data.Title);
		medication.BeginDate(data.BeginDate);
		medication.EndDate(data.EndDate);
		medication.Activity(data.Activity);
		self.Medications.push(medication);
	};
	this.MedicationsRemove = function(medication) {
		if(medication.Id() === undefined)
			self.Medications.remove(medication);
		else
			self.Medications.destroy(medication);
	};
	this.MedicationsCurrentSources = new SourcesLinkedControl(this.SourcesActive, 'medicationsCurrent');
	this.MedicationsPastSources = new SourcesLinkedControl(this.SourcesActive, 'medicationsPast');

	// Assessment Medications Current
	this.MedicationsCurrent = ko.computed(function() {
		return ko.utils.arrayFilter(
			self.Medications(),
			function(medication) { return isNaN(Date.parse(medication.EndDate())); }
		);
	});
	this.MedicationsActiveCurrent = function() {
		return ko.utils.arrayFilter(
			self.MedicationsCurrent(),
			function(medication) { return !medication._destroy; }
		);
	};
	this.MedicationsAddNewCurrent = function() {
		medication = new MedicationRecord();
		medication.Date(self.dateTimeString());
		medication.EndDate(null);
		self.Medications.push(medication);
	};

	// Assessment Medications Past
	this.MedicationsPast = ko.computed(function() {
		return ko.utils.arrayFilter(
			self.Medications(),
			function(medication) { return !isNaN(Date.parse(medication.EndDate())); }
		);
	});
	this.MedicationsActivePast = function() {
		return ko.utils.arrayFilter(
			self.MedicationsPast(),
			function(medication) { return !medication._destroy; }
		);
	};
	this.MedicationsAddNewPast = function() {
		medication = new MedicationRecord();
		medication.Date(self.dateTimeString());
		medication.EndDate(self.dateString());
		self.Medications.push(medication);
	};

	// Assessment Educational History
	this.EducationalHistory = ko.observableArray();
	this.EducationalHistoryActive = function() {
		return ko.utils.arrayFilter(
			self.EducationalHistory(),
			function(educational) { return !educational._destroy; }
		);
	};
	this.EducationalHistoryAdd = function(data) {
		educational = new EducationalRecord;
		educational.Id(data.Id);
		educational.School(data.School);
		educational.Grades(data.Grades);
		educational.Type(data.Type);
		educational.Plan(data.Plan);
		educational.Performance(data.Performance);
		educational.Behavior(data.Behavior);
		self.EducationalHistory.push(educational);
	};
	this.EducationalHistoryAddNew = function() {
		self.EducationalHistory.push(new EducationalRecord);
	};
	this.EducationalHistoryRemove = function(educational) {
		if(educational.Id() === undefined)
			self.EducationalHistory.remove(educational);
		else
			self.EducationalHistory.destroy(educational);
	};
	this.EducationalHistorySources = new SourcesLinkedControl(this.SourcesActive, 'educationalHistory');

	// Assessment Substance Abuse
	this.SubstanceAbuse = ko.observableArray();
	this.SubstanceAbuseAge = ko.computed(function() {
		return (self.dateOffset(self.ClientBirth(), 10,0,1) >= self.dateOffset(new Date()));
	});
	this.SubstanceAbuseActive = function() {
		return ko.utils.arrayFilter(
			self.SubstanceAbuse(),
			function(substance) { return !substance._destroy; }
		);
	};
	this.SubstanceAbuseAdd = function(data) {
		substance = new SubstanceAbuseRecord;
		substance.Id(data.Id);
		substance.Substance(data.Substance);
		substance.Frequency(data.Frequency);
		self.SubstanceAbuse.push(substance);
	};
	this.SubstanceAbuseAddNew = function() {
		self.SubstanceAbuse.push(new SubstanceAbuseRecord);
	};
	this.SubstanceAbuseRemove = function(substance) {
		if(substance.Id() === undefined)
			self.SubstanceAbuse.remove(substance);
		else
			self.SubstanceAbuse.destroy(substance);
	};
	this.SubstanceAbuseSources = new SourcesLinkedControl(this.SourcesActive, 'substanceAbuse');

	// Assessment Vocational History
	this.VocationalHistory = ko.observable();
	this.VocationalHistoryAge = ko.computed(function() {
		return (self.dateOffset(self.ClientBirth(), 14,0,1) >= self.dateOffset(new Date()));
	});
	this.VocationalHistorySources = new SourcesLinkedControl(this.SourcesActive, 'vocationalHistory');

	// Assessment Legal & Dependency History
	this.LegalDependencyHistory = ko.observable();
	this.LegalDependencyHistorySources = new SourcesLinkedControl(this.SourcesActive, 'legalDependencyHistory');

	// Assessment Sugnificant Relationships
	this.SignificantRelationships = ko.observable();

	// Assessment Current and Potential Strengths
	this.PotentialStrengths = {
		Client: ko.observable(),
		Parent: ko.observable(),
		Family: ko.observable()
	};

	// Assessment Resources Available
	this.ResourcesAvailable = ko.observable();

	// Assessment Safety Assessment
	this.Safety = {
		Types: ko.observableArray(),
		Other: ko.observable(),
		Description: ko.observable(),
		Plan: ko.observable()
	};
	this.SafetyAdd = function(data) {
		safety = new SafetyRecord;
		safety.Id(data.Id);
		safety.TypeId(data.TypeId);
		safety.Type(AssessmentVM.SafetyTypes().filter(
			function(safe) {
				return safe.Id() === safety.TypeId();
			}
		).slice(-1)[0]);
		safety.Selected(data.Selected);
		self.Safety.Types.push(safety);
	};

	// Assessment Current Services
	this.CurrentServices = ko.observableArray();
	this.CurrentServicesActive = function() {
		return ko.utils.arrayFilter(self.CurrentServices(),
			function(service) { return !service._destroy; }
		);
	};
	this.CurrentServicesAdd = function(data) {
		service = new CurrentServicesRecord;
		service.Id(data.Id);
		service.Provider(data.Provider);
		service.DateBegin(data.DateBegin);
		service.Type(data.Type);
		service.TypeOther(data.TypeOther);
		service.Effectiveness(data.Effectiveness);
		self.CurrentServices.push(service);
	};
	this.CurrentServicesAddNew = function() {
		self.CurrentServices.push(new CurrentServicesRecord);
	};
	this.CurrentServicesRemove = function(service) {
		if(service.Id() === undefined)
			self.CurrentServices.remove(service);
		else
			self.CurrentServices.destroy(service);
	};

	// Assessment Functional Assessment & Needs and Recommendations
	this.Functional = ko.observableArray();
	this.FunctionalAdd = function(data) {
		functional = new FunctionalRecord;
		functional.Id(data.Id);
		functional.TypeId(data.TypeId);
		functional.ServiceNeeds(data.ServiceNeeds);
		functional.Willingness(data.Willingness);
		functional.NeedSummary(data.NeedSummary);
		functional.ActivityRecommendations(data.ActivityRecommendations);
		functional.Type(AssessmentVM.FunctionalTypes().filter(
			function(func) {
				return func.Id() === functional.TypeId();
			}
		).slice(-1)[0]);
		self.Functional.push(functional);
	};
}