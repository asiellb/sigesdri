<?php

namespace DRI\PassportBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

use DRI\PassportBundle\Entity\Passport;


/**
 * PasaporteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PassportRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllInStore(){
        $em = $this->getEntityManager();

        $dql = 'SELECT p
                FROM DRIPassportBundle:Passport p
                WHERE p.drop = :dropPass AND p.inStore = :inStore
                ORDER BY u.createdAt DESC';

        $query = $em->createQuery($dql);
        $query->setParameter('dropPass', false);
        $query->setParameter('inStore',true);

        return $query->getResult();
    }

    public function findAllInUse(){
        $em = $this->getEntityManager();

        $dql = 'SELECT p
                FROM DRIPassportBundle:Passport p
                WHERE p.drop = :dropPass AND p.inStore = :inStore
                ORDER BY u.createdAt DESC';

        $query = $em->createQuery($dql);
        $query->setParameter('dropPass', false);
        $query->setParameter('inStore',false);

        return $query->getResult();
    }
}
