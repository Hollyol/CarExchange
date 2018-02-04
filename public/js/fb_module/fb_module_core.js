var FB_MODULE = (function (module) {
	module.statusChangeCallback = function (response) {
		console.log('statusChangeCallback');
		console.log(response);

		if (response.status === 'connected') {
			module.loginToApp();
		} else {
		}
	};

	module.getLocale = function () {
		return (window.location.href.match('/[a-z]{2}/')[0].substr(1, 2));
	}

	return module;
}) (FB_MODULE || {});
