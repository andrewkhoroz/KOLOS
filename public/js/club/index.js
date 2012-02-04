$(function(){
    function initParams(pageNumber){
        var params={};
        params.controller='club';
        params.action= 'index';
        params.page=pageNumber;
        params.changedObjectName='club';
        params.changedRowId=0;
        params.targetDomSelector=$('.clubs-action-index');
        params.request={};
        params.request.search={};
        
        $("input.search-club").each(function(){
            var $searchParam=$(this);
            if($searchParam.val().length>0){
                params.request.search[$searchParam.attr('name')]=$searchParam.val();
            }
        });
        return params;
    }
    $(".clubs-action-index a[page-number]").live('click',function(){
        var $this=$(this);
        var params=initParams($this.attr('page-number'));
        toolbar.refresh(params)
       
    });
    $("input.search-club").live('keyup',function(){
        var params=initParams(1);
        toolbar.search(params);
        $("input.search-club").val(params.request.search.name);
    });
    
});