function scrollCompensate()
{
    var inner = document.createElement('p');
    inner.style.width = "100%";
    inner.style.height = "200px";

    var outer = document.createElement('div');
    outer.style.position = "absolute";
    outer.style.top = "0px";
    outer.style.left = "0px";
    outer.style.visibility = "hidden";
    outer.style.width = "200px";
    outer.style.height = "150px";
    outer.style.overflow = "hidden";
    outer.appendChild(inner);

    document.body.appendChild(outer);
    var w1 = inner.offsetWidth;
    outer.style.overflow = 'scroll';
    var w2 = inner.offsetWidth;
    if (w1 == w2) w2 = outer.clientWidth;

    document.body.removeChild(outer);

    return (w1 - w2);
}
function resizeTopmenu(){
    if($(window).width()+scrollCompensate() >= 768){
        $("ul.dropdown-menu").each(function(){
            var obj_right = parseInt($(this).width()+$(this).parent().position().left);
            var obj_left = parseInt(obj_right - $('#topmenu ul.nav').width() + 30);            
            if (obj_left > 0){
                //$(this).css("right","0");
                $(this).css("left","-"+obj_left+"px");
            }
        });
    }
    if($(window).width()+scrollCompensate() < 992){
        $("#topmenu .level-1:not(.active) .dropdown-toggle").attr('data-toggle','dropdown');
    }else{
        $("#topmenu .level-1 a.dropdown-toggle").removeAttr('data-toggle');
    }
}
$(document).ready(function(){
    resizeTopmenu();
});
$(window).resize(function() {
    //$("ul.dropdown-menu").css("");
    resizeTopmenu();
});