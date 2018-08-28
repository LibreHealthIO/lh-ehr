
  <div class="col-12">

    <div class="card">

      <div class="card-header">Instrument Locations </div>
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
              <?php foreach($instrumentLocationData as $instrumentLocation) { ?>
              <tr>
                <td>#</td>
                <td><?php echo $instrumentLocation->title; ?></td>
                <td><?php echo $instrumentLocation->description; ?></td>
                <td><?php if ($instrumentLocation->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        
        
          <a href="index.php?action=instrument&sact=createlocation" class="btn btn-outline-success">Create new</a>
        </form>
      </div>

    </div>

  </div>