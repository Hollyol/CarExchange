function initMap()
{
	geocoder = new google.maps.Geocoder();

	var location = {lat: 48.859, lng: 2.346};
	map = new google.maps.Map(document.getElementById('map_canva'), {
		zoom: 5,
		center: location
	});
}
