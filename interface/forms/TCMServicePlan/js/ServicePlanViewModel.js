function ServicePlanViewModel() {
	var self = this;

	this.Configuration = {
		AjaxUri: '',
		WebRoot: '',
		ExitUri: '',
		ServicePlanId: ''
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

	this.AxisOptions = [
		{ Value: 'i', Label: 'Axis I'},
		{ Value: 'ii', Label: 'Axis II'},
		{ Value: 'iii', Label: 'Axis III'},
		{ Value: 'iv', Label: 'Axis IV'},
		{ Value: 'v', Label: 'Axis V'}
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
	
	this.DiagnosisCgas = ko.observable();	
	this.DiagnosisAgent = ko.observable();	
	
	
	this.ServicePlan = new ServicePlan(this);
	this.ServicePlanCreate = function(data) {
		servicePlan = self.ServicePlan;
		servicePlan.Id(data.Id);
		servicePlan.AssessmentId(data.AssessmentId);
		servicePlan.ServicePlanId(data.ServicePlanId);
		servicePlan.Type(data.Type);
		servicePlan.ClientId(data.ClientId);
		servicePlan.ClientName(data.ClientName);
		servicePlan.ClientBirth(data.ClientBirth);
		servicePlan.MedicaidId(data.MedicaidId);
		servicePlan.DateWritten(data.DateWritten);
		servicePlan.DateComplete(data.DateComplete);
		servicePlan.CaseManagerId(data.CaseManagerId);
		servicePlan.CaseManagerName(data.CaseManagerName);
		servicePlan.CaseManagerSupervisorId(data.CaseManagerSupervisorId);
		servicePlan.CaseManagerSupervisorName(data.CaseManagerSupervisorName);
		servicePlan.DiagnosisSource(data.DiagnosisSource);
		servicePlan.CurrentServiceNeeds(data.CurrentServiceNeeds);
		servicePlan.DischargePlan(data.DischargePlan);
		servicePlan.ManagerNote(data.ManagerNote);
		servicePlan.Finalized.Finalized(data.FinalizedDate);
		servicePlan.DiagnosisCgas(data.DiagnosisCgas);
		servicePlan.DiagnosisAgent(data.DiagnosisAgent);
		data.Diagnosis.forEach(function(diagnosis) {
			servicePlan.DiagnosisAdd(diagnosis);
		});
		while(servicePlan.Diagnosis().length < 5) {
			servicePlan.DiagnosisAddNew();
		};
		data.Problems.forEach(function(problem) {
			servicePlan.ProblemsAdd(problem);
		});
	};

	this.AjaxUri = function(action) {
		 return self.Configuration.AjaxUri
			+ '?action=' + action
			+ '&id=' + self.Configuration.ServicePlanId;
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
					if('ServicePlan' in response.data) {
						self.ServicePlanCreate(response.data.ServicePlan);
					}
				} else {
					self.ErrorAlertSet('Service Plan could not be retrieved from the server.');
				}
				self.ActivityWait(false);
			},
			error: function() {
				self.ErrorAlertSet('Service Plan could not be retrieved from the server.');
				self.ActivityWait(false);
			}
		});
	};

	this.ConvertForSave = function() {
		self.ActivityWait('Converting Data . . .');

		viewModel = self.ServicePlan.ViewModel;
		delete self.ServicePlan.ViewModel;
		finalized = self.ServicePlan.Finalized;
		delete self.ServicePlan.Finalized;

		data = ko.toJS(self.ServicePlan);

		self.ServicePlan.ViewModel = viewModel;
		self.ServicePlan.Finalized = finalized;

		delete data.dateOffset;
		delete data.dateString;
		delete data.dateTimeString;

		delete data.DiagnosisActive;
		delete data.DiagnosisAdd;
		delete data.DiagnosisAddNew;
		delete data.DiagnosisRemove;
		data.DiagnosisNew = [];
		data.Diagnosis.forEach(function(obj) {
			if(obj.Id !== undefined
				|| !(obj.Axis === undefined
					|| obj.Axis === '')
				|| !(obj.Code === undefined
					|| obj.Code === '')
				|| !(obj.Description === undefined
					|| obj.Description === '')
			)
				data.DiagnosisNew.push(obj);
		});
		data.Diagnosis = data.DiagnosisNew;
		delete data.DiagnosisNew;

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

			delete problem.Type;
		});
		self.ActivityWait(false);
		return data;
	};

	this.Save = function() {
		if(self.ServicePlan.Finalized.Finalized() == null) {
			var AjaxUri = self.AjaxUri('update');

			if('TestMode' in self.Configuration && self.Configuration.TestMode === true)
				AjaxUri = AjaxUri + '&test=true';
			servicePlanJson = ko.toJSON(self.ConvertForSave());
			self.ActivityWait('Saving Data . . .');

			$.post(AjaxUri,
				servicePlanJson,
				function(response) {
					try {
						response = ko.utils.parseJson(response);
					} catch(err) {
						self.ErrorAlertSet('Service Plan could not be saved, server error.');
					}
					if(typeof response === 'object') {
						if('status' in response && response.status === 'success') {
							self.Exit();
						} else if ('message' in response) {
							self.ErrorAlertSet('Service Plan could not be saved: ' + response.message);
						} else {
							self.ErrorAlertSet('Service Plan could not be saved.');
						}
					}
					self.ActivityWait(false);
				}
			);
		} else {
			self.ErrorAlertSet('Service Plan cannot be saved, document finalized.');
		}
	};

	this.Finalize = function() {
		if(self.ServicePlan.Finalized.Finalized() == null) {
			var AjaxUri = self.AjaxUri('finalize');

			self.ActivityWait('Finalizing Document . . .');
			$.ajax({
				url: AjaxUri,
				dataType: 'json',
				success: function(response) {
					if('status' in response && response.status === 'success') {
						if('finalized' in response.data) {
							if(self.ServicePlan.CaseManagerId() === response.data.finalized.CaseManagerId
								&& self.ServicePlan.CaseManagerSupervisorId() === response.data.finalized.CaseManagerSupervisorId
							) {
								self.ServicePlan.Finalized.Finalized(response.data.finalized.FinalizedDate);
							}
						}
					} else if ('message' in response) {
						self.ErrorAlertSet('Service Plan could not be finalized: ' + response.message);
					} else {
						self.ErrorAlertSet('Service Plan could not be finalized.');
					}
					self.ActivityWait(false);
				},
				error: function() {
					self.ErrorAlertSet('Service Plan could not be finalized, server error.');
					self.ActivityWait(false);
				}
			});
		} else {
			self.ErrorAlertSet('Service Plan cannot be finalized, already finalized.');
		}
	}
	this.Unfinalize = function() {
		if(self.ServicePlan.Finalized.Finalized() != null) {
			var AjaxUri = self.AjaxUri('unfinalize');
			self.ActivityWait('Unfinalizing Document . . .');

			$.ajax({
				url: AjaxUri,
				dataType: 'json',
				success: function(response) {
					if('status' in response && response.status === 'success') {
						if('finalized' in response.data) {
							if(self.ServicePlan.CaseManagerId() === response.data.finalized.CaseManagerId
								&& self.ServicePlan.CaseManagerSupervisorId() === response.data.finalized.CaseManagerSupervisorId
							) {
								self.ServicePlan.Finalized.Finalized(response.data.finalized.FinalizedDate);
							}
						}
					} else if ('message' in response) {
						self.ErrorAlertSet('Service Plan could not be unfinalized: ' + response.message);
					} else {
						self.ErrorAlertSet('Service Plan could not be unfinalized.');
					}
					self.ActivityWait(false);
				},
				error: function() {
					self.ErrorAlertSet('Service Plan could not be unfinalized, server error.');
					self.ActivityWait(false);
				}
			});
		} else {
			self.ErrorAlertSet('Service Plan cannot be unfinalized, not finalized.');
		}
	};

	this.Exit = function() {
		document.location.href = self.Configuration.ExitUri;
	};

	this.Print = function() {
		window.print();
	};
}
