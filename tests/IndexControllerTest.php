<?php

namespace App\Tests;

use PHPUnit\Runner\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\VarDumper\Test\VarDumperTestTrait;

class IndexControllerTest extends WebTestCase
{
    use VarDumperTestTrait;

    public function testIndexPage()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
