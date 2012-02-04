$(function(){
    function initParams(pageNumber){
        var params={};
        params.controller='club';
        params.action= 'manage';
        params.page=pageNumber;
        params.changedObjectName='club';
        params.changedRowId=0;
        params.request={};
        params.request.search={};
        params.request.search.name=$("input.search-club").val();
        
        return params;
    }
    $(".clubs-action-manage a[page-number]").live('click',function(){
        var $this=$(this);
        var params=initParams($this.attr('page-number'));
        toolbar.refresh(params);
       
    });
    $("input.search-club").live('keyup',function(){
        var params=initParams(1);
        toolbar.search(params);
        $("input.search-club").val(params.request.search.name);
    });
});