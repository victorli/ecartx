$(document).ready(function(){
    $("#home_blog").owlCarousel({
       loop:true,
       nav:true,
       responsive:{
            0:{
                items:1,
                margin:30
            },
            380:{
                items:1,
                margin:30
            },
            480:{
                items:2,
                margin:30
            },
            768:{
                items:3,
                margin:30
            }
        }
  });
});