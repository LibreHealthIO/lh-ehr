
  <div class="col-12">
    
    <div class="card">
      <div class="card-header">Sample Types</div>
      <div class="card-body">

        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Worksheet</th>
              <th>Analyst</th>
              <th>Template</th>
              <th>Samples</th>
              <th>QC Analyses</th>
              <th>Routine Analyses</th>
              <th>State</th>
            </tr>
          </thead>
            <?php foreach ($worksheets as $worksheet) {
              $worksheetData = getDataFromUrl($client, $worksheet->api_url)[0];
              $analystDetails = getDataFromUrl($client, 'users/'.$worksheet->Analyst)[0];
              $worksheetTemplateData = getDataFromUrl($client, $worksheetData->WorksheetTemplate->api_url)[0];

              ?>
            <tr>
              <td>#</td>
              <td><a href="index.php?action=worksheet&sact=worksheet&id=<?php echo $worksheetData->uid; ?>"><?php echo $worksheetData->title; ?></a></td>
              <td><?php echo $analystDetails->fullname; ?></td>
              <td><?php echo $worksheetTemplateData->title; ?></td>
              <td><?php echo count($worksheetData->Analyses); ?></td>
              <td>0</td>
              <td><?php echo count($worksheetData->Analyses); ?></td>
              <td><?php echo $worksheet->review_state; ?></td>
            </tr>
              
            <?php } ?>
          <tbody>
           
          </tbody>
        
        
        </table>

        <a href="index.php?action=worksheet&sact=createworksheets" class="btn btn-outline-success">Create new</a>



      </div>
    </div>



</div>