<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\Location;
use App\Form\Member\MemberSignUpType;
use App\Form\Member\ApiMemberSignUpType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsersController extends Controller
{
	public function index(Request $request)
	{
		$this->denyAccessUnlessGranted('ROLE_USER', null, 'You must have an account to access the personal space');

		return $this->render($request->getLocale() . '/users/home.html.twig'
		);
	}

	public function addMember(Request $request)
	{
		$member = new Member();
		$form = $this->createForm(MemberSignUpType::class, $member);

		$form->handleRequest($request);
		if ($form->isSubmitted() AND $form->isValid()){

			$em = $this->getDoctrine()->getManager();

			$em->persist($member);
			$em->flush();

			$this->addFlash('usignin', 'account.creation_success');

			return $this->redirectToRoute('app_security.login');
		}

		return $this->render($request->getLocale() . '/users/signup.html.twig', array(
			'form' => $form->createView(),
		));
	}

	public function addFacebookMember(Request $request)
	{
		$member = new Member();

		if (preg_match('(/users/signup)', $request->headers->get('referer'))) {
			$member->setUsername($_POST['name']);
			$member->setMail($_POST['email']);
			$member->setLanguage(substr($_POST['locale'], 0, 2));
		}

		echo($member->getMail());

		$form = $this->createForm(ApiMemberSignUpType::class, $member);

		$form->handleRequest($request);
		if ($form->isSubmitted() AND $form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			$em->persist($member);
			$em->flush();

			$this->addFlash('usignin', 'account.creation_success');

			return $this->redirectToRoute('app_security.login');
		}

		return $this->render($request->getLocale() . '/users/apiSignUp.html.twig', array(
			'form' => $form->createView(),
		));
	}
}
