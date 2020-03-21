
  <div class="col-12">

<div class="card">

  <div class="card-header">Instrument Types </div>
  <div class="card-body">
    <form action="index.php?action=instrument&sact=createlocation" method="POST">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($instrumentTypeData as $instrumentType) { ?>
          <tr>
            <td>#</td>
            <td><?php echo $instrumentType->title; ?></td>
            <td><?php echo $instrumentType->description; ?></td>
            <td><?php if ($instrumentType->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    
    
      <a href="index.php?action=instrument&sact=createtype" class="btn btn-outline-success">Create new</a>
    </form>
  </div>

</div>

</div>