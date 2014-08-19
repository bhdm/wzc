<?php
namespace Wzc\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Wzc\MainBundle\Entity\TestQuestion;
use Wzc\MainBundle\Form\TestQuestionType;

/**
 * Class TestQuestionController
 * @package Wzc\AdminBundle\Controller
 * @Route("/admin/testquestion")
 */
class TestQuestionController extends Controller{
        const ENTITY_NAME = 'TestQuestion';
    /**
     * @Route("/", name="admin_testquestion_list")
     * @Template()
     */
    public function listAction(){
        $items = $this->getDoctrine()->getRepository('WzcMainBundle:'.self::ENTITY_NAME)->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('testquestion', 1),
            20
        );

        return array('pagination' => $pagination);
    }

    /**
     * @Route("/add", name="admin_testquestion_add")
     * @Template()
     */
    public function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $item = new TestQuestion();
        $form = $this->createForm(new TestQuestionType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                return $this->redirect($this->generateUrl('admin_testquestion_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/edit/{id}/{aId}", name="admin_testquestion_edit", defaults={"aId"=null})
     * @Template()
     */
    public function editAction(Request $request, $id, $aId){
        $em = $this->getDoctrine()->getManager();

        $item = $this->getDoctrine()->getRepository('WzcMainBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(new TestQuestionType($em), $item);
        $formData = $form->handleRequest($request);

        $answer = $this->getDoctrine()->getRepository('WzcMainBundle:TestAnswer')->findOneById($aId);
        $formAnswer = $this->createForm(new TestQuestionType($em), $answer);
        $formDataAnswer = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){

            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('admin_testquestion_list'));
            }

            if ($formDataAnswer->isValid()){
                $answer = $formData->getData();
                $em->flush($answer);
                $em->refresh($answer);
                return $this->redirect($this->generateUrl('admin_testquestion_edit', array('id' => $id)));
            }


        }
        return array('formQuestion' => $form->createView(), 'formAnswer' => $formAnswer );
    }

    /**
     * @Route("/remove/{id}", name="admin_testquestion_remove")
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