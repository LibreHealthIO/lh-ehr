  <div class="col-12">

    <div class="card">
      <div class="card-header">
        Create new method
      </div>
      <div class="card-body">
        <form action="" class="w-50 mx-auto" method="POST">

          <div class="form-group">
            <label for="title"> Title <span class="badge badge-danger">Required</span> </label>
            <input type="text" class="form-control" id="title" name="title" /> 
          </div>

          <div class="form-group">
            <label for="description">Description <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="Describes the method in layman terms. This information is made available to lab clients"></i></label>
            <textarea name="description" id="description" class="form-control"></textarea>
          </div>

          <div class="form-group">
            <label for="methodid">Method ID <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="Define an identifier code for the method. It must be unique."></i></label>
            <input type="text" class="form-control" id="methodid" name="methodid" />
          </div>
        
          <div class="form-group">
            <label for="instructions">Instructions <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="Technical description and instructions intended for analysts"></i></label>
            <textarea name="instructions" id="instructions" class="form-control"></textarea>
          </div>

          <div class="form-check">  
            <input type="checkbox" class="form-check-input" id="manualentry" name="manualentry" />
            <label for="manualentry" class="form-check-label">
              Manual entry of results <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="The results for the Analysis Services that use this method can be set manually"></i>
            </label>
          </div>
          <br>
          <div class="form-check">  
            <input type="checkbox" class="form-check-input" id="accredited" name="accredited" />
            <label for="accredited" class="form-check-label">
              Accredited <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="Check if the method has been accredited"></i>
            </label>
          </div>
          <br>


          <button type="submit" name="submit" class="btn btn-primary">Submit</button>

        
        </form>
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
    </div>
  
  </div>