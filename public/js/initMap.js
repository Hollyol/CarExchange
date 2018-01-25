function initMap()
{
	MAP_MODULE.geocoder = new google.maps.Geocoder;

	MAP_MODULE.fetchAddresses();

	MAP_MODULE.map = new google.maps.Map(document.getElementById('map_canva'), {
		zoom: 2,
		center: MAP_MODULE.center
	});

	function setMarker (addresses) {
		return new Promise(function (resolve, reject) {
			var errors;
			var len = addresses.length;
			for (i = 0; i < len; i++) {
				errors = (MAP_MODULE.geocodeAddress(addresses[i]) || errors);
			}
			setTimeout(function () {
				if (errors) {
					reject(errors);
				} else if (MAP_MODULE.locationsCode.length == 0) {
					reject('No location found');
				} else {
					resolve(MAP_MODULE.locationsCode);
				}
			}, 1000);
			}).then(function(code) {
			var len = code.length;
			for (i = 0; i < len; i++) {
				MAP_MODULE.putMarker(code[i]);
			}
		}, function (errors) {
			throw errors;
		});
	}

	setMarker(MAP_MODULE.locationsAddresses).then(function() {MAP_MODULE.smartCenter();});

}
