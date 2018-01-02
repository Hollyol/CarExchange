<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LanguageController extends Controller
{
	public function change(Request $request)
	{
		if (isset ($_POST['language']) AND isset ($_POST['url'])){
			if (!in_array($_POST['language'], $this->container->getParameter('locale.support'))){
				throw new NotAcceptableHttpException('This language is not supported');
			}
			//replace the locale parameter with the language from form
			$url = preg_replace('/\/[a-z]{2}\//', '/' . $_POST['language'] . '/', $_POST['url'], 1);
		} else {
			throw new PreconditionFailedHttpException('Missing arguments');
		}

		return $this->redirect($url);
	}
}
