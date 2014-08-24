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
            'client_id'     => '3749200',
            'client_secret' => 'ktgIF9MQKSrEA4ZQNuKm',
            'redirect_uri'  => 'http://medram.ru/sociallogin?provider=vk'
        ),
        'odnoklassniki' => array(
            'client_id'     => '185768448',
            'client_secret' => '132BC2D1805BC6A88F202ECD',
            'redirect_uri'  => 'http://medram.ru/sociallogin?provider=odnoklassniki',
            'public_key'    => 'CBALJDBMABABABABA'
        ),
        'facebook' => array(
            'client_id'     => '433291173434876',
            'client_secret' => '2874deb722ee4051bbe7ab43936d6aac',
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
                        'email' => $auther->getEmail(),
                        #'' => $auther->getSex(),
                        #'date' => $auther->getBirthday()
                        #$auther->getAvatar()
                    );

                    #return new RedirectResponse($this->generateUrl('main_feed'));
                    #$this->registerAction(null,$values);
                    return $this->redirect($this->generateUrl('register',array('campaign' => NULL, 'user' => $values)));
                } else {
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


                    return new RedirectResponse($this->generateUrl('main_feed'));

                }
            }else{
                return $this->render($this->generateUrl('main-feed'));
            }
        }
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
