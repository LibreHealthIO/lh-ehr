
  <div class="col-12">
    
    <div class="card">
      <div class="card-header">Sample Types</div>
      <div class="card-body">

        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Sample Type</th>
              <th>Description</th>
              <th>Hazardous</th>
              <th>Retention Period</th>
              <th>Sample Matrix</th>
              <th>Default Container</th>
              <th>Sample Points</th>
              <th>Prefix</th>
              <th>Minimum Volume</th>
              <th>Active</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($sampleTypes as $sampleType) {
                  $sampleInformation = json_decode($client->get($sampleType->api_url)->getBody()->getContents())->items[0];   
                  $samplePoints = [];
                  $samplePointsAPI = [];
                  if ($sampleInformation->SamplePoints) {

                    foreach ($sampleInformation->SamplePoints as $pointInfo) {
                      $samplePointsAPI[] = $pointInfo->api_url;
                    } 
                    $samplePoints = [];
                    foreach ($samplePointsAPI as $samplePointAPI) {
                      $info = json_decode($client->get($samplePointAPI)->getBody()->getContents())->items[0];
                      $samplePoints[] = $info->title;
                    }
                  }
            ?>
            <tr>
              <td>#</td>
              <td><?php echo $sampleInformation->title; ?></td>
              <td><?php echo $sampleInformation->description; ?></td>
              <td><?php echo $sampleInformation->Hazardous; ?></td>
              <td><?php echo $sampleInformation->RetentionPeriod->days." days ".$sampleInformation->RetentionPeriod->hours." hours ".$sampleInformation->RetentionPeriod->minutes." minutes "; ?></td>
              <td><?php echo $sampleInformation->SampleMatrix; ?></td>
              <td><?php echo $sampleInformation->ContainerType; ?></td>
              <td>
              <?php if (count($samplePoints) > 0) {
                      foreach($samplePoints as $samplePoint) {
                        echo $samplePoint; 
                        if ($samplePoint != $samplePoints[count($samplePoints)-1]) {
                          echo ",";
                        }
                      } 
                    } 
              ?>
              </td>
              <td><?php echo $sampleInformation->Prefix; ?></td>
              <td><?php echo $sampleInformation->MinimumVolume; ?></td>
              <td><?php if ($sampleType->review_state === 'active') echo "Active"; else echo "Dormant"; ?></td>
            
            </tr>                
            <?php } ?>
          
          </tbody>
        
        
        </table>

        <a href="index.php?action=sample&sact=createtype" class="btn btn-outline-success">Create new</a>



      </div>
    </div>



</div>