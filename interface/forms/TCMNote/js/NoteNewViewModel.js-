function ServicePlanNewViewModel() {
	var self = this;

	this.Configuration = {
		AjaxUri: '',
		WebRoot: '',
		ExitUri: '',
		AssessmentId: ''
	};

	this.ActivityWait = ko.observable('Loading . . .');
	this.ActivityWaitSet = function(message) {
		message = message || false;
		self.ActivityWait(message);
	};

	this.ErrorAlert = ko.observable(false);
	this.ErrorAlertSet = function(message) {
		message = message || false;
		self.ErrorAlert(message);
	};

	this.ServicePlan = {
		CaseManagerId: ko.observable(),
		CaseManagerName: ko.observable(),
		CaseManagerSupervisorId: ko.observable(),
		CaseManagerSupervisorName: ko.observable(),
		ClientId: ko.observable(),
		ClientName: ko.observable(),
		ClientBirth: ko.observable(),
		MedicaidId: ko.observable(),
		Type: ko.observable(),
		Assessment: ko.observable(),
		ServicePlan: ko.observable()
	};
	this.ServicePlan.Type.subscribe(function() {
		self.ServicePlan.Assessment(undefined);
		self.ServicePlan.ServicePlan(undefined);
	});

	this.ServicePlanCreate = function(data) {
		servicePlan = self.ServicePlan;
		servicePlan.CaseManagerId(data.CaseManagerId);
		servicePlan.CaseManagerName(data.CaseManagerName);
		servicePlan.CaseManagerSupervisorId(data.CaseManagerSupervisorId);
		servicePlan.CaseManagerSupervisorName(data.CaseManagerSupervisorName);
		servicePlan.ClientId(data.ClientId);
		servicePlan.ClientName(data.ClientName);
		servicePlan.ClientBirth(data.ClientBirth);
		servicePlan.MedicaidId(data.MedicaidId);
	};

	this.ServicePlans = ko.observableArray();
	this.ServicePlanAdd = function(data) {
		servicePlan = new ParentServicePlan();
		servicePlan.Id(data.Id);
		servicePlan.AssessmentId(data.AssessmentId);
		servicePlan.CaseManagerId(data.CaseManagerId);
		servicePlan.CaseManagerName(data.CaseManagerName);
		servicePlan.CaseManagerSupervisorId(data.CaseManagerSupervisorId);
		servicePlan.CaseManagerSupervisorName(data.CaseManagerSupervisorName);
		servicePlan.ClientId(data.ClientId);
		servicePlan.ClientName(data.ClientName);
		servicePlan.ClientBirth(data.ClientBirth);
		servicePlan.MedicaidId(data.MedicaidId);
		servicePlan.Type(data.Type);
		servicePlan.ReportDate(data.ReportDate.slice(0, 10));
		self.ServicePlans.push(servicePlan);
	};

	this.Assessments = ko.observableArray();
	this.AssessmentAdd = function(data) {
		assessment = new ParentAssessment();
		assessment.Id(data.Id);
		assessment.CaseManagerId(data.CaseManagerId);
		assessment.CaseManagerName(data.CaseManagerName);
		assessment.CaseManagerSupervisorId(data.CaseManagerSupervisorId);
		assessment.CaseManagerSupervisorName(data.CaseManagerSupervisorName);
		assessment.ClientId(data.ClientId);
		assessment.ClientName(data.ClientName);
		assessment.ClientBirth(data.ClientBirth);
		assessment.MedicaidId(data.MedicaidId);
		assessment.Type(data.Type);
		assessment.ReportDate(data.ReportDate.slice(0, 10));
		self.Assessments.push(assessment);
	};

	this.Load = function(configuration) {
		if(configuration !== undefined)
			self.Configuration = configuration;

		var AjaxUri = self.Configuration.AjaxUri + '?action=new';

		if('TestMode' in self.Configuration && self.Configuration.TestMode === true)
			AjaxUri = AjaxUri + '&test=true';

		self.ActivityWait('Loading Data . . .');
		$.getJSON(AjaxUri,
			function(response) {
				if('status' in response &&
					response.status === 'success' &&
					'data' in response &&
					'ServicePlan' in response.data
				) {
					self.ServicePlanCreate(response.data.ServicePlan);
					if('Assessments' in response.data &&
						response.data.Assessments.length > 0
					) {
						response.data.Assessments.forEach(function(assessment) {
							self.AssessmentAdd(assessment);
						});
					} else {
						self.ErrorAlertSet('No Previous Assessments could be located for this Patient.');
					}
					if('ServicePlans' in response.data &&
						response.data.ServicePlans.length > 0
					) {
						response.data.ServicePlans.forEach(function(servicePlan) {
							self.ServicePlanAdd(servicePlan);
						});
					}
				} else {
					self.ErrorAlertSet('Initial Data could not be retrieved from the server.');
				}
				self.ActivityWait(false);
			}
		);
	};

	this.ConvertForSave = function() {
		self.ActivityWait('Converting Data . . .');

		data = ko.toJS(self.ServicePlan);

		if(data.ServicePlan !== undefined) {
			data.Assessment = data.ServicePlan.AssessmentId;
			data.ServicePlan = data.ServicePlan.Id;
		} else if(data.Assessment !== undefined) {
			data.Assessment = data.Assessment.Id;
		}

		return data;
	};

	this.Save = function() {
		if(self.ServicePlan.Type() === undefined) {
			self.ErrorAlertSet('Service Plan Type must be selected.');
		} else if(self.ServicePlan.Type() === 'INITIAL' &&
			self.ServicePlan.Assessment() === undefined
		) {
			self.ErrorAlertSet('Assessment must be selected.');
		} else if(self.ServicePlan.Type() === 'UPDATE' &&
			self.ServicePlan.Assessment() === undefined &&
			self.ServicePlan.ServicePlan() === undefined
		) {
			self.ErrorAlertSet('Assessment or Service Plan must be selected.');
		} else {
			self.ErrorAlertSet();
			var AjaxUri = self.Configuration.AjaxUri
				+ '?action=create';

			if('TestMode' in self.Configuration && self.Configuration.TestMode === true)
				AjaxUri = AjaxUri + '&test=true';

			servicePlanJson = ko.toJSON(self.ConvertForSave());

			self.ActivityWait('Saving Data . . .');

			$.post(AjaxUri,
				servicePlanJson,
				function(response) {
					response = ko.utils.parseJson(response);
					if('status' in response && response.status === 'success') {
						if('record' in response.data && response.data.record > 0) {
							window.location.replace(self.Configuration.WebRoot + '/interface/patient_file/encounter/view_form.php?formname=TCMServicePlan&id=' + response.data.record);
						} else {
							self.ErrorAlertSet('The response, after creating the Service Plan, was missing crutial data.');
						}
					} else if ('message' in response) {
						self.ErrorAlertSet('Service Plan could not be created: ' + response.message);
					} else {
						self.ErrorAlertSet('Service Plan could not be created.');
					}
					self.ActivityWait(false);
				}
			);
		}
	};

	this.Cancel = function() {
		if('restoreSession' in top)
			top.restoreSession();
		window.location.replace(self.Configuration.ExitUri);
	};
}