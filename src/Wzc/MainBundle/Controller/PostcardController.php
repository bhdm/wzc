<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Wzc\MainBundle\Entity\Map;

class PostcardController extends Controller
{
    /**
     * @Route("/postcard", name="postcard")
     * @Template()
     */
    public function indexAction()
    {
        $postcards = $this->getDoctrine()->getRepository('WzcMainBundle:Postcard')->findByEnabled(1);
        $page = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('postcard');
        $page2 = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('postcard2');



        return array('postcards' => $postcards, 'page' => $page, 'page2' => $page2);
    }


}