$(function(){
    var  $images=$("a[rel=competition-view]");
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
    $('#tournir-table-tabs').tabs({
        'select':0
    });
});