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
        $page = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('test');
        $question = $this->getDoctrine()->getRepository('WzcMainBundle:TestQuestion')->findByEnabled(1);
        if ($question){
            $question = $question[0];
        }

        return array('question' => $question, 'page' => $page, );
    }

    /**
     * @Route("/test/next/{questionId}", name="test_next", options={"expose"=true})
     * @Template()
     */
    public function nextQuestionAction($questionId, Request $request){
        $question = $this->getDoctrine()->getRepository('WzcMainBundle:TestQuestion')->findNext($questionId);
        return array('question' => $question);
    }



}