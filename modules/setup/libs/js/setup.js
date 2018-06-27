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

















});


