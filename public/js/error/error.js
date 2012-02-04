$(function(){
    $('#error-details').live('click',function(){
        var $dlg=$(".error-description").dialog({
            resizable:true,
            width: 960,
            height: 600,
            modal: true,
            'title' : "Error description",
            close: function(ev, ui) {
                $dlg.dialog("destroy");
                $('.competition-view-dialog-content').remove();
            },
            buttons: {
                'Ok': function() {
                    $dlg.dialog("close");
                }
            }
        });
    });

});

