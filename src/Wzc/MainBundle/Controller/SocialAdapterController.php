<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Wzc\MainBundle\Controller\AuthController,
    Wzc\MainBundle\Entity\User,
    SocialAuther\Adapter,
    SocialAuther\SocialAuther,


    Symfony\Component\Security\Core\Exception\AccessDeniedException,
//    JMS\SecurityExtraBundle\Annotation\Secure,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
//    DoctrineExtensions\NestedSet\Config,
//    DoctrineExtensions\NestedSet\Manager,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken,
    Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SocialAdapterController extends Controller
{
    private $adapterConfigs = array(
        'vk' => array(
            'client_id'     => '4524333',
            'client_secret' => 'MRAPcZtDAZO3fMiaYBmJ',
            'redirect_uri'  => 'http://medram.ru/sociallogin?provider=vk'
        ),
        'odnoklassniki' => array(
            'client_id'     => '1099625472',
            'client_secret' => '5F3326EFBDA6C27798B9A229',
            'redirect_uri'  => 'http://medram.ru/sociallogin?provider=odnoklassniki',
            'public_key'    => 'CBAFGJKCEBABABABA'
        ),
        'facebook' => array(
            'client_id'     => '554661164634665',
            'client_secret' => 'd464fa562bd3f59c29660701ae7a238d',
            'redirect_uri'  => 'http://medram.ru/sociallogin?provider=facebook'
        )
    );


    /**
     * Авторизация через токен полученный из соц сети
     *
     * @Route("/sociallogin", name = "social_login" )
     */
    public function socialLoginAction(){


        $adapters = array();

        foreach ($this->adapterConfigs as $adapter => $settings) {
            $class = ucfirst($adapter);
            $class = "\\SocialAuther\\Adapter\\" . $class;
            $adapters[$adapter] = new $class($settings);
        }

        $class = "\\SocialAuther\\SocialAuther";
        if (isset($_GET['provider']) && array_key_exists($_GET['provider'], $adapters) && !$this->getUser()) {
            $auther = new $class($adapters[$_GET['provider']]);

            if ($auther->authenticate()) {

                $record = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('WzcMainBundle:User');

                $record = $record->findOneBy(array(
                    'socialId' => $auther->getSocialId(),
                    'provider' => $auther->getProvider()
                ));



                if ($record==NULL) {
                    $values = array(
                        'provider' => $auther->getProvider(),
                        'socialId' => $auther->getSocialId(),
                        'name' => $auther->getName(),
                        'username' => $auther->getEmail(),
                        #'' => $auther->getSex(),
                        #'date' => $auther->getBirthday()
                        #$auther->getAvatar()
                    );


                    $user = new User();
                    $user->setProvider($auther->getProvider());
                    $user->setSocialId($auther->getSocialId());
                    $user->getFirstName($auther->getName());
                    $user->setUsername($auther->getEmail());
                    $this->getDoctrine()->getManager()->persist($user);
                    $this->getDoctrine()->getManager()->flush($user);

                    #return new RedirectResponse($this->generateUrl('main'));
                    #$this->registerAction(null,$values);
//                    return $this->redirect($this->generateUrl('register',array('campaign' => NULL, 'user' => $values)));



                }
                    //Пользователь с таким SocialId и provider есть и это $record, надо бы его авторизовать
                    $password = $record->getPassword();
                    $username = $record->getUsername();
                    $roles = $record->getRoles();

                    // Get the security firewall name, login
                    #$providerKey = $this->container->getParameter('fos_user.firewall_name');
                    $token = new UsernamePasswordToken($record, $password, 'security', $roles);
                    $this->get("security.context")->setToken($token);

                    // Fire the login event
                    $event = new InteractiveLoginEvent($this->getRequest(), $token);
                    $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);


                    return new RedirectResponse($this->generateUrl('main'));
            }
        }
        return $this->redirect($this->generateUrl('main'));
    }

    /**
     * @Route("/socialauthurl", name = "social_Auth_Url" )
     */
    public function socialAuthUrlAction(){
        $adapters = array();

        foreach ($this->adapterConfigs as $adapter => $settings) {
            $class = ucfirst($adapter);
            $class = "\\SocialAuther\\Adapter\\" . $class;
            $adapters[$adapter] = new $class($settings);
        }
        $url = array();
        foreach ($adapters as $adapter) {
            $url[] = array(
                'url'   =>$adapter->getAuthUrl(),
                'img'   =>$adapter->getProvider()
            );
        }
        return $this->render(
            'WzcMainBundle:Auth:social_icons.html.twig',
            array('socials' => $url)
        );
    }
}
