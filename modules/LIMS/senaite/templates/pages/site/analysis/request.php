<div class="col-12">

  <div class="card">
    <div class="card-header">Analysis Request - <?php echo $analysisRequestInformation->title; ?></div>
    <div class="card-body">
    <?php 
        if (!$procedureInformation) { ?>
          <div class='alert alert-danger'> Please assign the analysis request to a procedure order. </div>
       <?php } else { ?>
      <form method="POST">
      <?php if ($sampleInformation->workflow_info[0]->review_state === 'sample_due') { ?>
        <button type="submit" name="receiveSample" class="btn btn-success"> Receive Sample </button>
      <?php } else if($sampleInformation->workflow_info[0]->review_state === 'sample_received' && $analysisInformation->workflow_info[0]->review_state === 'sample_received') {
         ?>
        <table class="table">
          <thead>
            <tr>
              <th> Analysis </th>
              <th> Partition </th>
              <th> Result </th>
              <th> Instrument </th>
              <th> Analyst </th>
              <th> Status </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php 
                    $samplePartitionInformation = getDataFromUrl($client, $analysisInformation->SamplePartition->api_url)[0];
                    $instrumentTitle = ($analysisInformation->Instrument === '') ? getDataFromUrl($analysisInformation->Instrument->api_url)[0]->title : null;
              ?>
              <td> <?php echo $analysisInformation->title; ?></td>
              <td><?php echo $samplePartitionInformation->title; ?></td>
              <td><input class="form-control" type="text" name="result" /></td>
              <td><?php echo $instrumentTitle; ?></td>
              <td>
                  <select name="analyst" id="analyst" class="custom-select">
                    <?php foreach ($analysts as $analyst) { ?>
                      <option value="<?php echo $analyst->username;?>"> <?php echo $analyst->fullname; ?> </option>
                    <?php } ?>
                  </select>
              </td>
              <td> <?php echo $sampleInformation->workflow_info[0]->status; ?></td>

            </tr>
          </tbody>
        
        </table>      
        <button type="submit" name="submitResult" class="btn btn-primary">Submit Result</button>
        <?php } else if ($analysisInformation->workflow_info[0]->review_state === 'to_be_verified') { 
          ?>
          <table class="table">
          <thead>
            <tr>
              <th> Analysis </th>
              <th> Partition </th>
              <th> Result </th>
              <th> Status </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php 
                    $samplePartitionInformation = getDataFromUrl($client, $analysisInformation->SamplePartition->api_url)[0];
                    $instrumentTitle = ($analysisInformation->Instrument === '') ? getDataFromUrl($analysisInformation->Instrument->api_url)[0]->title : null;
              ?>
              <td> <?php echo $analysisInformation->title; ?></td>
              <td><?php echo $samplePartitionInformation->title; ?></td>
              <td><input class="form-control" type="text" name="result" value="<?php echo $analysisInformation->Result; ?>" readonly/></td>
              <td> <?php echo $sampleInformation->workflow_info[0]->status; ?></td>

            </tr>
          </tbody>
        
        </table> 
        <button type="submit" name="verifyResult" class="btn btn-primary">Verify Results</button>
        <?php } else if ($analysisInformation->workflow_info[0]->review_state === 'verified') {
          ?>
        <button name="publish" type="submit" class="btn btn-primary">Publish results</button>
        <?php } else { ?>
          
          <div class="alert alert-success"> The results for this analysis request have already been published! </div>

        <?php } ?>
      </form>
        
    
    
        <?php } ?>
    </div>
  </div>


</div>