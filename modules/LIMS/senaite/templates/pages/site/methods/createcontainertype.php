<div class="col-12">

<div class="card">
  <div class="card-header">
    Create new container type
  </div>
  <div class="card-body">
    <form action="" class="w-50 mx-auto" method="POST">

      <div class="form-group">
        <label for="title"> Title <span class="badge badge-danger">Required</span> </label>
        <input type="text" class="form-control" id="title" name="title" /> 
      </div>

      <div class="form-group">
        <label for="description">Description <i class="fas fa-question-circle" data-toggle="tooltip"
              data-placement="top" title="Used in item listings and search results."></i></label>
        <textarea name="description" id="description" class="form-control"></textarea>
      </div>



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