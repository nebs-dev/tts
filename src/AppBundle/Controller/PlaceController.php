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
     * @Get("/{placeId}", name="get_place_1")
     * @Get("/{token}/{placeId}", name="get_place_2")
     */
    public function getPlaceAction($token = null, $placeId) {
        if(!$this->get('permissionService')->checkToken($token))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        $em = $this->getDoctrine()->getManager();

        // find place by ID
        $place = $em->getRepository('AppBundle:Place')->find($placeId);
        return $this->get('responseService')->success($place);
    }

    /**
     * @Post("/")
     */
    public function postPlaceAction(Request $request) {
        if(!$this->get('permissionService')->checkToken($request))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        // populate place object
        $place = $this->get('placeService')->populateObject($request, new Place());
        $validator = $this->get('validator');
        $errors = $validator->validate($place);

        if (count($errors) > 0) {
            return $this->get('responseService')->badRequest($errors);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();
            return $this->get('responseService')->success($place);
        }
    }

    /**
     * @Put("/{placeId}")
     */
    public function putPlaceAction(Request $request, $placeId) {
        if(!$this->get('permissionService')->checkToken($request))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        // find place by ID
        $em = $this->getDoctrine()->getManager();
        $place = $em->getRepository('AppBundle:Place')->find($placeId);
        if(!$place) return $this->get('responseService')->notFound();

        //populate place object
        $place = $this->get('placeService')->populateObject($request, $place);
        $validator = $this->get('validator');
        $errors = $validator->validate($place);

        if (count($errors) > 0) {
            return $this->get('responseService')->badRequest($errors);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();
            return $this->get('responseService')->success($place);
        }
    }


    /**
     * @Get("/{lat}/{lng}/{radius}", name="get_places_1")
     * @Get("/{token}/{lat}/{lng}/{radius}", name="get_places_2")
     * @param null $token
     * @param $lang
     * @param $lat
     */
    public function getPlacesAction($token = null, $lat, $lng, $radius) {
        if(!$this->get('permissionService')->checkToken($token))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        // find places by lat && lng
        $places = $this->get('placeService')->findPlacesRadius($lat, $lng, $radius);
        return $this->get('responseService')->success($places);
    }

}