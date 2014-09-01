<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Wzc\MainBundle\Entity\ForumTheme;
use Wzc\MainBundle\Entity\ForumQuestion;
use Wzc\MainBundle\Entity\ForumAnswer;
use Wzc\MainBundle\Form\ForumThemeType;
use Wzc\MainBundle\Form\ForumQuestionType;
use Wzc\MainBundle\Form\ForumAnswerType;

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
    public function indexAction(Request $request){

        $em = $this->getDoctrine()->getManager();


        $item = new ForumTheme();
        $form = $this->createForm(new ForumThemeType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
            }
        }
        $page = $em->getRepository('WzcMainBundle:Page')->findOneByUrl('forum');
        $themes = $em->getRepository('WzcMainBundle:ForumTheme')->findByEnabled(true);
        return array(
            'page' => $page,
            'themes' => $themes,
            'form' => $form->createView()
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


        $item = new ForumQuestion();
        $form = $this->createForm(new ForumQuestionType($em), $item);
        $formData = $form->handleRequest($request);
        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $item->setTheme($theme);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
            }
        }

        return array(
            'questions' => $questions,
            'theme' => $theme,
            'form' => $form->createView()
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
//        $answers = $em->getRepository('WzcMainBundle:ForumAnswer')->findBy(array('enabled'=>true, 'question'=>$question));

        $item = new ForumAnswer();
        $form = $this->createForm(new ForumAnswerType($em), $item);
        $formData = $form->handleRequest($request);
        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $item->setTheme($theme);
                $item->setAuthor($this->getUser());
                $item->setQuestion($question);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);

            }
        }
        $answers = $em->getRepository('WzcMainBundle:ForumAnswer')->findBy(array('enabled'=>true, 'question'=>$question));

        return array(
            'answers' => $answers,
            'question' => $question,
            'theme' => $theme,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("answers/delete/{answerId}", name="answers-delete")
     */
    public function answerDeleteAction(Request $request, $answerId){
        $answer = $this->getDoctrine()->getRepository('WzcMainBundle:ForumAnswer')->find($answerId);
        if ($answer){
            $this->getDoctrine()->getManager()->remove($answer);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
}