
  <div class="col-12">
    
    <div class="card">
      <div class="card-header">Sample Matrices</div>
      <div class="card-body">

        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Sample Point</th>
              <th>Description</th>
              <th>Active</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($sampleMatrices as $sampleMatrix) { ?>
              <tr>
                <td># </td>
                <td><?php echo $sampleMatrix->title; ?></td>
                <td><?php echo $sampleMatrix->description; ?></td>
                <td><?php echo "Active"; ?></td>
              </tr>
           <?php } ?>
          </tbody>
        
        
        </table>

        <a href="index.php?action=sample&sact=creatematrices" class="btn btn-outline-success">Create new</a>



      </div>
    </div>



</div>