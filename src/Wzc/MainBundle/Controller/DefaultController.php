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
use Wzc\MainBundle\Form\ProfileType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="main")
     * @Route("/login")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $message = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('message');
        $banners = $this->getDoctrine()->getRepository('WzcMainBundle:Slidebar')->findByEnabled(1);

        $session = $request->getSession();
        $session->set('notice', 'Поздравляем, Вы зарегистрировались.');
        $session->save();

        $session = $request->getSession();
        if ($session->get('notice')){
            $notice = $session->get('notice');
            $session->set('notice', null);
        }else{
            $notice = null;
        }

        return array(
            'banners' => $banners,
            'message' => $message,
            'notice' => $notice
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

            $this->get('email.service')->send(
                $user->getUsername(),
//                array('zakaz@zdravzona.ru'),
                array('WzcMainBundle:Email:register.html.twig', array()),
                'Открытка с сайта WZC'
            );
            $session = $request->getSession();
            $session->set('notice', 'Поздравляем, Вы зарегистрировались.');
            $session->save();

        }

        return $this->redirect($this->generateUrl('main'));
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
        $form = $this->createForm(new ProfileType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($request->headers->get('referer'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/password-reset", name="password-reset")
     */
    public function refreshPasswordAction(Request $request){
        if ($request->getMethod() == 'POST'){
            $username = $request->request->get('username');
            $user = $this->getDoctrine()->getRepository('WzcMainBundle:User')->findOneByUsername($username);
            if ($user){
                $p = $this->generatePassword(6);
                $em = $this->getDoctrine()->getManager();
                $user->setSalt(md5(time()));
                $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
                $password = $encoder->encodePassword($p, $user->getSalt());
                $user->setPassword($password);
                $em->flush($user);

                $this->get('email.service')->send(
                    $user->getUsername(),
                    array('WzcMainBundle:Email:password.html.twig', array('password' => $p)),
                    'Открытка с сайта WZC'
                );
                $session = $request->getSession();
                $session->set('notice', 'Новый пароль отправлен Вам на почту');
                $session->save();
            }else{
                $session = $request->getSession();
                $session->set('notice', 'Пользователь с таким email не найден');
                $session->save();
            }
            return $this->redirect($this->generateUrl('main'));
        }else{
            return $this->redirect($this->generateUrl('main'));
        }

    }


    /**
     * @Route("/search", name="search")
     * @Template()
     */
    public function searchAction(Request $request){
        $page['title'] = 'Поиск';
        $page['keywords'] = 'Поиск';
        $page['description'] = 'Поиск';
        $searchString = htmlspecialchars($request->query->get('s'));
        $search_1 = null;
        $search_2 = null;
        $search_3 = null;
        if ( !empty($searchString) ){
            $search_1 = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->search($searchString);
            $search_2 = $this->getDoctrine()->getRepository('WzcMainBundle:Faq')->search($searchString);
            $search_3 = $this->getDoctrine()->getRepository('WzcMainBundle:ForumQuestion')->search($searchString);
        }

        return array(
          'search_1' =>$search_1,
          'search_2' =>$search_2,
          'search_3' =>$search_3,
          's' => $searchString,
        );

    }

    function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}
