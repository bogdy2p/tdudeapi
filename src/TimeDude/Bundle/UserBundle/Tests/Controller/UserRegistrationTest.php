<?php

namespace MissionControl\Bundle\UserBundle\Tests\Controller;

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegistrationTest extends WebTestCase {

    public function testUserRegistrationActiveParameterMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'username' => $random,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );
        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('Field active must have a value', $response);
    }

    public function testUserRegistrationUsernameParameterMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('Field username must have a value', $response);
    }

    public function testUserRegistrationLastnameParameterMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('Field lastname must have a value', $response);
    }

    public function testUserRegistrationFirstnameParameterMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'lastname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('Field firstname must have a value', $response);
    }

    public function testUserRegistrationEmailParameterMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'lastname' => $random,
            'firstname' => $random,
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('Field email must have a value', $response);
    }

    public function testUserRegistrationPhoneParameterMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('Field phone must have a value', $response);
    }

    public function testUserRegistrationTitleParameterMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('Field title must have a value', $response);
    }

    public function testUserRegistrationOfficeParameterMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('Field office must have a value', $response);
    }

    public function testUserRegistrationProfilePictureParameterMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('Field profile_picture must have a value', $response);
    }

    public function testUserRegistrationRoleIdParameterMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('Field role_id must have a value', $response);
    }

    public function testUserRegistrationPasswordParameterMissing() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('Field password must have a value', $response);
    }

    public function testUserRegistrationRoleIdParameterWrong() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 4,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertContains('You provided an invalid role id', $response);
    }

    public function testUserRegistrationUsernameIsTaken() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $username = 'PHaPUNITTEST';
        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $username,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $username,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains('The username provided is already in use.', $response);
    }

    public function testUserRegistrationEmailIsAlreadyInUse() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $email = 'PHPUNITTEST@emailFORTESTING.com';
        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $email,
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random.'1',
            'lastname' => $random,
            'firstname' => $random,
            'email' => $email,
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains('The email provided is already in use.', $response);
    }

     public function testUserRegistrationUsernameIsTooShort() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        
        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => '000',
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => $random,
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains('minimum of 4 characters.', $response);
    }
    
     public function testUserRegistrationPasswordIsTooShort() {
        $client = static::createClient();

        $random = Uuid::uuid4()->toString();

        $crawler = $client->request(
                'POST', '/users/registration', array(
            'active' => true,
            'username' => $random,
            'lastname' => $random,
            'firstname' => $random,
            'email' => $random . '@' . $random . '.com',
            'phone' => $random,
            'title' => $random,
            'office' => $random,
            'profile_picture' => $random,
            'role_id' => 1,
            'password' => 'aa12',
                ), array(), array()
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains('between 6 and 200 characters', $response);
    }
}
