  <div class="col-12">
  

    <div class="card">
      <div class="card-header">New Instrument</div>
      <div class="card-body">
      
          <form class="mx-auto w-50" method="POST" act="">
            <ul class="nav nav-tabs" id="instrumentTab" role="tablist">
              <li class="nav-item">
                <a href="#default" class="nav-link active" id="default-tab" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">Default <span class="badge badge-danger">Required</span></a>
              </li>

              <li class="nav-item">
                <a href="#procedure" class="nav-link" id="procedure-tab" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">Procedures</a>
              </li>

              <li class="nav-item">
                <a href="#info" class="nav-link" id="info-tab" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">Additional Info <span class="badge badge-danger">Required</span></a>
              </li>
            </ul>
            <br />

            <div class="tab-content" id="instrumentTabContent">
            
              <div class="tab-pane fade show active" id="default" role="tabpanel" aria-labelledby="default-tab">
                <div class="form-group">
                  <label for="title">Title <span class="badge badge-danger">Required</span></label>
                  <input type="text" class="form-control" id="title" name="title" required/>
                </div>
                 
                <div class="form-group">
                  <label for="description">Asset Number <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="The instrument's ID in the lab's asset register"></i></label>
                  <input type="text" class="form-control" id="assetNum" name="assetNum">
                </div>
                
                <div class="form-group">
                  <label for="description">Description  <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="Used in item listings and search results."></i></label>
                  <textarea name="description" id="description" class="form-control"></textarea>
                </div>

                <div class="form-group">
                  <label for="instrumenttype">Instrument Type <span class="badge badge-danger">Required</span></label>
                  <select name="instrumenttype" class="custom-select" id="instrumenttype">
                    <?php foreach($instrumentTypes as $instrumentType) { ?>
                      <option value="<?php echo $instrumentType->uid; ?>"> <?php echo $instrumentType->title; ?> </option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="manufacturer">Manufacturer <span class="badge badge-danger">Required</span></label>
                  <select name="manufacturer" class="custom-select" id="manufacturer">
                    <?php foreach($manufacturers as $manufacturer) { ?>
                      <option value="<?php echo $manufacturer->uid; ?>"> <?php echo $manufacturer->title; ?> </option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="supplier">Suppliers <span class="badge badge-danger">Required</span></label>
                  <select name="supplier" class="custom-select" id="supplier">
                    <?php foreach($suppliers as $supplier) { ?>
                      <option value="<?php echo $supplier->uid; ?>"> <?php echo $supplier->title; ?> </option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="modelno">Model <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="The instrument's model number"></i></label>
                  <input type="text" class="form-control" name="modelno" id="modelno">
                </div>

                <div class="form-group">
                  <label for="serialno">Serial No <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="The serial number that uniquely identifies the instrument"></i></label>
                  <input type="text" class="form-control" name="serialno" id="serialno">
                </div>
                
                <h3> Methods </h3>
                <?php foreach ($methods as $method) { ?>
                <div class="form-check">
                  <input class="form-check-input" name="methods[]" type="checkbox" value="<?php echo $method->uid; ?>" id="defaultCheck1">
                  <label class="form-check-label" for="defaultCheck1">
                    <?php echo $method->title; ?>
                  </label>                 
                </div>
                <br>
                <?php } ?>

                <div class="form-group">
                  <label for="exportinterface">Data interface <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="Select an Export interface for this instrument."></i></label>
                  <select name="exportinterface" class="custom-select" id="exportinterface">
                    <option value="">None</option>
                    <option value="foss.fiastar.fiastar" selected="selected">FOSS - FIAStar</option>
                    <option value="lachat.quickchem">LaChat QuickChem FIA</option>
                    <option value="varian.vistapro.icp">Varian Vista-PRO ICP</option>
                  </select>
                </div>


              </div>

              <div class="tab-pane fade" id="procedure" role="tabpanel" aria-labelledby="procedure-tab">
              
                <div class="form-group">
                  <label for="calibproc">In-lab calibration procedure <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="Instructions for in-lab regular calibration routines intended for analysts"></i></label>
                  <textarea name="calibproc" id="calibproc" class="form-control"></textarea>
                </div>

                <div class="form-group">
                  <label for="preventproc">Preventive maintenance procedure <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="Instructions for regular preventive and maintenance routines intended for analysts"></i></label>
                  <textarea name="preventproc" id="preventproc" class="form-control"></textarea>
                </div>
              
              </div>

              <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
              
                <div class="form-group">
                  <label for="location">Instrument Location <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle" data-toggle="tooltip"
                  data-placement="top" title="The room and location where the instrument is installed"></i></label>
                  <select name="location" id="location" class="form-control" value="">
                    <?php foreach($instrumentLocations as $instrumentLocation) { ?>
                        <option value="<?php echo $instrumentLocation->uid; ?>"> <?php echo $instrumentLocation->title; ?> </option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group">
                    <label for="instrumentphoto">Photo image file <i class="fas fa-question-circle" data-toggle="tooltip"
                    data-placement="top" title="Photo of the instrument"></i></label>
                    <input type="file" class="form-control-file" name="instrumentphoto" id="instrumentphoto" />
                </div>

                <!-- 
                  TO-DO: Add datepicker for installation date of instrument
                !-->


                <div class="form-group">
                    <label for="installcert">Installation Certificate<i class="fas fa-question-circle" data-toggle="tooltip"
                    data-placement="top" title="Installation certificate upload"></i></label>
                    <input type="file" class="form-control-file" name="installcert" id="installcert" />
                </div>
            
            </div>


            </div>
            <button name="submit" type="submit" class="btn btn-primary">Submit</button>
          </form>
          <br />
          <?php 
          if (isset($errors) && count($errors) > 0 ) {
            foreach($errors as $error) {
              ?>
              <div class=" mx-auto w-50 alert alert-danger"><?php echo $error; ?></div>
          <?php } } ?>


      </div>
    </div>
  
  
  
  
  </div>