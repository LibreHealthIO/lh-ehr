"use strict";

var images = new Array ('images/clinics/record.png', 'images/clinics/rad1.png',
    'images/clinics/images.png', 'images/clinics/stethoscope.png',
    'images/clinics/rad2.jpg', 'images/clinics/doctor.png',
    'images/clinics/call.png', 'images/clinics/docs.png',
    'images/clinics/location.png', 'images/clinics/msg.png');
var index = 1;

function rotateImage()
{
    $('#med-imgs').fadeOut('slow', function()
    {
        $(this).attr('src', images[index]);

        $(this).fadeIn('slow', function()
        {
            if (index == images.length-1)
            {
                index = 0;
            }
            else
            {
                index++;
            }
        });
    });
}

$(document).ready(function()
{
    setInterval (rotateImage, 4000);

    $('.next-btn').hover(function() {
        $(this).append('&nbsp;<span class="fa fa-chevron-right"></span>');
    }, function() {
        $(this).html('NEXT');
    });

    $('.prev-btn').hover(function() {
        $(this).prepend('<span class="fa fa-chevron-left"></span>&nbsp;');
    }, function() {
        $(this).html('BACK');
    });
});