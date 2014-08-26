<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{
    public function search($string){
        $result= $this
            ->createQueryBuilder('p')
            ->from('WzcMainBundle:Page','p')
            ->where('p.title LIKE %:string% ')
            ->orWhere('p.body LIKE %:string% ')
            ->setParameter('string', $string)
            ->getQuery()
            ->getResult();
        return $result;
    }

}
