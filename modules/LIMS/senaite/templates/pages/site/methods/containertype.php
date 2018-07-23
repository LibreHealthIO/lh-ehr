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
          <th>Container Type</th>
          <th>Description</th>
          <th>Status</th>
        </tr>
      </thead>  
      <tbody>
          <?php foreach($containerTypes as $containerType) { ?>
            <tr>
              <td>#</td>
              <td><?php echo $containerType->title; ?></td>
              <td><?php echo $containerType->description; ?></td>
              <td><?php if ($containerType->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
            </tr>
          <?php } ?>
      </tbody>
  
    </table>
    <a href="index.php?action=method&sact=createcontainertype" class="btn btn-outline-success">Create new</a>
  
  
  </div>
</div>



</div>