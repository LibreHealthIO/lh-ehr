
  <div class="col-12">
    
    <div class="card">
      <div class="card-header">Sample Types</div>
      <div class="card-body">

        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Profile</th>
              <th>Department</th>
              <th>Profile Key</th>
              <th>Active</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($analysisProfiles as $analysisProfile) {
                  $profileInformation = getDataFromUrl($client, $analysisProfile->api_url)[0];
            ?>
            <tr>
              <td>#</td>
              <td><?php echo $analysisProfile->title; ?></td>
              <td><?php echo $analysisProfile->description; ?></td>
              <td><?php echo $profileInformation->ProfileKey; ?></td>
              <td><?php if ($profileInformation->review_state === 'active') echo "Active"; else echo "Dormant"; ?></td>
            
            </tr>                
            <?php } ?>
          
          </tbody>
        
        
        </table>

        <a href="index.php?action=analysis&sact=createprofiles" class="btn btn-outline-success">Create new</a>



      </div>
    </div>



</div>