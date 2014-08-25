<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ForumController
 * @package Wzc\MainBundle\Controller
 * @Route("/forum")
 *
 */
class ForumController extends Controller
{
    /**
     * Главная страница форума
     * @Route("/", name="forum_index")
     * @Template()
     */
    public function indexAction(){

        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('WzcMainBundle:Page')->findOneByUrl('forum');
        $themes = $em->getRepository('WzcMainBundle:ForumTheme')->findByEnabled(true);

        return array(
            'page' => $page,
            'themes' => $themes
        );
    }

    /**
     * @Route("/questions/{themeId}", name="forum_questions")
     * @Template()
     */
    public function questionsAction(Request $request, $themeId){
        $em = $this->getDoctrine()->getManager();
        $theme = $em->getRepository('WzcMainBundle:ForumTheme')->find($themeId);
        $questions = $em->getRepository('WzcMainBundle:ForumQuestion')->findBy(array('enabled'=>true, 'theme'=>$theme));

        return array(
            'questions' => $questions,
            'theme' => $theme
        );
    }

    /**
     * @Route("/answers/{themeId}/{questionId}", name="forum_answers")
     * @Template()
     */
    public function answersAction(Request $request, $themeId, $questionId){
        $em = $this->getDoctrine()->getManager();
        $theme = $em->getRepository('WzcMainBundle:ForumTheme')->find($themeId);
        $question = $em->getRepository('WzcMainBundle:ForumQuestion')->find($questionId);
        $answers = $em->getRepository('WzcMainBundle:ForumAnswer')->findBy(array('enabled'=>true, 'question'=>$question));

        return array(
            'answers' => $answers,
            'question' => $question,
            'theme' => $theme
        );
    }
}