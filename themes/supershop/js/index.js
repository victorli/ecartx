$(document).ready(function(){
    if($(".option1").length > 0){
        var owlFul = {  loop:true,
                        nav:true,
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
                    };
        var owlSim = {  loop:false,
                        nav:false,
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
                    };            
        if($(".option1 .tab-content #homefeatured").length >0){        
            var elOwl = $(".option1 .tab-content #homefeatured");
            var total = parseInt(elOwl.data().total);        
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }
            }        
        }
        // Block Bestsellers
        if($(".option1 .tab-content #blockbestsellers").length >0){
            var elOwl = $(".option1 .tab-content #blockbestsellers");
            var total = parseInt(elOwl.data().total);        
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }            
            }        
        }    
        // Block Newproducts
        if($(".option1 .tab-content #blocknewproducts").length >0){
            var elOwl = $(".option1 .tab-content #blocknewproducts");
            var total = parseInt(elOwl.data().total);      
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }            
            }        
        }
        // Block blockspecials
        if($(".option1 .tab-content #blockspecials").length >0){
            var elOwl = $(".option1 .tab-content #blockspecials");
            var total = parseInt(elOwl.data().total);        
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }            
            }        
        }  
    }
    // Option2 
    if($(".option2").length >0 ){
        var owlFul = {  loop:true,
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
                                margin:13
                            },
                            992:{
                                items:3,
                                margin:24
                            },
                            1200:{
                                items:4,
                                margin:30
                            }
                        }
                    };
        var owlSim = {  loop:false,
                        nav:false,
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
                                margin:13
                            },
                            992:{
                                items:3,
                                margin:24
                            },
                            1200:{
                                items:4,
                                margin:30
                            }                       
                        }
                    }; 
        if($(".option2 .tab-content #homefeatured").length >0){
            var elOwl = $(".option2 .tab-content #homefeatured");
            var total = parseInt(elOwl.data().total);        
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }            
            }        
        }
        if($(".option2 .tab-content #blocknewproducts").length >0){
            var elOwl = $(".option2 .tab-content #blocknewproducts");
            var total = parseInt(elOwl.data().total);        
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }            
            }        
        }    
        if($(".option2 .tab-content #blockbestsellers").length >0){
            var elOwl = $(".option2 .tab-content #blockbestsellers");
            var total = parseInt(elOwl.data().total);        
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }            
            }        
        }
        if($(".option2 .tab-content #blockspecials").length >0){
            var elOwl = $(".option2 .tab-content #blockspecials");
            var total = parseInt(elOwl.data().total);        
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }            
            }        
        }   
    }
    // Option5 
    if($(".option5").length >0){
        var owlFul = {  loop:true,
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
                                margin:13
                            },
                            992:{
                                items:3,
                                margin:24
                            },
                            1200:{
                                items:4,
                                margin:30
                            }
                        }
                    };
        var owlSim = {  loop:false,
                        nav:false,
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
                                margin:13
                            },
                            992:{
                                items:3,
                                margin:24
                            },
                            1200:{
                                items:4,
                                margin:30
                            }                        
                        }
                    };
        if($(".option5 .tab-content #homefeatured").length >0){
            var elOwl = $(".option5 .tab-content #homefeatured");
            var total = parseInt(elOwl.data().total);        
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }            
            }        
        }
        if($(".option5 .tab-content #blockbestsellers").length >0){
            var elOwl = $(".option5 .tab-content #blockbestsellers");
            var total = parseInt(elOwl.data().total);        
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }            
            }        
        }
        if($(".option5 .tab-content #blocknewproducts").length >0){
            var elOwl = $(".option5 .tab-content #blocknewproducts");
            var total = parseInt(elOwl.data().total);        
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }            
            }        
        }
        if($(".option5 .tab-content #blockspecials").length >0){
            var elOwl = $(".option5 .tab-content #blockspecials");
            var total = parseInt(elOwl.data().total);        
            if(total >0){
                if(total >1){
                    elOwl.owlCarousel(owlFul); 
                }else{               
                    elOwl.owlCarousel(owlSim);
                }            
            }        
        }
    }
    $('#home-page-tabs li:first, #index .tab-content .carousel-list:first').addClass('active');
});