function NoteViewModel() {
	var self = this;

	this.Configuration = {
		AjaxUri: '',
		WebRoot: '',
		ExitUri: '',
		NoteId: ''
	};

	this.User = ko.observable();

	this.ActivityWait = ko.observable('Loading . . .');
	this.ActivityWaitSet = function(message) {
		message = message || false;
		self.ActivityWait(message);
	};

	this.ErrorAlert = ko.observable(false);
	this.ErrorAlertSet = function(message) {
		message = message || false;
		self.ErrorAlert(message);
    alert(message);
	};

	this.ObjectiveStatus = [
		{ Value: 'new', Label: 'New'},
		{ Value: 'ongoing', Label: 'Ongoing'},
		{ Value: 'deferred', Label: 'Deferred'},
		{ Value: 'achieved', Label: 'Achieved'}
	];

	// Non-editable catalog data - Comes from the server
	this.FunctionalTypes = ko.observableArray();
	this.FunctionalTypesAdd = function(data) {
		type = new FunctionalType();
		type.Id(data.Id);
		type.Label(data.Label);
		type.Priority(data.Priority);
		type.Disabled(data.Disabled);
		type.Description(data.Description);
		self.FunctionalTypes.push(type);
	};

	// Non-editable catalog data - Comes from the server
	this.AgentsTypes = ko.observableArray();
	this.AgentsTypesAdd = function(data) {
		type = new AgentType();
		type.Id(data.Id);
		type.Label(data.Label);
		type.Priority(data.Priority);
		type.Disabled(data.Disabled);
		self.AgentsTypes.push(type);
	};
  
  // Non-editable catalog data - Comes from the server
	this.ActivityTypes = ko.observableArray();
	this.ActivityTypesAdd = function(data) {
		type = new ActivityType();
		type.Value(data.Id);
		type.Label(data.Label);
		type.Priority(data.Priority);
		type.Disabled(data.Disabled);
		self.ActivityTypes.push(type);
	};
  
   
  // Non-editable catalog data - Comes from the server
	this.ActivityTypes = ko.observableArray();
	this.ActivityTypesAdd = function(data) {
		type = new ActivityType();
		type.Value(data.Id);
		type.Label(data.Label);
		type.Priority(data.Priority);
		type.Disabled(data.Disabled);
		self.ActivityTypes.push(type);
	};
  
  // Non-editable catalog data - Comes from the server
	this.ContactTypes = ko.observableArray();
	this.ContactTypesAdd = function(data) {
		type = new ContactType();
		type.Value(data.Id);
		type.Label(data.Label);
		type.Priority(data.Priority);
		type.Disabled(data.Disabled);
		self.ContactTypes.push(type);
	};

  // Non-editable catalog data - Comes from the server
	this.LocationTypes = ko.observableArray();
	this.LocationTypesAdd = function(data) {
		type = new LocationType();
		type.Value(data.Id);
		type.Label(data.Label);
		type.Priority(data.Priority);
		type.Disabled(data.Disabled);
		self.LocationTypes.push(type);
	};
    
  
  // Non-editable reference data - Comes from the server
	this.ServicePlanProblems = ko.observableArray();
	this.ServicePlanProblemsAdd = function(data) {
		prob = new ServicePlanProblemRecord();
		prob.Id(data.Id);
    prob.ProblemId(data.ProblemId);
		prob.Area(data.Area);
		prob.Problem(data.Problem);
		prob.Activities(data.Activities);

    data.Goals.forEach(function(goal) {
      prob.GoalsAdd(goal);
    });
    
		self.ServicePlanProblems.push(prob);
	};
	this.ServicePlanProblemsInactive = ko.computed(function() {
		return ko.utils.arrayFilter(  // exclude any for which notes already exist
			self.ServicePlanProblems(),
			function(problem) { 
        var retval = true;
        ko.utils.arrayForEach(self.Note.Problems(), function(noteProblem) {
            if(noteProblem.ProblemId() == problem.Id() && !noteProblem._destroy) {
              retval = false;
            }
        });
        return retval; 
      } 
		);
	});  
  

	this.Note = new Note(this);
	this.NoteCreate = function(data) {
		note = self.Note;
		note.Id(data.Note.Id);
    note.pid(data.Note.pid);
    // TODO many of these obsolete WKR
		note.AssessmentId(data.Note.AssessmentId);
		note.Type(data.Note.Type);
		note.ClientId(data.Note.ClientId);
    note.pid(data.Note.pid);
		note.ClientName(data.Note.ClientName);
		note.DateWritten(data.Note.DateWritten);
		note.DateComplete(data.Note.DateComplete);
		note.CaseManagerId(data.Note.CaseManagerId);
		note.CaseManagerName(data.Note.CaseManagerName);
		note.CaseManagerSupervisorId(data.Note.CaseManagerSupervisorId);
		note.CaseManagerSupervisorName(data.Note.CaseManagerSupervisorName);
		note.ManagerNote(data.Note.ManagerNote);
		note.DateWritten(data.Note.DateWritten);
		note.Finalized.Finalized(data.Note.FinalizedDate);
    note.TCMServicePlanId(data.Note.TCMServicePlanId);
    
		data.Note.Problems.forEach(function(problem) {
			note.ProblemsAdd(problem);
		});
       
    data.Note.Activities.forEach(function(activity) {
			note.ActivitiesAdd(activity);
		});
    
	};

	this.AjaxUri = function(action) {
  
//alert('AjaxUri: id = ' + self.Configuration.NoteId);  
  
		 return self.Configuration.AjaxUri
			+ '?action=' + action
			+ '&id=' + self.Configuration.NoteId;
	}

	this.Load = function(configuration) {
		if(configuration !== undefined)
			self.Configuration = configuration;
      
		var AjaxUri = self.AjaxUri('read');

		if('TestMode' in self.Configuration && self.Configuration.TestMode === true)
			AjaxUri = AjaxUri + '&test=true';

		self.ActivityWait('Loading Data . . .');
		$.ajax({
			url: AjaxUri,
			dataType: 'json',
			success: function(response) {
				if('status' in response &&
					response.status === 'success' &&
					'data' in response
				) {
					if('User' in response.data) {
						self.User(response.data.User);
					}

					if('FunctionalTypes' in response.data) {
						self.FunctionalTypes.removeAll();
						response.data.FunctionalTypes.forEach(
							function(type) { self.FunctionalTypesAdd(type); }
						);
					}
					if('AgentsTypes' in response.data) {
						self.AgentsTypes.removeAll();
						response.data.AgentsTypes.forEach(
							function(type) { self.AgentsTypesAdd(type); }
						);
						self.AgentsTypesAdd({
							Id: "0",
							Label: "Other",
							Priority: "100",
							Disabled: "0"							
						});
					}         
          if('ActivityTypes' in response.data) {
            self.ActivityTypes.removeAll();
            response.data.ActivityTypes.forEach(
              function(type) { 
              self.ActivityTypesAdd(type); }
              );           
          }
          if('ContactTypes' in response.data) {
            self.ContactTypes.removeAll();
            response.data.ContactTypes.forEach(
              function(type) { 
              self.ContactTypesAdd(type); }
              );           
          }
          if('LocationTypes' in response.data) {
            self.LocationTypes.removeAll();
            response.data.LocationTypes.forEach(
              function(type) { 
              self.LocationTypesAdd(type); }
              );           
          }          
           if('ServicePlanProblems' in response.data) {
            self.ServicePlanProblems.removeAll();
            response.data.ServicePlanProblems.forEach(
              function(prob) { 
                self.ServicePlanProblemsAdd(prob); }
              );           
          }
       
					if('Note' in response.data) {
						self.NoteCreate(response.data);
					}
				} else {
					self.ErrorAlertSet('Note could not be retrieved from the server.');
				}
				self.ActivityWait(false);
			},
			error: function() {
				self.ErrorAlertSet('Note could not be retrieved from the server.');
				self.ActivityWait(false);
			}
		});
	};
  
  this.isNumber = function(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
  };
  
  // TODO proper field-level validations
  this.validateFormData = function () {     
    var valid = true;
    var totalMins = 0;
    ko.utils.arrayForEach(self.Note.Activities(), function(activity) {
      if(!activity._destroy) {
        totalMins += activity.DurationMins();

        if(!self.isNumber(activity.ActivityTypeId())) {
          self.ErrorAlertSet('Activity Type is required.');
          valid = false;
        }
        if(!self.isNumber(activity.ContactTypeId())) {
          self.ErrorAlertSet('Contact Type is required.');
          valid = false;
        }        
        if(!self.isNumber(activity.LocationTypeId())) {
          self.ErrorAlertSet('Location Type is required.');
          valid = false;
        }      
      }
    });
    
    if( !valid || (totalMins < 8)) {
      self.ErrorAlertSet('At least one unit (8 minutes) of activities must be present.');
      return false;
    }
    return true;
  }

	this.ConvertForSave = function() {
		self.ActivityWait('Converting Data . . .');

		viewModel = self.Note.ViewModel;
		delete self.Note.ViewModel;
		finalized = self.Note.Finalized;
		delete self.Note.Finalized;

		data = ko.toJS(self.Note);

		self.Note.ViewModel = viewModel;
		self.Note.Finalized = finalized;

		delete data.dateOffset;
		delete data.dateString;
		delete data.dateTimeString;

		delete data.DiagnosisActive;
		delete data.DiagnosisAdd;
		delete data.DiagnosisAddNew;
		delete data.DiagnosisRemove;

		delete data.ManagerNoteVisible;
		delete data.ManagerNoteVisibleToggle;

		delete data.ProblemsAdd;
		delete data.ProblemsAddNew;
		delete data.ProblemsRemove;
		delete data.ProblemsSelected;

		delete data.TypeDisplay;

		data.Problems.forEach(function(problem) {
			problem.Agents.forEach(function(agent) {
				delete agent.TypeToggle;
			});
			delete problem.AgentsActive;
			delete problem.AgentsAdd;
			delete problem.AgentsAddNew;
			delete problem.AgentsRemove;

			problem.Goals.forEach(function(goal) {
				delete goal.ObjectivesAdd;
				delete goal.ObjectivesAddNew;
				delete goal.ObjectivesRemove;
			});
			delete problem.GoalsAdd;
			delete problem.GoalsAddNew;
			delete problem.GoalsRemove;
      delete problem.Goals;
      delete problem.Agents;
			delete problem.Type;
      delete problem.Diagnosis;
      delete problem.Area;
      delete problem.Problem;
      delete problem.Activities;
     
		});
    
		self.ActivityWait(false);
		return data;
	};

	this.Save = function() {
		if(self.Note.Finalized.Finalized() == null) {   
    
      if( !self.validateFormData()) {
        return;
      }
       
			var AjaxUri = self.AjaxUri('update');

			if('TestMode' in self.Configuration && self.Configuration.TestMode === true)
				AjaxUri = AjaxUri + '&test=true';
        
      noteFlat = self.ConvertForSave();
			noteJson = ko.toJSON(noteFlat);
       
			self.ActivityWait('Saving Data . . .');

			$.post(AjaxUri,
				noteJson,
				function(response) {
					try {
						response = ko.utils.parseJson(response);
					} catch(err) {
						self.ErrorAlertSet('Notes could not be saved, server error.');
					}
					if(typeof response === 'object') {
						if('status' in response && response.status === 'success') {
							self.Exit();
						} else if ('message' in response) {
							self.ErrorAlertSet('Notes could not be saved: ' + response.message);
						} else {
							self.ErrorAlertSet('Notes could not be saved.');
						}
					}
					self.ActivityWait(false);
				}
			);
		} else {
			self.ErrorAlertSet('Notes can not be saved, document finalized.');
		}
	};

	this.Finalize = function() {
		if(self.Note.Finalized.Finalized() == null) {
			var AjaxUri = self.AjaxUri('finalize');

			self.ActivityWait('Finalizing Document . . .');
			$.ajax({
				url: AjaxUri,
				dataType: 'json',
				success: function(response) {
					if('status' in response && response.status === 'success') {
						if('finalized' in response.data) {
							if(self.Note.CaseManagerId() === response.data.finalized.CaseManagerId
								&& self.Note.CaseManagerSupervisorId() === response.data.finalized.CaseManagerSupervisorId
							) {
								self.Note.Finalized.Finalized(response.data.finalized.FinalizedDate);
							}
						}
					} else if ('message' in response) {
						self.ErrorAlertSet('Notes could not be finalized: ' + response.message);
					} else {
						self.ErrorAlertSet('Notes could not be finalized.');
					}
					self.ActivityWait(false);
				},
				error: function() {
					self.ErrorAlertSet('Notes could not be finalized, server error.');
					self.ActivityWait(false);
				}
			});
		} else {
			self.ErrorAlertSet('Notes can not be finalized, already finalized.');
		}
	}
	this.Unfinalize = function() {
		if(self.Note.Finalized.Finalized() != null) {
			var AjaxUri = self.AjaxUri('unfinalize');
			self.ActivityWait('Unfinalizing Document . . .');

			$.ajax({
				url: AjaxUri,
				dataType: 'json',
				success: function(response) {
					if('status' in response && response.status === 'success') {
						if('finalized' in response.data) {
							if(self.Note.CaseManagerId() === response.data.finalized.CaseManagerId
								&& self.Note.CaseManagerSupervisorId() === response.data.finalized.CaseManagerSupervisorId
							) {
								self.Note.Finalized.Finalized(response.data.finalized.FinalizedDate);
							}
						}
					} else if ('message' in response) {
						self.ErrorAlertSet('Notes could not be unfinalized: ' + response.message);
					} else {
						self.ErrorAlertSet('Notes could not be unfinalized.');
					}
					self.ActivityWait(false);
				},
				error: function() {
					self.ErrorAlertSet('Notes could not be unfinalized, server error.');
					self.ActivityWait(false);
				}
			});
		} else {
			self.ErrorAlertSet('Notes can not be unfinalized, not finalized.');
		}
	};

	this.Exit = function() {
		document.location.href = self.Configuration.ExitUri;
	};

	this.Print = function() {
		window.print();
	};
 
}