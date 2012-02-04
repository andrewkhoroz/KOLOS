function getAjaxNotice(clearSession)
{

    $.post(
        "/async/getnotice",
        {
            "msg" : $("#msg").val(),
            "clear" : clearSession
        },
        function(response)
        {
            var notice = $(response);
            $("#notices").prepend(notice.hide().fadeIn());
            setTimeout(function(emt)
            {
                emt.fadeOut("slow", function()
                {
                    $(this).remove();
                });

            },1500 , notice);
        });
}

function getItemsJson()
{
    $.post(
        "/async/getitems"
        ,{},
        function(data){
            $("#items").html('');
            for (var i = 0; i < data.length; i++)
            {
                $("#items").append("<dt>"+data[i].id+": "+data[i].name+"</dt>");
                $("#items").append("<dd>"+data[i].description+"</dd>");
            }
        }
        ,'json');
}
function getItemJson(itemId)
{
    $.post(
        "/async/getitem"
        ,{
            "itemId" : itemId
        },
        function(data){
            $("#items").html('');
            $("#items").append("<dt>"+data.id+": "+data.name+"</dt>");
            $("#items").append("<dd>"+data.description+"</dd>");
        }
        ,'json');
}