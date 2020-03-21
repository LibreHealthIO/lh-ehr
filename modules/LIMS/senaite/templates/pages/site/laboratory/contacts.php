
  <div class="col-12">
    <div class="card">
      <div class="card-header"> Lab Contacts </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Department</th>
              <th>Phone</th>
              <th>Fax</th>
              <th>Mobile Phone</th>
              <th>Email Address</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($contacts as $contact) {
              $contactDetails = json_decode($client->get($contact->api_url)->getBody()->getContents())->items[0];
            ?> 
            <tr>
              <td>#</td>
              <td><?php echo $contact->title; ?></td>
              <td><!-- To-Do: Schema related data !--></td>
              <td><?php echo $contactDetails->BusinessPhone; ?></td>
              <td><?php echo $contactDetails->BusinessFax; ?></td>
              <td><?php echo $contactDetails->MobilePhone; ?></td>
              <td><?php echo $contactDetails->EmailAddress; ?></td>
              <td><?php if ($contact->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
            </tr>
          <?php } ?>
          
          </tbody>
        </table>
        <a href="index.php?action=laboratory&sact=createcontacts" class="btn btn-outline-success">Create new</a>

      </div>
    </div>
  
  </div>