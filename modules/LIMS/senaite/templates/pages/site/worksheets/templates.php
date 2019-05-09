
  <div class="col-12">
    
    <div class="card">
      <div class="card-header">Sample Types</div>
      <div class="card-body">

        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Description</th>
              <th>Instrument</th>
              <th>Active</th>
            </tr>
          </thead>
            <?php foreach ($worksheetTemplates as $worksheetTemplate) {
              $templateData = getDataFromUrl($client, $worksheetTemplate->api_url)[0];

              ?>
            <tr>
              <td>#</td>
              <td> <?php echo $worksheetTemplate->title; ?> </td>
              <td> <?php echo $worksheetTemplate->description; ?></td>
              <td> <?php echo $templateData->InstrumentTitle; ?></td>
              <td> <?php if ($worksheetTemplate->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
            </tr>
              
            <?php } ?>
          <tbody>
           
          </tbody>
        
        
        </table>

        <a href="index.php?action=worksheet&sact=createtemplate" class="btn btn-outline-success">Create new</a>



      </div>
    </div>



</div>