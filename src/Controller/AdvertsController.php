<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use App\Form\Advert\AddAdvertType;
use App\Form\Advert\SearchAdvertType;
use App\Form\Rental\AddRentalType;
use App\Entity\Advert;
use App\Entity\Rental;
use App\Entity\Location;

class AdvertsController extends Controller
{
	public function addAdvert(Request $request)
	{
		$this->denyAccessUnlessGranted('ROLE_USER', null, 'You must have an account to rent your car');

		$advert = new Advert();
		$advert->setLocation(clone $this->getUser()->getLocation());
		$form = $this->createForm(AddAdvertType::class, $advert);

		$form->handleRequest($request);
		if ($form->isSubmitted() AND $form->isValid()){
			$em = $this->getDoctrine()->getManager();

			//Set owner
			$advert->setOwner($this->getUser());

			$em->persist($advert);
			$em->flush();

			$this->addFlash('uhome', 'advert.creation_success');

			return $this->redirectToRoute('app_users.home');
		}

		return $this->render($request->getLocale() . '/adverts/add.html.twig', array(
			'form' => $form->createView(),
		));
	}

	public function rent(Request $request, $id)
	{
		$this->denyAccessUnlessGranted('ROLE_USER', 'You must be logged in with a registered account to rent a car');

		$em = $this->getDoctrine()->getManager();
		$advert = $em->getRepository(Advert::class)->find($id);

		if (!$advert){
			throw new NotFoundHttpException('The advert #' . $id . ' doesn\'t exists');
		}

		$rental = new Rental();
		$rental->setAdvert($advert);
		$rental->setRenter($this->getUser());
		$form = $this->createForm(AddRentalType::class, $rental);

		$form->handleRequest($request);
		if ($form->isSubmitted() AND $form->isValid()){
			$em = $this->getDoctrine()->getManager();

			if ($advert->isRented($rental->getBeginDate(), $rental->getEndDate())){
				$this->addFlash('arent', 'advert.already_rented');
				
				return $this->render($request->getLocale() . '/adverts/rent.html.twig', array(
					'advert' => $advert,
					'form' => $form->createView(),
				));
			}

			$rental->setStatus('asking');
			$em->persist($rental);
			$em->flush();

			$this->addFlash('uhome', 'rental.aks_success');
			return $this->redirectToRoute('app_users.home');
		}

		return $this->render($request->getLocale() . '/adverts/rent.html.twig', array(
			'advert' => $advert,
			'form' => $form->createView(),
		));
	}

	public function search(Request $request)
	{

		$advert = new Advert();
		$listAdverts = [];
		$form = $this->createForm(SearchAdvertType::class, $advert);

		$form->handleRequest($request);
		if ($form->isSubmitted() AND $form->isValid()){
			$listAdverts = $this->getDoctrine()->getManager()
				->getRepository(Advert::class)
				->fetchSearchResults($advert);
		} else if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){
			$listAdverts = $this->getDoctrine()->getManager()
				->getRepository(Advert::class)
				->findByLocation($advert->getLocation(), null, 15);
		}

		return $this->render($request->getLocale() . '/adverts/search.html.twig', array(
			'form' => $form->createView(),
			'listAdverts' => $listAdverts,
			'count' => count($listAdverts),
		));
	}

	public function delete(Request $request, int $id)
	{
		$this->denyAccessUnlessGranted('ROLE_USER', null, 'You must be logged as a registered member to delete adverts');

		$em = $this->getDoctrine()->getManager();
		$advert = $em->getRepository(Advert::class)->find($id);

		//If advert doesn't exists
		if (!$advert){
			throw new NotFoundHttpException('The advert #' . $id . ' doesn\'t exists');
		}

		//If user is not owner and not allowed to delete any advert
		if (!
			($this->getUser() === $advert->getOwner()) OR
			($this->get('security.authorization_checker')->isGranted('ROLE_ADVERT_DELETOR'))
		){
			throw new AccessDeniedHttpException('You are not allowed to delete this advert');
		}

		if ($advert->isRented(new \Datetime(), $advert->getEndDate())){
			throw new UnauthorizedHttpException('Your car is rented, you can\'t remove it');
		}

		$em->remove($advert);
		$em->flush();

		$this->addFlash('uhome', 'advert.deletion_success');
		return $this->redirectToRoute('app_users.home');
	}

	public function edit(Request $request, int $id)
	{
		$this->denyAccessUnlessGranted('ROLE_USER', null, 'You must be logged as a registered member to edit adverts');

		$em = $this->getDoctrine()->getManager();
		$advert = $em->getRepository(Advert::class)->find($id);

		//If advert doesn't exists
		if (!$advert){
			throw new NotFoundHttpException('The advert #' . $id . ' doesn\'t exists');
		}

		//If user is not owner and not allowed to edit any advert
		if (!
			($this->getUser() === $advert->getOwner()) OR
			($this->get('security.authorization_checker')->isGranted('ROLE_ADVERT_EDITOR'))
		){
			throw new AccessDeniedHttpException('You are not allowed to delete this advert');
		}

		$em->flush();

		$this->addFlash('uhome', 'advert.edition_success');
		return $this->redirectToRoute('app_users.home');
	}
}
