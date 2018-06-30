        
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              System Dashboard
            </div>
            <div class="card-body">
              <div class="card">
                <div class="card-header">
                  Analyses
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      Assignments Pending ( <?php echo count($analysesAssignPending); ?> of <?php echo $analysesItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-info" role="progressbar" style="width: <?php echo ((count($analysesAssignPending)) / $analysesItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        
                      </div>
                    </div>
                    <div class="col">
                      Results Pending ( <?php echo count($analysesResultPending); ?> of <?php echo $analysesItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-info" role="progressbar"  style="width: <?php echo ((count($analysesResultPending)) / $analysesItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        
                      </div>
                    </div>
                    <div class="col">
                      To be verified ( <?php echo count($analysesToVerify); ?> of <?php echo $analysesItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-warning" role="progressbar" style="width: <?php echo ((count($analysesToVerify)) / $analysesItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        
                      </div>
                    </div>
                    <div class="col">
                      Verified ( <?php echo count($analysesVerified); ?> of <?php echo $analysesItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-success" role="progressbar" style="width: <?php echo ((count($analysesVerified)) / $analysesItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        
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
                        <div class="progress-bar progress-bar bg-info" role="progressbar" style="width: <?php echo ((count($arReceptionPending)) / $arItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Results Pending ( <?php echo count($arResultsPending); ?> of <?php echo $arItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-info" role="progressbar" style="width: <?php echo ((count($arResultsPending)) / $arItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      To be verified ( <?php echo count($arToVerify); ?> of <?php echo $arItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-warning" role="progressbar" style="width: <?php echo ((count($arToVerify)) / $arItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Verified ( <?php echo count($arVerified); ?> of <?php echo $arItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-success" role="progressbar" style="width: <?php echo ((count($arVerified)) / $arItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Published ( <?php echo count($arPublished); ?> of <?php echo $arItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-success" role="progressbar" style="width: <?php echo ((count($arPublished)) / $arItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
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
                        <div class="progress-bar progress-bar bg-info" role="progressbar" style="width: <?php echo ((count($worksheetResultPending)) / $worksheetItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      To be verified ( <?php echo count($worksheetToVerify); ?> of <?php echo $worksheetItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-warning" role="progressbar" style="width: <?php echo ((count($worksheetToVerify)) / $worksheetItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Verified ( <?php echo count($worksheetVerified); ?> of <?php echo $worksheetItemCount; ?> )
                      <div class="progress">
                        <div class="progress-bar progress-bar bg-success" role="progressbar" style="width: <?php echo ((count($worksheetVerified)) / $worksheetItemCount) * 100; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
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
                      Reception pending
                      <div class="progress">
                        <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Samples received
                      <div class="progress">
                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="col">
                      Samples rejected
                      <div class="progress">
                        <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        
        </div>
        <br />