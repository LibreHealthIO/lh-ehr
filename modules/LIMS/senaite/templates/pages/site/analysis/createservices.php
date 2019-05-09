
  <div class="col-12">

<div class="card">

  <div class="card-header">New Analysis Service</div>
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

      <h5>Identifier Information <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Select identifiers by which this object can be referenced."></i></h5>
      <div class="form-group">
        <label for="identifierType">Identifier Type <span class="badge badge-danger">Required</span></label>
        <select name="identifierType" id="identifierType" class="form-control">
          <?php foreach ($identifierTypes as $identifierType) { ?>
            <option value="<?php echo $identifierType->uid; ?>"><?php echo $identifierType->title; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="identifier">Identifier <span class="badge badge-danger">Required</span></label>
        <input type="text" class="form-control" name="identifier" id="identifier">
      </div>
      <div class="form-group">
        <label for="identifierDescription">Description</label>
        <input type="text" class="form-control" name="identifierDescription" id="identifierDescription">
      </div>
      <hr>

      <div class="form-group">  
        <label for="category">Analysis Category <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The category the analysis service belongs to"></i></label>
        <select name="category" id="category" class="form-control">
          <?php foreach ($analysisCategories as $analysisCategory) {
            ?> <option value="<?php echo $analysisCategory->uid; ?>"><?php echo $analysisCategory->title; ?> </option>
          <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label for="shortTitle">Short Title <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="If text is entered here, it is used instead of the title when the service is listed in column headings. "></i></label>
        <input type="text" name="shortTitle" id="shortTitle" class="form-control">
      </div>

      <div class="form-group">
        <label for="sortKey">Sort Key <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Float value from 0.0 - 1000.0 indicating the sort order. Duplicate values are ordered alphabetically."></i></label>
        <input type="text" name="sortKey" class="form-control" id="sortKey">
      </div>

      <div class="form-group">
        <label for="commercialID">Commercial ID <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The service's commercial ID for accounting purposes"></i></label>
        <input type="text" name="commercialID" class="form-control" id="commercialID">
      </div>

      <div class="form-group">
        <label for="protocolID"> Protocol ID  <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The service's analytical protocol ID"></i></label>
        <input type="text" name="protocolID" class="form-control" id="protocolID">
      </div>

      <div class="form-group">
        <label for="unit"> Unit <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The measurement units for this analysis service' results, e.g. mg/l, ppm, dB, mV, etc."></i> </label>
        <input type="text" name="unit" class="form-control" id="unit">
      </div>

      <div class="form-group">
        <label for="analysisKeyword"> Analysis Keyword <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The unique keyword used to identify the analysis service in import files of bulk AR requests and results imports from instruments.
                   It is also used to identify dependent analysis services in user defined results calculations"></i> <span class="badge badge-danger">Required</span> </label>
        <input type="text" name="analysisKeyword" class="form-control" id="analysisKeyword">
      </div>

      <label for="pointOfCapture"> Point Of Capture <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The unique keyword used to identify the analysis service in import files of bulk AR requests and results imports from instruments.
                   It is also used to identify dependent analysis services in user defined results calculations"></i> <span class="badge badge-danger">Required</span>  </label>
      <div class="form-check">
        <input type="radio" name="pointOfCapture" value="lab" class="form-check-input" id="pointOfCapture" /> 
        <label for="pointOfCapture">Lab</label>
      </div>

      <div class="form-check">
        <input type="radio" name="pointOfCapture" value="field" class="form-check-input" id="pointOfCapture" /> 
        <label for="pointOfCapture">Field</label>
      </div>

      <div class="form-group">
        <label for="price"> Price (excluding VAT) </label>
        <input type="text" name="price" class="form-control" id="price" />
      </div>

      <div class="form-group">
        <label for="bulkPrice"> Bulk price (excluding VAT) <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The price charged per analysis for clients who qualify for bulk discounts"></i> </label>
        <input type="text" name="bulkPrice" class="form-control" id="bulkPrice" />
      </div>

      <div class="form-group">
        <label for="VAT"> VAT % <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Enter percentage value eg. 14.0"></i></label>
        <input type="text" name="VAT" class="form-control" id="VAT" />
      </div>

      <div class="form-group">
        <label for="labDepartment">Department <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The laboratory department"></i></label>
        <select name="department" id="labDepartment" class="form-control">
            <?php foreach ($labDepartments as $labDepartment) {
              ?> <option value="<?php echo $labDepartment->uid; ?>"> <?php echo $labDepartment->title; ?></option>
            <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label for="remarks">Remarks</label>
        <input type="text" class="form-control" id="remarks" name="remarks"/>
      </div>

      <div class="form-group">
        <label for="exponentialFormatPrecision">Exponential format precision <span class="badge badge-danger">Required</span> </label>
        <input type="text" class="form-control" id="exponentialFormatPrecision" name="exponentialFormatPrecision" />
      </div>

      <div class="form-group">
        <label for="lowerDetectionLimit">Lower Detection Limit (LDL) <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The Lower Detection Limit is the lowest value to which the measured parameter can be measured using the specified testing methodology.
                   Results entered which are less than this value will be reported as < LDL"></i></label>
        <input type="text" class="form-control" placeholder="0.0" id="lowerDetectionLimit" name="lowerDetectionLimit" />
      </div>

      <div class="form-group">
        <label for="upperDetectionLimit">Upper Detection Limit (LDL) <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The Upper Detection Limit is the highest value to which the measured parameter can be measured using the specified testing methodology.
                   Results entered which are greater than this value will be reported as > UDL"></i></label>
        <input type="text" class="form-control" placeholder="10000" id="upperDetectionLimit" name="upperDetectionLimit" />
      </div>
      









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