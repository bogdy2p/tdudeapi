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

        $reward = new Reward();
        $reward->setAmmount($ammount);
        $reward->setUser($user);
        $reward->setRewardtype($rewardType);
        $reward->setGameId($gameId);
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


        $pbc_google_id = '108258724289500664552';
        $pbc_device_id = '281D722E3CD25E9A';
//        $pbc_GSF = '305C153A78982501';
//        $identifier = '94A7066D6454216';
//        $gsf = '3FB6668736BA0A95';
//        $message->setData('data');


        $messagea = 'REEA REEA REEA Test';

        $push_message = new AndroidMessage();
        $push_message->setGCM(true);
        $push_message->setMessage($messagea);
        $push_message->setDeviceIdentifier($pbc_device_id);

        $RMS = $this->container->get('rms_push_notifications.android');



        $test = $RMS->send($push_message);


        //return new Response('Push notification send!');

        var_dump($RMS);




        die();









        $response->setStatusCode(201);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => 'User ' . $user->getId() . ' ' . $lost_receive . ' ' . abs($ammount) . ' items of type ' . ucfirst($rewardType->getName()) . ' for game ' . $gameId
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
        $asd = self::thecall();
        
        var_dump($asd);
        die();
        
        
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
     * @Route("/listusers", name="list_game_users")
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
    public function getGameUsersAction() {

        $gameUsers = $this->getDoctrine()->getRepository("TimeDudeBundle:TimeDudeUser")->findAll();

        $response = new Response();

        if (!$gameUsers) {
            $response->setStatusCode(404);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'There are no game users currently in the database.'
            )));
            return $response;
        }

        $return_array = array();

        foreach ($gameUsers as $user) {
            $return_array[ucfirst($user->getGoogleUid())] = $user->getFirstname() . ' ' . $user->getLastname();
        }

        $response->setStatusCode(200);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => 'Listing Existing Users ',
            'users' => $return_array
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
     *          {"name"="email",     "dataType"="string", "required"=true, "description"="The user's email adress."},
     *          {"name"="firstname", "dataType"="string", "required"=false, "description"="The user's firstname"},
     *          {"name"="lastname",  "dataType"="integer", "required"=false, "description"="The user's lastname"},
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
        $email = $request->get('email');
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');


        if (empty($googleUid) || empty($email)) {
            $response->setStatusCode(400);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'googleUid and email parameters are required.'
            )));
            return $response;
        }


        $newUser = new TimeDudeUser();

        $newUser->setGoogleUid($googleUid);
        $newUser->setFirstname($firstname);
        $newUser->setLastname($lastname);


        $em = $this->getDoctrine()->getManager();
//        $user = $this->getDoctrine()->getRepository('TimeDudeBundle:TimeDudeUser')->findOneByGoogleUid($userId);
//
//        if (!$user) {
//            $response->setStatusCode(400);
//            $response->setContent(json_encode(array(
//                'success' => false,
//                'message' => 'The user id provided is wrong. (no such user in the db)'
//            )));
//            return $response;
//        }
//        $rewardType = $this->getDoctrine()->getRepository('TimeDudeBundle:RewardType')->findOneById($itemId);
//
//        if (!$rewardType) {
//            $response->setStatusCode(400);
//            $response->setContent(json_encode(array(
//                'success' => false,
//                'message' => 'Invalid reward type / itemId'
//            )));
//            return $response;
//        }
//
//        $reward = new Reward();
//        $reward->setAmmount($ammount);
//        $reward->setUser($user);
//        $reward->setRewardtype($rewardType);
//        $reward->setGameId($gameId);
//        $reward->setDate($date);
//        $reward->setHttpcallby($http_call_by);
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($reward);
//        $em->flush();
//        if ($ammount > 0) {
//            $lost_receive = 'received';
//        } else {
//            $lost_receive = 'lost';
//        }
//
//        $response->setStatusCode(201);
//        $response->setContent(json_encode(array(
//            'success' => true,
//            'message' => 'User ' . $user->getId() . ' ' . $lost_receive . ' ' . abs($ammount) . ' items of type ' . ucfirst($rewardType->getName()) . ' for game ' . $gameId
//        )));
//        return $response;
    }

    public function thecall() {
        $message = "thetest message";
        $tickerText = "ticker text message";
        $contentTitle = "content title";
        $contentText = "content body";

        $registrationId = 'abcdef...';
        
        $pbc_google_id = '108258724289500664552';
        $pbc_device_id = '281D722E3CD25E9A';
        $projectid = 'timedudeapitestv001';
        $apiKey = "AIzaSyBcR3kkCOqVWCMjhHpzL66KwgF54x_bxqM";

        $headers = array("Content-Type:" . "application/json", "Authorization:" . "key=" . $apiKey);

        $data = array(
            'data' => array("message" => $message),
            'registration_ids' => array($pbc_google_id)
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
        
        
        
        
    }

    
       /**
     * @Route("/test1", name="test1")
     * @Method("GET")
     *
     * @ApiDoc(
     *      deprecated=true,
     * 		description = "Returns true if the user has been found by an id, or false.",
     *      section="Item Related",
     * 		statusCodes = {
     * 			200 = "Ok",
     * 		},
     * )
     *
     */
    public function test1Action() {

      
        $pbc_google_id = '108258724289500664552';
        $pbc_device_id = '281D722E3CD25E9A';
//        $pbc_GSF = '305C153A78982501';
//        $identifier = '94A7066D6454216';
//        $gsf = '3FB6668736BA0A95';
//        $message->setData('data');


        $messagea = 'REEA REEA REEA Test';

        $push_message = new AndroidMessage();
        $push_message->setGCM(true);
        $push_message->setMessage($messagea);
        $push_message->setDeviceIdentifier($pbc_device_id);

        $RMS = $this->container->get('rms_push_notifications');



        $test = $RMS->send($push_message);


        //return new Response('Push notification send!');

        var_dump($RMS->getResponses('rms_push_notifications.os.android.gcm'));
        
//        var_dump($test);


      die();
      
      
        $response = new Response();
        $response->setStatusCode(200);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => 'Test1Output',
            'rewards' => $return_array
        )));
        return $response;
    }
    
    
}
