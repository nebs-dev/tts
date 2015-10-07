<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Persona;
use AppBundle\Entity\PersonaGallery;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;

class PersonaController extends FOSRestController {

    /**
     * @Get("/getOne/{personaId}", name="get_persona_1")
     * @Get("/getOne/{token}/{personaId}", name="get_persona_2")
     */
    public function getPersonaAction($token = null, $personaId) {
        if(!$this->get('permissionService')->checkToken($token))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        $em = $this->getDoctrine()->getManager();

        // find persona by ID
        $persona = $em->getRepository('AppBundle:Persona')->find($personaId);
        return $this->get('responseService')->success($persona);
    }

    /**
     * @Post("/")
     */
    public function postPersonaAction(Request $request) {
        if(!$this->get('permissionService')->checkToken($request))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        // populate persona object
        $persona = $this->get('personaService')->populateObject($request, new Persona());
        $validator = $this->get('validator');
        $errors = $validator->validate($persona);

        if (count($errors) > 0) {
            return $this->get('responseService')->badRequest($errors);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($persona);
            $em->flush();
            return $this->get('responseService')->success($persona);
        }
    }

    /**
     * @Put("/{personaId}")
     */
    public function putPersonaAction(Request $request, $personaId) {
        if(!$this->get('permissionService')->checkToken($request))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        // find persona by ID
        $em = $this->getDoctrine()->getManager();
        $persona = $em->getRepository('AppBundle:Persona')->find($personaId);
        if(!$persona) return $this->get('responseService')->notFound();

        // populate persona object
        $persona = $this->get('personaService')->populateObject($request, $persona);
        $validator = $this->get('validator');
        $errors = $validator->validate($persona);

        if (count($errors) > 0) {
            return $this->get('responseService')->badRequest($errors);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($persona);
            $em->flush();
            return $this->get('responseService')->success($persona);
        }
    }

    /**
     * @Get("/get", name="get_personas_1")
     * @Get("/get/{token}", name="get_personas_2")
     * @param null $token
     */
    public function getPersonasAction($token = null) {
        if(!$this->get('permissionService')->checkToken($token))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        // Find all personas
        $em = $this->getDoctrine()->getManager();
        $personas = $em->getRepository('AppBundle:Persona')->findAll();

        return $this->get('responseService')->success($personas);
    }


    /**
     * @Get("/search/{term}", name="get_personas_search_1")
     * @Get("/search/{token}/{term}", name="get_personas_search_2")
     * @param null $token
     * @param $term
     * @return mixed
     */
    public function getSearchAction($token = null, $term) {
        if(!$this->get('permissionService')->checkToken($token))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        $em = $this->getDoctrine()->getManager();

        // find personas by search term
        $personas = $em->getRepository('AppBundle:Persona')->search($term);
        return $this->get('responseService')->success($personas);
    }


    /**
     * @Post("/images/{personaId}", name="post_persona_image")
     * @param Request $request
     */
    public function postImageAction(Request $request, $personaId) {
        if(!$this->get('permissionService')->checkToken($request))
            return $this->get('responseService')->accessDenied('INVALID_TOKEN');

        // find place by ID
        $em = $this->getDoctrine()->getManager();
        $persona = $em->getRepository('AppBundle:Persona')->find($personaId);
        if(!$persona) return $this->get('responseService')->notFound();

        // Upload image and save it to db
        if($image = $this->get('uploadService')->uploadBase64File($request)) {
            $personaImage = new PersonaGallery();
            $personaImage->setPersona($persona);
            $personaImage->setImage($image);

            $em = $this->getDoctrine()->getManager();
            $em->persist($personaImage);
            $em->flush();

            return $this->get('responseService')->success($personaImage);
        } else {
            return $this->get('responseService')->internalServerError();
        }
    }
    
}