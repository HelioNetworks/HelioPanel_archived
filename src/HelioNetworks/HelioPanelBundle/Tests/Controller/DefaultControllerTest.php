<?php

namespace HelioNetworks\HelioPanelBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testLoginScreen()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertTrue($crawler->filter('html:contains("Login to HelioPanel")')->count() > 0);
    }
}