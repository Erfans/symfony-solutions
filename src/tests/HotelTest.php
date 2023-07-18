<?php

namespace App\Tests;

use App\Entity\Hotel;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HotelTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/hotel/');

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        /** @var HotelRepository $hotelRepo */
        $hotelRepo = $entityManager->getRepository(Hotel::class);

        $allHotels = $hotelRepo->findAll();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hotel index');

        $rows = $crawler->filter('table > tbody > tr');
        $this->assertCount(count($allHotels), $rows);
    }
}
