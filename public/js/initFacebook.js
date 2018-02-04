//Loads facebook SDK
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	var locale = window.location.href.match('/[a-z]{2}/')[0].substr(1, 2);
	js.src = 'https://connect.facebook.net/' + locale + '/sdk.js';
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

window.fbAsyncInit = function () {
	FB.init({
		appId: '139420946865203',
		cookie: true,
		mfbml: true,
		version: 'v2.8',
	});

	FB.getLoginStatus(function(response) {
		FB_MODULE.statusChangeCallback(response);
	});

	if (typeof FB_MODULE.initLogin == 'function')
		FB_MODULE.initLogin('facebook_login');
	if (typeof FB_MODULE.initSignUp == 'function')
		FB_MODULE.initSignUp('facebook_signup', 'email,public_profile,user_location');
};
