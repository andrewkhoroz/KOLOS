var matchObject = {
    init:function(){
        $("input[name=match_date]:visible").datepicker({
            firstDay: 1,
            dateFormat: 'yy/mm/dd',
            dayNamesMin: ['Нд','Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            monthNames: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень',
            'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень']
        });

        $("input[name=owner_score], input[name=guest_score]").each(function(){
            var $this = $(this);
            $this.SpinButton({
                min: 0,
                max: $this.attr("max")==0? 9:$this.attr("max"),
                step: 1
            });
        });
        $("input[name=owner_score], input[name=guest_score]").each(function(){
            var $this=$(this);
            $this.mask("9", {
                placeholder: ""
            });
        });
        toolbar.initUploading();
        toolbar.initNicedit();
    },
    remove:function(compId,tourId,matchId){
        var controller='match';
        var id=matchId;
        var controllerForRefresh='tour';
        
        var $dlg=$("<div class='"+controller+"-remove-dialog-content'>Are you sure you want to delete "+controller+" ?</div>").dialog({
            resizable:false,
            width: 300,
            height: 150,
            modal: true,
            'title' : "Delete "+controller,
            close: function(ev, ui) {
                $dlg.dialog("destroy");
                $('.'+controller+'-remove-dialog-content').remove();
            },
            buttons: {
                'Yes': function() {
                    var data={};
                    data[controller+'_id']=id;
                    $dlg.dialog('close');
                    ajaxRequest({
                        url:"/"+controller+"/delete/format/html",
                        type:"POST",
                        dataType:"HTML",
                        data:data,
                        success:function(response){
                            var $content=$("."+controllerForRefresh+"s-action-manage");
                            var pageForRefresh = $content.find(".table-controls .selected-page:first").text();
                            var params={};

                            params.controller='tour';
                            params.action= 'manage';
                            params.changedObjectName=controller
                            params.page=pageForRefresh;
                            params.changedRowId=0;
                            params.targetDomSelector="."+controllerForRefresh+"s-action-manage";
                            params.request={};
                            params.request.comp_id=compId;
                            var functionName=controller+'Object'
                            toolbar.refresh(params,functionName.init);
                            $dlg.dialog("close");
                        },
                        error:function(response){
                            alert(response.responseText);
                        }
                    });
                },
                'No': function() {
                    $dlg.dialog("close");
                }
            }
        });
    } 
}
$(function(){
    var  $images=$("a[rel=example_group]");
    if($images.length>0){
        $images.fancybox({
            'transitionIn'		: 'none',
            'transitionOut'		: 'none',
            'titlePosition' 	: 'inside',
            'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
                return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
            }
        });
    }
});
