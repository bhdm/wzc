<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Wzc\MainBundle\Entity\Page;
use Wzc\MainBundle\Entity\User;
use Wzc\MainBundle\Entity\Slidebar;
use Wzc\MainBundle\Form\UserType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="main")
     * @Route("/login")
     * @Template()
     */
    public function indexAction()
    {
        $message = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('message');
        $banners = $this->getDoctrine()->getRepository('WzcMainBundle:Slidebar')->findByEnabled(1);
        return array(
            'banners' => $banners,
            'message' => $message
        );
    }


    /**
     * @Route("/page/{url}" ,name="page")
     * @Template()
     */
    public function pageAction($url)
    {
        $page = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl($url);
        return array('page' => $page);
    }

    /**
     * @Route("/register" ,name="register")
     */
    public function registerAction(Request $request){
        $manager = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST'){
            $user = new User();
            $user->setUsername($request->request->get('username'));
            $user->setSalt(md5(time()));

            // шифрует и устанавливает пароль для пользователя,
            // эти настройки совпадают с конфигурационными файлами
            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $encoder->encodePassword($request->request->get('password'), $user->getSalt());
            $user->setPassword($password);

            $user->setRoles('ROLE_USER');

            $manager->persist($user);
            $manager->flush($user);

        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/faq" ,name="faq")
     * @Template()
     */
    public function faqAction(){
        $faqs = $this->getDoctrine()->getRepository('WzcMainBundle:Faq')->findByEnabled(1);

        return array('faqs' => $faqs);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/profile", name="profile")
     * @Template()
     */
    public function profileAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('WzcMainBundle:User')->findOneById($this->getUser()->getId());
        $form = $this->createForm(new UserType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('admin_user_list'));
            }
        }
        return array('form' => $form->createView());
    }
}
