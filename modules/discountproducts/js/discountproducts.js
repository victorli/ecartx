$(document).ready(function() {
    $("#discountproducts_list").owlCarousel({
        loop:true,
        nav:true,
        //margin:30,
        responsive:{
            0:{
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
            },
            992:{
                items:4,
                margin:24
            },
            1200:{
                items:4,
                margin:30
            }
            
        }
    });
});