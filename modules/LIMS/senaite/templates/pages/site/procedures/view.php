<div class="col-12">

  <div class="card">
    <div class="card-header">Procedure Order Details</div>
    <div class="card-body">
      <?php if ($procedure['order_status'] === 'pending') {
        ?> <div class="alert alert-danger"> Please accept the procedure order. </div>
      <?php } else { 
        
        $arAssigned = sqlFetchArray(sqlStatement("SELECT * FROM lims_analysisrequests WHERE procedure_order_id = ?", [ $id ]));
        
        if (!$arAssigned) { ?>
          <form method="POST" class="w-50 mx-auto">
            
            <div class="form-group">
              <label for="analysisrequest"> Analysis Request </label>
              <select name="analysisrequest" id="analysisrequest" class="form-control"> 
                <?php foreach ($analysisRequests as $analysisRequest) { ?>
                  <option value="<?php echo $analysisRequest->uid;?>"> <?php echo $analysisRequest->title; ?> </option>
                <?php } ?>
              </select>
      
              
            </div>

            <button name="assignAR" type="submit" class="btn btn-primary">Assign Analysis Request</button>
          </form>
      <?php } else if ($procedureInformation) { ?>
        <form method="POST" class="w-50 mx-auto">
            
            <div class="form-group">
              <label for="analysisrequest"> Analysis Request </label>
              <select name="analysisrequest" id="analysisrequest" class="form-control"> 
                <?php foreach ($analysisRequests as $analysisRequest) { ?>
                  <option value="<?php echo $analysisRequest->uid;?>"> <?php echo $analysisRequest->title; ?> </option>
                <?php } ?>
              </select>
      
              
            </div>

            <button name="updateAR" type="submit" class="btn btn-primary">Update Analysis Request</button>
          </form>


      <?php   }
       
       ?>
        
        



      
    




     <?php } ?>
    </div>
  
  </div>




</div>