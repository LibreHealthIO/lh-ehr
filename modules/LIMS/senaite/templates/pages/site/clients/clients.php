
  <div class="col-12">
    
      <div class="card">
        <div class="card-header">Clients</div>
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Client ID</th>
                <th>Email Address</th>
                <th>Phone</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($limsClients as $limsClient) { 
                $limsClientData = json_decode($client->get($limsClient->api_url)->getBody()->getContents())->items[0]; ?>
              <tr>
                <td>#</td>
                <td><?php echo $limsClientData->Name; ?></td>
                <td><?php echo $limsClientData->ClientID; ?></td> 
                <td><?php echo $limsClientData->EmailAddress; ?></td>
                <td><?php echo $limsClientData->Phone; ?></td>
                <td><?php if ($limsClient->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          
          </table>

          <a href="index.php?action=client&sact=createclient" class="btn btn-outline-success">Create new</a>
        </div>
      </div>
  
  </div>