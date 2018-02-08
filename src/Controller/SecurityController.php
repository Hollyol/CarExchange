<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
	public function login(Request $request, AuthenticationUtils $authUtils)
	{
		$error = $authUtils->getLastAuthenticationError();
		$lastUsername = $authUtils->getLastUsername();

		return $this->render($request->getLocale() . '/security/login.html.twig', array(
			'last_username' => $lastUsername,
			'error' => $error,
		));
	}

	public function facebookLogin(Request $request)
	{
		return new Response('<body><h1>Facebook login</h1>
			<a href = \'/fr\'>Home</a></body>');
	}
}
