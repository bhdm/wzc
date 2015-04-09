<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class StatsRepository extends EntityRepository
{
    public function stats($type){
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('s.city city, COUNT(s.id) amount')
            ->from('WzcMainBundle:Stats','s')
            ->where('s.category = \''.$type.'\' ')
            ->groupBy('s.city')
            ->getQuery();

//        echo $result->getSQL();
        $result = $result->getResult();

        return $result;
    }

}
