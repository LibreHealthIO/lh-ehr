<div class="col-12">

  <f class="card">
    <div class="card-header">Create new client contact</div>
    <div class="card-body">
      
    <form class="mx-auto form w-50" method="POST" act="">
          <div class="form-group">
            <label for="contactTitle"> Title <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"data-placement="top" title="Greeting title eg. Mr, Mrs, Dr"></i> </label>
            <input type="text" name="contactTitle" id="contactTitle" class="form-control" />
          </div>

          <div class="form-group">
            <label for="contactFirstName"> First Name <span class="badge badge-danger">Required</span> </label>
            <input type="text" name="contactFirstName" id="contactFirstName" class="form-control" />
          </div>

          <div class="form-group">
            <label for="contactMiddleInitial"> Middle Initial </label>
            <input type="text" name="contactMiddleInitial" id="contactMiddleInitial" class="form-control" />
          </div>

          <div class="form-group">
            <label for="client"> Client <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"data-placement="top" title="Pick the client this contact belongs to"></i> </label>
            <select name="client" id="client" class="form-control">
              <?php foreach ($labClients as $labClient) { ?>
                <option value="<?php echo $labClient->uid;?>"> <?php echo $labClient->title; ?> </option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="contactMiddleName"> Middle name </label>
            <input type="text" name="contactMiddleName" id="contactMiddleName" class="form-control"/>
          </div>

          <div class="form-group">
            <label for="contactSurname"> Surname <span class="badge badge-danger">Required</span> </label>
            <input type="text" name="contactSurname" id="contactSurname" class="form-control"/>
          </div>

          <div class="form-group">
            <label for="contactJobTitle"> Job title </label>
            <input type="text" name="contactJobTitle" id="contactJobTitle" class="form-control"/>
          </div>

          <div class="form-group">
            <label for="contactDepartment"> Department </label>
            <input type="text" name="contactDepartment" id="contactDepartment" class="form-control" />
          </div>

          <div class="form-group">
            <label for="contactEmailAddress"> Email Address </label>
            <input type="text" name="contactEmailAddress" id="contactEmailAddress"  class="form-control"/>
          </div>

          <div class="form-group">
            <label for="contactPhoneBusiness"> Phone (business) </label>
            <input type="text" name="contactPhoneBusiness" id="contactPhoneBusiness" class="form-control"/>
          </div>
    
          <button name="submit" class="btn btn-primary">Create</button>
    </form>
    <br />
    <?php if (count($errors) > 0) { ?>
      <div class="alert alert-danger w-50 mx-auto">
        <ul>
          <?php foreach($errors as $error) { ?>
            <li> <?php echo $error; ?> </li>
          <?php } ?>
        </ul>
      </div>
    <?php } ?>
    





</div>

