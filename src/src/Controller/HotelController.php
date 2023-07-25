<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Hotel\SearchService;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/hotel')]
class HotelController extends AbstractController
{
    #[Route('/search', name: 'app_hotel_search', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function search(Request $request, SearchService $hotelSearchService): Response
    {
        $query = $request->query->get('q');

        return $this->render('hotel/index.html.twig', [
            'q' => $query,
            'hotels' => $hotelSearchService->search($query),
        ]);
    }

    #[Route('/', name: 'app_hotel_index', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function index(HotelRepository $hotelRepository): Response
    {
        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_hotel_new', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_HOTEL_OWNER")]
    public function new(Request $request, HotelRepository $hotelRepository): Response
    {
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hotelRepository->save($hotel, true);

            return $this->redirectToRoute('app_hotel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hotel/new.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_hotel_show', methods: ['GET'])]
    #[IsGranted("VIEW", "hotel")]
    public function show(Hotel $hotel): Response
    {
        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_hotel_edit', methods: ['GET', 'POST'])]
    //    #[IsGranted("ROLE_HOTEL_OWNER")]
    #[IsGranted("EDIT", "hotel")]
    public function edit(Request $request, Hotel $hotel, HotelRepository $hotelRepository): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hotelRepository->save($hotel, true);

            return $this->redirectToRoute('app_hotel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hotel/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_hotel_delete', methods: ['POST'])]
//    #[IsGranted("ROLE_HOTEL_OWNER")]
    #[IsGranted("DELETE", "hotel")]
    public function delete(Request $request, Hotel $hotel, HotelRepository $hotelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $hotel->getId(), $request->request->get('_token'))) {
            $hotelRepository->remove($hotel, true);
        }

        return $this->redirectToRoute('app_hotel_index', [], Response::HTTP_SEE_OTHER);
    }
}
