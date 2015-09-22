<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Place;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;

class PlaceController extends FOSRestController {

    /**
     * @Get("/{placeId}")
     */
    public function getTestAction($placeId) {
        $em = $this->getDoctrine()->getManager();

        // find place by ID
        $place = $em->getRepository('AppBundle:Place')->find($placeId);
        return $this->get('responseService')->success($place);
    }

}