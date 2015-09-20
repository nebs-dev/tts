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
        $token = $request->request->get('token');

        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setPhoto($photo);
        $user->setPlatform($platform);
        $user->setToken($token);

        return $user;
    }
}