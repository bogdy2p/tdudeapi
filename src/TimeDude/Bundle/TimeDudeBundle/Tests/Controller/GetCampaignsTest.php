<?php

namespace MissionControl\Bundle\CampaignBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class GetCampaignsTest extends WebTestCase {

    public $existing_user_id_with_no_access = 1;
    public $existing_api_key = 'testuser1';
    public $invalid_campaign_id = 'abcdefghjklkmni123456';
    public $invalid_api_key = 'invalidapikeyhere';

    public function testGetCampaignsStatus200() {


        $client = static::createClient();

        $crawler = $client->request(
                'GET', 'api/v1/campaigns', array(), array(), array(
            'HTTP_x-wsse' => 'ApiKey="' . $this->existing_api_key . '"',
                )
        );

        $response = $client->getResponse()->getContent();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetCampaignsNoAuthentication500() {
        $client = static::createClient();

        $crawler = $client->request(
                'GET', 'api/v1/campaigns', array(), array(), array()
        );

        $response = $client->getResponse()->getContent();

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }

    public function testGetCampaignsInvalidAuthentication403() {
        $client = static::createClient();

        $crawler = $client->request(
                'GET', 'api/v1/campaigns', array(), array(), array(
            'HTTP_x-wsse' => 'ApiKey="' . $this->invalid_api_key . '"',
                )
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

}
