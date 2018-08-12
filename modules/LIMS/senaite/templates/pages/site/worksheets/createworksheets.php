
  <div class="col-12">

<div class="card">

  <div class="card-header">New Worksheet</div>
  <div class="card-body">
  
    <form class="mx-auto form w-50" method="POST" act="">
      <div class="form-group">
        <label for="analyst">Analyst <span class="badge badge-danger">Required</span></label>
        <select name="analyst" id="analyst" class="custom-select">
          <?php foreach ($analysts as $analyst) { ?>
            <option value="<?php echo $analyst->username;?>"> <?php echo $analyst->fullname; ?> </option>
          <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label for="template">Select Template</label>
        <select name="template" id="template" class="custom-select">
          <?php foreach ($worksheetTemplates as $worksheetTemplate) { ?>
            <option value="<?php echo $worksheetTemplate->uid;?>"> <?php echo $worksheetTemplate->title; ?> </option>
          <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label for="description">Select Instrument</label>
        <select name="instrument" id="instrument" class="custom-select">
          <?php foreach ($instruments as $instrument) { ?>
            <option value="<?php echo $instrument->uid;?>"> <?php echo $instrument->title; ?> </option>
          <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label for="analyses"> Analyses <span class="badge badge-danger">Required</span>  </label>
        <table class="table w-100">
          <thead>
            <tr>
              <th> <input class="form-control" type="checkbox" id="check-all" onchange="checkAll()"/></th>
              <th>Priority</th>
              <th>Client</th>
              <th>Order</th>
              <th>Request ID</th>
              <th>Category</th>
              <th>Analysis</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($analyses as $analysis) {
                  $analysisData = getDataFromUrl($client, $analysis->api_url)[0];
                  $aRequestData = getDataFromUrl($client, $analysisData->parent_url)[0];
                  $aCategoryData = getDataFromUrl($client, $analysisData->Category->api_url)[0];
                  $clientData = getDataFromUrl($client, $aRequestData->Client->api_url)[0];
              ?>
              <tr> 
                <td><input class="form-control" value="<?php echo $analysis->uid; ?>" type="checkbox" name="analyses[]" id="checkbox-<?php echo $analysis->uid;?>" name="<?php echo $analysis->uid; ?>" /></td>
                <td><?php echo $aRequestData->Priority; ?></td>
                <td><?php echo $clientData->title; ?></td>
                <td><?php echo $aRequestData->ClientOrderNumber; ?></td>
                <td><?php echo $analysisData->parent_id; ?></td>
                <td><?php echo $aCategoryData->title; ?></td>
                <td><?php echo $analysisData->title; ?></td>
              </tr>
           <?php  } ?>
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

<script type="text/javascript">

  function checkAll() {
    var checkAll = document.getElementById('check-all');
    if (checkAll.checked == true) {
      var elements = document.querySelectorAll('*[id^="checkbox-"]');
      elements.forEach(function(element) {
        element.checked = true;
      });
    } else {
      var elements = document.querySelectorAll('*[id^="checkbox-"]');
      elements.forEach(function(element) {
        element.checked = false;
      });
    }
    
  }

</script>

