<?php

require_once("includes/header.inc.php");

?>

<div class="card">
    <div class="col-md-4 pull-right">
        <div class="balloon">
            <div><span class="bal"><i class="librehealth-color">☺</i></span></div>
            <div><span class="bal">E</span></div>
            <div><span class="bal">H</span></div>
            <div><span class="bal">R</span></div>
        </div>
    </div>
    <div id='printStep'>
    <div class="col-md-12 col-xs-12 col-sm-12 text-center">
        <h1 class="librehealth-color" style="font-family: Circle;font-size: 50px">
            Congratulations!!!
        </h1>
    </div>

    <p class="clearfix">
    <p class="clearfix">
    <div class="col-md-12">
    <h4 class="green" style="font-weight: bolder;">Congratulations! LibreHealth EHR is now installed.</h4>

    <ul>
        <li>Access controls (php-GACL) are installed for fine-grained security, and can be administered in
            LibreHealth EHR's admin->acl menu.</li>
        <li>Reviewing <?php echo $OE_SITE_DIR; ?>/config.php is a good idea. This file
            contains some settings that you may want to change.</li>
        <li>There's much information and many extra tools bundled within the LibreHealth EHR installation directory.
            Please refer to LibreHealth EHR/Documentation. Many forms and other useful scripts can be found at LibreHealth EHR/contrib.</li>
        <li>To ensure a consistent look and feel through out the application using
            <a href='http://www.mozilla.org/products/firefox/'>Firefox</a> is recommended.</li>
        <li>The LibreHealth EHR project home page, documentation, and forums can be found at <a href = "https://forums.librehealth.io/c/7-support" target="_blank">LibreHealth EHR</a></li>
        <li>We pursue grants to help fund the future development of LibreHealth EHR.  Please contact us via our website to let us know how you use LibreHealth EHR.  This information is valuable for evaluating the benefits of LibreHealth EHR to the medical community worldwide, and serves as a great way for you to advise us on how to serve you better.</li>
    </ul>
        </div>
    </div>

        <p>
        We recommend you print these instructions for future reference.
            <button class='small btn-default printMe'><span class='fa fa-print'></span> Print</button>
    </p>
<p class="clearfix"></p>
<p class="clearfix"></p>
<p class="clearfix"></p>
    <div class="col-md-12 col-sm-12 text-center">
        <a href="./?site=<?php echo $site_id; ?>" class="btn controlBtn btn-group-lg">Click here to start using LibreHealth EHR.</a>
    </div>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
</div>


<?php

require_once("includes/footer.inc.php");

?>
