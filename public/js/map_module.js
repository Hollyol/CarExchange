var MAP_MODULE = (function () {
	var center = {
		lat: 48.859,
		lng: 2.346
	};

	var putMarker = function (position) {
		if (this.map == null) {
			alert('no map');
		}
		var marker = new google.maps.Marker({
			position: position,
			map: this.map
		});
	}

	var getLatLng = function (coordinates) {
		strCoords = String(coordinates);
		XY = strCoords.split(', ');
		return {
			lat: Number(XY[0].replace('(', '')),
			lng: Number(XY[1].replace(')', '')),
		};
	}

	var geocodeAddress = function (address) {
		if (this.geocoder == null) {
			throw "No Geocoder";
		}
		this.geocoder.geocode({'address': address}, function (results, status) {
			if (status == 'OK') {
				locationsCode.push(getLatLng(results[0].geometry.location));
			} else {
				return("Geocode failed : " + status);
			}
		});
	}

	var fetchAddresses = function () {
		elements = document.getElementsByClassName('address');
		var nbAddresses = elements.length;
		for (i = 0; i < nbAddresses; i++) {
			locationsAddresses.push(elements[i].innerHTML);
		}
	}

	var smartCenter = function () {
		var len = locationsCode.length;

		var externs = [
			locationsCode[0],
			locationsCode[0],
			locationsCode[0],
			locationsCode[0]
		];
		//fetch the most external positions
		//(0 -> north, 1 -> south, 2->east , 3->west)
		for (i = 1; i < len; i++) {
			if (locationsCode[i].lat > externs[0].lat) {
				externs[0] = locationsCode[i];
			}
			if (locationsCode[i].lat < externs[1].lat) {
				externs[1] = locationsCode[i];
			}
			if (locationsCode[i].lng > externs[2].lng) {
				externs[2] = locationsCode[i];
			}
			if (locationsCode[i].lng < externs[3].lng) {
				externs[3] = locationsCode[i];
			}
		}

		this.center.lat = (externs[0].lat + externs[1].lat) / 2;
		this.center.lng = (externs[2].lng + externs[3].lng) / 2;

		this.map.setCenter(this.center);

		//And now the auto zoom
		this.map.fitBounds({
			east: externs[2].lng,
			north: externs[0].lat,
			south: externs[1].lat,
			west: externs[3].lng
		});
	}


	var geocoder;

	var locationsCode = [];
	var locationsAddresses = [];

	var map;

	return {
		center: center,
		geocoder: geocoder,
		map: map,
		putMarker: putMarker,
		locationsCode: locationsCode,
		locationsAddresses: locationsAddresses,
		fetchAddresses: fetchAddresses,
		smartCenter: smartCenter,
		geocodeAddress: geocodeAddress
	};
}) ();
