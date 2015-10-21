<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Place;
use AppBundle\Entity\PlaceGallery;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;

class PlaceController extends FOSRestController {

    /**
     * @Get("/getOne/{placeId}", name="get_place_1")
     * @Get("/getOne/{token}/{placeId}", name="get_place_2")
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
     * Create new Place
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
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($place);
                $em->flush();

                if ($request->request->get('personas')) {
                    // Related personas
                    $relatedPersonas = $request->request->get('personas');
                    $relatedPersonas = explode(",", $relatedPersonas);
                    if (count($relatedPersonas) > 0) {
                        foreach ($relatedPersonas as $personaId) {
                            $persona = $em->getRepository('AppBundle:Persona')->find($personaId);
                            $persona->addPlace($place);
                            $em->persist($persona);
                            $em->flush();
                        }
                    }
                }
                return $this->get('responseService')->success($place);

            } catch(\ExportException $e) {
                return $this->get('responseService')->internalServerError('Internal Server Error', array($e->getMessage()));
            }
        }
    }

    /**
     * Update Place
     * @Put("/{placeId}")
     */
    public function putPlaceAction(Request $request, $placeId) {
        if(!$this->get('permissionService')->checkToken($request))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        // find place by ID
        $em = $this->getDoctrine()->getManager();
        $place = $em->getRepository('AppBundle:Place')->find($placeId);
        if(!$place) return $this->get('responseService')->notFound();

        // populate place object
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
     * @Get("/get/{lat}/{lng}/{radius}", name="get_places_1")
     * @Get("/get/{token}/{lat}/{lng}/{radius}", name="get_places_2")
     * @param null $token
     */
    public function getPlacesAction($token = null, $lat, $lng, $radius) {
        if(!$this->get('permissionService')->checkToken($token))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        $em = $this->getDoctrine()->getManager();
        // find user by token
        $user = $em->getRepository('AppBundle:User')->findOneByToken($token);
        if(!$user) return $this->get('responseService')->notFound();
        // Get all places by location
        $places = $em->getRepository('AppBundle:Place')->getAllByLocation($lat, $lng, $radius, $user->getId());

//        $places = $em->getRepository('AppBundle:Place')->findBy(array(), array('createdAt' => 'DESC'));
//        $places = $this->get('placeService')->findPlacesRadius($lat, $lng, $radius);

        return $this->get('responseService')->success($places, false);
    }


    /**
     * @Get("/search/{term}", name="get_places_search_1")
     * @Get("/search/{token}/{term}", name="get_places_search_2")
     * @param null $token
     * @param $term
     * @return mixed
     */
    public function getSearchAction($token = null, $term) {
        if(!$this->get('permissionService')->checkToken($token))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        // find user by token
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneByToken($token);
        if(!$user) return $this->get('responseService')->notFound();

        // find places by search term
        $places = $em->getRepository('AppBundle:Place')->search($term, $user->getId());
        return $this->get('responseService')->success($places, false);
    }


    /**
     * @Post("/comment")
     * Post new comment for Place
     * @param Request $request
     * @return mixed
     */
    public function postCommentAction(Request $request) {
        if(!$this->get('permissionService')->checkToken($request))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        // populate comment object
        $comment = $this->get('placeService')->populateCommentObject($request, new Comment());
        $validator = $this->get('validator');
        $errors = $validator->validate($comment);

        if (count($errors) > 0) {
            return $this->get('responseService')->badRequest($errors);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->get('responseService')->success($comment);
        }
    }

    /**
     * @Post("/images/{placeId}", name="post_place_image")
     * @param Request $request
     */
    public function postImageAction(Request $request, $placeId) {
        if(!$this->get('permissionService')->checkToken($request))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        // find place by ID
        $em = $this->getDoctrine()->getManager();
        $place = $em->getRepository('AppBundle:Place')->find($placeId);
        if(!$place) return $this->get('responseService')->notFound();

        // Upload image and save it to db
        if($image = $this->get('uploadService')->uploadBase64File($request)) {
            $placeImage = new PlaceGallery();
            $placeImage->setPlace($place);
            $placeImage->setImage($image);

            $em = $this->getDoctrine()->getManager();
            $em->persist($placeImage);
            $em->flush();

            return $this->get('responseService')->success($placeImage);
        } else {
            return $this->get('responseService')->internalServerError();
        }
    }

    /**
     * @Post("/favourite/{placeId}", name="post_place_favourite")
     * @param Request $request
     */
    public function postFavouriteAction(Request $request, $placeId) {
        if(!$this->get('permissionService')->checkToken($request))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        $em = $this->getDoctrine()->getManager();

        try {
            $place = $em->getRepository('AppBundle:Place')->find($placeId);
            $user = $em->getRepository('AppBundle:User')->findOneByToken($request->request->get('token'));

            if(!$user || !$place) return $this->get('responseService')->notFound();

            // if relationship doesn't exist
            if(!$place->getFavouriteUsers()->contains($user)) {
                $place->addFavouriteUser($user);
                $em->persist($place);
                $em->flush();

                return $this->get('responseService')->success($place);
            } else {
                $message = array(
                    'message' => 'Relationship already exist'
                );
                return $this->get('responseService')->internalServerError('Internal Server Error', array($message));
            }

        } catch(\ExportException $e) {
            return $this->get('responseService')->internalServerError($e->getMessage());
        }
    }

}