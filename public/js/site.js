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
