<?php
require_once("../../interface/globals.php");
require_once("$srcdir/headers.inc.php");
require_once("includes/admin_helper.php");

// get bootstrap
call_required_libraries(true, false, false, false);

$categories = getCategories();

?>

<html>
<head>
  <link href="css/admin.css" rel="stylesheet" />
</head>
<body>
  <div class="container-fluid">
    
    <div class="row block cat-body">
      <form class="form-horizontal">
        <div class="form-group col-xs-12">
          <label for="category">Select Category</label>
          <select class="form-control" id="category" name="category" required>
            <option value="__NEW__">New Category</option>
            <?php 
            foreach($categories as $category) {
              $catid = $category['pc_catid'];
              $catname = $category['pc_catname'];
              echo "<option value='$catid'>$catname</option>";
            }
            ?>
          </select>
        </div>
      </form>
    </div>
  
    <div class="row block">
      <div class="cat-head">
        <h4>New Category</h4>
      </div>
      <form class="form-horizontal cat-body"  action="" method="POST">
        
        <div class="row">
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catName">Name</label>
            <div class="col-md-10">
              <input type="text" class="form-control" id="catName" name="catName" placeholder="Category Name" required>
            </div>
          </div>
          
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catCol">Color</label>
            <div class="col-md-10">
              <input type="color" class="form-control" id="catCol" name="catcol" placeholder="Select a Color" value="#e5e5e5" required>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catDur">Duration</label>
            <div class="col-md-10">
              <input type="text" class="form-control" id="catDur" name="catDur" placeholder="HH:MM" required>
            </div>
          </div>
          
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catAllDay">All Day</label>
            <div class="col-md-10">
              <div class="radio-inline">
                <label><input type="radio" name="catAllDay" value="yes" required>Yes</label>
              </div>
              <div class="radio-inline">
                <label><input type="radio" name="catAllDay" value="no" required>No</label>
              </div>
            </div>
          </div>
        </div>
      
        <div class="row">
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catDes">Description</label>
            <div class="col-md-10">
              <textarea rows="2" class="form-control" id="catDes" name="catDes" placeholder="Category Description" required></textarea>
            </div>
          </div>
          
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catType">Type</label>
            <div class="col-md-10">
              <select class="form-control" id="catType" name="catType" required>
                <option value="0">Patient</option>
                <option value="1">Provider</option>
              </select>
            </div>
          </div>
        </div>
        
        <div class="form-group"> 
          <div class="col-sm-12">
            <button type="submit" class="btn btn-default" name="submit" value="1">Submit</button>
          </div>
        </div>
        
      </form>
    </div>
  </div>
    
</body>
