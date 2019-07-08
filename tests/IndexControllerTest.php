<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\VarDumper\Test\VarDumperTestTrait;

class IndexControllerTest extends WebTestCase
{
    use VarDumperTestTrait;

    public function testIndexPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // asserts that the response matches a given CSS selector.
//        $this->assertGreaterThan(0, $crawler->filter('.festival-card')->count());

    }

//    public function testAdminIndexPage() {
//
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'admin@admin.com',
//            'PHP_AUTH_PW'   => 'password',
//        ]);
//
//        $crawler = $client->request('GET', '/');
//
//        $this->assertContains('admin', $crawler->filter('.sidebar-heading')->text());
//
//    }

}
