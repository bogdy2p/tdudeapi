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
use RMS\PushNotificationsBundle\Message\AndroidMessage;
use RMS\PushNotificationsBundle\Message\MessageInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\SerializationContext;
use TimeDude\Bundle\TimeDudeBundle\Entity\Reward;
use TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser;
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
     *      section="User",
     * 		statusCodes = {
     * 			200 = "True / False if User Exists",
     * 			404 = "User not found."
     * 		},
     * requirements = {
     *          {"name" = "userId", "requirement" = "true"}
     * 		}
     * )
     *
     */
    public function getUserexistsAction($userId) {


        $user = $this->getDoctrine()->getRepository('TimeDudeBundle:TimeDudeUser')->findOneByGoogleUid($userId);
        $response = new Response();


        if ($user) {
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => true,
                'message' => 'User found'
            )));
            return $response;
        }

        $response->setStatusCode(200);
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
     * 			201 = "User Has Been Rewarded / Penalized",
     * 			404 = "User not found.",
     *                  500 = "No token / Invalid API KEY",
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

        $user_making_the_call = $this->getUser();
        $http_call_by = $user_making_the_call->getUsername();

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
        $user = $this->getDoctrine()->getRepository('TimeDudeBundle:TimeDudeUser')->findOneByGoogleUid($userId);

        if (!$user) {
            $response->setStatusCode(400);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'The user id provided is wrong. (no such user in the db)'
            )));
            return $response;
        }

        $rewardType = $this->getDoctrine()->getRepository('TimeDudeBundle:RewardType')->findOneById($itemId);

        if (!$rewardType) {
            $response->setStatusCode(400);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'Invalid reward type / itemId'
            )));
            return $response;
        }

        $game = $this->getDoctrine()->getRepository('TimeDudeBundle:Game')->findOneById($gameId);

        if (!$game) {
            $response->setStatusCode(400);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'Invalid game.'
            )));
            return $response;
        }



        $reward = new Reward();
        $reward->setAmmount($ammount);
        $reward->setUser($user);
        $reward->setRewardtype($rewardType);
        $reward->setGame($game);
        $reward->setDate($date);
        $reward->setHttpcallby($http_call_by);
        $em = $this->getDoctrine()->getManager();
        $em->persist($reward);
        $em->flush();

        if ($ammount > 0) {
            $lost_receive = 'received';
        } else {
            $lost_receive = 'lost';
        }

        $message = "Redeem Items";

        $registration_id = $user->getRegistrationId();

        $data = array(
            'message' => $message,
            'title' => 'You received ' . abs($ammount) . ' ' . ucfirst($rewardType->getName()),
            'collapse_key' => 'do_not_collapse',
            'vib' => 1,
            'pw_msg' => 1,
            'p' => 5);

        $notify = self::notifyAndroid($registration_id, $data);

        $response->setStatusCode(201);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => 'User ' . $user->getId() . ' ' . $lost_receive . ' ' . abs($ammount) . ' items of type ' . ucfirst($rewardType->getName()) . ' for game ' . $game->getName(),
            'notify' => $notify
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
     *      section="User",
     * 		statusCodes = {
     * 			200 = "User Exists",
     * 			404 = "User not found."
     * 		},
     *      requirements= {
     * 		 {"name" = "userId", "requirement" = "true"}
     * 		}
     * )
     *
     */
    public function getUserInformationAction($userId) {

        $user = $this->getDoctrine()->getRepository('TimeDudeBundle:TimeDudeUser')->findOneByGoogleUid($userId);
        $response = new Response();

        if (!$user) {
            $response->setStatusCode(400);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'The user id provided is wrong.'
            )));
            return $response;
        }


        $user_information = array();


        $rewards = $user->getRewards();
        $user_information['number_of_calls'] = count($rewards);
        $reward_value = 0;
        foreach ($rewards as $reward) {
            $reward_value += $reward->getAmmount();
        }
        $user_information['value'] = $reward_value;

        $response = new Response();
        $response->setStatusCode(200);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => $user_information
        )));
        return $response;
    }

    /**
     * @Route("/usergameinfo/{userId}/{gameId}", name="get_user_information_specific_game")
     * @Method("Get")
     *
     * @ApiDoc(
     *      deprecated=FALSE,
     * 		description = "Get User Information + Coin ammount.",
     *      section="User",
     * 		statusCodes = {
     * 			200 = "User Exists",
     * 			404 = "User not found."
     * 		},
     *      requirements = {
     *          {"name" = "userId", "requirement" = "true"},
     *          {"name" = "gameId", "requirement" = "true"}
     * }
     * 		
     * )
     *
     */
    public function getRewardInformationForGameAction(Request $request) {
        $response = new Response();

        $userId = $request->get('userId');
        $gameId = $request->get('gameId');

        if (!isset($userId) || !isset($gameId)) {
            $response->setStatusCode(400);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'Please provide both required request parameters'
            )));
            return $response;
        }

        $user = $this->getDoctrine()->getRepository('TimeDudeBundle:TimeDudeUser')->findOneByGoogleUid($userId);
        if (!$user) {
            $response->setStatusCode(400);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'The user id provided is wrong.'
            )));
            return $response;
        }

        $game = $this->getDoctrine()->getRepository('TimeDudeBundle:Game')->findOneById($gameId);
        if (!$game) {
            $response->setStatusCode(400);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'The game id provided is wrong.'
            )));
            return $response;
        }

        $user_information = array();

        if ($user) {
            $rewards = $user->getRewards();
            $user_information['number_of_calls'] = count($rewards);
            $reward_value = 0;
            foreach ($rewards as $reward) {
                $reward_value += $reward->getAmmount();
            }
            $user_information['value'] = $reward_value;
        }


        $response->setStatusCode(200);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => $user_information
        )));
        return $response;
    }

    /**
     * @Route("/itemtypes", name="getitemtypes")
     * @Method("GET")
     *
     * @ApiDoc(
     *      deprecated=FALSE,
     * 		description = "Returns true if the user has been found by an id, or false.",
     *      section="Item Related",
     * 		statusCodes = {
     * 			200 = "Ok",
     * 		},
     * )
     *
     */
    public function getItemTypesAction() {

        $rewards = $this->getDoctrine()->getRepository("TimeDudeBundle:RewardType")->findAll();
        $response = new Response();

        if (!$rewards) {
            $response->setStatusCode(404);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'There are no reward types in the database.'
            )));
            return $response;
        }

        $return_array = array();

        foreach ($rewards as $reward) {
            $return_array[ucfirst($reward->getName())] = $reward->getId();
        }

        $response->setStatusCode(200);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => 'Listing item types availlable.',
            'rewards' => $return_array
        )));
        return $response;
    }

    /**
     * @Route("/list_database_informations", name="list_db_information")
     * @Method("GET")
     *
     * @ApiDoc(
     *      deprecated=TRUE,
     * 		description = "Returns a list of all the users in the system. (DEVELOPMENT ONLY / WILL BE DISABLED)",
     *      section="Z DEVELOPMENT Z",
     * 		statusCodes = {
     * 			200 = "Ok",
     * 		},
     * )
     *
     */
    public function getDbInformationAction() {

        $response = new Response();
        $users = $this->getDoctrine()->getRepository("TimeDudeBundle:TimeDudeUser")->findAll();
        $games = $this->getDoctrine()->getRepository("TimeDudeBundle:Game")->findAll();
        $reward_types = $this->getDoctrine()->getRepository("TimeDudeBundle:RewardType")->findAll();
        
        
        
        

        $return_array = array();

        foreach ($users as $user) {
            $return_array['users'][ucfirst($user->getGoogleUid())] = $user->getName() . ' ' . $user->getEmail();
        }
        
        foreach ($games as $game) {
            $return_array['games'][ucfirst($game->getId())] = $game->getName();
        }

        foreach ($reward_types as $reward_type) {
            $return_array['rewards'][ucfirst($reward_type->getId())] = $reward_type->getName();
        }
        
        $response->setStatusCode(200);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => 'Listing Database Informations ',
            'informations' => $return_array
        )));
        return $response;
    }

    /**
     * @Route("/newgameuser", name="new_google_user")
     * @Method("Post")
     *
     * @ApiDoc(
     *      deprecated=FALSE,
     * 		description = "Call to create a new google user entry in the database.",
     *      section="User",
     * 		statusCodes = {
     * 			201 = "User Has Been Created",
     * 			400 = "Bad request. Already exists ?",
     *                  500 = "No token / Invalid API KEY",
     * 		},
     *      parameters={
     *          {"name"="googleUid", "dataType"="string", "required"=true, "description"="The user's google id."},
     *          {"name"="registrationId",     "dataType"="string", "required"=true, "description"="The user's api registration ID."},
     *          {"name"="email", "dataType"="string", "required"=false, "description"="The user's email"},
     *          {"name"="name",  "dataType"="integer", "required"=false, "description"="The user's name"},
     * }
     * 		
     * )
     *
     */
    public function postNewGameUserAction(Request $request) {

        $user_making_the_call = $this->getUser();
        $http_call_by = $user_making_the_call->getUsername();


        $date = new \DateTime();
        $response = new Response();

        $googleUid = $request->get('googleUid');
        $registrationId = $request->get('registrationId');
        $email = $request->get('email');
        $name = $request->get('name');


        if (empty($googleUid) || empty($registrationId) || empty($email)) {
            $response->setStatusCode(400);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'GoogleId , RegistrationId and Email are required.'
            )));
            return $response;
        }

        $newUser = new TimeDudeUser();

        $newUser->setGoogleUid($googleUid);
        $newUser->setRegistrationId($registrationId);
        $newUser->setEmail($email);
        $newUser->setName($name);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newUser);
        $em->flush();

        $response->setStatusCode(201);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => 'A user for acccount ' . $email . ' has been registered.'
        )));
        return $response;
    }

