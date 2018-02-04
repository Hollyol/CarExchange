var FB_MODULE = (function (module) {
	module.sendSignUpData = function (userData) {
		//create a form
		var form = document.createElement("form");
		form.setAttribute("method", "post");
		form.setAttribute("action", '/' + module.getLocale() + '/users/facebookSignUp/');

		for (var key in userData) {
			if (userData.hasOwnProperty(key)) {
				var newField = document.createElement("input");
				newField.setAttribute("type", "hidden");
				newField.setAttribute("name", key);
				newField.setAttribute("value", userData[key]);

				form.appendChild(newField);
			}
		}

		document.body.appendChild(form);
		form.submit();
	}

	module.fetchRequiredUserData = function () {
		FB.api('/me?fields=email,name,locale', function (response) {
			module.sendSignUpData({
				name: response.name,
				email: response.email,
				locale: response.locale,
			});
		});
	};

	module.initSignUp = function (id) {
		document.getElementById(id).addEventListener('click', function () {
			FB.login(function (response) {
				if (response.authResponse) {
					module.fetchRequiredUserData();
				} else {
					console.log('user authentication failed (facebook)');
				}
			}, {
				scope: 'email,public_profile',
				return_scopes: true
			});
		});
	};

	return module;
}) (FB_MODULE || {});
