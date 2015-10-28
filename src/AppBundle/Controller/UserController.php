<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Facebook\Facebook;
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
        if(!$this->get('permissionService')->checkSignUp($request))
            return $this->get('responseService')->badRequest();

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
        $user = $em->getRepository('AppBundle:User')->getOneByToken($token);

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
        $token = $request->request->get('token');
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

            $user = $em->getRepository('AppBundle:User')->getOneByToken($user->getToken());
            return $this->get('responseService')->success($user);
        }
    }


    /**
     * @Post("/facebook/signin")
     */
    public function postFacebookSigninAction(Request $request) {
        if(!$request->request->get('accessToken') || !$request->request->get('platform'))
            return $this->get('responseService')->badRequest();

        $fb = new Facebook([
            'app_id' => '416061541919312',
            'app_secret' => 'e6e4a0ac76a00566393be42f5fd05cc6',
            'default_graph_version' => 'v2.2'
        ]);

        if($user = $this->get('userService')->loginFacebook($request, $fb)) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')->getOneByToken($user->getToken());
            return $this->get('responseService')->success($user);
        }

        return $this->get('responseService')->accessDenied();
    }

    /**
     * @Post("/twitter/signin")
     */
    public function postTwitterSigninAction(Request $request) {
        if(!$request->request->get('twitterId'))
            return $this->get('responseService')->badRequest();

        if($user = $this->get('userService')->loginTwitter($request)) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')->getOneByToken($user->getToken());
            return $this->get('responseService')->success($user);
        }

        return $this->get('responseService')->accessDenied();
    }
}