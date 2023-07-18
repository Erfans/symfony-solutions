<?php

namespace App\Tests\Hotel;

use App\Entity\Hotel;
use App\Hotel\SearchService;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class SerachServiceTest extends TestCase
{
    public function testSomething(): void
    {
        $hotel = new Hotel();
        $hotel->setName("Hotel 1");

        // mock repository
        $hotelRepository = $this->createMock(HotelRepository::class);
        $hotelRepository->expects($this->exactly(1))
            ->method('searchByName')
            ->with("1234")
            ->willReturn([$hotel]);

        // mock entity manager
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->exactly(1))
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($hotelRepository);

        // instance service
        $searchService = new SearchService($entityManager);
        $result = $searchService->search("1234");

        // assert
        $this->assertContains($hotel, $result);
    }
}
