$( function(){
    $('.col-sm-3').draggable({
        addClasses: true
    });

    var addClasses = $('.col-sm-3').draggable("option", "addClasses");
    $('#second').droppable({
        drop: function(event, ui){
            $(this)
                .addClass('ui-state-highlight')
                .find('p')
                .html("Dropped " + $(ui.draggable).attr("id"));
            $(ui.draggable).addClass('ui-state-highlight');
            //$(ui.draggable).draggable('disable');
            console.log('Dragged: ' + $(ui.draggable).attr("id"));
        }
    });
});
