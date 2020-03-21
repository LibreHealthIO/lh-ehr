
  <div class="col-12">
    
    <div class="card">
      <div class="card-header">Sample Points</div>
      <div class="card-body">

        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Sample Point</th>
              <th>Description</th>
              <th>Owner</th>
              <th>Sample Types</th>
              <th>Active</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($samplePoints as $samplePoint) {
                  $samplePointInformation = getDataFromUrl($client, $samplePoint->api_url)[0]; 
                  $sampleTypeIDs = [];
                  $sampleTypes = [];
                  if ($samplePointInformation->SampleTypes) {
                    foreach ($samplePointInformation->SampleTypes as $samplePointTypeIDs) {
                      $sampleTypeIDs[] = $samplePointTypeIDs->uid;
                    }
                    foreach ($sampleTypeIDs as $sampleTypeID) {
                      $sampleTypeInformation = getDataFromUrl($client, 'sampletype/'.$sampleTypeID)[0];
                      $sampleTypes[] = $sampleTypeInformation->title;
                    }
                  }
                  
            ?>
            <tr>
              <td>#</td>
              <td><?php echo $samplePointInformation->title; ?></td>
              <td><?php echo $samplePointInformation->description; ?></td>
              <td>temp name</td>
              <td><?php 
              foreach ($sampleTypes as $key => $sampleType) {
                  echo $sampleType;
                  if ($key != count($sampleTypes)-1) {
                    echo ",";
                  }
              } ?></td>
              <td><?php if ($samplePoint->review_state === 'active') echo "Active"; else echo "Dormant"; ?></td>
            
            </tr>                
            <?php } ?>
          
          </tbody>
        
        
        </table>

        <a href="index.php?action=sample&sact=createpoints" class="btn btn-outline-success">Create new</a>



      </div>
    </div>



</div>