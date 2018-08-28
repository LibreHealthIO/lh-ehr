
  <div class="col-12">

<div class="card">

  <div class="card-header">New Manufacturer Type</div>
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
        <label for="profileKeyword">Profile Keyword <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The profile's keyword is used to uniquely identify it in import files. 
                  It has to be unique, and it may not be the same as any Calculation Interim field ID."></i></label>
        <input type="text" class="form-control" id="profileKeyword" name="profileKeyword">
      </div>

      <div class="form-group">
        <label for="analyses">Profile Analyses <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The analyses included in this profile, grouped per category."></i> </label>
        <select multiple="multiple" name="analyses[]" id="analyses" class="form-control">
          <?php foreach ($analysesServices as $analysesService) { ?>
            <option value="<?php echo $analysesService->uid; ?>"><?php echo $analysesService->title; ?></option>
          <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label for="commercialID">Commercial ID <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The profile's commercial ID for accounting purposes."></i></label>
        <input type="text" class="form-control" name="commercialID" id="commercialID">
      </div>

      <br>



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