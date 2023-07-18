<?php

namespace App\Tests\Integration;

use App\Entity\Hotel;
use App\Hotel\SearchService;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HotelSearchTest extends KernelTestCase
{

    protected static function getKernelClass(): string
    {
        return \App\Kernel::class;
    }

    public function testSomething(): void
    {
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());


        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $hotelRepo = $entityManager->getRepository(Hotel::class);


        $hotel1 = new Hotel();
        $hotel1->setName("abcd");
        $hotel1->setAddress("some address");

        $hotel2 = new Hotel();
        $hotel2->setName("cdef");
        $hotel2->setAddress("some address");

        $hotelRepo->save($hotel1);
        $hotelRepo->save($hotel2, true);

        $hotelSearchService = static::getContainer()->get(SearchService::class);
        $this->assertInstanceOf(SearchService::class, $hotelSearchService);

        $result = $hotelSearchService->search("ab");
        $this->assertContains($hotel1, $result);
        $this->assertNotContains($hotel2, $result);

        $result = $hotelSearchService->search("ef");
        $this->assertContains($hotel2, $result);
        $this->assertNotContains($hotel1, $result);

        $result = $hotelSearchService->search("cd");
        $this->assertContains($hotel1, $result);
        $this->assertContains($hotel2, $result);
    }
}
