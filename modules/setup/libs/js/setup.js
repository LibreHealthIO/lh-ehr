$(document).ready(function(){

    //to get timer
    var timer;


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

    // add a hidden class to the ajaxerror-success box if the close button is clicked
    $("#ajaxDivClose").click(function () {
        $("#ajaxAlert").delay(1000).fadeOut(1000, function () {
            $(this).addClass("hidden");
        });
    });



// prevent form submission and submit via ajax

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
            url: "ajaxprocess.php",
            success:function(data){
                console.log(data);
                if(data.status === 400){
                    $("#libreehrProgress").attr({
                        "style" : "width:"+data.percentage+"%",
                        "aria-valuenow" : ""+data.percentage+""
                    }).html(data.message).removeClass("progress-bar-success progress-bar-striped active").addClass("progress-bar-danger");
                    $("#ajaxAlert").removeClass("hidden alert-success").addClass("alert-danger");
                    $("#ajaxResponse").html(data.message);
                    $(".ajaxLoader").addClass("hidden");
                    window.clearInterval(timer);
                    timer = window.setInterval(errorComplete(data.message), 1000);
                }
                else {
                    $("#libreehrProgress").attr("style","width:"+data.percentage+"%").html(data.message).removeClass("progress-bar-danger").addClass("progress-bar-success progress-bar-striped active");
                    $("#ajaxAlert").removeClass("hidden alert-danger").addClass("alert-success");
                    $("#ajaxResponse").html(data.message);
                    $(".ajaxLoader").addClass("hidden");
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
        $("#ajaxAlert").removeClass("hidden alert-danger").addClass("alert-success");
        $("#ajaxResponse").html("Completed");
        $("#submitStep4").removeAttr("disabled").css("cursor","pointer");
        $("#backStep4").removeAttr("disabled").removeClass("btnDisabled").css("cursor","pointer");
        window.clearInterval(timer);
        $("#databaseForm").off('submit').submit();
    }

    // function to clear the timer if process has completed to a hundred percent
    function errorComplete(msg) {
        $("#ajaxAlert").removeClass("hidden alert-success").addClass("alert-danger");
        $("#ajaxResponse").html("Error- setup failed: " + msg);
        $("#submitStep4").removeAttr("disabled").css("cursor","pointer");
        $("#backStep4").removeAttr("disabled").removeClass("btnDisabled").css("cursor","pointer");

        window.clearInterval(timer);
    }















});

