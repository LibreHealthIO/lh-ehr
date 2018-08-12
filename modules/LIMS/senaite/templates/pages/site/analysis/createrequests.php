
  <div class="col-12">

<div class="card">

  <div class="card-header">Create Analysis Request</div>
  <div class="card-body">
  
    <form class="mx-auto form w-50" method="POST" act="">
      <div class="form-group">
        <label for="client">Client <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The assigned client of this request"></i></label>
        <select name="client" id="client" class="form-control" onchange="updateContacts(this)">
          <?php foreach ($clients as $labClient) {
            ?>
            <option value="<?php echo $labClient->uid; ?>"> <?php echo $labClient->title; ?> </option>
          <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label for="contact">Contact  <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The primary contact of this analysis request, who will receive notifications and publications via email"></i></label>
       <select name="contact" id="contact" class="form-control">
       </select>
      </div>


      <!-- optional-fields:
      cc-contacts
      cc-emails
      !-->

      <div class="form-group">
        <label for="analysisProfiles">Analysis Profiles <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Analysis profiles apply a certain set of analyses"></i></label>
        <select name="analysisProfiles" id="analysisProfiles" class="form-control">
          <?php foreach ($analysisProfiles as $analysisProfile) { ?>
            <option value="<?php echo $analysisProfile->uid; ?>"> <?php echo $analysisProfile->title; ?> </option> 
          <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label for="datepicker">Date Sampled <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The date when the sample was taken"></i></label>
        <input type="text" name="sampleDate" id="datepicker" onchange="parseDate(this)" />
      </div>


      <div class="form-group">
        <label for="sampleType">Sample Type <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Create a new sample of this type"></i></label>
        <select name="sampleType" id="sampleType" class="form-control">
        <?php foreach ($sampleTypes as $sampleType) { ?>
          <option value="<?php echo $sampleType->uid; ?>"> <?php echo $sampleType->title; ?> </option>
        <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label for="samplePoint">Sample Point <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Location where sample was taken"></i></label>
        <select name="samplePoint" id="samplePoint" class="form-control">
        <?php foreach ($samplePoints as $samplePoint) { ?>
          <option value="<?php echo $samplePoint->uid; ?>"> <?php echo $samplePoint->title; ?> </option>
        <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label for="priority">Priority</label>
        <select name="priority" id="priority" class="form-control">

                        <option value="1">Highest</option>
                        <option value="2">High</option>
                        <option value="3" selected="selected">Medium</option>
                        <option value="4">Low</option>
                        <option value="5">Lowest</option>

        </select>
      </div>

      <div class="form-group">
        <label for="environmentalConditions"> Environmental Conditions <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The environmental condition during sampling"></i> </label>
        <input type="text" name="environmentalConditions" class="form-control" id="environmentalConditions" />
      </div>


      <div class="form-group">
        <label for="adhoc">Sampled AdHoc <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Was the sample taken in non-scheduled matter, e.g. out of a recurring sampling schedule?"></i></label>
        <input type="text" name="adhoc" class="form-control" id="adhoc" />       
      </div>


      <div class="form-group">
        <label for="analyses"> Analyses <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"data-placement="top" title="Select the analyses to perform"></i> </label>
        <select multiple="multiple" name="analyses" id="analyses" class="form-control"> 
          <?php foreach ($analyses as $analysis) { ?>
            <option value="<?php echo $analysis->uid; ?> "> <?php echo $analysis->title; ?> </option>
          <?php } ?>
        </select>
      </div>


      <div class="form-group">
      
      </div>




      <button name="submit" class="btn btn-primary">Create</button>
    </form>
    <br />
    <?php if (count($errors) > 0) { ?>
      <div class="alert alert-danger w-50 mx-auto">
        <ul>
          <?php foreach($errors as $error) { ?>
            <li> <?php echo $error; ?> </li>
          <?php } ?>
        </ul>
      </div>
    <?php } ?>
    

  </div>
</div>
</div>


<script type="text/javascript">

  var contactsArray = <?php echo json_encode($contacts); ?>;

  document.addEventListener("DOMContentLoaded", function(event) { 

    var client = document.querySelector('#client');
    updateContacts(client);
  });
  

  function updateContacts(client) {

    // get the contact select element
   var contactSelect = document.getElementById('contact');
   // clear it of all current options
   contactSelect.options.length = 0;

   // get the contacts for the selected client
   var contacts = contactsArray[client.value];

   // generate an option list based on the contacts
   if (contacts) {
     contacts[0].forEach(function(element) {
       contactSelect.options[contactSelect.options.length] = new Option(element.title, element.uid, 0, 0);
     });
   } else {
     contactSelect.options[contactSelect.options.length] = new Option('Please add a contact for this client in the site setup', '', 0,0);
   }
  }


  function parseDate(date) {
    var parsedDate = Date.parse(date.value);
    parsedDate = new Date(parsedDate);
    parsedDate = parsedDate.getFullYear() + "-" + (parsedDate.getMonth()+1) + "-" + parsedDate.getDate() + " " + parsedDate.getHours() + ":" +parsedDate.getMinutes();
    date.value = parsedDate;
  }

</script>
