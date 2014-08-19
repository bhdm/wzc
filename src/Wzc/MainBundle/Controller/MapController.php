<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Wzc\MainBundle\Entity\Map;

class MapController extends Controller
{
    /**
     * @Route("/map", name="map")
     * @Template()
     */
    public function indexAction()
    {
        $coords = $this->getDoctrine()->getRepository('WzcMainBundle:Map')->findByEnabled(1);
        return array('coords' => $coords);
    }


}