var FB_MODULE = (function (module) {
	module.sendLoginData = function (name, id) {
		var form = document.createElement("form");
		form.setAttribute("method", "post");
		form.setAttribute("action", '/' + module.getLocale() +
				'/facebookLogin/');

		var authField = document.createElement("input");
		authField.setAttribute("name", "authType");
		authField.setAttribute("type", "hidden");
		authField.setAttribute("value", "facebookAuthentication");
		form.appendChild(authField);

		var nameField = document.createElement("input");
		nameField.setAttribute("name", "name");
		nameField.setAttribute("type", "hidden");
		nameField.setAttribute("value", name);
		form.appendChild(nameField);

		var idField = document.createElement("input");
		idField.setAttribute("name", "id");
		idField.setAttribute("type", "hidden");
		idField.setAttribute("value", id);
		form.appendChild(idField);

		document.body.appendChild(form);
		form.submit();
	}

	module.loginToApp = function () {
		FB.api('/me', function(response) {
			module.sendLoginData(response.name, response.id);
		});
	};

	module.initLogin = function (id) {
		document.getElementById(id).addEventListener('click', function () {
			FB.login(function (response) {
				if (response.authResponse) {
					module.loginToApp();
				} else {
					console.log('user authentication failed (facebook)');
				}
			});
		});
	};

	return module;
}) (FB_MODULE || {});
