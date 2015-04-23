<?php

namespace MissionControl\Bundle\CampaignBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class GetCampaignRealLivesTest extends WebTestCase {

    public $existing_campaign_id = 'f29a70c2-2ea1-4dbc-bbf8-c4787e48092f';
    public $existing_api_key_with_no_access = 'testuser1';
    public $existing_api_key = '8f81fa58-a901-45a5-b444-c11dac04e384';
    public $invalid_campaign_id = 'abcdefghjklkmni123456';
    public $invalid_api_key = 'invalidapikeyhere';

    public function testGetCampaignRealLivesStatus200() {

        $client = static::createClient();
        $crawler = $client->request(
                'GET', 'api/v1/campaigns/' . $this->existing_campaign_id . '/reallives', array(), array(), array(
            'HTTP_x-wsse' => 'ApiKey="' . $this->existing_api_key . '"',
                )
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('real_lives_url', $response);
    }

    public function testGetCampaignRealLivesNoAuthentication500() {
        $client = static::createClient();
        $crawler = $client->request(
                'GET', 'api/v1/campaigns/' . $this->existing_campaign_id . '/reallives', array(), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
        $this->assertContains('A Token was not found', $response);
    }

    public function testGetCampaignRealLivesInvalidAuthentication403() {
        $client = static::createClient();

        $crawler = $client->request(
                'GET', 'api/v1/campaigns/' . $this->existing_campaign_id . '/reallives', array(), array(), array(
            'HTTP_x-wsse' => 'ApiKey="' . $this->invalid_api_key . '"',
                )
        );
        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testGetCampaignRealLivesCampaignNotFound404() {
        $client = static::createClient();

        $crawler = $client->request(
                'GET', 'api/v1/campaigns/' . $this->invalid_campaign_id . '/reallives', array(), array(), array(
            'HTTP_x-wsse' => 'ApiKey="' . $this->existing_api_key . '"',
                )
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertContains('Campaign does not exist', $response);
    }

}
