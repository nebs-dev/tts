<?php

namespace AppBundle\Entity\Repository;

/**
 * PlaceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlaceRepository extends \Doctrine\ORM\EntityRepository {

    public function getOne($placeId, $userId) {
        $sql = "SELECT p.*, UNIX_TIMESTAMP(CAST(p.created_at AS DATETIME)) as created_at_timestamp,
                  CASE
                     WHEN f.place_id IS NOT NULL AND f.user_id = :userId THEN true
                     ELSE false
                  END as favourited,
                  (SELECT COUNT(*) FROM place_favourites fav WHERE fav.place_id = f.place_id) as totalFav
                FROM places p
                LEFT JOIN place_favourites f ON f.place_id = p.id
                WHERE p.id = :placeId
                GROUP BY p.id
                LIMIT 0, 50
                ";

        $places = $this->getEntityManager()->getConnection()->executeQuery($sql, array(
            'placeId' => $placeId,
            'userId' => $userId
        ))->fetchAll();

        $places = $this->findGallery($places);
        $places = $this->getComments($places);
        $places = $this->getRelatedPersonas($places, $userId);
        return (count($places) > 0) ? $places[0] : null;
    }

    /**
     * Get all places by name
     * @param $string
     * @return array
     */
    public function search($string, $userId) {
//        $qb = $this->createQueryBuilder('p')
//            ->select('p')
//            ->where('p.name like :string')
//            ->orWhere('p.address like :string')
//            ->setParameter('string', '%' . $string . '%')
//            ->setMaxResults(50);
//
//        return $qb->getQuery()->getResult();


        $sqlFindPlaces = "SELECT p.*, UNIX_TIMESTAMP(CAST(p.created_at AS DATETIME)) as created_at_timestamp,
                              CASE
                                 WHEN f.place_id IS NOT NULL AND f.user_id = :userId THEN true
                                 ELSE false
                              END as favourited,
                              (SELECT COUNT(*) FROM place_favourites fav WHERE fav.place_id = f.place_id) as totalFav
                            FROM places p
                            LEFT JOIN place_favourites f ON f.place_id = p.id
                            WHERE p.name LIKE :string
                            OR p.address LIKE :string
                            GROUP BY p.id
                            LIMIT 0, 50
                            ";

        $places = $this->getEntityManager()->getConnection()->executeQuery($sqlFindPlaces, array(
            'string' => '%' . $string . '%',
            'userId' => $userId
        ))->fetchAll();

        $places = $this->findGallery($places);
        $places = $this->getComments($places);
        $places = $this->getRelatedPersonas($places, $userId);
        return $places;
    }


    public function getAllByLocation($lat, $lng, $radius, $userId) {
        $sqlFindPlaces = "SELECT p.*, UNIX_TIMESTAMP(CAST(p.created_at AS DATETIME)) as created_at_timestamp,
                                (
                                    6371 * acos (
                                        cos ( radians(:lat) )
                                        * cos( radians( lat ) )
                                        * cos( radians( lng ) - radians(:lng) )
                                        + sin ( radians(:lat) )
                                        * sin( radians( lat ) )
                                    )
                                ) AS distance,
                              CASE
                                 WHEN EXISTS(SELECT 1 FROM place_favourites as fff WHERE fff.user_id = :userId AND fff.place_id = p.id) THEN true
                                 ELSE false
                              END as favourited,
                              (SELECT COUNT(*) FROM place_favourites fav WHERE fav.place_id = f.place_id) as totalFav
                            FROM places p
                            LEFT JOIN place_favourites f ON f.place_id = p.id
                            HAVING distance < :radius
                            ORDER BY distance
                            LIMIT 0, 50
                            ";

        $places = $this->getEntityManager()->getConnection()->executeQuery($sqlFindPlaces, array(
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius,
            'userId' => $userId
        ))->fetchAll();

        $places = $this->findGallery($places);
        $places = $this->getComments($places);
        $places = $this->getRelatedPersonas($places, $userId);
        return $places;
    }





    /**
     * Place gallery
     * @param $places
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    private function findGallery($places) {
        foreach($places as &$place) {
            $sqlPlaceGallery = "SELECT g.* FROM place_gallery g WHERE g.place_id = :placeId";
            $gallery = $this->getEntityManager()->getConnection()->executeQuery($sqlPlaceGallery, array(
                'placeId' => $place['id']
            ))->fetchAll();

            $place['gallery'] = $gallery;
        }

        return $places;
    }

    private function findPersonaGallery($personas) {
        foreach($personas as &$persona) {
            $sqlPersonaGallery = "SELECT g.* FROM persona_gallery g WHERE g.persona_id = :personaId";
            $gallery = $this->getEntityManager()->getConnection()->executeQuery($sqlPersonaGallery, array(
                'personaId' => $persona['id']
            ))->fetchAll();

            $persona['gallery'] = $gallery;
        }

        return $personas;
    }

    /**
     * Place comments
     * @param $places
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    private function getComments($places) {
        foreach($places as &$place) {
            $sqlPlaceComments = "SELECT c.*,
                                  CASE
                                     WHEN u.name IS NOT NULL THEN u.name
                                     ELSE 'anonymous'
                                  END as username
                                FROM comments c
                                INNER JOIN users u ON u.id = c.user_id
                                WHERE c.place_id = :placeId";
            $comments = $this->getEntityManager()->getConnection()->executeQuery($sqlPlaceComments, array(
                'placeId' => $place['id']
            ))->fetchAll();

            $place['comments'] = $comments;
        }

        return $places;
    }

    /**
     * Related personas
     * @param $places
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    private function getRelatedPersonas($places, $userId) {
        foreach($places as &$place) {
            $sqlRelatedPersonas = "SELECT per.*, UNIX_TIMESTAMP(CAST(per.created_at AS DATETIME)) as created_at_timestamp,
                                  CASE
                                     WHEN f.persona_id IS NOT NULL AND f.user_id = :userId THEN true
                                     ELSE false
                                  END as favourited,
                                  CASE
                                     WHEN l.persona_id AND l.user_id = :userId IS NOT NULL THEN true
                                     ELSE false
                                  END as liked,
                                  (SELECT COUNT(*) FROM persona_favourites fav WHERE fav.persona_id = f.persona_id) as totalFav,
                                  (SELECT COUNT(*) FROM persona_likes lik WHERE lik.persona_id = l.persona_id) as totalLikes
                              FROM personas per
                              INNER JOIN persona_place pp ON pp.persona_id = per.id
                              INNER JOIN places p ON pp.place_id = p.id
                              LEFT JOIN persona_favourites f ON f.persona_id = per.id
                              LEFT JOIN persona_likes l ON l.persona_id = per.id
                              WHERE p.id = :placeId
                              GROUP BY per.id";
            $personas = $this->getEntityManager()->getConnection()->executeQuery($sqlRelatedPersonas, array(
                'placeId' => $place['id'],
                'userId' => $userId
            ))->fetchAll();

            $personas = $this->findPersonaGallery($personas);
            $place['relatedPersonas'] = $personas;
        }

        return $places;
    }
}
