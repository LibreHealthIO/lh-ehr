        
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              System Dashboard
            </div>
            <div class="card-body">
              <div class="card">
                <div class="card-header">
                  Quick Links
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <a href="index.php?action=analysis&sact=createrequests" class="btn btn-primary">Create analysis requests</a>
                    </div>
                    <div class="col">
                      <a href="index.php?action=worksheet&sact=worksheets" class="btn btn-primary">Create worksheets</a>
                    </div>
                  </div>
                </div>

              </div>
              <br>
              <div class="card">
                <div class="card-header">
                  Analyses
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      Assignments Pending ( <?php echo count($analysesAssignPending); ?> of <?php echo $analysesItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-info" role="progressbar" style="width: <?php echo ((count($analysesAssignPending)) / valueOrZero($analysesItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        
                      </div>
                    </div>
                    <div class="col">
                      Results Pending ( <?php echo count($analysesResultPending); ?> of <?php echo $analysesItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-info" role="progressbar"  style="width: <?php echo ((count($analysesResultPending)) / valueOrZero($analysesItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        
                      </div>
                    </div>
                    <div class="col">
                      To be verified ( <?php echo count($analysesToVerify); ?> of <?php echo $analysesItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-warning" role="progressbar" style="width: <?php echo ((count($analysesToVerify)) / valueOrZero($analysesItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        
                      </div>
                    </div>
                    <div class="col">
                      Verified ( <?php echo count($analysesVerified); ?> of <?php echo $analysesItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-success" role="progressbar" style="width: <?php echo ((count($analysesVerified)) / valueOrZero($analysesItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <br />
              <div class="card">
                <div class="card-header">
                  Analysis Requests
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      Reception Pending (<?php echo count($arReceptionPending); ?> of <?php echo $arItemCount; ?>)
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-info" role="progressbar" style="width: <?php echo ((count($arReceptionPending)) / valueOrZero($arItemCount))* 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Results Pending ( <?php echo count($arResultsPending); ?> of <?php echo $arItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-info" role="progressbar" style="width: <?php echo ((count($arResultsPending)) / valueOrZero($arItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      To be verified ( <?php echo count($arToVerify); ?> of <?php echo $arItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-warning" role="progressbar" style="width: <?php echo ((count($arToVerify)) / valueOrZero($arItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Verified ( <?php echo count($arVerified); ?> of <?php echo $arItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-success" role="progressbar" style="width: <?php echo ((count($arVerified)) / valueOrZero($arItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Published ( <?php echo count($arPublished); ?> of <?php echo $arItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-success" role="progressbar" style="width: <?php echo ((count($arPublished)) / valueOrZero($arItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <br />
              <div class="card">
                <div class="card-header">
                  Worksheets
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      Results Pending ( <?php echo count($worksheetResultPending); ?> of <?php echo $worksheetItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-info" role="progressbar" style="width: <?php echo ((count($worksheetResultPending)) / valueOrZero($worksheetItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      To be verified ( <?php echo count($worksheetToVerify); ?> of <?php echo $worksheetItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-warning" role="progressbar" style="width: <?php echo ((count($worksheetToVerify)) / valueOrZero($worksheetItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Verified ( <?php echo count($worksheetVerified); ?> of <?php echo $worksheetItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-success" role="progressbar" style="width: <?php echo ((count($worksheetVerified)) / valueOrZero($worksheetItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <br />
              <div class="card">
                <div class="card-header">
                  Samples
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      Reception pending ( <?php echo count($sampleReceptionPending); ?> of <?php echo $sampleDataItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-info" role="progressbar" style="width: <?php echo ((count($sampleReceptionPending)) / valueOrZero($sampleDataItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Samples received ( <?php echo count($sampleReceived); ?> of <?php echo $sampleDataItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-success" role="progressbar" style="width: <?php echo ((count($sampleReceived)) / valueOrZero($sampleDataItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Samples rejected ( <?php echo count($sampleRejected); ?> of <?php echo $sampleDataItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-danger" role="progressbar" style="width: <?php echo ((count($sampleRejected)) / valueOrZero($sampleDataItemCount)) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        
        </div>
        <br />