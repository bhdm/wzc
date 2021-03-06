<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
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
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $message = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('message');
        $banners = $this->getDoctrine()->getRepository('WzcMainBundle:Slidebar')->findByEnabled(1);
        $news = $this->getDoctrine()->getRepository('WzcMainBundle:Publication')->findByEnabled(1);
        krsort($news);
        $news = array_slice($news,0,3);

        //$session = $request->getSession();
        //$session->set('notice', 'Поздравляем, Вы зарегистрировались.');
        //$session->save();

        $session = $request->getSession();
        if ($session->get('notice')){
            $notice = $session->get('notice');
            $session->set('notice', null);
            $session->save();
        }else{
            $notice = null;
        }

        return array(
            'banners' => $banners,
            'message' => $message,
            'notice' => $notice,
            'news' => $news
        );
    }

    /**
     * @Route("/test-auth", name="test_auth", options={"expose" = true})
     */
    public function testauthAction(Request $request){
        $login = $request->request->get('login');
        $password = $request->request->get('pass');

        $user = $this->getDoctrine()->getRepository('WzcMainBundle:User')->findOneByUsername($login);

        if ( $user ){
            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $encoder->encodePassword($password, $user->getSalt());

            if($user->getPassword() == $password){
                echo 'ok';
            }else{
                echo 'no';
            }
        }else{
            echo 'no';
        }
        exit;
    }

    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction(){
        return array();
    }

    /**
     * @Route("/page/{url}" ,name="page")
     * @Template()
     */
    public function pageAction($url)
    {
        $page = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl($url);
        if ($page == null ){
            throw $this->createNotFoundException('');
        }
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
            $user->setFirstName($request->request->get('name'));
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
                'Открытка с сайта VZK-LIFE'
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
                    'Открытка с сайта VZK-LIFE'
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

    /**
     * @Route("/news", name="news")
     * @Template()
     */
    public function newsAction(){
        $news = $this->getDoctrine()->getRepository('WzcMainBundle:Publication')->findBy(array('enabled'=>true),array('id' => 'DESC'));
//        ksort($news);
        return array('news' => $news);
    }

    /**
     * @Route("/new/{newId}", name="new")
     * @Template()
     */
    public function newAction($newId){
        $new = $this->getDoctrine()->getRepository('WzcMainBundle:Publication')->findOneByid($newId);
        return array('new' => $new);
    }

    /**
     * @Route("/menu", name="menu")
     * @Template()
     */
    public function menuAction(){
        $menu = $this->getDoctrine()->getRepository('WzcMainBundle:Menu')->findBy(array('enabled' => true, 'parent' => null));
        $url = $_SERVER['REQUEST_URI'];
        $url = str_replace('/app_dev.php','',$url);
        $url = str_replace('/app.php','',$url);
        $url = 'http://vzk-life.ru'.$url;
        $active = $this->getDoctrine()->getRepository('WzcMainBundle:Menu')->findOneBy(array('enabled' => true, 'url' => $url));
        if ($active != null){
            if ($active->getParent() != null){
                $act = $active->getParent();
            } else{
                $act = $active;
            }
        }else{
            $act['url'] = '';
        }
        return array('menu' => $menu, 'act' => $act);
    }

    /**
     * @Route("/footer", name="footer")
     * @Template()
     */
    public function footerAction(){
        $footer = $this->getDoctrine()->getRepository('WzcMainBundle:Menu')->findBy(array('enabled' => true, 'parent' => null));
        return array('footer' => $footer);
    }

    /**
     * @Route("/category/{id}", name="category")
     * @Template()
     */
    public function categoryAction($id){

        if ( $id == 1 ){
            return $this->redirect('http://vzk-life.ru/page/about',301);
        }
        if ( $id == 18 ){
            return $this->redirect('http://vzk-life.ru/forum/',301);
        }
        $category = $this->getDoctrine()->getRepository('WzcMainBundle:Menu')->findOneById($id);
        $page = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByurl('/category/'.$id);
        return array('category' => $category,'page' => $page);
    }

    /**
     * @Route("/sitemap", name="sitemap")
     */
    public function sitemapAction(){
        $sitemap = '';
        $menus = $this->getDoctrine()->getRepository('WzcMainBundle:Menu')->findByEnabled(true);
        $pages = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findByEnabled(true);
        $sitemap .= '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

//        foreach($pages as $item){
//            if ($item->getUrl() != 'message' && $item->getUrl() != 'map' &&$item->getUrl() != 'postcard' && $item->getUrl() != 'postcard2' && $item->getUrl() != 'test'   ){
//                $sitemap .= '<url>';
//                $sitemap .= '<loc>http://vzk-life.ru/page/'.$item->getUrl().'</loc>';
//                $sitemap .= '<lastmod>'.$item->getUpdated()->format('d.m.Y').'</lastmod>';
//                $sitemap .= '<changefreq>monthly</changefreq>';
//                $sitemap .= '<priority>0.8</priority>';
//                $sitemap .= '</url>';
//            }
//        }

        foreach($menus as $item){
            if (strripos($item->getUrl(),'forum')===false){
                $sitemap .= '<url>';
                if (strripos($item->getUrl(), 'vzk-life') === false){
                    if ($item->getUrl()[0] == '/'){
                        $sitemap .= '<loc>http://vzk-life.ru'.$item->getUrl().'</loc>';
                    }else{
                        $sitemap .= '<loc>http://vzk-life.ru/'.$item->getUrl().'</loc>';
                    }
                }else{
                    $sitemap .= '<loc>'.$item->getUrl().'</loc>';
                }

                $sitemap .= '<lastmod>'.$item->getUpdated()->format('d.m.Y').'</lastmod>';
                $sitemap .= '<changefreq>monthly</changefreq>';
                $sitemap .= '<priority>0.8</priority>';
                $sitemap .= '</url>';
            }
        }

        $sitemap .= '
        <url>
            <loc>http://vzk-life.ru/</loc>
            <lastmod>27.10.2014</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/page/facts</loc>
            <lastmod>27.10.2014</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/new/4</loc>
            <lastmod>27.10.2014</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/new/3</loc>
            <lastmod>27.10.2014</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/new/2</loc>
            <lastmod>27.10.2014</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/site-map</loc>
            <lastmod>27.10.2014</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/news</loc>
            <lastmod>27.10.2014</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>

        <url>
            <loc>http://vzk-life.ru/page/yazvenniy-kolit</loc>
            <lastmod>20.04.2015</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/page/yazvenniy-kolit-simptomi</loc>
            <lastmod>20.04.2015</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/page/bolezn-krona</loc>
            <lastmod>20.04.2015</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/page/bolezn-krona-lecenie</loc>
            <lastmod>20.04.2015</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/page/bolezn-krona-simptomi</loc>
            <lastmod>20.04.2015</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/page/nedifferencirovanniy-kolit</loc>
            <lastmod>20.04.2015</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        <url>
            <loc>http://vzk-life.ru/page/yazvenniy-kolit-lecenie</loc>
            <lastmod>17.05.2015</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
        ';

        $sitemap .= '</urlset>';

        $response = new Response($sitemap);
        $response->headers->set('Content-Type', 'text/xml');
        return $response;

    }

    /**
     * @Route("/site-map", name="site-map")
     * @Template()
     */
    public function sitemapclientAction(){
        $menus = $this->getDoctrine()->getRepository('WzcMainBundle:Menu')->findByEnabled(true);

        return array('menus' => $menus);
    }
}
