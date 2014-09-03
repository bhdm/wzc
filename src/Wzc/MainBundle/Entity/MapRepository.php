<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MapRepository extends EntityRepository
{
    public function search($string){
        $result= $this
            ->createQueryBuilder('m')
            ->select('m')
            ->where("m.title LIKE '%$string%' ")
            ->orWhere("m.body LIKE '%$string%' ")
            ->getQuery()
            ->getResult();
        return $result;
    }

}
