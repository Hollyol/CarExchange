<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Securtiy\Core\User\UserProviderInterface;
use Symfony\Component\Securtiy\Core\User\UserInterface;

class FacebookAuthenticator extends AbstractGuardAuthenticator
{
	public function supports(Request $request)
	{
		return (isset($_POST['authType']) && $_POST['authType'] == 'facebookAuthentication');
	}

	public function getUser($credentials, \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider)
	{
		if ($credentials === null) {
			return;
		}

		return $userProvider->loadUserByUsername($credentials);
	}

	public function getCredentials(Request $request)
	{
		return $_POST['name'];
	}

	public function checkCredentials($credentials, \Symfony\Component\Security\Core\User\UserInterface $user)
	{
		return true;
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
	{
		return new RedirectResponse('/' . $request->getLocale() . '/users/');
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		$message = array(
			'message' => 'You are not a registered member(' . $_POST['id'] . ')'
		);

		return new JsonResponse($message, Response::HTTP_FORBIDDEN);
	}

	public function start(Request $request, AuthenticationException $exception = null)
	{
		$message = array(
			'message' => 'Authentication Required'
		);

		return new JsonResponse($message, Response::HTTP_FORBIDDEN);
	}

	public function supportsRememberMe()
	{
		return false;
	}
}
