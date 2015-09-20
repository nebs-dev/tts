<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BaseController {

//    public function __construct(Request $request) {
//        if(!$request->request->get('token')) {
//            return new JsonResponse('Ne more');
//        }
//    }

}