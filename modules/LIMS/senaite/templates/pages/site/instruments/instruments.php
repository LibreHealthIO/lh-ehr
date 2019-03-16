
  <div class="col-12">
    
      <div class="card">
        <div class="card-header">Instruments</div>
        <div class="card-body">

          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Instrument</th>
                <th>Type</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Expiry Date</th>
                <th>Methods</th>
                <th>Status</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach($instrumentData as $instrument) {
                    $instrumentInformation = json_decode($client->get($instrument->api_url)->getBody()->getContents())->items[0];    
              ?>
              <tr>
                <td>#</td>
                <td><?php echo $instrumentInformation->title; ?></td>
                <td><?php echo $instrumentInformation->InstrumentTypeName; ?></td>
                <td><?php echo $instrumentInformation->ManufacturerName; ?></td>
                <td><?php echo $instrumentInformation->Model; ?></td>
                <td><?php echo $instrumentInformation->expires; ?></td>
                <td><?php echo "no methods"; ?></td>
                <td><?php if ($instrument->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
              
              </tr>                
              <?php } ?>
            
            </tbody>
          
          
          </table>

          <a href="index.php?action=instrument&sact=createinstrument" class="btn btn-outline-success">Create new</a>



        </div>
      </div>
  
  
  
  </div>