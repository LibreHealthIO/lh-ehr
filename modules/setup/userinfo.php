<?php
/**
 * Created by PhpStorm.
 * User: rachmann mua <muarachmann@gmail.com>
 * Date: 5/21/18
 * Time: 2:32 AM
 */
?>

<?php
session_start();
require_once("includes/shared.inc.php");
require_once("includes/settings.inc.php");
require_once("includes/functions.inc.php");
require_once("includes/header.inc.php");


//==========================
// HANDLING FORM SUBMISSION
//==========================


?>

    <div class="card">
        <div class="pull-right" id="pics-row">
                <div class="iu-pics hidden">
                    <span id="closeProfilePic" class="fa fa-times-circle-o pics-close"></span>
                    <img src=""  id="profile-img" class="img-responsive profile-pics" />
                    <span class="pic-name">Profile pic</span>
                </div>
                <div class="iu-pics hidden">
                    <span id="closeFacilityPic" class="fa fa-times-circle-o pics-close"></span>
                    <img src=""  id="facility-img" class="img-responsive profile-pics" />
                    <span class="pic-name">Facility logo</span>
                </div>
                <p class="clearfix"></p>
        </div>
        <h2><strong>User Information</strong></h2>

        <i>Fields with (<span class="arial librehealth-color"> *</span>) are mandatory</i>
        <p class="clearfix"></p>
        <h4 class="librehealth-color text-right"><small>(Please provide information about yourself below) </small>PERSONAL INFO</h4>
        <p class="clearfix"></p>
        <form id="userForm" method="post" action="index3.php">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2"><label>First Name <span class="arial librehealth-color"> *</span> :</label></div>
                    <div class="col-md-9"><input value="Administrator" name="iufname" placeholder="Enter First Name" type="text" class="form-control" required></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Last Name <span class="arial librehealth-color"> *</span> :</label></div>
                    <div class="col-md-9"><input type="text" value="Administrator"  placeholder="Enter Last Name" name="iulname" class="form-control" required></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Middle Name:</label></div>
                    <div class="col-md-9"><input type="text" value="" placeholder="Enter Middle Name" name="iumname" class="form-control"></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Email <span class="arial librehealth-color"> *</span> :</label></div>
                    <div class="col-md-9"><input type="email" value="" placeholder="Email address" name="iuemail" class="form-control" required></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Phone <span class="arial librehealth-color"> *</span> :</label></div>
                    <div class="col-md-9"><input type="text" value="" placeholder="Phone number" name="iuphone" class="form-control" required></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Profile Picture:</label></div>
                    <div class="col-md-3"><input type="file" id="iuprofilepic" name="iuprofilepic" class="form-control"></div>
                </div>
                <p class="clearfix"></p>
                <p class="clearfix"></p>
                <p class="clearfix"></p>
                <h4 class="librehealth-color text-right"><small>(Please provide information about your default facility) </small>ABOUT FACILITY</h4>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Facilty Name <span class="arial librehealth-color"> *</span> : <span class="hint-symbol" data-toggle="tooltip" title="Hooray!">[ ? ]</span></label></div>
                    <div class="col-md-4"><input type="text" value="" placeholder="Facility name e.g Medline Plus Clinic" name="iufacility" class="form-control" required></div>
                    <div class="col-md-2"><label>Legal Entity <span class="arial librehealth-color"> *</span> : <span class="hint-symbol" data-toggle="tooltip" title="Hooray!">[ ? ]</span></label></div>
                    <div class="col-md-3"><input type="text" value="" placeholder="Legal entity name e.g Medline Plus Clinic" name="iufacility" class="form-control" required></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Provider Type:</label></div>
                    <div class="col-md-9">
                        <select class="form-control"  data-style="btn-success" data-live-search="true" name="see_auth" >
                            <option value=''>Select Type</option>
                            <option value='attending_physician'>Attending physician</option>
                            <option value='audiological_physician'>Audiological physician</option>
                            <option value='chest_physician' selected>Chest physician</option>
                            <option value='community_health_physician'>Community health physician</option>
                            <option value='consultant_physician'>Consultant physician</option>
                            <option value='general_physician'>General physician</option>
                            <option value='genitourinarymedicinephysician'>Genitourinary medicine physician</option>
                            <option value='occupational_physician'>Occupational physician</option>
                            <option value='palliative_care_physician'>Palliative care physician</option>
                            <option value='physician'>Physician</option>
                            <option value='public_health_physician'>Public health physician</option>
                            <option value='rehabilitation_physician'>Rehabilitation physician</option>
                            <option value='resident_physician'>Resident physician</option>
                            <option value='specialized_physician'>Specialized physician</option>
                            <option value='thoracic_physician'>Thoracic physician</option>
                        </select>
                    </div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Facility Email <span class="arial librehealth-color"> *</span> :</label></div>
                    <div class="col-md-9"><input type="email" value="" placeholder="Email address" name="iuemail" class="form-control" required></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Facility Phone <span class="arial librehealth-color"> *</span> :</label></div>
                    <div class="col-md-9"><input type="text" value="" placeholder="Phone number" name="iuphone" class="form-control" required></div>
                </div>

                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Address(street) <span class="arial librehealth-color"> *</span> :</label></div>
                    <div class="col-md-9"><input type="text" value="" placeholder="Address" name="iuphone" class="form-control" required></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-3"><label>Country/State/City/Zip <span class="arial librehealth-color"> *</span> :</label></div>
                    <div class="col-md-2"><input type="text" value="" placeholder="Country" name="iuphone" class="form-control" required></div>
                    <div class="col-md-2"><input type="text" value="" placeholder="State" name="iuphone" class="form-control" required></div>
                    <div class="col-md-2"><input type="text" value="" placeholder="City" name="iuphone" class="form-control" required></div>
                    <div class="col-md-2"><input type="text" value="" placeholder="Zip" name="iuphone" class="form-control" required></div>
                </div>

                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Facility Picture(logo):</label></div>
                    <div class="col-md-3"><input type="file" id="iufacilitypic" name="iuprofilepic" class="form-control"></div>
                </div>



            </div>
        </form>

        <?php
        echo '<form action="step3.php" method="post">
                            <div class="control-btn2">
                            <input type="hidden" value="3" name="step">
                            <button id="backStep4" type="submit" class="controlBtn">
                            <i class="fa fa-arrow-circle-left"></i> Back
                            </button>
                            </div>
                            </form>
                    ';
        ?>

        <p class="clearfix"></p>
        <p class="clearfix"></p>
        <p class="clearfix"></p>
        <form method="post">
            <input type="hidden" value="annul" name="task">
            <div class="cancel-btn">
                <button type="submit" class="cancelBtn">
                    <i class="fa fa-times-circle-o"></i> Cancel
                </button>
            </div>
        </form>


    </div>

<?php
require_once("includes/footer.inc.php");
?>
