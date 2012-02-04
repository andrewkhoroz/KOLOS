$(function(){
    $(".tours-action-manage a[page-number]").live('click',function(){
        var $this=$(this);
        var params={};
        params.controller=$this.attr('controller-name');
        params.action= $this.attr('action-name');
        params.page=$this.attr('page-number');
        params.changedObjectName=$this.attr('controller-name');
        params.changedRowId=0;
        params.request={};
        params.request['comp_id']=$('.tours-action-manage').attr('comp-id');
        toolbar.refresh(params ,matchObject.init)
    });
});
