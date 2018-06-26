$(document).ready(function() {

    $('#dbForm').submit(function(e){
        e.preventDefault();

        $(".center-div").removeClass('hidden');
        $(".loading").removeClass('hidden');
        // variables to hold all form parameters such as login name, password , iuname etc
        var localhost = $('#server');                var port = $('#port');
        var dbname = $('#dbname');                var pass = $('#pass');
        var rootpass = $('#rootpass');               var loginhost = $('#loginhost');
        var login = $('#login');                     var root = $('#root');
        var collate = $('#collate');                  var iuser = $('#iuser');
        var iuserpass = $('#iuserpass');              var iufname = $('#iufname');
        var iuname = $('#iuname');                  var igroup = $('#igroup');
       $.ajax({
           url : "database.php",
           type:'POST',
           data: localhost,
           success : function (response) {
               alert(response);
               $(".center-div").addClass('hidden');
               $(".loading").addClass('hidden');
           },
           error: function (error) {
               $(".center-div").addClass('hidden');
               $(".loading").addClass('hidden');
                   alert(error);
           }
       });
    });

    // save comment to database
    $(document).on('click', '#submit_btn', function () {
        var name = $('#name').val();
        var comment = $('#comment').val();
        $.ajax({
            url: 'server.php',
            type: 'POST',
            data: {
                'save': 1,
                'name': name,
                'comment': comment,
            },
            success: function (response) {
                $('#name').val('');
                $('#comment').val('');
                $('#display_area').append(response);
            }
        });
    });

    $("#test").click(function () {
        iziToast.success({
            title: 'Success -',
            message: successMsg,
            position: 'center',
            icon: 'fa fa-inbox'
        });
    });
});