<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use AppBundle\Form\UserType;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends FOSRestController {

    public function __construct() {
        return new JsonResponse('double test');
    }

    /**
     * @Post("/signin")
     */
    public function getSigninAction(Request $request) {
        if(!$this->get('permissionService')->checkToken($request))
            return $this->get('responseService')->accessDenied();

        if(!$request->request->get('email') || !$request->request->get('password'))
            return $this->get('responseService')->badRequest();

        if($user = $this->get('userService')->login($request)) {
            return $this->get('responseService')->success($user);
        }

        return $this->get('responseService')->accessDenied();
    }

    /**
     * @Post("/signup")
     */
    public function getSignupAction(Request $request) {
        if(!$this->get('permissionService')->checkToken($request))
            return $this->get('responseService')->accessDenied();

        $user = $this->get('userService')->populateObject($request, new User());
        $validator = $this->get('validator');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->get('responseService')->badRequest($errors);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->get('responseService')->success($user);
        }
    }

}