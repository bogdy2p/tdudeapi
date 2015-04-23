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
use TimeDude\Bundle\TimeDudeBundle\Entity\Coin;
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
     * 			200 = "User Exists",
     * 			404 = "User not found."
     * 		},
     * 		
     * )
     *
     */

    public function getUserexistsAction($userId) {
       
        
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneByGoogleUid($userId);
        
        if($user) {
            return true;
        }
        else{
            return false;
        }

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
     * 		
     * )
     *
     */
    
    public function postRedeemItemsAction(){
        
        $date = new \DateTime();
        
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneByGoogleUid(12312432);
        
        if($user){
        $coin = new Coin();
        
        $coin->setAmmount('7');
        $coin->setUser($user);
        $coin->setGameId('000003');
        $coin->setDate($date);
        $em = $this->getDoctrine()->getManager();
        $em->persist($coin);
        $em->flush();
        return 'added coinz';
        }
        return 'failed to add coinza.';
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
    
    public function getUserInformationAction($userId){
        
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneByGoogleUid($userId);
        
        $user_information = array();
        
        if($user){
                $coins = $user->getCoins();
//                print_r(count($coins));
                $user_information['number_of_coins'] = count($coins);
                $coins_value = 0;
                foreach ($coins as $coin) {
                    $coins_value += $coin->getAmmount();
                }
                $user_information['value'] = $coins_value;
        }
        print_r($user_information);
//        print_r($user); exit();
        
        die();
        
        
        
        
        
        
        
        return 'THE RETURN VALUE';
    }
    
    
    
    
}
