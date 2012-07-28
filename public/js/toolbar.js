ROWS_PER_PAGES=5;
GALLERY = new Array();
var nicEditorObj;
var toolbar= {

    initNicedit:function(){
        nicEditorObj = new nicEditor({
            fullPanel : true,
            iconsPath : '/js/jQuery/nicEditorIcons.gif'
        }).panelInstance('description');

    },
    refresh:function(params,initFunction){
        params.page=parseInt(params.page);
        if(params.request == undefined){
            params.request = {}
        }
        params.request['page']=params.page;
        ajaxRequest({
            url:"/"+params.changedObjectName+"/"+params.action+"/format/html",
            type:"POST",
            dataType: 'HTML',
            data :params.request,
            success: function(response){
                var contentSelector='.'+params.changedObjectName+'s-action-'+params.action;
                if(params.targetDomSelector!=undefined){
                    contentSelector=params.targetDomSelector;
                }
                $(contentSelector).replaceWith(response);
                $(contentSelector).find('.'+params.changedObjectName+'s-list tr['+params.controller+'-id='+params.changedRowId+']').addClass('red');

                if(typeof initFunction == "function"){
                    initFunction();
                }
            },
            error: function(response){
                alert(response.responseText);
            }
        });
    },
    remove:function(id,controller){

        var $dlg=$("<div class='"+controller+"-remove-dialog-content'>Are you sure you want to delete "+controller+" ?</div>").dialog({
            resizable:false,
            width: 'auto',
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
                            var $content=$("."+controller+"s-action-manage");
                            var pageForRefresh = 1;
                            pageForRefresh = $content.find(".table-controls .selected-page:first").text();
                            if($('.'+controller+'s-action-manage .'+controller+'s-list tr['+controller+'-id]').length==1){
                                pageForRefresh--;
                            }
                            var params={};
                            params.controller=controller;
                            params.action= 'manage';
                            params.changedObjectName=controller
                            params.page=pageForRefresh;
                            params.changedRowId=0;
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
    } ,
    collect:function($form){
        var data={};
        $form.find('input[name][type=text,hidden]').each(function(){
            data[$(this).attr('name')]=$(this).val();
        });
        $form.find('input[name][type=checkbox]:checked').each(function(){
            data[$(this).attr('name')]=$(this).val();
        });
        if(nicEditorObj != null){
            for(var i=0; i<nicEditorObj.nicInstances.length; i++){
                nicEditorObj.nicInstances[i].saveContent();
            }
        }
        $form.find('textarea[name]').each(function(){
            data[$(this).attr('name')]=$(this).val();
        });
        $form.find('select[name]').each(function(){
            data[$(this).attr('name')]=$(this).val();
        });
        return data;
    },
    initUploading:function(){
        /*
         * jQuery File Upload Plugin JS Example 5.0.2
         * https://github.com/blueimp/jQuery-File-Upload
         *
         * Copyright 2010, Sebastian Tschan
         * https://blueimp.net
         *
         * Licensed under the MIT license:
         * http://creativecommons.org/licenses/MIT/
         */

        /*jslint nomen: true */
        /*global $ */
        'use strict';

        // Initialize the jQuery File Upload widget:
        var $fileUploadDiv= $('#fileupload');
        if($fileUploadDiv.length>0){
            $('#fileupload').fileupload();

            // Load existing files:
            $.getJSON($('#fileupload form').attr('action'), function (files) {
                var fu = $('#fileupload').data('fileupload');
                fu._adjustMaxNumberOfFiles(-files.length);
                fu._renderDownload(files)
                .appendTo($('#fileupload .files'))
                .fadeIn(function () {
                    // Fix for IE7 and lower:
                    $(this).show();
                });
            });

            // Open download dialogs via iframes,
            // to prevent aborting current uploads:
            $('#fileupload .files a:not([target^=_blank])').live('click', function (e) {
                e.preventDefault();
                $('<iframe style="display:none;"></iframe>')
                .prop('src', this.href)
                .appendTo('body');
            });
        }
    },
    search:function(searchConditions,initFunction){
        this.refresh(searchConditions, initFunction);
    },
    getDom:function(params,controller){
        
    }

}
var framework= {
    request:function(params){
        if(undefined == params){
            params={};
        }
        if(undefined == params.controller || ""==params.controller){
            params.controller='index';
        }
        if(undefined == params.action || ""==params.action){
            params.action='index';
        }
        if(undefined == params.format || ""==params.format){
            params.format='html';
        }
        if(undefined == params.method || ""==params.method){
            params.method='get';
        }
        ajaxRequest({
            url:"/"+params.controller+"/"+params.action+"/format/"+params.format,
            type:params.method,
            dataType: params.format,
            success: function(response){
                return response;
            },
            error: function(response){
                alert(response.responseText);
            }
        });
    }
}

function ImageGallery (galleryId, imgsCnt,showImgCount) {
    this.speed=300;
    this.id = galleryId;
    this.gSize = imgsCnt;
    this.step = 0;
    this.showImgCount=(undefined==showImgCount?5:showImgCount);
    this.moveForward =  function(){
        if((this.gSize - this.showImgCount) <= this.step){
            return;
        }  
        this.step++;
        $('#'+this.id+'_'+this.step).hide(this.speed);
        $('#'+this.id+'_'+(this.step+this.showImgCount)).show(this.speed);
    };
    this.moveBack = function(){
        if(this.step == 0){
            return;
        }  
        $('#'+this.id+'_'+this.step).show(this.speed);
        $('#'+this.id+'_'+(this.step+this.showImgCount)).hide(this.speed);
        this.step--;
    };
}