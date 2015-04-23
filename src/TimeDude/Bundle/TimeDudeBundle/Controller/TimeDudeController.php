<?php

namespace TimeDude\Bundle\TimeDudeBundle\Controller;

use TimeDude\Bundle\TimeDudeBundle\Model\FileType;

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
        if ($userId < 1000) return true;
        
        if ($userId >= 1000) return false;
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
        return 'THE RETURN VALUE';
    }
    
    
    
    
}
