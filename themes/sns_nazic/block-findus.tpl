{******
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script>
	jQuery(document).ready(function($){
		var geocoder;
		var map;
		var mapZoom = 12;
		var companyName = "";
		var companyPhone = "{$SNS_NAZ_STORE_PHONE}";
		var companyEmail = "{$SNS_NAZ_STORE_EMAIL}";
		var address = "{$SNS_NAZ_STORE_ADDRESS}";
		var companyInfo = "";

		var contentString = '<div style="min-width: 240px; min-height: 130px;">';
		contentString += '<h3>'+companyName+'</h3>';
		contentString += '<p>'+companyInfo+'</p>';
		contentString += '<ul class="fa-ul">';
		contentString += '<li><i class="fa-li fa fa-map-marker"></i>'+address+'</li>';
		contentString += '<li><i class="fa-li fa fa-mobile-phone"></i>'+companyPhone+'</li>';
		contentString += '<li><i class="fa-li fa fa-envelope"></i><a href="mailto:'+companyEmail+'">'+companyEmail+'</a></li>';
		contentString += '</ul>';
		contentString += '</div>';

		function initialize() {
			geocoder = new google.maps.Geocoder();
			geocoder.geocode( { 'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
					var marker = new google.maps.Marker({
						map: map,
						position: results[0].geometry.location,
						title: companyName
					});
					infowindow = new google.maps.InfoWindow({ content:contentString });
					//infowindow.open(map,marker);
					google.maps.event.addListener(marker, 'click', function() {
						infowindow.open(map,marker);
					});
				} else {
					alert('Geocode was not successful for the following reason: ' + status);
				}
			});
			var mapOptions = {
				zoom: mapZoom,
			}
			map = new google.maps.Map(document.getElementById('google_map'), mapOptions);
		}

		google.maps.event.addDomListener(window, 'load', initialize);
		google.maps.event.addDomListener(window, "resize", function() {
			 
		});	
		$('.btn_gmap').on('click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				
			} else {
				$(this).addClass('active');	
				
			}
		})	
	});
</script>
<!-- end google map -->
******}
