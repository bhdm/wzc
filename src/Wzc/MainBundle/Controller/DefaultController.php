<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wzc\MainBundle\Entity\Page;
use Wzc\MainBundle\Entity\Slidebar;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="main")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }


    /**
     * @Route("/page/{url}")
     * @Template()
     */
    public function pageAction($url)
    {
        $page = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl($url);
        return array('page' => $page);
    }
}
