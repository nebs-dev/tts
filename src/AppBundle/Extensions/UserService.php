<?php

namespace AppBundle\Extensions;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserService {

    private $em;
    private $userRepo;
    private $container;

    function __construct(EntityManager $em, ContainerInterface $container) {
        $this->em = $em;
        $this->container = $container;
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
    public function loginFacebook($request, $fb) {
        $fb->setDefaultAccessToken($request->request->get('accessToken'));

        try {
            $response = $fb->get('/me?fields=id,name,picture,email');
            $userNode = $response->getGraphUser();

            $photo = $userNode->getPicture()['url'];
            $email = $userNode->getField('email');
            $facebookId = $userNode->getID();
            $platform = $request->request->get('platform');
            $name = $userNode->getField('name');

            // If user exists
            if($user = $this->em->getRepository('AppBundle:User')->findOneByEmail($email)) {
                $user->setEmail($email);
                $user->setPhoto($photo);
                if (isset($name)) {
                    $user->setName($name);
                }
                $this->em->persist($user);
                $this->em->flush();

            // New facebook user
            } else {
                $user = new User();
                $user->setPlatform($platform);
                $user->setPhoto($photo);
                $user->setEmail($email);
                $user->setFacebookId($facebookId);
                if (isset($name)) {
                    $user->setName($name);
                }

                $token = $request->request->get('token');
                if(!$token) {
                    $random = substr( md5(rand()), 0, 7);
                    $newToken = sha1('MIDGET' . $random .'NINJA');
                    $user->setToken($newToken);
                }

                $this->em->persist($user);
                $this->em->flush();
            }

            $user = $this->em->getRepository('AppBundle:User')->getOneByToken($user->getToken());
            return $user;

        } catch(\FacebookResponseException $e) {
            return false;
        } catch(\FacebookSDKException $e) {
            return false;
        }
    }

    /**
     * Login user with twitterId
     * @param $request
     * @return bool
     */
    public function loginTwitter($request) {
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
        if(is_null($user->getToken()) && is_null($token)) {
            $random = substr( md5(rand()), 0, 7);
            $newToken = sha1('MIDGET' . $random .'NINJA');
            $user->setToken($newToken);
        }

        if($name)
            $user->setName($name);
        if($email)
            $user->setEmail($email);
        if(isset($password) && $password != '') {
            $user->setPassword($password);
            $user->encryptPassword();
        }
        if($photo)
            $user->setPhoto($photo);
        if($platform)
            $user->setPlatform($platform);

        return $user;
    }
}