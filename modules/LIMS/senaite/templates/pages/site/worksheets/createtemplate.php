
  <div class="col-12">

<div class="card">

  <div class="card-header">New Worksheet Template</div>
  <div class="card-body">
  
    <form class="mx-auto form w-50" method="POST" act="">
      <div class="form-group">
        <label for="title">Title <span class="badge badge-danger">Required</span></label>
        <input type="text" class="form-control" id="title" name="title" />
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea type="text" class="form-control" id="description" name="description"></textarea>
      </div>

      <div class="form-group">
        <label for="method">Method <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Restrict the available analysis services and instruments to
                   those with the selected method. In order to apply this change to the services list, you should save the change first."></i>
        </label>

        <select name="method" id="method" class="form-control">
          <option value="">Not specified</option>

          <?php foreach ($methods as $method) { ?>
          <option value="<?php echo $method->uid; ?>"> <?php echo $method->title; ?></option>
          <?php } ?>

        </select>

      </div>

      <div class="form-group">
        <label for="instrument">Instrument <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Select the preferred instrument"></i>
        </label>
        
        <select name="instrument" id="instrument" class="form-control">
          <option value="">Not specified</option>

          <?php foreach ($instruments as $instrument) { ?>
          <option value="<?php echo $instrument->uid; ?>"> <?php echo $instrument->title; ?></option>
          <?php } ?>

        </select>

      </div>

      <div class="form-group">
        <label for="analysisService">Analysis Service <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Select the preferred instrument"></i></label>

        <select name="analysisServices[]" multiple="multiple" id="analysisService" class="form-control">
          <?php foreach ($analysisServices as $analysisService) { ?>
            <option value="<?php echo $analysisService->uid;?>"><?php echo $analysisService->title; ?></option>
          <?php } ?>
        </select>
      </div>
      <h4 for="layout">Worksheet Layout <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Specify the size of the Worksheet, e.g. corresponding to a specific instrument's tray size. 
                  Then select an Analysis 'type' per Worksheet position. Where QC samples are selected, also select which Reference Sample should be used.
                   If a duplicate analysis is selected, indicate which sample position it should be a duplicate of"></i> </h4>
      <br>
      <div class="form-group">
        <table class="table"> 
          <thead>
            <tr>
              <th>Pos</th>
              <th>Analysis Type</th>
              <th>Reference Definition</th>
              <th>Dup of  <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="(only if analysis type is a duplicate)"></i></th>
            </tr>
          </thead>
          <tbody id="posContainer">
            <?php for ($i = 0; $i < 5; $i++) { ?>
              <tr>
                <td> <?php echo $i+1; ?></td>
                <td>
                  <select name="analysisTypes[]" class="form-control">
                    <option value="">No layout</option>
                    <option value="a">Analysis</option>
                    <option value="d">Duplicate</option>
                  </select>
                </td>
                <td></td>
                <td>
                  <select name="duplicateOf[]" class="form-control">
                    <option value=""></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </td>
              </tr>

            <?php } ?>
          </tbody>
        
        </table>

        
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

<script type='text/javascript'>
        function addFields(){
            // Number of inputs to create
            var number = document.getElementById("numOfPos").value;
            // Container <div> where dynamic content will be placed
            var container = document.getElementById("layoutFieldContainer");
            // Clear previous contents of the container
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
            for (i=0;i<number;i++){
                // Append a node with a random text
                container.appendChild(document.createTextNode("Member " + (i+1)));
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("td");
                input.type = "text";
                input.name = "member" + i;
                input.className += ' form-control';
                container.appendChild(input);
                // Append a line break 
                container.appendChild(document.createElement("br"));
            }
        }
</script>