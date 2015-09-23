<?php

namespace AppBundle\Extensions;

use Doctrine\ORM\EntityManager;

class PersonaService {

    private $em;
    private $personaRepo;

    function __construct(EntityManager $em) {
        $this->em = $em;
        $this->personaeRepo = $this->em->getRepository('AppBundle:Persona');
    }


    /**
     * Populate Persona object
     * @param $request
     * @param $persona
     * @return mixed
     */
    public function populateObject($request, $persona) {
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $occupation = $request->request->get('occupation');
        $wikipedia = $request->request->get('wikipedia');
        $lat = json_decode($request->request->get('lat'));
        $lng = json_decode($request->request->get('lng'));

        if ($name)
            $persona->setName($name);
        if ($description)
            $persona->setDescription($description);
        if ($occupation)
            $persona->setOccupation($occupation);
        if ($wikipedia)
            $persona->setWikipedia($wikipedia);
        if ($lat)
            $persona->setLat($lat);
        if ($lng)
            $persona->setLng($lng);

        return $persona;
    }

    /**
     * Find personas in radius
     * @param $lat
     * @param $lng
     * @param $radius
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findPersonasRadius($lat, $lng, $radius) {
        $sqlFindPersonas = "SELECT *, (
            6371 * acos (
                cos ( radians(:lat) )
                * cos( radians( lat ) )
                * cos( radians( lng ) - radians(:lng) )
                + sin ( radians(:lat) )
                * sin( radians( lat ) )
            )
        ) AS distance
        FROM personas
        HAVING distance < :radius
        ORDER BY distance
        LIMIT 0 , 20;";

        $personas = $this->em->getConnection()->executeQuery($sqlFindPersonas, array(
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius
        ))->fetchAll();

        return $personas;
    }

}