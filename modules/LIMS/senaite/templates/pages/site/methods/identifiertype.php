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
          <th>Title</th>
          <th>Description</th>
          <th>Status</th>
        </tr>
      </thead>  
      <tbody>
          <?php foreach($identifierTypes as $identifierType) { ?>
            <tr>
              <td>#</td>
              <td><?php echo $identifierType->title; ?></td>
              <td><?php echo $identifierType->description; ?></td>
              <td><?php if ($identifierType->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
            </tr>
          <?php } ?>
      </tbody>
  
    </table>
    <a href="index.php?action=method&sact=createidentifiertype" class="btn btn-outline-success">Create new</a>
  
  
  </div>
</div>



</div>