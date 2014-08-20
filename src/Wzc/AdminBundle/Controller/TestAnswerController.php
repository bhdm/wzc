<?php
namespace Wzc\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Wzc\MainBundle\Entity\TestAnswer;
use Wzc\MainBundle\Form\TestAnswerType;

/**
 * Class TestAnswerController
 * @package Wzc\AdminBundle\Controller
 * @Route("/admin/testanswer")
 */
class TestAnswerController extends Controller{
    const ENTITY_NAME = 'TestAnswer';
    /**
     * @Route("/{questionId}", name="admin_testanswer_list")
     * @Template()
     */
    public function listAction($questionId){
        $question = $this->getDoctrine()->getRepository('WzcMainBundle:TestQuestion')->findOneById($questionId);
        $items = $this->getDoctrine()->getRepository('WzcMainBundle:'.self::ENTITY_NAME)->findByQuestion($question);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('testanswer', 1),
            20
        );

        return array('pagination' => $pagination, 'questionId' => $questionId);
    }

    /**
     * @Route("/add/{questionId}", name="admin_testanswer_add")
     * @Template()
     */
    public function addAction(Request $request,$questionId){
        $question = $this->getDoctrine()->getRepository('WzcMainBundle:TestQuestion')->findOneById($questionId);
        $em = $this->getDoctrine()->getManager();
        $item = new TestAnswer();
        $form = $this->createForm(new TestAnswerType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $item->setQuestion($question);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                return $this->redirect($this->generateUrl('admin_testanswer_list', array('questionId' => $questionId)));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/edit/{id}", name="admin_testanswer_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('WzcMainBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(new TestAnswerType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('admin_testanswer_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/remove/{id}", name="admin_testanswer_remove")
     */
    public function removeAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('WzcMainBundle:'.self::ENTITY_NAME)->findOneById($id);
        if ($item){
            $em->remove($item);
            $em->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
}