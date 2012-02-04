$(function(){
    function initParams(pageNumber){
        var params={};
        params.controller='competition';
        params.action= 'manage';
        params.page=pageNumber;
        params.changedObjectName='competition';
        params.changedRowId=0;
        params.request={};
        params.request.search={};
        params.request.search.name=$("input.search-competition").val();
        
        return params;
    }
    $(".competitions-action-manage a[page-number]").live('click',function(){
        var $this=$(this);
        var params=initParams($this.attr('page-number'));
        toolbar.refresh(params)
       
    });
    $("input.search-competition").live('keyup',function(){
        var params=initParams(1);
        toolbar.search(params);
        $("input.search-competition").val(params.request.search.name);
    });
});
