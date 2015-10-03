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
            $user->encryptPassword();
            $em->persist($user);
            $em->flush();
            return $this->get('responseService')->success($user);
        }
    }

    /**
     * @Get("/test")
     */
    public function testAction() {
        $fb = new Facebook([
            'app_id' => '416061541919312',
            'app_secret' => 'e6e4a0ac76a00566393be42f5fd05cc6',
            'default_graph_version' => 'v2.2'
        ]);

        $accessToken = 'CAAF6ZAZBBpKlABAEuHvwpkVFHLS7MdL6odahwKliPAvkD6dkmJxk175uiaebDBfebMyZCVxfFJNqwffJ9BwpAve5ZBihuA4rOlk08mbkzVRJOq2kOe1PZB7OnbtmdkcI4DQSMO7MZCscZBz9vU1PMgmdPYWTzC0YfZBFMJOhzvx9vypQKZA9w9k3tK6uhoVHPLRPBcDKyZBVhT3Of9I0e7ZASSgLfMNXs3caqkZD';
        $fb->setDefaultAccessToken($accessToken);

        $myID = '100003197765742';

        try {
//            $fb->delete('/100003197765742/permissions');
            $response = $fb->get('/100003197765742?fields=id,name,picture,email');
            $userNode = $response->getGraphUser();

            echo'<pre>';
            exit(\Doctrine\Common\Util\Debug::dump($userNode));
            echo'</pre>';

        } catch(\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        return $this->get('responseService')->success('OK');
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
            return $this->get('responseService')->success($user);
        }

        return $this->get('responseService')->accessDenied();
    }
}