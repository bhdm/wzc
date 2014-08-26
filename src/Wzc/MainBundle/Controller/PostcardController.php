<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Wzc\MainBundle\Entity\Postcard;

class PostcardController extends Controller
{
    /**
     * @Route("/postcard", name="postcard")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        if ($request->getMethod() == 'POST'){

            $email =$request->request->get('email');
            $img = $this->getDoctrine()->getRepository('WzcMainBundle:Postcard')->find($request->request->get('imgid'));
            $this->get('email.service')->send(
                $email,
//                array('zakaz@zdravzona.ru'),
                array('WzcMainBundle:Email:postcard.html.twig', array(
                    'img' => $img,
                    'title' => $request->request->get('title'),
                    'text' => $request->request->get('text')
                )),
                'Открытка с сайта WZC'
            );

        }

        $postcards = $this->getDoctrine()->getRepository('WzcMainBundle:Postcard')->findByEnabled(1);
        $page = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('postcard');
        $page2 = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('postcard2');



        return array('postcards' => $postcards, 'page' => $page, 'page2' => $page2);
    }


}