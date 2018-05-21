<?php
/**
 * Created by PhpStorm.
 * User: rachmann mua <muarachmann@gmail.com>
 * Date: 5/21/18
 * Time: 1:16 AM
 */
// this file will handle functions partaining to the iziModal/Toast
?>

var iziTitle = '';
var iziSubTitle = '';

// New Issue button is clicked.
$('.newIssue').click(function () {
    var f = document.forms[0];
    var tmp = (keyid && f.form_key[1].checked) ? ('?enclink=' + keyid) : '';
    iziTitle = '<?php echo xlt('Add New Issue'); ?>';
        iziSubTitle = '<?php echo xlt('Add Relevant Patient Issues'); ?>';
        initIziLink('summary/add_edit_issue.php'+ tmp , 850 , 400);
    });

// New Encounter button is clicked.
$('.newEncounter').click(function () {
    var f = document.forms[0];
    if (!f.form_save.disabled) {
        if (!confirm('<?php echo xls('This will abandon your unsaved changes. Are you sure?'); ?>'))
                    return;
            }
    top.restoreSession();
    var tmp = (keyid && f.form_key[0].checked) ? ('&issue=' + keyid) : '';
    iziTitle = '<?php echo xlt('Add New Encounter'); ?>';
        iziSubTitle = '<?php echo xlt('Add an encounter about Patient'); ?>';
        initIziLink('../../interface/forms/patient_encounter/new.php?mode=new' + tmp , 1200, 450);
    });

function  initIziLink(link , width , height) {
    $('#izi-iframe').iziModal({
                title: iziTitle,
                subtitle: iziSubTitle,
                headerColor: '#88A0B9',
                closeOnEscape: true,
                fullscreen:true,
                overlayClose: false,
                closeButton: true,
                theme: 'light',  // light
                iframe: true,
                width:width,
                focusInput: true,
                padding:2,
                iframeHeight: height,
                iframeURL:link,
                onClosed: function () {
        parent.$('.fa-refresh').click();
    }
            });
            setTimeout(function () {
                call_izi();
            },500);

        }

function call_izi() {
    $('#izi-iframe').iziModal('open');
}

});



