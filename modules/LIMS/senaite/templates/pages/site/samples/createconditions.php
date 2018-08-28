<div class="col-12">

  <div class="card">
    <div class="card-header">Create Sample Condition</div>
    <div class="card-body">


    <form method="POST" action="" class="w-50 mx-auto" > 
      <div class="form-group">
        <label for="title"> Title <span class="badge badge-danger">Required</span> </label>
        <input type="text" name="title" id="title" class="form-control" />
      </div>

      <div class="form-group">
        <label for="description">Description  </label>
        <textarea name="description" id="description" class="form-control" ></textarea>
      </div>


      <button type="submit" name="submit" class="btn btn-primary"> Submit </button>

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
  
  </div>



</div>