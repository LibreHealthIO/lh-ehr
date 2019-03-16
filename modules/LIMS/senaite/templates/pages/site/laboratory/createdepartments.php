<div class="col-12">

  <div class="card">
    <div class="card-header">Create Lab Department</div>
    <div class="card-body">

      <form method="POST" action="" class="w-50 mx-auto">
        <div class="form-group">
          <label for="title">Title <span class="badge badge-danger">Required</span></label>
          <input type="text" class="form-control" id="title" name="title" />
        </div>

        <div class="form-group">
          <label for="description"> Description </label>
          <input type="text" class="form-control" id="desc" name="desc" />
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