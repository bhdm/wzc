<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Wzc\MainBundle\Entity\Map;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     * @Template()
     */
    public function indexAction()
    {
        $test = $this->getDoctrine()->getRepository('WzcMainBundle:TestQuestion')->findByEnabled(1);
        $a = $this->getDoctrine()->getRepository('WzcMainBundle:TestAnswer')->findByEnabled(1);

        $page = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('test');

        return array('test' => $test, 'page' => $page, );
    }


}