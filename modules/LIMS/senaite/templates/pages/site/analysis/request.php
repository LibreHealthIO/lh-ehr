<div class="col-12">

  <div class="card">
    <div class="card-header">Analysis Request - <?php echo $analysisRequestInformation->title; ?></div>
    <div class="card-body">
    
    
      <form method="POST">
      <?php if ($analysisRequestInformation->DateReceived === null) { ?>
        <button type="submit" name="receiveSample" class="btn btn-success"> Receive Sample </button>
      <?php } ?>
      </form>
    
    
    
    </div>
  </div>


</div>