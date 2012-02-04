$(function(){
    $('#results-table select.competition-selector').change(function(){
        var $select=$(this);
        ajaxRequest({
            url     :"/competition/save-into-session/",
            type    :"POST",
            dataType: 'JSON',
            data:
            {
                "id" : $select.val()
            },
            success: function(response){
                window.location.reload();
            }
        });
    });
});
var cTools = {
    'toggle' : function(){
        $("a.ct").toggleClass("opened");
        $("a.ct~.submenu").slideToggle('fast');
    },
    'fn' : {
        'addClub' : function(){
            toolbar.addEdit(0);
        },
        'addCompetition' : function(){
            competitionObject.addEdit(0);
        }
    }
};