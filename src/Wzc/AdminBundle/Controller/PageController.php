<?php
namespace Wzc\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Wzc\MainBundle\Entity\Page;
use Wzc\MainBundle\Form\PageType;

/**
 * Class PageController
 * @package Wzc\AdminBundle\Controller
 * @Route("/admin/page")
 */
class PageController extends Controller{
        const ENTITY_NAME = 'Page';
    /**
     * @Route("/" name="page_list")
     * @Template()
     */
    public function listAction(){
        $items = $this->getDoctrine()->getRepository('WzcMainBundle:'.self::ENTITY_NAME)->findAll();
        return array('items' => $items);
    }

    /**
     * @Route("/add" name="page_add")
     * @Template()
     */
    public function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $class = self::ENTITY_NAME.'()';
        $item = new $class;
        $form = $this->createForm(new PageType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/edit/{id}" name="page_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('WzcMainBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(new PageType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/remove/{id}" name="page_remove")
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