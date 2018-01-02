<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\Location;
use App\Form\Member\MemberSignUpType;

use Symfony\Component\HttpFoundation\Request;
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
			//Encode password
			$member->setPassword(
				$this->get('security.password_encoder')
				->encodePassword($member, $member->getPassword())
			);

			$em = $this->getDoctrine()->getManager();
			//Avoid duplicate location
			$member->setLocation(
				$em->getRepository(Location::class)
				->alreadyExists($member->getLocation())
			);

			$em->persist($member);
			$em->flush();

			$this->addFlash('usignin', 'account.creation_success');

			return $this->redirectToRoute('app_security.login');
		}

		return $this->render($request->getLocale() . '/users/signup.html.twig', array(
			'form' => $form->createView(),
		));
	}
}
