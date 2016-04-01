$(document).ready(function() {
  $("#brand_list").owlCarousel({
 
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      navigation : true,
      items : 6,
      itemsDesktop : [1199,5],
      itemsDesktopSmall : [979,4],
      itemsTablet: [768,4],
      itemsTabletSmall: false,
      itemsMobile : [479,2]
 
  });
 
});