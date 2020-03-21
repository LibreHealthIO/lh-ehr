<div class="col-12">

  <div class="card">
    <div class="card-header">Create Lab Product</div>
    <div class="card-body">

      <form method="POST" action="" class="w-50 mx-auto">
        <div class="form-group">
          <label for="title">Title <span class="badge badge-danger">Required</span></label>
          <input type="text" class="form-control" id="title" name="title" />
        </div>

        <div class="form-group">
          <label for="description"> Description </label>
          <textarea type="text" class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="form-group">
          <label for="volume">Volume </label>
          <input type="text" class="form-control" id="volume" name="volume" />
        </div>

        <div class="form-group">
          <label for="unit"> Unit </label>
          <input type="text" class="form-control" id="unit" name="unit" />
        </div>

        <div class="form-group">
          <label for="vat"> VAT %   <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="Enter percentage value eg. 14.0"></i></label>
          <input type="text" class="form-control" id="vat" name="vat" />
        </div>

        <div class="form-group">
          <label for="pwVAT">Price excluding VAT <span class="badge badge-danger">Required</span></label>
          <input type="text" class="form-control" id="pwVAT" name="pwVAT" />
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