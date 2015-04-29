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
use TimeDude\Bundle\TimeDudeBundle\Entity\Registration;
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
     * 			200 = {"True / False if User Exists","User not found."},
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
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'Parameters are wrong or missing.'
            )));
            return $response;
        }
        $user = $this->getDoctrine()->getRepository('TimeDudeBundle:TimeDudeUser')->findOneByGoogleUid($userId);

        if (!$user) {
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'The user id provided is wrong. (no such user in the db)'
            )));
            return $response;
        }

        $rewardType = $this->getDoctrine()->getRepository('TimeDudeBundle:RewardType')->findOneById($itemId);

        if (!$rewardType) {
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'Invalid reward type / itemId'
            )));
            return $response;
        }

        $game = $this->getDoctrine()->getRepository('TimeDudeBundle:Game')->findOneById($gameId);

        if (!$game) {
            $response->setStatusCode(200);
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

        
        // Send the Push Notification to the Google Cloud.
        
        
        $message = "Redeem Items";

        $apikey = $game->getGcmApiKey();
        $registration = $this->getDoctrine()->getRepository('TimeDudeBundle:Registration')->findOneBy([
            'googleuser' => $user,
            'game' => $game,
            'game_version' => $game->getVersion()
            ]);
        
        if(!$registration){
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'This user is not registered'
            )));
            return $response;
        }
        
        $registration_id = $registration->getRegistrationId();

        $data = array(
            'message' => $message,
            'title' => 'Coin ammount changed.',
            'collapse_key' => 'do_not_collapse',
            'vib' => 1,
            'pw_msg' => 1,
            'p' => 5);

        $notify = self::notifyAndroidNew($registration_id, $data, $apikey);

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
            $response->setStatusCode(200);
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
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'Please provide both required request parameters'
            )));
            return $response;
        }

        $user = $this->getDoctrine()->getRepository('TimeDudeBundle:TimeDudeUser')->findOneByGoogleUid($userId);
        if (!$user) {
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'The user id provided is wrong.'
            )));
            return $response;
        }

        $game = $this->getDoctrine()->getRepository('TimeDudeBundle:Game')->findOneById($gameId);
        if (!$game) {
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'The game id provided is wrong.'
            )));
            return $response;
        }

        $user_information = array();

        if ($user) {
            $all_rewards = $user->getRewards();
            $game_rewards = array();
            foreach ($all_rewards as $reward) {
                if ($reward->getGame() == $game) {
                    $game_rewards[] = $reward;
                }
            }

            $user_information['number_of_calls'] = count($game_rewards);
            $reward_value = 0;
            foreach ($game_rewards as $reward) {
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
     * @Route("/newuser", name="new_google_user")
     * @Method("Post")
     *
     * @ApiDoc(
     *      deprecated=FALSE,
     * 		description = "Call to create a new google user entry in the database.",
     *      section="User",
     * 		statusCodes = {
     *                  200 = {"At least 2 Parameters Required","User Already Exists"},
     * 			201 = "Account Has Been Created",
     *                  500 = "No token / Invalid API KEY",
     * 		},
     *      parameters={
     *          {"name"="googleUid", "dataType"="string", "required"=true, "description"="The user's google id."},
     *          {"name"="email", "dataType"="string", "required"=true, "description"="The user's email"},
     *          {"name"="firstname",  "dataType"="string", "required"=false, "description"="The user's firstname"},
     *          {"name"="lastname",  "dataType"="string", "required"=false, "description"="The user's lastname"},
     *          {"name"="location",  "dataType"="string", "required"=false, "description"="The user's location"},
     *          {"name"="language",  "dataType"="string", "required"=false, "description"="The user's language"},
     *          {"name"="birthday",  "dataType"="string", "required"=false, "description"="The user's birthday"},
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
        $location = $request->get('location');
        $language = $request->get('language');
        $birthday = $request->get('birthday');



        if (empty($googleUid) || empty($email)) {
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'GoogleId , and Email are required.'
            )));
            return $response;
        }

        $user_already_exists = $this->getDoctrine()->getRepository('TimeDudeBundle:TimeDudeUser')->findOneByGoogleUid($googleUid);

        if ($user_already_exists) {
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'An user already exists for the specified google id'
            )));
            return $response;
        }


        $newUser = new TimeDudeUser();
        $newUser->setGoogleUid($googleUid);
        $newUser->setEmail($email);
        $newUser->setFirstname($firstname);
        $newUser->setLastname($lastname);
        $newUser->setLocation($location);
        $newUser->setLanguage($language);
        $newUser->setBirthday($birthday);

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

    /**
     * @Route("/registration", name="put_user_registration")
     * @Method("PUT")
     *
     * @ApiDoc(
     *      deprecated=FALSE,
     * 		description = "Call to create/update a user's GCM registration entry in the database.",
     *      section="User",
     * 		statusCodes = {
     *                  500 = "No token / Invalid API KEY",
     * 		},
     *      parameters={
     *          {"name"="googleUid", "dataType"="string", "required"=true, "description"="The user's google id."},
     *          {"name"="registrationKey", "dataType"="string", "required"=true, "description"="The user's registration key."},
     *          {"name"="gameId", "dataType"="string", "required"=true, "description"="The id of the game"},
     *          {"name"="game_version", "dataType"="string", "required"=true, "description"="The version of the game"},
     *       
     * }
     * 		
     * )
     *
     */
    public function putUserGameRegistrationAction(Request $request) {

        $user_making_the_call = $this->getUser();
        $http_call_by = $user_making_the_call->getUsername();


        $date = new \DateTime();
        $response = new Response();

        $googleUid = $request->get('googleUid');
        $registrationKey = $request->get('registrationKey');
        $gameId = $request->get('gameId');
        $version = $request->get('game_version');


        if (empty($googleUid) || empty($registrationKey) || empty($gameId) || empty($version)) {
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'Parameters missing.'
            )));
            return $response;
        }

        $user = $this->getDoctrine()->getRepository('TimeDudeBundle:TimeDudeUser')->findOneByGoogleUid($googleUid);
        if (!$user) {
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'Google Uid is invalid'
            )));
            return $response;
        }

        $game = $this->getDoctrine()->getRepository('TimeDudeBundle:Game')->find($gameId);
        if (!$game) {
            $response->setStatusCode(200);
            $response->setContent(json_encode(array(
                'success' => false,
                'message' => 'Game Id is invalid.'
            )));
            return $response;
        }


        $registration_already_exists = $this->getDoctrine()->getRepository('TimeDudeBundle:Registration')->findOneBy([
            'googleuser' => $user,
            'game' => $game,
            'game_version' => $version
        ]);

        $em = $this->getDoctrine()->getManager();

        if (!$registration_already_exists) {

            $registration = new Registration();

            $registration->setGoogleuser($user);
            $registration->setGame($game);
            $registration->setGameVersion($version);
            $registration->setRegistrationId($registrationKey);

            $em->persist($registration);
            $em->flush();

            $response->setStatusCode(201);
            $response->setContent(json_encode(array(
                'success' => true,
                'message' => 'User ' . $user->getGoogleUid() . ' has been registered for game' . $game->getName() . ' version ' . $version
            )));
            return $response;
        }

        $registration_already_exists->setRegistrationId($registrationKey);
        $registration_already_exists->setGameVersion($version);

        $em->persist($registration_already_exists);
        $em->flush();

        $response->setStatusCode(201);
        $response->setContent(json_encode(array(
            'success' => true,
            'message' => 'Existing registration updated for user ' . $user->getGoogleUid()
        )));
        return $response;
    }

    /**
     * This function uses RMS push notification bundle to send a push notification to the android device.
     * 
     * @param type $registrationId
     * @param type $data
     * @return type
     * 
     */
    public function notifyAndroid($registrationId, $data) {



        $push_message = new AndroidMessage();
        $push_message->setGCM(true);
        $push_message->setDeviceIdentifier($registrationId);
        $push_message->setData($data);
        $RMS = $this->container->get('rms_push_notifications')->send($push_message);

        return $RMS;
    }

    public function notifyAndroidNew($registrationId, $data, $apiKey) {

             
        $registrationIds = array($registrationId);
        $fields = array
            (
            'registration_ids' => $registrationIds,
            'data' => $data
        );
        $headers = array
            (
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * @Route("/asd", name="asd")
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
    public function getAsdAction() {


//        $notify = self::notifyAndroid($registration_id, $data);
//
//        return $notify;
//        return self::notifyAndroid(null, null);

        $registration_id = 'APA91bGEIBO9bLyfY7HSNqnDz6tgoUFawIYPOxw7TaTnKBLTK9_3gNspATnXCkFnWxIj-Zb4D5HmAWhFVRDAViH05ed6IkPrdjRwaRGs98Och3agZHOOjbfKK87K8XZSLh4Cyesg46rptVbE62R2_1Y_wkDa5PDPTw';
        
         $data = array(
            'message' => '$message',
            'title' => 'Coin ammount changed.',
            'collapse_key' => 'do_not_collapse',
            'vib' => 1,
            'pw_msg' => 1,
            'p' => 5);
        
        $apiKey = 'AIzaSyD0SWi_s_gdWIgfWLZOVxoXYiAGOudTKQE';
        
        
        return self::notifyAndroidNew($registration_id,$data,$apiKey);
    }

}
