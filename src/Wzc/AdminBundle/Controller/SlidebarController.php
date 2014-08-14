<?php
namespace Wzc\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Wzc\MainBundle\Entity\Slidebar;
use Wzc\MainBundle\Form\SlidebarType;

/**
 * Class SlidebarController
 * @package Wzc\AdminBundle\Controller
 * @Route("/admin/slidebar")
 */
class SlidebarController extends Controller{
        const ENTITY_NAME = 'Slidebar';
    /**
     * @Route("/", name="admin_slidebar_list")
     * @Template()
     */
    public function listAction(){
        $items = $this->getDoctrine()->getRepository('WzcMainBundle:'.self::ENTITY_NAME)->findAll();
        return array('items' => $items);
    }

    /**
     * @Route("/add", name="admin_slidebar_add")
     * @Template()
     */
    public function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $item = new Slidebar();
        $form = $this->createForm(new SlidebarType($em), $item);
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
     * @Route("/edit/{id}", name="admin_slidebar_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('WzcMainBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(new SlidebarType($em), $item);
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
     * @Route("/remove/{id}", name="admin_slidebar_remove")
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