//    /**
//     * @Route("/test1", name="test1")
//     * @Method("GET")
//     *
//     * @ApiDoc(
//     *      deprecated=true,
//     * 		description = "Returns true if the user has been found by an id, or false.",
//     *      section="Item Related",
//     * 		statusCodes = {
//     * 			200 = "Ok",
//     * 		},
//     * )
//     *
//     */
//    public function test1Action() {
//
//
//        $data = array(
//            'collapse_key' => 'do_not_collapse',
//            'vib' => 1,
//            'pw_msg' => 1,
//            'p' => 5);
//
//        $registrationId = 'APA91bG_-Dkxjfh-6IxOiw6bJPs1KNs3Brw_Yh_lZ4b2TDBsurhe_fkWO6sj3LX-6QU0T77BOB1SJiUKuSLgy4GGKp_U0hKVMQ4v7z_mTPepN8dEMs3WXi9-j2m8BUAcmMa9LsiGfPpiqy40kOxXJ0FFjmX3ZX8XWA';
//
//        $messagea = 'REEA EAasd4 432432 23432423 REA Test';
//
//        $push_message = new AndroidMessage();
//        $push_message->setGCM(true);
//        $push_message->setMessage($messagea);
//        $push_message->setDeviceIdentifier($registrationId);
//        $push_message->setData($data);
//
//
//        $RMS = $this->container->get('rms_push_notifications');
//        $RMS->send($push_message);
//
//
//        $response = new Response();
//        $response->setStatusCode(200);
//        $response->setContent(json_encode(array(
//            'success' => true,
//            'message' => 'Push message sent.',
//        )));
//        return $response;
//    }
//
//    public function notifyAndroid($registrationId, $data) {
//
//        $push_message = new AndroidMessage();
//        $push_message->setGCM(true);
////        $push_message->setMessage($message);
//        $push_message->setDeviceIdentifier($registrationId);
//        $push_message->setData($data);
//        $RMS = $this->container->get('rms_push_notifications')->send($push_message);
//
//        return $RMS;
//    }

}
