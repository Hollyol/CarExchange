<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
	public function index(Request $request)
	{
		return $this->render(
			$request->getLocale() . '/layout.html.twig'
		);
	}
}
