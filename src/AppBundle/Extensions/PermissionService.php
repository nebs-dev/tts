<?php

namespace AppBundle\Extensions;

use Doctrine\ORM\EntityManager;

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
            if (count($this->em->getRepository('AppBundle:User')->findOneByToken($request)) > 0)
                return true;
        }

        return false;
    }
}