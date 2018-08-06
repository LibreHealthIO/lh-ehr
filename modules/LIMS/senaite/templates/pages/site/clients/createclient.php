
  <div class="col-12">

<div class="card">

  <div class="card-header">New Client</div>
  <div class="card-body">
  
    <form class="mx-auto form w-50" method="POST" act="">

      <ul class="nav nav-tabs" id="clientTab" role="tablist">

        <li class="nav-item">
          <a href="#default" class="nav-link active" id="default-tab" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">Default <span class="badge badge-danger">Required</span></a>
        </li>

        <li class="nav-item">
          <a href="#address" class="nav-link" id="address-tab" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">Address</a>
        </li>

        <li class="nav-item">
          <a href="#bank" class="nav-link" id="bank-tab" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">Bank Details</a>
        </li>

      </ul>
      <br />
      <div class="tab-content" id="supplierTabContent">

        <div class="tab-pane fade show active" id="default" role="tabpanel" aria-labelledby="default-tab">
          <div class="form-group">
            <label for="title">Name <span class="badge badge-danger">Required</span></label>
            <input type="text" class="form-control" id="name" name="name" >
          </div>

          <div class="form-group">
            <label for="clientid">Client ID <span class="badge badge-danger">Required</span></label>
            <input type="text" class="form-control" id="clientid" name="clientid" >
          </div>

          <div class="form-group">
            <label for="vat">VAT number</label>
            <input type="text" class="form-control" id="vat" name="vat" >
          </div>
          
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone">
          </div>

          <div class="form-group">
            <label for="fax">Fax</label>
            <input type="text" class="form-control" id="fax" name="fax">
          </div>

          <div class="form-group">
            <label for="title">eMail Address</label>
            <input type="email" class="form-control" id="email" name="email"/>
          </div>
        </div>

        <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
          <h5 style>Physical Address</h5>
          <br />

          <div class="form-row">
            <div class="form-group col-6">
              <label for="phycountry">Country:</label>
              <input type="text" class="form-control" name="phycountry" />
            </div>
            <div class="form-group col-6">
              <label for="Copy">Copy From</label>
              <select class="custom-select" name="copy">
                <option value="postal">Postal Address</option>
                <option value="billing">Billing Address</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-6">
              <label for="phystate">State</label>
              <input type="text" class="form-control" name="phystate" />
            </div>
            <div class="form-group col-6">
              <label for="phydistrict">District</label>
              <input type="text" class="form-control" name="phydistrict" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-6">
              <label for="phycity">City</label>
              <input type="text" class="form-control" name="phycity">
            </div>
            <div class="form-group col-6">
              <label for="phypostal">Postal Code</label>
              <input type="text" class="form-control" name="phypostal">
            </div>
          </div>

          <div class="form-group">
            <label for="phyaddress">Address: </label>
            <textarea class="form-control" name="phyaddress" id="phyaddress"></textarea>
          </div>

          <hr />
          <h5 style>Postal Address</h5>
          <br />

          <div class="form-row">
            <div class="form-group col-6">
              <label for="postcountry">Country:</label>
              <input type="text" class="form-control" name="postcountry" />
            </div>
            <div class="form-group col-6">
              <label for="Copy">Copy From</label>
              <select class="custom-select" name="copy">
                <option value="physical">Physical Address</option>
                <option value="billing">Billing Address</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-6">
              <label for="poststate">State</label>
              <input type="text" class="form-control" name="poststate" />
            </div>
            <div class="form-group col-6">
              <label for="postdistrict">District</label>
              <input type="text" class="form-control" name="postdistrict" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-6">
              <label for="postcity">City</label>
              <input type="text" class="form-control" name="postcity">
            </div>
            <div class="form-group col-6">
              <label for="postpostal">Postal Code</label>
              <input type="text" class="form-control" name="postpostal">
            </div>
          </div>

          <div class="form-group">
            <label for="postaddress">Address: </label>
            <textarea class="form-control" name="postaddress" id="postaddress"></textarea>
          </div>
          
          <hr />

          <h5 style>Billing Address</h5>
          <br />

          <div class="form-row">
            <div class="form-group col-6">
              <label for="billcountry">Country:</label>
              <input type="text" class="form-control" name="billcountry" />
            </div>
            <div class="form-group col-6">
              <label for="Copy">Copy From</label>
              <select class="custom-select" name="copy">
                <option value="postal">Postal Address</option>
                <option value="physical">Physical Address</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-6">
              <label for="billstate">State</label>
              <input type="text" class="form-control" name="billstate" />
            </div>
            <div class="form-group col-6">
              <label for="billdistrict">District</label>
              <input type="text" class="form-control" name="billdistrict" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-6">
              <label for="billcity">City</label>
              <input type="text" class="form-control" name="billcity">
            </div>
            <div class="form-group col-6">
              <label for="billpostal">Postal Code</label>
              <input type="text" class="form-control" name="billpostal">
            </div>
          </div>

          <div class="form-group">
            <label for="billaddress">Address: </label>
            <textarea class="form-control" name="billaddress" id="billaddress"></textarea>
          </div>


        </div>

        <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
          <div class="form-group">
            <label for="title">Account Type</label>
            <input type="text" class="form-control" id="acctype" name="acctype" />
          </div>

          <div class="form-group">
            <label for="description">Account Name</label>
            <input type="text" class="form-control" id="accname" name="accname">
          </div>

          <div class="form-group">
            <label for="description">Account Number</label>
            <input type="text" class="form-control" id="accnum" name="accnum">
          </div>

          <div class="form-group">
            <label for="description">Bank Name</label>
            <input type="text" class="form-control" id="bankname" name="bankname">
          </div>
          
        </div>

        



      </div>
      
      

      <button name="submit" type="submit" class="btn btn-primary">Create</button>
    
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