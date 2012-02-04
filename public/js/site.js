$("a.login").live('click',function(){
    ajaxRequest({
        url     :"/user/login/",
        type    :"GET",
        dataType: 'JSON',
        success: function(response){
            var $dlg=$(response.content).dialog({
                closeText: 'Close',
                resizable:true,
                modal: true,
                width:'auto',
                'title' : "Вікно логування",
                zIndex: 3999,
                close: function(ev, ui) {
                    $dlg.dialog("destroy");
                },                    
                buttons: {
                    'Ok': function() {
                        var username=$dlg.find('#username').val();
                        var password=$dlg.find('#password').val();
                        ajaxRequest({
                            url:"/user/login/format/json",
                            type:"POST",
                            dataType: 'JSON',
                            data:{
                                'username':username,
                                'password':password
                
                            },
                            success: function(response){
                                if(response.is_logged==true){
                                    if(window.location.pathname=='/user/logout'){
                                        $('html').html(framework.request());
                                    }else{
                                        window.location.reload();
                                        $dlg.dialog("close");
                                    }
                                }else{
                                    $('#login-form .login-error-description').show();
                                }
                            },
                            error: function(response){
                                alert(response.responseText);
                            }
                        });
                    }
                }
            });  
        }
    });       
});
$(function(){
    var  $images=$("a[rel=fancy_images]");
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
    
    $(".competitions-action-tours-viewer a[page-number]").live('click',function(){
        var $this=$(this);
        var params={};
        params.controller='competition';
        params.action= 'tours-viewer';
        params.page=$this.attr('page-number');
        params.changedObjectName='competition';
        params.changedRowId=0;
        params.request={};
        params.request.search={};
        params.request.search.name=$("input.search-club").val();
        toolbar.refresh(params);
       
    });
    $('.instruments').click(function(){
        $(this).find('~.submenu').slideToggle('fast');
    });
    
});
