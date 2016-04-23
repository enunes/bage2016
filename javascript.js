$('.navbar a').on('click', function(event) {
     event.preventDefault();
     var location_anchor = $(this).attr('href');
     if(location_anchor == '#'){
        location_anchor = 'html';
     }
     $('html, body').animate({
      scrollTop: $(location_anchor).offset().top
      }, 1000);
});

$('#footer-menu li a').on('click', function(event) {
     event.preventDefault();
     var location_anchor = $(this).attr('href');
     if(location_anchor == '#'){
        location_anchor = 'html';
     }
     $('html, body').animate({
      scrollTop: $(location_anchor).offset().top
      }, 1000);
});

$('#buscar_caminho').on('click', function(e) {
    e.preventdefault()
    if (navigator.geolocation) { 
        navigator.geolocation.getCurrentPosition(function(position) {

        var point = new google.maps.LatLng(position.coords.latitude, 
                                    position.coords.longitude);
        calcRoute(point);
        });
    }
});

$('.schedule-tbl a').on('click', function(event) {
     event.preventDefault();
     var location_anchor = $(this).attr('href');
     if(location_anchor == '#'){
        location_anchor = 'html';
     }
     $('html, body').animate({
      scrollTop: $(location_anchor).offset().top
      }, 1000);
});

//map
var geocoder = new google.maps.Geocoder();
var unipampa;
var directionsDisplay;
var directionsService;
geocoder.geocode( { 'address': 'Av. Maria Anunciação Gomes Godoy 1650, Bagé'}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
    	unipampa = results[0]['geometry']['location'];
    	
    	var mapOptions = {
    	  zoom: 15,
    	  center: unipampa,
    	  streetViewControl: false,
    	  panControl: true,
    	  overviewMapControl: true,
    	  zoomControl: true,
    	  scaleControl: true
    	}
    	var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

    	directionsService = new google.maps.DirectionsService();

    	directionsDisplay = new google.maps.DirectionsRenderer();
    	directionsDisplay = new google.maps.DirectionsRenderer();
    	directionsDisplay.setMap(map);
    	directionsDisplay.setPanel(document.getElementById('directions-panel'));
    	
    	marker = new google.maps.Marker({
			position: unipampa,
			map: map,
			title: "UNIPAMPA"
    	});
    } else {
    	console.log("Geocode was not successful for the following reason: " + status);
    }
});


function calcRoute(starte) {
	var start = starte != undefined ? starte : document.getElementById("search-route").value;
	var request = {
		origin: start,
		destination: unipampa,
		travelMode: google.maps.TravelMode.DRIVING
	};
	directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
		}
	});
}

autocomplete = new google.maps.places.Autocomplete(
    document.getElementById('search-route'),
    { types: ['geocode'] }
);
	
// When the user selects an address from the dropdown,
// populate the address fields in the form.
google.maps.event.addListener(autocomplete, 'place_changed', function() {
	calcRoute();
});


document.getElementById("search-route").addEventListener("keypress", function(e){
    if (e.keyCode == 13) {
        calcRoute(); 
        return false;
    }
});

$('body').scrollspy({ target: '#main-navbar' });

$(document).on('click','.navbar-collapse.in',function(e) {
    if( $(e.target).is('a') ) {
        $(this).collapse('hide');
    }
});

$(document).on('click','a.navbar-brand',function(e) {
    if( $(e.target).is('a') ) {
        $('.navbar-collapse.in').collapse('hide');
    }
});
