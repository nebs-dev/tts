<?php

namespace AppBundle\Extensions;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\Email;

class PermissionService {

    protected $em;

    function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Check if token exists in database
     * @param $request
     * @return bool
     */
    public function checkToken($request) {
        if(isset($request->request)) {
            if ($request->request->get('token')) {
                if (count($this->em->getRepository('AppBundle:User')->findOneByToken($request->request->get('token'))) > 0)
                    return true;
            }
        } else {
            if (count($this->em->getRepository('AppBundle:User')->findOneByToken($request)) > 0 && !is_null($request))
                return true;
        }

        return false;
    }


    public function checkSignUp($request) {
        if(isset($request->request)) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $name = $request->request->get('name');

            if(isset($email) && isset($password) && isset($name)) {
                return true;
            }
        }

        return false;
    }
}