<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FaqRepository extends EntityRepository
{
    public function search($string){
        $result= $this
            ->createQueryBuilder('f')
            ->select('f')
            ->where("f.question LIKE '%$string%' ")
            ->orWhere("f.answer LIKE '%$string%' ")
            ->getQuery()
            ->getResult();
        return $result;
    }

}
