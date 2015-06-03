<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
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
                return $this->redirect($this->generateUrl('forum_index'));
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

        if ($theme == null){
            throw $this->createNotFoundException('');
        }

        $questions = $this->getDoctrine()->getRepository('WzcMainBundle:ForumQuestion')->findBy(array('enabled'=>true, 'theme'=>$theme), array('id' => 'DESC'));


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
                return $this->redirect($this->generateUrl('forum_questions', array('themeId' =>$themeId)));
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

        if ($theme == null || $question == null){
            throw $this->createNotFoundException('');
        }


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
                return $this->redirect($this->generateUrl('forum_answers', array('themeId' =>$themeId, 'questionId' => $questionId)));
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

    /**
     * @Route("questions/delete/{questionId}", name="questions-delete")
     */
    public function questionDeleteAction(Request $request, $questionId){
        $question = $this->getDoctrine()->getRepository('WzcMainBundle:ForumQuestion')->find($questionId);
        if ($question){
            $this->getDoctrine()->getManager()->remove($question);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("theme/delete/{themeId}", name="theme-delete")
     */
    public function themeDeleteAction(Request $request, $themeId){
        $theme = $this->getDoctrine()->getRepository('WzcMainBundle:ForumTheme')->find($themeId);
        if ($theme){
            $this->getDoctrine()->getManager()->remove($theme);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }

}