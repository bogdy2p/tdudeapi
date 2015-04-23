<?php

namespace TimeDude\Bundle\TimeDudeBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use \Symfony\Component\HttpKernel\Exception\HttpException;
//use Rhumsaa\Uuid\Uuid;
//use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\SerializationContext;
use TimeDude\Bundle\TimeDudeBundle\Entity\Reward;
use TimeDude\Bundle\UserBundle\Entity\User;

class TimeDudeController extends FOSRestController {

    public function timezoneUTC() {
        return new \DateTimeZone('UTC');
    }

    /**
     * @Route("/userexists/{userId}", name="checkuserexists")
     * @Method("GET")
     *
     * @ApiDoc(
     *      deprecated=FALSE,
     * 		description = "Returns true if the user has been found by an id, or false.",
     *      section="Check User Exists",
     * 		statusCodes = {
     * 			200 = "True / False if User Exists",
     * 			404 = "User not found."
     * 		},
     * 		
     * )
     *
     */
    public function getUserexistsAction($userId) {


        $user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneByGoogleUid($userId);
        $response = new Response();


        if ($user) {
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => true,
                'message' => 'User found'
            )));
            return $response;
        }

        $response->setStatusCode(404);
        $response->setContent(json_encode(array(
            'success' => false,
            'message' => 'User not found.'
        )));
        return $response;
    }

    /**
     * @Route("/redeemItems", name="redeemItems")
     * @Method("Post")
     *
     * @ApiDoc(
     *      deprecated=FALSE,
     * 		description = "DESCRIPTION HERE.",
     *      section="RedeemItems",
     * 		statusCodes = {
     * 			200 = "User Exists",
     * 			404 = "User not found."
     * 		},
     *      parameters={
     *          {"name"="userId", "dataType"="string", "required"=true, "description"="The user's google id."},
     *          {"name"="gameId", "dataType"="string", "required"=true, "description"="The id of the game."},
     *          {"name"="itemId", "dataType"="string", "required"=true, "description"="The id of the item to add."},
     *          {"name"="ammount", "dataType"="integer", "required"=true, "description"="The ammount of items to add."},
     * }
     * 		
     * )
     *
     */
    public function postRedeemItemsAction(Request $request) {

        $date = new \DateTime();
        $response = new Response();

        $userId = $request->get('userId');
        $gameId = $request->get('gameId');
        $itemId = $request->get('itemId');
        $ammount = $request->get('ammount');

        if (empty($userId) || empty($gameId) || empty($itemId) || empty($ammount)) {
            $response->setStatusCode(400);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'Parameters are wrong or missing.'
            )));
            return $response;
        }
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneByGoogleUid($userId);

        if (!$user) {
            $response->setStatusCode(400);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'The user id provided is wrong. (no such user in the db)'
            )));
            return $response;
        }






        $type = 'coin';
        $coin = new Reward();
        $coin->setAmmount($ammount);
        $coin->setUser($user);

        $coin->setGameId($gameId);
        $coin->setDate($date);
        $em = $this->getDoctrine()->getManager();
        $em->persist($coin);
        $em->flush();

        $response->setStatusCode(201);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => 'User ' . $user->getFirstname() . ' received ' . $ammount . ' items of type ' . $type . ' in game ' . $gameId
        )));
        return $response;
    }

    /**
     * @Route("/userInformation/{userId}", name="get_user_information")
     * @Method("Get")
     *
     * @ApiDoc(
     *      deprecated=FALSE,
     * 		description = "Get User Information + Coin ammount.",
     *      section="User Information",
     * 		statusCodes = {
     * 			200 = "User Exists",
     * 			404 = "User not found."
     * 		},
     * 		
     * )
     *
     */
    public function getUserInformationAction($userId) {

        $user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneByGoogleUid($userId);

        $user_information = array();

        if ($user) {
            $rewards = $user->getRewards();
            $user_information['number_of_coins'] = count($rewards);
            $reward_value = 0;
            foreach ($rewards as $reward) {
                $reward_value += $reward->getAmmount();
            }
            $user_information['value'] = $reward_value;
        }
//        print_r($user_information);
//
//        die();

        $response = new Response();
        $response->setStatusCode(200);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => $user_information
        )));
        return $response;
    }

}
