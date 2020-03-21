
  <div class="col-12">

<div class="card">

  <div class="card-header">New Analysis Category</div>
  <div class="card-body">
  
    <form class="mx-auto form w-50" method="POST" act="">
      <div class="form-group">
        <label for="title">Title <span class="badge badge-danger">Required</span></label>
        <input type="text" class="form-control" id="title" name="title" required/>
      </div>

      <div class="form-group">
        <label for="description">Description <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Used in item listings and search results."></i></label>
        <textarea type="text" class="form-control" id="description" name="description"></textarea>
      </div>

      <div class="form-group">
        <label for="comments">Comments <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="To be displayed below each Analysis Category section on results reports."></i></label>
        <textarea type="text" class="form-control" id="comments" name="comments"></textarea>
      </div>

      <div class="form-group">
        <label for="department">Department <span class="badge badge-danger">Required</span> </label>
        <select name="department" id="department" class="form-control">
          <?php foreach ($labDepartments as $labDepartment) { ?>
            <option value="<?php echo $labDepartment->uid; ?>"><?php echo $labDepartment->title; ?></option>
          <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label for="sortKey">Sort Key <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Float value from 0.0 - 1000.0 indicating the sort order. Duplicate values are ordered alphabetically."></i></label>
        <input type="text" class="form-control" name="sortKey" id="sortKey">
      </div>
      

      <div class="form-group">
      
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