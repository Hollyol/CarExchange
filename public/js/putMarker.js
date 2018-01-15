function putMarker(address)
{
	geocoder.geocode({
		'address': address
	}, function(results, status) {
		if (status == 'OK') {
			var marker = new google.maps.Marker({
				map: map,
				position: results[0].geometry.location
			});
		} else {
			alert('Geocode failed because ' + status);
		}
	});
}
