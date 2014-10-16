<?php
namespace Wzc\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Wzc\MainBundle\Entity\Faq;
use Wzc\MainBundle\Form\FaqType;

/**
 * Class FaqController
 * @package Wzc\AdminBundle\Controller
 * @Route("/admin/test-statistic")
 */
class TestStatisticController extends Controller{
    const ENTITY_NAME = 'TestStatistic';
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/", name="admin_teststatistic_list")
     * @Template()
     */
    public function listAction(){
        $items = $this->getDoctrine()->getRepository('WzcMainBundle:'.self::ENTITY_NAME)->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('page', 1),
            20
        );

        $sum = 0;
        foreach ($items as $val){
            $sum += $val->getBalls();
        }
        if ( $items != null ){
            $avg = $sum / count($items);
        }else{
            $avg = 0;
        }
        return array('pagination' => $pagination, 'avg' => $avg, 'count' => count($items) );
    }


    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/remove/{id}", name="admin_teststatistic_remove")
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