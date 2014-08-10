<?php

namespace Wzc\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class IndexController extends Controller
{
    /**
     * @Route("/hello/{name}/{count}")
     * @Template()
     */
    public function indexAction($name,$count)
    {
        return array(
            'name' => $name,
            'count' => $count,
            'ss' => '19'
        );
    }



}
