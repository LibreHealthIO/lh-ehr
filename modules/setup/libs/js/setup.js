$(document).ready(function(){


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


// prevent form submission and submit via ajax

    $("#databaseForm").submit(function (e) {
        e.preventDefault();
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
        var clone_database = $("[name='clone_database']").val();


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
            data : dataArray,
            success : function (response) {
                alert(response);
            }
        });
        
    })














});


