<?php

namespace MissionControl\Bundle\UserBundle\Tests\Controller;

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserAuthenticationTest extends WebTestCase {

    public function testUserAuthenticationParametersMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/authentication', array(
            'username' => $random . $random,
            'password' => $random . $random,
                ), array(), array()
        );
        $response = $client->getResponse()->getContent();
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains('Could not find a User with the given username and password', $response);
    }

    public function testUserAuthenticationResponse() {

        $client = static::createClient();
        $random = Uuid::uuid4()->toString();
        $request_user_register = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => 'TESTDELETE' . $random,
            'lastname' => 'TESTDELETE' . $random,
            'firstname' => 'TESTDELETE' . $random,
            'email' => 'TESTDELETE' . $random . '@' . $random . '.com',
            'phone' => 'TESTDELETE' . $random,
            'title' => 'TESTDELETE' . $random,
            'office' => 'TESTDELETE' . $random,
            'profile_picture' => 'TESTDELETE' . $random,
            'role_id' => 1,
            'password' => 'TESTDELETE' . $random,
                ), array(), array()
        );

        $request_user_authenticate = $client->request(
                'POST', '/users/authentication', array(
            'username' => 'TESTDELETE' . $random,
            'password' => 'TESTDELETE' . $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        //print_r($response);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertContains('API_KEY', $response);
    }

}
