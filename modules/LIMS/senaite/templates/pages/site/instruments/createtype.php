
  <div class="col-12">

<div class="card">

  <div class="card-header">New Instrument Type</div>
  <div class="card-body">
  
    <form class="mx-auto form w-50" method="POST" act="">
      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" required/>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <input type="text" class="form-control" id="description" name="description">
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
</div>


</div>