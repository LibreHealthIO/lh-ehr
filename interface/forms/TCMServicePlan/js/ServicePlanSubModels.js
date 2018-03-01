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

function ParentAssessment() {
	var self = this;

	this.Id = ko.observable();
	this.CaseManagerId = ko.observable();
	this.CaseManagerName = ko.observable();
	this.CaseManagerSupervisorId = ko.observable();
	this.CaseManagerSupervisorName = ko.observable();
	this.ClientId = ko.observable();
	this.ClientName = ko.observable();
	this.ClientBirth = ko.observable();
	this.MedicaidId = ko.observable();
	this.ReportDate = ko.observable();
	this.Type = ko.observable();
	this.TypeDisplay = ko.computed(function() {
		return self.Type === 'UPDATE' ? 'Annual Update' : 'Initial';
	});
	this.OptionsText = ko.computed(function() {
		return self.TypeDisplay() + ' - ' +
			self.ReportDate() + ' - ' +
			self.CaseManagerName();
	});
}

function ParentServicePlan() {
	var self = this;

	this.Id = ko.observable();
	this.AssessmentId = ko.observable();
	this.CaseManagerId = ko.observable();
	this.CaseManagerName = ko.observable();
	this.CaseManagerSupervisorId = ko.observable();
	this.CaseManagerSupervisorName = ko.observable();
	this.ClientId = ko.observable();
	this.ClientName = ko.observable();
	this.ClientBirth = ko.observable();
	this.MedicaidId = ko.observable();
	this.ReportDate = ko.observable();
	this.Type = ko.observable();
	this.TypeDisplay = ko.computed(function() {
		return self.Type === 'UPDATE' ? 'Review' : 'Initial';
	});
	this.OptionsText = ko.computed(function() {
		return self.TypeDisplay() + ' - ' +
			self.ReportDate() + ' - ' +
			self.CaseManagerName();
	});
}

function DiagnosisRecord() {
	var self = this;
	this.Id = ko.observable();
	this.ListsId = ko.observable();
	this.ICD = ko.observable();
	this.Axis = ko.observable();
	this.Code = ko.observable();
	this.Description = ko.observable();
	this.AxisDisplay = function() {
		axis = ServicePlanVM.AxisOptions.filter(
			function(axis) {
				return axis.Value === self.Axis();
			}
		).slice(-1)[0];
		if(axis !== undefined)
			return axis.Label;
	};
}
	
function ProblemRecord() {
	var self = this;

	this.Id = ko.observable();
	this.Area = ko.observable();
	this.AreaId = ko.observable();
	this.Problem = ko.observable();
	this.Activities = ko.observable();

	this.Goals = ko.observableArray();
	this.GoalsAdd = function(data) {
		goal = new GoalRecord;
		goal.Id(data.Id);
		goal.Goal(data.Goal);
		data.Objectives.forEach(function(objective) {
			goal.ObjectivesAdd(objective);
		});
		self.Goals.push(goal);
	};
	this.GoalsAddNew = function() {
		self.Goals.push(new GoalRecord);
    if (typeof jQuery.ui !== 'undefined') {
      $('input[type=date]').not('hasDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
      }); 
    }         
    
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
    if (typeof jQuery.ui !== 'undefined') {
      $('input[type=date]').not('hasDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
      }); 
    }    
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