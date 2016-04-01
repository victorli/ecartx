$(document).ready(function(){
    if (!(typeof defaultLat === 'undefined') && !(typeof defaultLong === 'undefined')) {
        var mapCanvas = document.getElementById('map_canvas');
        var myLatlng = new google.maps.LatLng(defaultLat,defaultLong);
        var mapOptions = {
          center: myLatlng,
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(mapCanvas, mapOptions)
        var marker = new google.maps.Marker({
          position: myLatlng,
          map: map,
          title: storeName
      });
    }
});