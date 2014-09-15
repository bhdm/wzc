<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Wzc\MainBundle\Entity\Postcard;

class PostcardController extends Controller
{
    /**
     * @Route("/postcard", name="postcard")
     * @Template()
     */
    public function indexAction(Request $request)
    {


        $error = false;
        $formData = null;
        if ($request->getMethod() == 'POST'){
            $formData = $request->request->get('form');
            $c = $request->getSession()->get('gcb_captcha');
            if ( $c['phrase'] == $formData['captcha']){
                $email =$formData['email'];
                $img = $this->getDoctrine()->getRepository('WzcMainBundle:Postcard')->find($formData['img']);
                $this->get('email.service')->send(
                    $email,
//                array('zakaz@zdravzona.ru'),
                    array('WzcMainBundle:Email:postcard.html.twig', array(
                        'img' => $img,
                        'title' => $formData['title'],
//                    'text' => $request->request->get('text')
                    )),
                    'Открытка с сайта WZC'
                );
            }else{
                $error = 'Неправильно введена капча';
            }
        }
        $form = $this->createFormBuilder($formData)
            ->add('img', 'hidden')
            ->add('title', 'hidden')
            ->add('email', null, array('label' => 'E-mail: ', 'attr' => array('class' => 'styler')))
            ->add('captcha', 'captcha', array('label' => 'Картинка: ', 'attr' => array('class' => 'styler')))
            ->add('submit', 'submit', array('label' => 'Отправить', 'attr' => array('class' => 'styler')))
            ->getForm();


        $postcards = $this->getDoctrine()->getRepository('WzcMainBundle:Postcard')->findByEnabled(1);
        $page = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('postcard');
        $page2 = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('postcard2');

        return array(
            'postcards' => $postcards,
            'page' => $page,
            'page2' => $page2,
            'form' => $form->createView(),
            'error' => $error,
            'formData' => $formData
        );
    }


}