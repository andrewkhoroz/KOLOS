$(function() {

    var availableTags = [
    "ActionScript",
    "AppleScript",
    "Asp",
    "BASIC",
    "C",
    "C++",
    "Clojure",
    "COBOL",
    "ColdFusion",
    "Erlang",
    "Fortran",
    "Groovy",
    "Haskell",
    "Java",
    "JavaScript",
    "Lisp",
    "Perl",
    "PHP",
    "Python",
    "Ruby",
    "Scala",
    "Scheme"
    ];

    $('#demo1').tagit({
        tagSource: availableTags, 
        select: true
    });
    $('#demo2').tagit({
        tagSource: availableTags
    });
    $('#demo3').tagit({
        tagSource: availableTags, 
        triggerKeys: ['enter', 'comma', 'tab']
        });


    $('#demo1GetTags').click(function(){
        showTags($('#demo1').tagit('tags'))
        });
    $('#demo2GetTags').click(function(){
        showTags($('#demo2').tagit('tags'))
        });
    $('#demo3GetTags').click(function(){
        showTags($('#demo3').tagit('tags'))
        });

    function showTags(tags){
        var string = "Tags\r\n";
        string +="--------\r\n";
        for(var i in tags)
            string += tags[i]+"\r\n";
        alert(string);
    }

    $('.browser').hover(
        function(){
            $(this).children('a').children('div').show('fast');
        },
        function(){
            $(this).children('a').children('div').hide('fast');
        });
});