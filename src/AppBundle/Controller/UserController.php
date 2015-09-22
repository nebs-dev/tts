<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;

class UserController extends FOSRestController {

    /**
     * @Post("/signin")
     */
    public function postSigninAction(Request $request) {
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
    public function postSignupAction(Request $request) {
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

    /**
     * @Get("/{token}")
     */
    public function getUserAction($token) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneByToken($token);

        if(!$user) return $this->get('responseService')->notFound();

        return $this->get('responseService')->success($user);
    }

    /**
     * @Put("/")
     * @param Request $request
     * @return mixed
     */
    public function putUserAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        // check token
        $token = $request->get('token');
        if(!isset($token)) return $this->get('responseService')->badRequest();

        // find user by token
        $user = $em->getRepository('AppBundle:User')->findOneByToken($request->request->get('token'));
        if(!$user) return $this->get('responseService')->notFound();

        // validate user object
        $user = $this->get('userService')->populateObject($request, $user);
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