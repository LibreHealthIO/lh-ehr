<div class="col-12">

  <div class="card">
    <div class="card-header">Create Lab Contact</div>
    <div class="card-body">

      <form method="POST" action="" class="w-50 mx-auto">
        <div class="form-group">
          <label for="title">Title <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="Greeting title eg. Mr, Mrs, Dr"></i></label>
          <input type="text" class="form-control" id="title" name="title" />
        </div>

        <div class="form-group">
          <label for="fname">First name <span class="badge badge-danger">Required</span> </label>
          <input type="text" class="form-control" id="fname" name="fname" />
        </div>

        <div class="form-group">
          <label for="midinitial"> Middle Initial </label>
          <input type="text" class="form-control" id="midinitial" name="midinitial" />
        </div>

        <div class="form-group">
          <label for="mname"> Middle name </label>
          <input type="text" class="form-control" id="mname" name="mname" />
        </div>

        <div class="form-group">
          <label for="sname">Surname <span class="badge badge-danger">Required</span> </label>
          <input type="text" class="form-control" id="sname" name="sname" />
        </div>

        <div class="form-group">
          <label for="jtitle"> Job title </label>
          <input type="text" class="form-control" id="jtitle" name="jtitle" />
        </div>

        <div class="form-group">
          <label for="email"> Email Address </label>
          <input type="text" class="form-control" id="email" name="email" />
        </div>

        <div class="form-group">
          <label for="bphone"> Phone (business) </label>
          <input type="text" class="form-control" id="bphone" name="bphone" />
        </div>

        <div class="form-group">
          <label for="bfax"> Fax (business) </label>
          <input type="text" class="form-control" id="bfax" name="bfax" />
        </div>

        <div class="form-group">
          <label for="hphone"> Phone (home) </label>
          <input type="text" class="form-control" id="hphone" name="hphone" />
        </div>

        <div class="form-group">
          <label for="mphone"> Phone (mobile) </label>
          <input type="text" class="form-control" id="mphone" name="mphone" />
        </div>

         <div class="form-group">
            <label for="signature">Signature <i class="fas fa-question-circle" data-toggle="tooltip"
                    data-placement="top" title="Upload a scanned signature to be used on printed analysis results reports. Ideal size is 250 pixels wide by 150 high"></i></label>
            <input type="file" class="form-control-file" name="signature" id="signature" />
        </div>

        <!-- TO-DO: Departments, Default Departments !-->

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