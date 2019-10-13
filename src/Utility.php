<?php

namespace App;

use Doctrine\ORM\EntityManager;

class Utility extends EntityManager {
    public function findOther($id) {
        return $this->createQueryBuilder('posts')
            ->select('posts')
            ->orderBy('ad.id', 'DESC')
            ->andWhere('ad.id < :id')
            //->setParameter('true', 1)
            //->setParameter('false', 0)
            //->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}