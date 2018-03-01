function Note(ViewModel) {
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
	this.AssessmentId = ko.observable();
  
 	this.TCMServicePlanId = ko.observable();
 	this.TCMServicePlanFinalizedDate = ko.observable();

	// Assessment Type
	this.Type = ko.observable();
	this.TypeDisplay = ko.computed(function() {
		return self.Type === 'UPDATE' ? 'Annual Update' : 'Initial';
	});

	// Assessment Form Information
  
  // TODO cleanup, some of these obsolete 
	this.CaseManagerId = ko.observable();
	this.CaseManagerName = ko.observable();
  this.ServicePlanId = ko.observable();
	this.CaseManagerSupervisorId = ko.observable();
	this.CaseManagerSupervisorName = ko.observable();
	this.ClientId = ko.observable();
	this.ClientName = ko.observable();
	this.ClientBirth = ko.observable();
	this.MedicaidId = ko.observable();
  
  this.pid = ko.observable();

	this.DateWritten = ko.observable();
	this.DateComplete = ko.observable();
	this.CurrentServiceNeeds = ko.observable();
	this.DischargePlan = ko.observable();

	this.ManagerNote = ko.observable();
	this.ManagerNoteVisible = ko.observable(false);
	this.ManagerNoteVisibleToggle = function() {
		self.ManagerNoteVisible(!self.ManagerNoteVisible());
		if(self.ManagerNoteVisible())
			self.Finalized.Visible(false);
	};

	this.Finalized = new Finalized(this);

	this.Problems = ko.observableArray();
	this.ProblemsSelected = ko.observable();
	this.ProblemsAdd = function(data) {
		problem = new ProblemRecord;
		problem.Id(data.Id);
		problem.Area(data.Area);
		problem.AreaId(data.AreaId);
    problem.Id(data.Id);  
    problem.ProblemId(data.ProblemId);
		problem.Problem(data.Problem);
		problem.Activities(data.Activities);
    problem.ProgressNotes(data.Note);
		data.Goals.forEach(function(goal) {
			problem.GoalsAdd(goal);
		});
    
/* @todo: delete    
		data.Agents.forEach(function(agent) {
			problem.AgentsAdd(agent);
		});
		problem.Type(NoteVM.FunctionalTypes().filter(
			function(func) {
				return func.Id() === problem.AreaId();
			}
		).slice(-1)[0]);
*/
		self.Problems.push(problem);
	};
  
  this.ProblemsSelected = ko.observable();
	this.ProblemsAddNew = function() {
		if(self.ProblemsSelected() !== undefined) {    
			problem = new ProblemRecord;
      problem.ProblemId(self.ProblemsSelected().Id());
			problem.Area(self.ProblemsSelected().Area());
      problem.Problem(self.ProblemsSelected().Problem());
      problem.Activities(self.ProblemsSelected().Activities());
      problem.Goals(self.ProblemsSelected().Goals());
			self.Problems.push(problem);
			self.ProblemsSelected(undefined);
		}
	};
  
	this.ProblemsRemove = function(problem) {
		if(problem.Id() === undefined)
			self.Problems.remove(problem);
		else
			self.Problems.destroy(problem);
	};
  
	this.Activities = ko.observableArray();
  this.ActivitiesActive = ko.computed( function() {
    return ko.utils.arrayFilter(
      self.Activities(),
      function(activity) { return !activity._destroy }
    );
  });
   
	this.ActivitiesAdd = function(data) {
    activity = new ActivityRecord;
		activity.Id(data.Id);
    activity.ActivityTypeId(data.ActivityTypeId);
		activity.StartTime(data.StartTime);
		activity.EndTime(data.EndTime);
		activity.Description(data.Description);
		activity.LocationTypeId(data.LocationTypeId);
		activity.ContactTypeId(data.ContactTypeId);
    self.Activities.push(activity);
	};
  
	this.ActivitiesAddNew = function() {
    activity = new ActivityRecord;
    self.Activities.push(activity);
    
    if (typeof jQuery.ui !== 'undefined') {
      $('input[type=time]').not('.hasDatePicker').timepicker({
  			dateFormat: 'yy-mm-dd'
      }); 
    }                    
        
	};
  
	this.ActivitiesRemove = function(activity) {
var oLen = self.Activities().length;
		if(activity.Id() === undefined)
			self.Activities.remove(activity);
		else
			self.Activities.destroy(activity);
	};    
  
  // Display value methods
  this.sumActivityMins = function () {
    var total = 0;
    ko.utils.arrayForEach(self.ActivitiesActive(), 
      function(activity) {
        total += activity.DurationMins();
    });
    return total;   
  };
     
  this.totalActivityMins = ko.computed( function() {
    return this.sumActivityMins();   
  }, this);
  
  this.totalActivityUnits = ko.computed( function() {
    return Math.round(this.sumActivityMins() / 15);
  }, this);   
  

}