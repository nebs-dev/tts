<?php

namespace AppBundle\Extensions;

use Doctrine\ORM\EntityManager;

class UserService {

    private $em;
    private $userRepo;

    function __construct(EntityManager $em) {
        $this->em = $em;
        $this->userRepo = $this->em->getRepository('AppBundle:User');
    }


    /**
     * login user
     * @param $request
     * @return bool
     */
    public function login($request) {
        if($user = $this->userRepo->findOneByEmail($request->request->get('email'))) {
            if (password_verify($request->request->get('password'), $user->getPassword())) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Login user with facebookId
     * @param $request
     * @return bool
     */
    public function loginFacebook($request) {
        if($user = $this->userRepo->findOneByFacebookId($request->request->get('facebookId'))) {
            return $user;
        }

        return false;
    }

    /**
     * Login user with twitterId
     * @param $request
     * @return bool
     */
    public function loginTwitter($request) {
        if($user = $this->userRepo->findOneByTwitterId($request->request->get('twitterId'))) {
            return $user;
        }

        return false;
    }

    /**
     * Populate user object
     * @param $request
     * @param $user
     * @return mixed
     */
    public function populateObject($request, $user) {
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $photo = $request->request->get('photo');
        $platform = json_decode($request->request->get('platform'));
        $facebookId = $request->request->get('facebookId');
        $twitterId = $request->request->get('twitterId');

        $token = $request->request->get('token');
        if(is_null($user->getToken()) && is_null($token)) {
            $random = substr( md5(rand()), 0, 7);
            $newToken = sha1('MIDGET' . $random .'NINJA');
            $user->setToken($newToken);
        }

        if($name)
            $user->setName($name);
        if($email)
            $user->setEmail($email);
        if($password)
            $user->setPassword($password);
        if($photo)
            $user->setPhoto($photo);
        if($platform)
            $user->setPlatform($platform);
//        if($token)
//            $user->setToken($token);
        if($facebookId)
            $user->setFacebookId($facebookId);
        if($twitterId)
            $user->setTwitterId($twitterId);

        return $user;
    }
}