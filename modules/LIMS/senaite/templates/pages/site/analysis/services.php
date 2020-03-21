
  <div class="col-12">
    
    <div class="card">
      <div class="card-header">Sample Types</div>
      <div class="card-body">

        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Service</th>
              <th>Category</th>
              <th>Keyword</th>
              <th>Methods</th>
              <th>Unit</th>
              <th>Price</th>
              <th>Calculation</th>
              <th>Sort Key</th>
              <th>Status</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($analysisServices as $analysisService) {
                  
                  $analysisServiceInformation = getDataFromUrl($client, $analysisService->api_url)[0];
                  if (isset($analysisServiceInformation->Category->api_url)) {
                    $categoryInformation = getDataFromUrl($client, $analysisServiceInformation->Category->api_url)[0];
                  }
                  
                  $methods = [];
                  $calculations = [];
                  if ($analysisServiceInformation->Method) {
                    foreach ($analysisServiceInformation->Method as $method) {
                      $methods[] = $method->title;
                    }
                  }
                  if ($analysisServiceInformation->Calculation) {
                    foreach ($analysisServiceInformation->Calculation as $calculation) {
                      $calculations[] = $calculation->title;
                    }
                  }

            ?>
            <tr>
              <td>#</td>
              <td><?php echo $analysisService->title; ?></td>
              <td><?php if (isset($categoryInformation)) echo $categoryInformation->title; else echo "No category found!"; ?></td>
              <td><?php echo $analysisServiceInformation->Keyword; ?></td>
              <td><?php foreach ($methods as $index => $method) {
                        echo $method; 
                        if ($index != count($methods) - 1) 
                          echo ",";
                  }?>
              </td>
              <td><?php echo $analysisServiceInformation->Unit; ?> </td>
              <td><?php echo $analysisServiceInformation->Price; ?> </td>
              <td><?php foreach ($calculations as $index => $calculation) {
                        echo $calculation; 
                        if ($index != count($calculations) - 1) 
                          echo ",";
                  }?>
              </td>
              <td><?php echo $analysisServiceInformation->SortKey; ?> </td>
              <td><?php if ($analysisService->review_state === 'active') echo "Active"; else echo "Dormant"; ?></td>
            
            </tr>                
            <?php } ?>
          
          </tbody>
        
        
        </table>

        <a href="index.php?action=analysis&sact=createservices" class="btn btn-outline-success">Create new</a>



      </div>
    </div>



</div>