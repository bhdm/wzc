<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TestQuestionRepository extends EntityRepository
{
    public function findNext($qId){
        $result= $this ->createQueryBuilder('q');
        $result = $result
            ->select('q')
//            ->from('WzcMainBundle:TestQuestion','q')
            ->where(' q.id > :qId ')
            ->orderBy('q.id','ASC')
            ->setParameter('qId', $qId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        return $result;
    }

}
