<?php

namespace App\Tests;


use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\VarDumper\Test\VarDumperTestTrait;

class IndexControllerTest extends PantherTestCase
{
    use VarDumperTestTrait;
    
    public function testIndexPage()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');
        sleep(2);
        $this->assertCount(1, $crawler->filter('h1'));
        $this->assertSame(['Connexion'], $crawler->filter('h1')->text());
        $this->assertCount(1,$crawler->filter('form'));
        $this->assertCount(3,$crawler->filter('div'));
        sleep(2);
        $this->assertSame(['Email', 'Mot de passe'], $crawler->filter('input')->extract('type'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
//    public function testAdminIndexPage() {
//
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'admin@admin.com',
//            'PHP_AUTH_PW'   => 'password',
//        ]);
//        sleep(4);
//
//        $crawler = $client->request('GET', '/');
//    }

}
