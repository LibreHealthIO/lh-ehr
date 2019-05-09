<div class="col-12">


<div class="card">
  <div class="card-header">
    Methods
  </div>
  <div class="card-body">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Container</th>
          <th>Description</th>
          <th>Container Type</th>
          <th>Capacity</th>
          <th>Pre-Preserved</th>
          <th>Status</th>
        </tr>
      </thead>  
      <tbody>
          <?php foreach($containers as $container) {
                $containerInformation = json_decode($client->get($container->api_url)->getBody()->getContents())->items[0];
                $containerTypeInformation = null;
                if(!empty($containerInformation->ContainerType)) {
                  $containerTypeInformation = json_decode($client->get($containerInformation->ContainerType->api_url)->getBody()->getContents())->items[0];             
                }
          ?>
            <tr>
              <td>#</td>
              <td><?php echo $container->title; ?></td>
              <td><?php echo $container->description; ?></td>
              <td><?php if (isset($containerTypeInformation)) echo $containerTypeInformation->title; else echo "No Container Type"; ?></td>
              <td><?php echo $containerInformation->Capacity; ?></td>
              <td></td>
              <td><?php if ($container->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
            </tr>
          <?php } ?>
      </tbody>
  
    </table>
    <a href="index.php?action=method&sact=createcontainer" class="btn btn-outline-success">Create new</a>
  
  
  </div>
</div>



</div>