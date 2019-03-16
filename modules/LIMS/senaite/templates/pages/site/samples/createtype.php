
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

      <h5>Retention Period <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The period for which un-preserved samples of this type can be kept before they expire and cannot be analysed any further"></i> </h5>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="retentionDays" placeholder="Number of Days" aria-label="Number of Days" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Days</span>
        </div>
      </div>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="retentionHours"  placeholder="Number of Hours" aria-label="Number of Days" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Hours</span>
        </div>
      </div>

      <div class="input-group w-50 mb-3">
        <input type="text" class="form-control" name="retentionMinutes"  placeholder="Number of Minutes" aria-label="Number of Days" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon2">Minutes</span>
        </div>
      </div>

      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
        <label class="form-check-label" for="defaultCheck1">
          Hazardous <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Samples of this type should be treated as hazardous"></i>
        </label>
      </div>
      <br>
      <div class="form-group">
        <label for="matrix">Sample Matrix</label>
        <select name="matrix" id="matrix" class="form-control"></select>
      </div>

      <div class="form-group">
        <label for="typePrefix">Sample Type Prefix <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Prefixes can not contain spaces."></i> </label>
        <input type="text" class="form-control" name="typePrefix" id="typePrefix">
      </div>

      <div class="form-group">
        <label for="minimumVolume">Minimum Volume<span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The minimum sample volume required for analysis eg. '10 ml' or '1 kg'."></i> </label>
        <input type="text" class="form-control" name="minimumVolume" id="minimumVolume">
      </div>

      <div class="form-group">
        <label for="containerType">Default Container Type</label>
        <select name="containerType" id="containerType" class="form-control"></select>
      </div>

      <div class="form-group">
        <label for="points">Sample Points <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="The list of sample points from which this sample type can be collected. If no sample points are selected, then all sample points are available."></i></label>
        <select name="points" id="points" class="form-control">
          <option value=""></option>
          <?php foreach ($samplePoints as $samplePoint) { ?>
            <option value="<?php echo $samplePoint->uid; ?>"><?php echo $samplePoint->title; ?></option>
          <?php
          }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label for="stickerTemplates"> Admitted sticker templates <span class="badge badge-danger">Required</span> <i class="fas fa-question-circle fa-sm" data-toggle="tooltip"
                  data-placement="top" title="Defines the stickers to use for this sample type."></i> </label>
        <br>
        <small> Admitted Sticker Templates </small>
        <select multiple="multiple" class="form-control" name="admittedStickerTemplates[]" id="">
          <option value="Code_128_1x48mm.pt">Code 128 1x48mm</option>
          <option value="Code_128_1x72mm.pt">Code 128 1x72mm</option>
          <option value="Code_39_1x54mm.pt">Code 39 1x54mm</option>
          <option value="Code_39_1x72mm.pt">Code 39 1x72mm</option>
          <option value="Code_39_2ix1i.pt">Code 39 2ix1i</option>
          <option value="Code_93_2dx38mm.pt">Code 93 2dx38mm</option>
          <option value="Code_93_2x38mm.pt">Code 93 2x38mm</option>
          <option value="QR_1x14mmx39mm.pt">QR 1x14mmx39mm</option>
        </select>
        <br>

        <small> Default small sticker </small>
        <select class="form-control" name="defaultSmallSticker" id="">
          <option value="Code_128_1x48mm.pt">Code 128 1x48mm</option>
          <option value="Code_128_1x72mm.pt">Code 128 1x72mm</option>
          <option value="Code_39_1x54mm.pt">Code 39 1x54mm</option>
          <option value="Code_39_1x72mm.pt">Code 39 1x72mm</option>
          <option value="Code_39_2ix1i.pt">Code 39 2ix1i</option>
          <option value="Code_93_2dx38mm.pt">Code 93 2dx38mm</option>
          <option value="Code_93_2x38mm.pt">Code 93 2x38mm</option>
          <option value="QR_1x14mmx39mm.pt">QR 1x14mmx39mm</option>
        </select>
        <br>

        <small> Default large sticker </small>
        <select class="form-control" name="defaultLargeSticker" id="">
          <option value="Code_128_1x48mm.pt">Code 128 1x48mm</option>
          <option value="Code_128_1x72mm.pt">Code 128 1x72mm</option>
          <option value="Code_39_1x54mm.pt">Code 39 1x54mm</option>
          <option value="Code_39_1x72mm.pt">Code 39 1x72mm</option>
          <option value="Code_39_2ix1i.pt">Code 39 2ix1i</option>
          <option value="Code_93_2dx38mm.pt">Code 93 2dx38mm</option>
          <option value="Code_93_2x38mm.pt">Code 93 2x38mm</option>
          <option value="QR_1x14mmx39mm.pt">QR 1x14mmx39mm</option>
        </select>
        <br>
      
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