<div class="col-12">

  <div class="card">
    <div class="card-header">Laboratory Information</div>
    <div class="card-body">

      <form method="POST" action="" class="w-50 mx-auto">
        <div class="form-group">
          <label for="title">Name <span class="badge badge-danger">Required</span></label>
          <input type="text" class="form-control" id="name" name="name" />
        </div>

        <div class="form-group">
          <label for="description"> VAT number </label>
          <input type="text" class="form-control" id="vat" name="vat" />
        </div>

        <div class="form-group">
          <label for="description"> Phone </label>
          <input type="text" class="form-control" id="phone" name="phone" />
        </div>

        <div class="form-group">
          <label for="description"> Fax </label>
          <input type="text" class="form-control" id="fax" name="fax" />
        </div>

        <div class="form-group">
          <label for="supervisor"> Fax  <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="Supervisor of the lab"></i> </label>
          <select name="supervisor" class="custom-select">
            <?php foreach ($labContacts as $contact) { ?>
              
          </select>
        </div>



        <!-- TO-DO: Manager !-->

        <button name="submit" type="submit" class="btn btn-primary">Submit</button>
      
      </form>
      <br />
      <?php 
          if (isset($errors) && count($errors) > 0 ) {
            foreach($errors as $error) {
              ?>
              <div class=" mx-auto w-50 alert alert-danger"><?php echo $error; ?></div>
          <?php } } ?>
    
    </div>
  
  </div>


</div>