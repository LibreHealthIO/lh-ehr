
  <div class="col-12">
    <div class="card">
      <div class="card-header"> Lab Departments </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Description</th>
              <th>Manager</th>
              <th>Manager Phone</th>
              <th>Manager Email</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($departments as $department) {
              $departmentDetails = json_decode($client->get($department->api_url)->getBody()->getContents())->items[0];
            ?> 
            <tr>
              <td>#</td>
              <td><?php echo $department->title; ?></td>
              <td><?php echo $department->description; ?></td>
              <td><?php echo $departmentDetails->ManagerName; ?></td>
              <td><?php echo $departmentDetails->ManagerPhone; ?></td>
              <td><?php echo $departmentDetails->ManagerEmail; ?></td>
              <td><?php if ($department->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
            </tr>
          <?php } ?>
          
          </tbody>
        </table>
        <a href="index.php?action=laboratory&sact=createdepartments" class="btn btn-outline-success">Create new</a>

      </div>
    </div>
  
  </div>