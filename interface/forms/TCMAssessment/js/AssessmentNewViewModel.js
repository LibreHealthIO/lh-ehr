function AssessmentNewViewModel() {
	var self = this;

	this.Configuration = {
		AjaxUri: '',
		WebRoot: '',
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

	this.Assessment = {
		CaseManagerId: ko.observable(),
		CaseManagerName: ko.observable(),
		CaseManagerSupervisorId: ko.observable(),
		CaseManagerSupervisorName: ko.observable(),
		ClientId: ko.observable(),
		ClientName: ko.observable(),
		ClientBirth: ko.observable(),
		MedicaidId: ko.observable(),
		Type: ko.observable()
	};

	this.AssessmentCreate = function(data) {
		assessment = self.Assessment;
		assessment.CaseManagerId(data.CaseManagerId);
		assessment.CaseManagerName(data.CaseManagerName);
		assessment.CaseManagerSupervisorId(data.CaseManagerSupervisorId);
		assessment.CaseManagerSupervisorName(data.CaseManagerSupervisorName);
		assessment.ClientId(data.ClientId);
		assessment.ClientName(data.ClientName);
		assessment.ClientBirth(data.ClientBirth);
		assessment.MedicaidId(data.MedicaidId);
	}

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
					'data' in response
				) {
					self.AssessmentCreate(response.data);
				} else {
					alert('Initial Data could not be retrieved from the server.');
				}
				self.ActivityWait(false);
			}
		);
	};

	this.Save = function() {
		if(self.Assessment.Type() === undefined) {
			self.ErrorAlertSet('Assessment Type must be selected.');
		} else {
			self.ErrorAlertSet();
			var AjaxUri = self.Configuration.AjaxUri
				+ '?action=create';

			if('TestMode' in self.Configuration && self.Configuration.TestMode === true)
				AjaxUri = AjaxUri + '&test=true';

			assessmentJson = ko.toJSON(self.Assessment);

			self.ActivityWait('Saving Data . . .');

			$.post(AjaxUri,
				assessmentJson,
				function(response) {
					response = ko.utils.parseJson(response);
					if('status' in response && response.status === 'success') {
						if(response.data.record === null || response.data.record.length === 0) {
							self.ErrorAlertSet('The response, after creating the Assessment, was missing crutial data.');
						} else {
							window.location.replace(self.Configuration.WebRoot + '/interface/patient_file/encounter/view_form.php?formname=TCMAssessment&id=' + response.data.record);
						}
					} else if ('message' in response) {
						self.ErrorAlertSet('Assessment could not be created: ' + response.message);
					} else {
						self.ErrorAlertSet('Assessment could not be created.');
					}
					self.ActivityWait(false);
				}
			);
		}
	};

	this.Cancel = function() {
		top.restoreSession();
	};
}