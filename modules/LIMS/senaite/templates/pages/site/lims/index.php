<div class="col-12">

  <div class="card">
    <div class="card-header">Manage LIMS</div>
    <div class="card-body">

      <form class="mx-auto w-50" method="POST">
        
        <div class="form-group">
          <label for="passLifetime"> Password lifetime <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"data-placement="top" title="The number of days before a password expires. 0 disables password expiry"></i> </label>
          <input type="text" name="passLifetime" id="passLifetime" class="form-control" value="<?php echo $limsInformation->PasswordLifetime; ?>" />
        </div>

        <div class="form-check">
          <input class="form-check-input" id="selfVerify" type="checkbox" name="selfVerify" <?php if ($limsInformation->SelfVerificationEnabled) echo "checked"; ?>>
          <label class="form-check-label" for="selfVerify">
          Allow self-verification of results <span class="badge badge-danger">Required</span>
          </label>
        </div>

        <br />
      
      

        <button name="submit" type="submit" class="btn btn-primary">Update Settings</button>
      </form>
    
    
    </div>
  </div>


</div>