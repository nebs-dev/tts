<?php

namespace AppBundle\Extensions;

use Doctrine\ORM\EntityManager;

class PlaceService {

    private $em;
    private $placeRepo;

    function __construct(EntityManager $em) {
        $this->em = $em;
        $this->placeRepo = $this->em->getRepository('AppBundle:Place');
    }


    /**
     * Populate Place object
     * @param $request
     * @param $place
     * @return mixed
     */
    public function populateObject($request, $place) {
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $address = $request->request->get('address');
        $lat = json_decode($request->request->get('lat'));
        $lng = json_decode($request->request->get('lng'));

        if ($name)
            $place->setName($name);
        if ($description)
            $place->setDescription($description);
        if ($address)
            $place->setAddress($address);
        if ($lat)
            $place->setLat($lat);
        if ($lng)
            $place->setLng($lng);

        return $place;
    }


    /**
     * Find places in radius
     * @param $lat
     * @param $lng
     * @param $radius
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findPlacesRadius($lat, $lng, $radius) {
        $sqlFindPlaces = "SELECT *, (
            6371 * acos (
                cos ( radians(:lat) )
                * cos( radians( lat ) )
                * cos( radians( lng ) - radians(:lng) )
                + sin ( radians(:lat) )
                * sin( radians( lat ) )
            )
        ) AS distance
        FROM places p
        HAVING distance < :radius
        ORDER BY distance
        LIMIT 0 , 20;";

        $places = $this->em->getConnection()->executeQuery($sqlFindPlaces, array(
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius
        ))->fetchAll();


        foreach($places as &$place) {
            $placeObj = $this->em->getRepository('AppBundle:Place')->find($place['id']);
            $place['personas'] = $placeObj->getPersonas();
        }

        return $places;
    }


    /**
     * Populate Comment object
     * @param $request
     * @param $place
     * @return mixed
     */
    public function populateCommentObject($request, $comment) {
        $text = $request->request->get('text');
        $place = $this->em->getRepository('AppBundle:Place')->find(intval($request->request->get('place_id')));
        $user = $this->em->getRepository('AppBundle:User')->find(intval($request->request->get('user_id')));

        if ($text)
            $comment->setText($text);
        if ($place)
            $comment->setPlace($place);
        if ($user)
            $comment->setUser($user);

        return $comment;
    }
}