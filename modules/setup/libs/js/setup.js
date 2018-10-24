    /**
     * This file is the javascript file for the setup process, contains client side validations ,event polling and
     * ajaxing with handling of various actions made by user during the setup process.
     *
     * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
     * See the Mozilla Public License for more details.
     * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
     *
     * @package Librehealth EHR
     * @author Mua Laurent <muarachmann@gmail.com>
     * @link http://librehealth.io
     *
     * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
     *
     */
$(document).ready(function(){

    //to get timer
    var timer;
    // to get ajax request
    $.xhrPool = [];


    // client validation for special characters in siteID field
    // if false then copies value into hidden input field site id for submission
    var specialChars = "<>@!#$%^&*()+[]{}?:;|'\"\\,/~`= ";
    var check = function(string){
        for(i = 0; i < specialChars.length;i++){
            if(string.indexOf(specialChars[i]) > -1){
                return true
            }
        }
        return false;
    };

// show user note to copy his/password down
    $('.password_note')
        .attr('data-toggle', 'tooltip')
        .attr('data-placement', 'right')
        .tooltip({
            trigger: 'manual'
        })
        .tooltip('show');

    $("#siteID").bind('input', function() {
        if(check($(this).val()) === false){
            $("#errorSiteId").addClass("hidden");
            $("#site").val($(this).val());
            $("#submitStep3").removeAttr("disabled").css("cursor","pointer");
        }else{
            $("#errorSiteId").removeClass("hidden");
            $("#submitStep3").attr("disabled","disabled").css("cursor","not-allowed");
        }
    });

    // check to see if clone database checkbox is checked or not to hide lIBREUSER info

    var checkbox;
    $("#checkbox").on("click", function(){
        checkbox = $("#checkbox").prop("checked");
        if(checkbox) {
            $("#libreUser").slideUp(1000);
        } else {
            $("#libreUser").slideDown(1000);
        }
    });

    // =========================================
    //  prevent form submission and submit via ajax
    // =========================================


    $("#databaseForm").submit(function (e) {
        e.preventDefault();
        $("#submitStep4").attr("disabled","disabled").css("cursor","not-allowed");
        $("#backStep4").attr("disabled","disabled").addClass("btnDisabled").css("cursor","not-allowed");
         $(".ajaxLoader").removeClass("hidden");
        //values for mysql server
        var server  = $("[name='server']").val();
        var port    = $("[name='port']").val();
        var dbname  = $("[name='dbname']").val();
        var login   = $("[name='login']").val();
        var pass    = $("[name='pass']").val();

        //values for root credentials if any
        var root      = $("[name='root']").val();
        var rootpass  = $("[name='rootpass']").val();
        var loginhost = $("[name='loginhost']").val();
        var collate   = $("[name='collate']").val();
        var inst      = $("[name='inst']").val();

        //values for siteID, cloning of database etc
        var site = $("[name='site']").val();
        var source_site_id = $("[name='source_site_id']").val();

        var clone_database = $("#checkbox").prop("checked");
        if(clone_database) {
            clone_database = true;
        } else {
            clone_database = null;
        }


        // values of libreehr user
        var iuser     = $("[name='iuser']").val();
        var iuserpass = $("[name='iuserpass']").val();
        var iufname   = $("[name='iufname']").val();
        var iuname    = $("[name='iuname']").val();
        var igroup    = $("[name='igroup']").val();

        //array to store all variables

        var dataArray = {
            server : server,
            port : port,
            dbname : dbname,
            login : login,
            pass : pass,
            root : root,
            rootpass : rootpass,
            loginhost : loginhost,
            collate : collate,
            inst : inst,
            site : site,
            source_site_id : source_site_id,
            clone_database : clone_database,
            iuser : iuser,
            iuserpass : iuserpass,
            iufname :iufname,
            iuname : iuname,
            igroup : igroup
        };

        $.ajax({
            beforeSend: function(jqXHR) {
                $.xhrPool.push(jqXHR);
            },
            url : "database.php",
            type : "post",
            data : dataArray
        });
        // Refresh the progress bar every 1 second.
        timer = window.setInterval(refreshAjaxProgress, 1000);

    });


    // The function to refresh the progress bar.
    function refreshAjaxProgress() {
        // We use Ajax again to check the progress by calling the ajaxprocess script.
        // Also pass the session id to read the file because the file which storing the progress is placed in a file per session.
        // If the call was success, display the progress bar.
        $.ajax({
            beforeSend: function(jqXHR) {
                $.xhrPool.push(jqXHR);
            },
            url: "ajaxprocess.php",
            success:function(data){
                console.log(data);
                if(data.status === 400){
                    $("#libreehrProgress").attr({
                        "style" : "width:"+data.percentage+"%",
                        "aria-valuenow" : ""+data.percentage+""
                    }).html(data.message)
                        .removeClass("progress-bar-success progress-bar-striped active")
                        .addClass("progress-bar-danger")
                        .parent().attr("title",data.message);
                    window.clearInterval(timer);
                    timer = window.setInterval(errorComplete(data.message), 1000);
                }
                else {
                    $("#libreehrProgress")
                        .attr("style","width:"+data.percentage+"%").html(data.message)
                        .removeClass("progress-bar-danger")
                        .addClass("progress-bar-success progress-bar-striped active")
                        .parent().attr("title",data.message);
                }

                // // If the process is completed, we should stop the checking process.
                 if (data.percentage === 100 ) {
                     var html = "<input type='hidden' value='"+data.next_state+"' name='stepholder'>";
                     $("#nextStep").html(html);
                     window.clearInterval(timer);
                     timer = window.setInterval(completed, 1000);
                 }
            }
        });
    }


    // function to clear the timer if process has completed to a hundred percent
    function completed() {
        $("#submitStep4").removeAttr("disabled").css("cursor","pointer").html("Continue");
        $("#backStep4").removeAttr("disabled").removeClass("btnDisabled").css("cursor","pointer");
        window.clearInterval(timer);
        iziToast.show({
            type: "success",
            position: "bottomRight",
            color:"green",
            icon:"fa fa-check",
            message: "completed"
        });
        $("#databaseForm").off('submit').submit();
    }

    // function to clear the timer if process has completed to a hundred percent
    function errorComplete(msg) {
        iziToast.show({
            type: "error",
            position: "bottomRight",
            color:"red",
            icon:"fa fa-times",
            message: msg
        });
        $(".ajaxLoader").addClass("hidden");
        $("#submitStep4").removeAttr("disabled").css("cursor","pointer").html("<span class='fa fa-refresh'></span> Retry Again");
        $("#backStep4").removeAttr("disabled").removeClass("btnDisabled").css("cursor","pointer");
        window.clearInterval(timer);
        $.xhrPool.abortAll = function() {
            _.each(this, function(jqXHR) {
                jqXHR.abort();
            });
        };
        $("#databaseForm")[0].reset();
    }

    //Displaying facility and user profile image once selected
    function readURL(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#'+imgId).attr('src', e.target.result).parent().removeClass("hidden").fadeIn(500);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#iuprofilepic").change(function(){
        var file = this.files[0];
        filename = file.name;
        size = file.size;
        type = file.type;

        console.log(size);
        var ext = filename.split('.').pop().toLowerCase();
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) === -1) {
            iziToast.show({
                type: "error",
                position: "bottomRight",
                color:"red",
                icon:"fa fa-times",
                message: "Invalid file Type"
            });
            $("#iuprofilepic").val(null).clone(true);
            $("#closeProfilePic").parent().fadeOut(1000).addClass("hidden");
        }
        if(size > 2000000){
            iziToast.show({
                type: "error",
                position: "bottomRight",
                color:"red",
                icon:"fa fa-times",
                message: "File size too large choose a smaller one please"
            });
            $("#iuprofilepic").val(null).clone(true);
            $("#closeProfilePic").parent().fadeOut(1000).addClass("hidden");
        }
        else {
            iziToast.show({
                type: "success",
                position: "bottomRight",
                color:"green",
                icon:"fa fa-picture-o",
                message: "File choosen"
            });
            readURL(this, "profile-img");
        }

    });

    //removing the images if user doesnt wants them any longer

    $("#closeProfilePic").click(function () {
        $("#iuprofilepic").val(null).clone(true);
        $(this).parent().fadeOut(1000).addClass("hidden");
    });


   // =========================================
   //     BLOCK FOR IZI DEMO TO USER
   // =========================================
    function gentarate_random(min, max) {
        var value = (min - 0.5) + (Math.random() * ((max - min) + 1));
        return Math.round(value);
    }

    var izi_type_parameter =  [
        "success",
        "error",
        "warning",
        "info"
    ];

    var izi_position_parameter =  [
        "topLeft",
        "topRight",
        "bottomLeft",
        "bottomRight",
        "topLeft",
        "topCenter",
        "bottomCenter"
    ];

    $(".izi-demo-toast").click(function () {
        var izi_type = izi_type_parameter[gentarate_random(1,4)];
        var izi_pos  = izi_position_parameter[gentarate_random(1,7)];
        var icon, color, msg;
        if(izi_type === "error"){
            color = "red"; icon ="fa fa-times"; msg = "Failure notification";
        }else if(izi_type === "info"){
            color = "blue"; icon ="fa fa-info"; msg = "Informative notification"
        }
        else if(izi_type === "warning"){
            color = "orange"; icon ="fa fa-warning"; msg = "Warming notification"
        }
        else {
            color = "green"; icon ="fa fa-check"; msg = "Success notification";
        }
        iziToast.show({
            type: izi_type,
            position: izi_pos,
            color:color,
            icon:icon,
            message: msg
        });
    });


    function getColor() {
        return "#"+$(".jscolor").val();
    }

    function call_izi() {
        $("#demo-iframe").iziModal('open');
    }

    function initIziLink(color) {
        $("#demo-iframe").iziModal({
            title: 'Modal demo',
            subtitle: 'An example of a modal',
            headerColor: color,
            closeOnEscape: true,
            fullscreen:true,
            overlayClose: false,
            closeButton: true,
            theme: 'light',  // light
            iframe: true,
            width:700,
            focusInput: true,
            iframeHeight: 400,
            onClosed: function(){
                location.reload();
            },
            iframeURL: "template.html"

        });

        setTimeout(function () {
            call_izi();
        },200);
    }

    $(".izi-demo-modal").click(function () {
       var color = getColor();
         initIziLink(color);
    });




    // =========================================
    //     BLOCK FOR PRINT RESULTS
    // =========================================

    $(".printMe").click(function () {
        var prtContent = document.getElementById("printStep");
        var WinPrint = window.open('', '', 'letf=0,top=0,width=1,height=1,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write(prtContent.innerHTML);
        var oldstr = document.body.innerHTML;
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
        prtContent.innerHTML = oldstr;
    });

    // =========================================
    //     FORCE INSTALL HANDLE SELECT CHANGE EVENTS
    // =========================================


    $('.loginsite').on('change', function() {
        var site_id =  $(this).find(":selected").val();
        var location = '../../interface/login/login.php?site='+ site_id;
        $("#loginEHR").attr("href", location);
    });


});

