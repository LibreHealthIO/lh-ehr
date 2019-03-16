
  <div class="col-12">
    
    <div class="card">
      <div class="card-header">Sample Types</div>
      <div class="card-body">

        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Category</th>
              <th>Department</th>
              <th>Sort Key</th>
              <th>Active</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($categories as $category) {
                  $categoryInformation = getDataFromUrl($client, $category->api_url)[0];
                  $departmentInformation = getDataFromUrl($client, $categoryInformation->Department->api_url)[0];
            ?>
            <tr>
              <td>#</td>
              <td><?php echo $category->title; ?></td>
              <td><?php echo $departmentInformation->title; ?></td>
              <td><?php echo $categoryInformation->SortKey; ?></td>
              <td><?php if ($category->review_state === 'active') echo "Active"; else echo "Dormant"; ?></td>
            
            </tr>                
            <?php } ?>
          
          </tbody>
        
        
        </table>

        <a href="index.php?action=analysis&sact=createcategories" class="btn btn-outline-success">Create new</a>



      </div>
    </div>



</div>