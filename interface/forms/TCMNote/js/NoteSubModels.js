// Class to represent finalization information
function Finalized(servicePlan) {
	var self = this;

	this.Finalized = ko.observable();
/*
	this.IsCaseManger = ko.computed(function() {
		return this.CaseManagerId() == this.ViewModel.User();
	}.bind(servicePlan));
	this.IsSupervisor = ko.computed(function() {
		return this.CaseManagerSupervisorId() == this.ViewModel.User();
	}.bind(servicePlan));
*/
	this.Display = ko.computed(function() {
		return this.CaseManagerSupervisorId() == this.ViewModel.User()
			|| (
				this.CaseManagerSupervisorId() == null
				&& this.CaseManagerId() == this.ViewModel.User()
			);
	}.bind(servicePlan));

	this.DisplayFinalize = ko.computed(function() {
		return self.Finalized() == null;
	});
	this.DisplayRevert = ko.computed(function() {
		return self.Finalized() !== null;
	});

	this.Visible = ko.observable(false);
	this.ManagerNoteOff = function() {
		this.ManagerNoteVisible(false);
	}.bind(servicePlan);
	this.VisibleToggle = function() {
		self.Visible(!self.Visible());
		if(self.Visible())
			self.ManagerNoteOff();
	};
};

function ActivityRecord() {
	var self = this;
  
	this.Id = ko.observable();
	this.CaseManagerId = ko.observable();
	this.CaseManagerName = ko.observable();
	this.CaseManagerSupervisorId = ko.observable();
	this.CaseManagerSupervisorName = ko.observable();  this.ActivityTypeId = ko.observable();
  this.ActivityType = ko.observable();
  this.Type = ko.observable();
	this.StartTime = ko.observable();
	this.EndTime = ko.observable();
	this.LocationTypeId = ko.observable();
	this.ContactTypeId = ko.observable();
	this.Description = ko.observable();
  
  this.DurationMins = ko.computed( function() { 
    // This inline code should perhaps be moved to a common area replaced by a utility package WKR110414  *****/
    startTime = self.StartTime();
    endTime = self.EndTime();

    var startDate = new Date("January 1, 1970 " + startTime);
    var endDate = new Date("January 1, 1970 " + endTime);
    var timeDiff = endDate - startDate;
    var hh = Math.floor(timeDiff / 1000 / 60 / 60);
    timeDiff -= hh * 1000 * 60 * 60;
    var mm = Math.floor(timeDiff / 1000 / 60);
    timeDiff -= mm * 1000 * 60;
    var ss = Math.floor(timeDiff / 1000);
    var elapsed = (60 * hh) + mm;    
    return isNaN(elapsed) ? '' : elapsed;
  });

  this.ActivityLabelDisplay =  function() {
    type = NoteVM.ActivityTypes().filter(
      function(type) {
        return type.Value() === self.ActivityTypeId();
      }
    ).slice(-1)[0];
    if(type !== undefined)
      return type.Label;
  }
 this.LocationLabelDisplay =  function() {
    type = NoteVM.LocationTypes().filter(
      function(type) {
        return type.Value() === self.LocationTypeId();
      }
    ).slice(-1)[0];
    if(type !== undefined)
      return type.Label;
  }  
 this.ContactLabelDisplay =  function() {
    type = NoteVM.ContactTypes().filter(
      function(type) {
        return type.Value() === self.ContactTypeId();
      }
    ).slice(-1)[0];
    if(type !== undefined)
      return type.Label;
  }
}
	
function ProblemRecord() {
	var self = this;  
	this.Id = ko.observable();
	this.Area = ko.observable();
	this.AreaId = ko.observable();
  this.ProblemId = ko.observable();
	this.Problem = ko.observable();
	this.Activities = ko.observable();
  this.ProgressNotes = ko.observable();

	this.Goals = ko.observableArray();
	this.GoalsAdd = function(data) {
//console.log( 'ProblemRecord.GoalsAdd: data = ' + ko.toJSON(data,null,4));
		goal = new GoalRecord;
		goal.Id(data.Id);
		goal.Goal(data.Goal);
		data.Objectives.forEach(function(objective) {
      if(objective.Status !== 'achieved')
        goal.ObjectivesAdd(objective);
		});
		self.Goals.push(goal);
	};
	this.GoalsAddNew = function() {
		self.Goals.push(new GoalRecord);
	};
	this.GoalsRemove = function(goal) {
		if(goal.Id() === undefined)
			self.Goals.remove(goal);
		else
			self.Goals.destroy(goal);
	};

	this.Agents = ko.observableArray();
	this.AgentsActive = ko.computed(function() {
		return ko.utils.arrayFilter(
			self.Agents(),
			function(agent) { return !agent._destroy; }
		);
	});
  
	this.AgentsAdd = function(data) {
		agent = new AgentRecord;
		agent.Id(data.Id);
		agent.Type(data.Type);
		agent.TypeOther(data.TypeOther);
		agent.Agency(data.Agency);
		agent.Agent(data.Agent);
		self.Agents.push(agent);
	};
	this.AgentsAddNew = function() {
		self.Agents.push(new AgentRecord);
	};
	this.AgentsRemove = function(agent) {
		if(agent.Id() === undefined)
			self.Agents.remove(agent);
		else
			self.Agents.destroy(agent);
	};

	this.Type = ko.observable();
}

