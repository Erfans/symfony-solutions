<?php

namespace App\Hotel;

use App\Entity\Hotel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class SearchService
{

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {

        $this->entityManager = $entityManager;
    }

    /**
     * @param $hotelNameQuery
     *
     * @return Hotel[]
     */
    public function search($hotelNameQuery): array
    {
        /** @var \App\Repository\HotelRepository $hotelRepository */
        $hotelRepository = $this->entityManager->getRepository(Hotel::class);
        return $hotelRepository->searchByName($hotelNameQuery);
    }
}
