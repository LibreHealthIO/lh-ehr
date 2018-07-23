
  <div class="col-12">

<div class="card">

  <div class="card-header">New Sample Type</div>
  <div class="card-body">
  
    <form class="mx-auto form w-50" method="POST" act="">
      <div class="form-group">
        <label for="title">Title <span class="badge badge-danger">Required</span></label>
        <input type="text" class="form-control" id="title" name="title" required/>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <input type="text" class="form-control" id="description" name="description">
      </div>

      <h5>Sampling Frequency <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="If a sample is taken periodically at this sample point, enter frequency here, e.g. weekly"></i> </h5>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="sampleDays" placeholder="Number of Days" aria-label="Number of Days" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Days</span>
        </div>
      </div>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="sampleHours"  placeholder="Number of Hours" aria-label="Number of Days" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Hours</span>
        </div>
      </div>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="sampleMinutes"  placeholder="Number of Minutes" aria-label="Number of Days" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Minutes</span>
        </div>
      </div>

      <div class="form-group">
        <label for="sampleTypes">Sample Types</label>
        <select multiple="multiple" name="sampleTypes[]" id="sampleTypes" class="form-control">
          <?php foreach ($sampleTypes as $sampleType) { ?>
            <option value="<?php echo $sampleType->uid; ?>"> <?php echo $sampleType->title; ?> </option>
          <?php } ?>
        </select>
      </div>
            
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="Composite" name="composite">
        <label class="form-check-label" for="Composite">
          Composite <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Check this box if the samples taken at this point are 'composite' and put together from more than one sub sample
                  , e.g. several surface samples from a dam mixed together to be a representative sample for the dam. The default, unchecked, indicates 'grab' samples"></i>
        </label>
      </div>
      <br>

      <h5>Latitude <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Enter the Sample Point's latitude in degrees 0-90, minutes 0-59, seconds 0-59 and N/S indicator"></i> </h5>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="latDeg" placeholder="0" aria-label="Degrees" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Degrees</span>
        </div>
      </div>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="latMin"  placeholder="0" aria-label="Minutes" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Minutes</span>
        </div>
      </div>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="latSec"  placeholder="0" aria-label="Seconds" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Seconds</span>
        </div>
      </div>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="latBear"  placeholder="0" aria-label="Bearing" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Bearing</span>
        </div>
      </div>

      <h5>Longitude <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Enter the Sample Point's longitutde in degrees 0-90, minutes 0-59, seconds 0-59 and E/W indicator"></i> </h5>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="longDeg" placeholder="0" aria-label="Degrees" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Degrees</span>
        </div>
      </div>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="longMin"  placeholder="0" aria-label="Minutes" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Minutes</span>
        </div>
      </div>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="longSec"  placeholder="0" aria-label="Seconds" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Seconds</span>
        </div>
      </div>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="longBear"  placeholder="0" aria-label="Bearing" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Bearing</span>
        </div>
      </div>

      <div class="form-group">
        <label for="elevation">Elevation <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The height or depth at which the sample has to be taken"></i></label>
        <input type="text" class="form-control" id="elevation" name="elevation"/>
      </div>

      <br>





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