function GoalRecord() {
	var self = this;

	this.Id = ko.observable();
	this.Goal = ko.observable();

	this.Objectives = ko.observableArray();
	this.ObjectivesAdd = function(data) {
		objective = new ObjectiveRecord;
		objective.Id(data.Id);
		objective.Objective(data.Objective);
		objective.TargetDate(data.TargetDate);
		objective.ProgressRate(data.ProgressRate);
		objective.Status(data.Status);
		self.Objectives.push(objective);
	};
	this.ObjectivesAddNew = function() {
		objective = new ObjectiveRecord;
		objective.Status('new');
		self.Objectives.push(objective);
	};
	this.ObjectivesRemove = function(objective) {
		if(objective.Id() === undefined)
			self.Objectives.remove(objective);
		else
			self.Objectives.destroy(objective);
	};
}

function ObjectiveRecord() {
	var self = this;
	this.Id = ko.observable();
	this.Objective = ko.observable();
	this.TargetDate = ko.observable();
	this.ProgressRate = ko.observable();
	this.Status = ko.observable();
	this.StatusDisplay = function() {
		return ServicePlanVM.ObjectiveStatus.filter(
			function(status) {
				return status.Value === self.Status();
			}
		).slice(-1)[0].Label;
	};
}

function AgentRecord() {
	var self = this;
	this.Id = ko.observable();
	this.Type = ko.observable();
	this.TypeOther = ko.observable();
	this.Agency = ko.observable();
	this.Agent = ko.observable();
	this.TypeToggle = function() {
		self.Type(self.Type() === '0' ? null : '0');
	};
	this.TypeDisplay = function() {
		if(self.Type() === 0)
			return self.TypeOther();
		else
			return ServicePlanVM.AgentsTypes().filter(
				function(agent) {
					return agent.Id() === self.Type();
				}
			).slice(-1)[0].Label;
	};
}

// Class to represent a record in the Functional Types catalog data
function FunctionalType() {
	var self = this;
	this.Id = ko.observable();
	this.Label = ko.observable();
	this.Priority = ko.observable();
	this.Disabled = ko.observable();
	this.Description = ko.observable();
	this.ControlName = ko.computed(function() {
		return 'functionalNeed-' + self.Id();
	});
}

// Class to represent a record in the Functional Types catalog data
function AgentType() {
	this.Id = ko.observable();
	this.Label = ko.observable();
	this.Priority = ko.observable();
	this.Disabled = ko.observable();
}

// Class to represent a record in the TCM Activity Types catalog data
function ActivityType() {
	this.Value = ko.observable();
	this.Label = ko.observable();
	this.Priority = ko.observable();
	this.Disabled = ko.observable();
}

// Class to represent a record in the TCM Contact Types catalog data
function ContactType() {
	this.Value = ko.observable();
	this.Label = ko.observable();
	this.Priority = ko.observable();
	this.Disabled = ko.observable();
}

// Class to represent a record in the TCM Location Types catalog data
function LocationType() {
	this.Value = ko.observable();
	this.Label = ko.observable();
	this.Priority = ko.observable();
	this.Disabled = ko.observable();
}

// Class to represent a service plan problem.  
function ServicePlanProblemRecord() {
  var self = this;
  this.Id = ko.observable();
  this.ProblemId = ko.observable();
  this.Area = ko.observable();
  this.Problem = ko.observable();
  this.Activities = ko.observable();
  this.Goals = ko.observableArray();
	this.GoalsAdd = function(data) {
		goal = new GoalRecord;
		goal.Id(data.Id);
		goal.Goal(data.Goal);
		data.Objectives.forEach(function(objective) {
      if(objective.Status != 'achieved')
        goal.ObjectivesAdd(objective);
		});
		self.Goals.push(goal);
	};
	this.GoalsAddNew = function() {
		self.Goals.push(new GoalRecord);
	};
  this.Agents = ko.observableArray();
  this.Label = ko.computed(function() {
    return self.Area() + ': ' + self.Problem() + ' (' + self.Activities() + ')';
  });
}