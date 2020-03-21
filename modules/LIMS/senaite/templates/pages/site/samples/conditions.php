
  <div class="col-12">
    
    <div class="card">
      <div class="card-header">Sample Conditions</div>
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
            <?php foreach ($sampleConditions as $sampleCondition) { ?>
              <tr>
                <td># </td>
                <td><?php echo $sampleCondition->title; ?></td>
                <td><?php echo $sampleCondition->description; ?></td>
                <td><?php echo "Active"; ?></td>
              </tr>
           <?php } ?>
          </tbody>
        
        
        </table>

        <a href="index.php?action=sample&sact=createconditions" class="btn btn-outline-success">Create new</a>



      </div>
    </div>



</div>