<?php

namespace AppBundle\Entity\Repository;

/**
 * PersonaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonaRepository extends \Doctrine\ORM\EntityRepository {

    /**
     * Get all personas by name
     * @param $string
     * @return array
     */
    public function search($string) {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.name like :string')
            ->orWhere('p.occupation like :string')
            ->setParameter('string', '%' . $string . '%')
            ->setMaxResults(50);

        return $qb->getQuery()->getResult();
    }


    /**
     * @return array
     */
    public function getAll() {
        $qb = $this->createQueryBuilder('p')
            ->select('p');

        return $qb->getQuery()->getResult();
    }

}
