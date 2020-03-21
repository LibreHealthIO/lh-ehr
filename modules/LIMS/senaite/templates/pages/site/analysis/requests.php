
  <div class="col-12">
    
    <div class="card">
      <div class="card-header">Analysis Requests</div>
      <div class="card-body">

        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Priority</th>
              <th>Progress</th>
              <th>Request ID</th>
              <th>Creator</th>
              <th>Sample</th>
              <th>Client</th>
              <th>Sample Type</th>
              <th>Date Sampled</th>
              <th>Date Verified</th>
              <th>State</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($analysisRequests as $analysisRequest) {
                  $arInformation = getDataFromUrl($client, $analysisRequest->api_url)[0];
                  $progress = 0;
                  $state = '';
                  switch ($analysisRequest->review_state) {
                    case 'sample_due':
                      $progress = 0;
                      $state = 'Sample Due';
                    break;
                    case 'sample_received':
                      $progress = 0;
                      $state = 'Sample Received';
                    break;
                    case 'to_be_verified': 
                      $progress = 50;
                      $state = 'To be verified';
                    break;
                    case 'received':
                      $progress = 75;
                      $state = 'Received';
                    break;
                    case 'verified':
                      $state = 'Verified';
                    case 'published':
                      $progress = 100;
                      $state = 'Published';
                    break;
                  }
                  $dateSampled = new DateTime($arInformation->DateSampled);
                  $dateVerified = new DateTime($arInformation->DateReceived);
                  
            ?>
            
              <tr>
                <td>#</td>
                <td><?php echo $arInformation->Priority; ?></td>
                <td>  
                  <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $progress; ?>%" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $progress; ?>%</div>
                  </div>
                </td>
                <td><a href="index.php?action=analysis&sact=request&id=<?php echo $arInformation->uid; ?>"><?php echo $arInformation->id; ?></a></td>
                <td><?php echo $arInformation->CreatorFullName; ?></td>
                <td><?php echo $arInformation->SampleID; ?></td>
                <td><?php echo $arInformation->ClientTitle; ?></td>
                <td><?php echo $arInformation->SampleTypeTitle; ?></td>
                <td><?php echo $dateSampled->format('Y-m-d');  ?></td>
                <td><?php echo $dateVerified->format('Y-m-d'); ?></td>
                <td><?php echo $state; ?></td>
              </tr>     
                   
            <?php } ?>
          
          </tbody>
        
        
        </table>

        <a href="index.php?action=analysis&sact=createrequests" class="btn btn-outline-success">Create new</a>



      </div>
    </div>



</div>