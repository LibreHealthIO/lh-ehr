<?php
require_once("../../interface/globals.php");
require_once("$srcdir/headers.inc.php");
require_once("includes/admin_helper.php");

// get bootstrap
  call_required_libraries(array("jquery-min-3-1-1","bootstrap"));


// if update category button is used
if($_POST['updateCat'] == 1) {
  // call for creation/updation of category
  createUpdateCategory($_SESSION['category'], $_POST['catName'], $_POST['catCol'], 
    $_POST['catDes'], $_POST['catType'], $_POST['catDur'], 
    $_POST['catAllDay'], $_SESSION['category'] == "__NEW__");
}

// if delete category button is used
if($_POST['deleteCat'] == 1) {
  deleteCategory($_SESSION['category']);
  $_SESSION['category'] = "__NEW__";
}

// retrieve available categories from admin_helper
$categories = getCategories();

// assign POSTed category to session
if(isset($_POST['category'])) {
  $_SESSION['category'] = $_POST['category'];
}

// get category info if we have a category selected
$selectedCat = array();
if(isset($_SESSION['category']) && $_SESSION['category']!=NULL) {
  foreach($categories as $category) {
    if($category['pc_catid'] == $_SESSION['category']) {
      $selectedCat = $category;
      break;
    }
  }
}

?>

<html>
<head>
  <title><?php echo xlt('Calendar Administration');?></title>
  <link href="css/admin.css" rel="stylesheet" />
  <script src="<?php echo $GLOBALS['standard_js_path']; ?>jquery-min-3-1-1/index.js"></script>
</head>
<body>
  <div class="container-fluid">
    <div class="row block cat-body">
      
      <form class="form-horizontal" id="cat-select-form" action="" method="POST">
        <div class="form-group col-xs-12">
          <label for="category"><?php echo xlt('Select Category');?></label>
          <select class="form-control" id="category" name="category" required>
           <option value="__NEW__" select="selected"><?php echo xlt('New Category');?></option>
            <?php 
            if (empty($_SESSION['category'])) {
                error_log("nul?: ".$_SESSION['category'], 0);
                $_SESSION['category'] = "__NEW__";
            }
            foreach($categories as $category) {
              $catid = $category['pc_catid'];
              $catname = $category['pc_catname'];
              echo "<option value='$catid'"; 
                if($catid == $_SESSION['category']) {
                  echo " selected";
                }
              echo ">$catname</option>";
            }
            ?>
          </select>
        </div>
      </form>
      
    </div>
  
    <div class="row block">
      <div class="cat-head">
        <?php
          // if our selected category info array isn't empty
          if(!empty($selectedCat)) {
            echo "<h4>". $selectedCat['pc_catname'] . "</h4>";
          } else {
            echo "<h4>" . xlt('New Category') . "</h4>";
          }
        ?>
      </div>
      <form class="form-horizontal cat-body" action="" method="POST">
        
        <div class="row">
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catName"><?php echo xlt('Name');?></label>
            <div class="col-md-10">
              <input type="text" class="form-control" id="catName" name="catName" placeholder="<?php echo xlt('Category Name');?>" required 
              <?php if(!empty($selectedCat)) echo " value=\"" . $selectedCat['pc_catname'] . "\"";  ?> >
            </div>
          </div>
          
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catCol"><?php echo xlt('Color');?></label>
            <div class="col-md-10">
              <input type="color" class="form-control" id="catCol" name="catCol" placeholder="<?php echo xlt('Select a Color');?>" required
              <?php if(!empty($selectedCat)) echo " value=\"" . $selectedCat['pc_catcolor'] . "\"";
              else echo "value=\"#e5e5e5\"";  ?> >
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catDur"><?php echo xlt('Duration (Minutes)');?></label>
            <div class="col-md-10">
              <input type="number" class="form-control" id="catDur" name="catDur" placeholder="<?php echo xlt('Minutes');?>" required
              <?php if(!empty($selectedCat)) echo " value=\"" . $selectedCat['pc_duration']/60 . "\"" ?> 
              <?php if(!empty($selectedCat) && $selectedCat['pc_end_all_day'] == 1) echo " disabled"; ?> >
            </div>
          </div>
          
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catAllDay"><?php echo xlt('All Day');?></label>
            <div class="col-md-10">
              <div class="radio-inline">
                <label><input type="radio" id="allDay1" name="catAllDay" value="1" required
                <?php if(!empty($selectedCat) && $selectedCat['pc_end_all_day'] == 1) echo " checked"; ?>><?php echo xlt('Yes');?></label>
              </div>
              <div class="radio-inline">
                <label><input type="radio" id="allDay0" name="catAllDay" value="0" required
                <?php if(!empty($selectedCat) && $selectedCat['pc_end_all_day'] == 0) echo " checked"; ?>><?php echo xlt('No');?></label>
              </div>
            </div>
          </div>
        </div>
      
        <div class="row">
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catDes"><?php echo xlt('Description');?></label>
            <div class="col-md-10">
              <textarea rows="2" class="form-control" id="catDes" name="catDes" placeholder="<?php echo xlt('Category Description');?>" 
              required><?php if(!empty($selectedCat)) echo $selectedCat['pc_catdesc']; ?></textarea>
            </div>
          </div>
          
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catType"><?php echo xlt('Type');?></label>
            <div class="col-md-10">
              <select class="form-control" id="catType" name="catType" required>
                <option value="0"><?php echo xlt('Patient');?></option>
                <option value="1" <?php if(!empty($selectedCat) && $selectedCat['pc_cattype'] == 1) echo "selected" ?>><?php echo xlt('Provider');?></option>
                <option value="2" <?php if(!empty($selectedCat) && $selectedCat['pc_cattype'] == 2) echo "selected" ?>><?php echo xlt('Clinic');?></option>
              </select>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="form-group col-xs-9"> 
            <div class=col-md-12>
              <button type="submit" class="btn btn-primary cp-positive" name="updateCat" value="1"><?php echo xlt('Update');?></button>
            </div>
          </div>
          
          <!-- show delete button only if you select a category -->
          <?php if(!empty($selectedCat)) { ?>
          <div class="form-group col-xs-3">
            <div class=col-md-12>
              <button type="submit" class="btn btn-danger cp-negative" name="deleteCat" value="1" 
              onclick="return confirm('<?php echo xlt('Are you sure you want to do that?');?>');"><?php echo xlt('Delete');?></button>
            </div>
          </div>
          <!-- end if -->
          <?php } ?>
          
        </div>
        
      </form>
    </div>
  </div>
    
    <script>
    // selector for category submits the form
    $("#category").change(function() { $('#cat-select-form').submit(); });
    
    // change card color according to selected color
    $('.cat-head').css("background", $("#catCol").val());
    $("#catCol").change(function() { $('.cat-head').css("background", $("#catCol").val());});
    
    // disable duration if all day is selected
    $('#allDay1').click(function () {
        if ($(this).is(':checked')) {
          $("#catDur").prop("disabled", true);
        }
    });
    
    // enale duration if not all day
    $('#allDay0').click(function () {
        if ($(this).is(':checked')) {
          $("#catDur").prop("disabled", false);  
        }
    });
    </script>
    
</body>
</html>
