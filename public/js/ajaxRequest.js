ajaxRequest=function(options){
    $.ajax({
        url     :options.url,
        type    :options.type,
        dataType: options.dataType,
        data:options.data,
        success: options.success,
        error:function(response){
            //            $.ajax({
            //                url     :"/ajax/logErrorResponse",
            //                type    :"POST",
            //                dataType: 'JSON',
            //                data:response,
            //                success:function(response){
            //                    alert(response);
            //                },
            //                error:function(response){
            //                    alert(response);
            //                }
            //            });

            //log wrong request
            //            alert(response.responseText);
            var $dlg=$("<div class='request-error-dialog-content'></div>").dialog({
                closeText: 'Close',
                resizable:true,
                width: 1200,
                height: 600,
                modal: true,
                'title' : "Error description",
                zIndex: 3999,
                open: function(event, ui) {
                    var $this=$(this);
                    $this.html(response.responseText);
                    $this.find('.error-description').show();
                    $this.find('#error-details').hide();
                },
                close: function(ev, ui) {
                    $('.request-error-dialog-content').remove();
                    $dlg.dialog("destroy");
                }
            });
        }
    })
}