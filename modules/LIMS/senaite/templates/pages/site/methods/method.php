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
                  <th>Method</th>
                  <th>Description</th>
                  <th>Instrument</th>
                  <th>Status</th>
                </tr>
              </thead>  
              <tbody>
                  <?php foreach($methods as $method) { ?>
                    <tr>
                      <td>#</td>
                      <td><?php echo $method->title; ?></td>
                      <td><?php echo $method->description; ?></td>
                      <td><?php echo "Instruments Here"; ?></td>
                      <td><?php if ($method->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
                    </tr>
                  <?php } ?>
              </tbody>
          
            </table>
            <a href="index.php?action=method&sact=createmethod" class="btn btn-outline-success">Create new</a>
          
          
          </div>
        </div>
  
  
  
  </div>