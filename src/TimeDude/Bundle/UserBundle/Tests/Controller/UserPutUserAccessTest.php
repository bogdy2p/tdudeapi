<?php

namespace MissionControl\Bundle\UserBundle\Tests\Controller;

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserPutUserAccessTest extends WebTestCase {

    //TESTS CONFIGURATION // THIS MUST BE REAL VALUES OR TESTS WILL TOTALLY FAIL.
    //TESTS CONFIGURATION // THIS MUST BE REAL VALUES OR TESTS WILL TOTALLY FAIL.
    //TESTS CONFIGURATION // THIS MUST BE REAL VALUES OR TESTS WILL TOTALLY FAIL.

    public $existing_user_id_with_no_access = 1;
    public $existing_api_key = 'testuser1';

    /*
     * Test One Description
     * 
     * This test is intended to validate the case when the client_id parameter is not valid
     */

    public function testOne() {

        $client = static::createClient();

        $crawler = $client->request(
                'PUT', 'api/v1/users/' . $this->existing_user_id_with_no_access . '/access', array(
            'client_id' => 'This_should_be_a_integer_not_a_string',
            'region_id' => 3,
            'country_id' => 3,
            'all_countries' => 1,
                ), array(), array(
            'HTTP_x-wsse' => 'ApiKey="' . $this->existing_api_key . '"',
                )
        );

        $response = $client->getResponse()->getContent();

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains('Invalid client', $response);
    }

    /*
     * Test the API response of this call for the case when all the 
     * 
     */

    public function testTwo() {

        $client = static::createClient();

        $crawler = $client->request(
                'PUT', 'api/v1/users/thisisnotavaliduser/access', array(
                ), array(), array(
            'HTTP_x-wsse' => 'ApiKey="' . $this->existing_api_key . '"',
                )
        );

        $response = $client->getResponse()->getContent();

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains('Invalid user', $response);
    }

    public function testThree() {

        $client = static::createClient();

        $crawler = $client->request(
                'PUT', 'api/v1/users/' . $this->existing_user_id_with_no_access . '/access', array(
            'client_id' => 1,
            'region_id' => 6543210,
            'country_id' => 3,
            'all_countries' => 1,
                ), array(), array(
            'HTTP_x-wsse' => 'ApiKey="' . $this->existing_api_key . '"',
                )
        );

        $response = $client->getResponse()->getContent();
        //print_r($response);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains('Invalid region', $response);
    }

    public function testFour() {

        $client = static::createClient();
        $crawler = $client->request(
                'PUT', 'api/v1/users/' . $this->existing_user_id_with_no_access . '/access', array(
            'client_id' => 1,
            'region_id' => 2,
                ), array(), array(
            'HTTP_x-wsse' => 'ApiKey="' . $this->existing_api_key . '"',
                )
        );

        $response = $client->getResponse()->getContent();

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains('All countries must be set to true or false', $response);
    }

    public function testFive() {

        $client = static::createClient();
        $crawler = $client->request(
                'PUT', 'api/v1/users/' . $this->existing_user_id_with_no_access . '/access', array(
            'client_id' => 1,
            'region_id' => 1,
            'all_countries' => false,
                ), array(), array(
            'HTTP_x-wsse' => 'ApiKey="' . $this->existing_api_key . '"',
                )
        );


        $response = $client->getResponse()->getContent();

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        // $this->assertContains('If all_countries for the region is false , you must set a country', $response);
    }

}
