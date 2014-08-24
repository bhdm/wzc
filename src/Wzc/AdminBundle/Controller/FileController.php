<?php

namespace Wzc\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Wzc\MainBundle\Entity\File;

/**
 * Class FilesController
 * @package Wzc\AdminBundle\Controller
 * @Route("/file")
 */
class FileController extends Controller
{
    /**
     * @Route("/{folderId}", name="file_index", defaults={"folderId"="null"})
     * @Template()
     */
    public function indexAction($folderId = null){
        if ( $folderId ){
            $parent = $this->getDoctrine()->getRepository('WzcMainBundle:File')->findOneById($folderId);
        }else{
            $parent = null;
        }
        $files = $this->getDoctrine()->getRepository('WzcMainBundle:File')->findByParent($parent);

        return array(
            'folder'    => $parent,
            'files'     => $files
        );
    }

    /**
     * @Route("/add/{folderId}", name="file_add", defaults={"folderId"="null"})
     * @Template()
     */
    public function addFileAction(Request $request, $folderId = null){

        $file = new File();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm($file);
        $form->add('title',null, array('label' => 'Название файла'));
        $form->add('file','iphp_file', array('label' => 'файл'));
        $form->add('submit', 'submit', array('label' => 'Сохранить'));

        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('file_index',array('folderId' => $folderId)));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/remove/{fileId}", name="file_remove")
     */
    public function removeFileAction(Request $request, $fileId){
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('WzcMainBundle:File')->findOneById($fileId);
        if ($file){
            $em->remove($file);
            $em->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
}