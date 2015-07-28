/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

var map, ele, mapH, mapW, addEle, mapL, mapN, mapZ;

ele = 'config_company_mapcanvas';
addEle = 'config_company_address';
mapLat = 'config_company_maplat';
mapLng = 'config_company_maplng';
mapZ = 'config_company_mapzoom';
mapArea = 'config_company_maparea';
mapShow = 'config_company_mapshow';

if( ! document.getElementById('googleMapAPI') ){
	var s = document.createElement('script');
	s.type = 'text/javascript';
	s.id = 'googleMapAPI';
	s.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&callback=controlMap';
	document.body.appendChild(s);
}else{
	controlMap();
}

function initializeMap(){
	var zoom = parseInt($("#" + mapZ).val()), lat = parseFloat($("#" + mapLat).val()), lng = parseFloat($("#" + mapLng).val());
	zoom || (zoom = 13, $("#" + mapZ).val(zoom));
	lat || (lat = 21.0278, $("#" + mapLat).val(lat));
	lng || (lng = 105.8441, $("#" + mapLng).val(lng));
	
	mapW = $('#' + ele).innerWidth();
	mapH = mapW * 3 / 4;
	
	$('#' + ele).width(mapW).height(mapH > 500 ? 500 : mapH);
	map = new google.maps.Map(document.getElementById(ele),{
		zoom: zoom,
		center: {
			lat: 21.0278,
			lng: 105.8441
		}
	});
	
	var markers = [];
	markers[0] = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(lat,lng),
        draggable: true,
        animation: google.maps.Animation.DROP
    });
				
	var searchBox = new google.maps.places.SearchBox(document.getElementById(addEle));
	
	google.maps.event.addListener(searchBox, 'places_changed', function(){
	    var places = searchBox.getPlaces();
	
	    if (places.length == 0) {
	        return;
	    }
	    
	    for (var i = 0, marker; marker = markers[i]; i++) {
	        marker.setMap(null);
	    }
	
	    markers = [];
	    var bounds = new google.maps.LatLngBounds();
	    for (var i = 0, place; place = places[i]; i++) {
	        var image = {
	            url: place.icon,
	            size: new google.maps.Size(71, 71),
	            origin: new google.maps.Point(0, 0),
	            anchor: new google.maps.Point(17, 34),
	            scaledSize: new google.maps.Size(25, 25)
	        };
	
	        var marker = new google.maps.Marker({
	            map: map,
	            icon: image,
	            title: place.name,
	            position: place.geometry.location
	        });
	
	        markers.push(marker);
	
	        bounds.extend(place.geometry.location);
	    }
	
	    map.fitBounds(bounds);
		console.log( places );
	});
}

function controlMap(manual){
	if( $('#' + mapShow).val() == 1 ){
		if(manual){
			$('body,html').animate({scrollTop:$('#' + mapShow).offset().top},500, function(){
				$('#' + mapArea).slideDown(100, function(){
					initializeMap();
				});
			});
		}else{
			$('#' + mapArea).slideDown(100, function(){
				initializeMap();
			});
		}
	}else{
		$('#' + mapArea).slideUp(100);
	}
	return !1;
}