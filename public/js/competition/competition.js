var competitionObject = {
    init:function(){
        $("input[name=start_date]:visible, input[name=finish_date]:visible").datepicker({
            firstDay: 1,
            dateFormat: 'yy/mm/dd',
            dayNamesMin: ['Нд','Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            monthNames: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень',
            'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень']
        });
        toolbar.initNicedit();
        toolbar.initUploading();
    },
    view:function(compId){
        ajaxRequest({
            url     :"/competition/view/",
            type    :"POST",
            dataType: 'JSON',
            data:
            {
                "id" : compId
            },
            success: function(response){
                var $dlg=$("<div class='competition-view-dialog-content'>"+response+"</div>").dialog({
                    resizable:false,
                    width: 500,
                    height: 600,
                    modal: true,
                    'title' : "View competition",
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
                return false;
            }
        });
    },
    associate:function(compId){
        ajaxRequest({
            url     :"/competition/associate/format/json",
            type    :"GET",
            dataType: 'json',
            data:
            {
                "competition_id" : compId
            },
            success: function(response){
                var $dlg=$("<div class='competition-associate-dialog-content'>"+response.content+"</div>").dialog({
                    resizable:true,
                    width: 650,
                    height: 500,
                    modal: true,
                    'title' : "Associate competition",
                    open:function(ev, ui) {                   
//                        competitionObject.initAssociate();
                    },
                    close: function(ev, ui) {
                        $dlg.dialog("destroy");
                        $('.competition-associate-dialog-content').remove();
                    },
                    buttons: {
                        'Yes': function() {
                            
                            var $form=$dlg.find('form');
                            var collectedData={};
                            $form.find('input[name][type=checkbox]:checked').each(function(){
                                collectedData[$(this).attr('name')]=$(this).val();
                            });
                            collectedData['competition_id']=$form.find('input[name=competition_id]').val();
                            ajaxRequest({
                                url:"/competition/associate/format/json",
                                type:"POST",
                                dataType:"JSON",
                                data:collectedData,
                                success:function(response){
                                    
                                    $dlg.dialog("close");
                                    var $content=$(".competitions-action-manage");
                                    var pageForRefresh= $content.find(".table-controls .selected-page:first").attr('page-number');
                                    var params={};
                                    params.controller='competition';
                                    params.action= 'manage';
                                    params.changedObjectName='competition';
                                    params.page=pageForRefresh;
                                    params.changedRowId=response.competition_id;
                                    toolbar.refresh(params);
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
                return false;
            },
            error: function(response){
                alert(response.responseText);
            }
        })
    }
};