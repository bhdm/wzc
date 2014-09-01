<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TestQuestionRepository extends EntityRepository
{
    public function findNext($qId){
        $result= $this
            ->createQueryBuilder('q')
            ->from('WzcMainBundle:testQuestion','q')
            ->where(' q.id > :qId% ')
            ->orderBy('q.id ASC')
            ->setParameter('qId', $qId)
            ->getQuery()
            ->getFirstResult();
        return $result;
    }

}
