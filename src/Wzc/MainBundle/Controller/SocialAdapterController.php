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
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class SocialAdapterController extends Controller
{
    private $adapterConfigs = array(
        'vk' => array(
            'client_id'     => '4589715',
            'client_secret' => '6wfJE98rQfqGQeiMcXLd',
            'redirect_uri'  => 'http://wzc.loc/app_dev.php/sociallogin?provider=vk'
        ),
        'odnoklassniki' => array(
            'client_id'     => '1099625472',
            'client_secret' => '5F3326EFBDA6C27798B9A229',
            'redirect_uri'  => 'http://vzk-life.ru/sociallogin?provider=odnoklassniki',
            'public_key'    => 'CBAFGJKCEBABABABA'
        ),
        'facebook' => array(
            'client_id'     => '545052125595569',
            'client_secret' => 'b1d967d9d5bb29b4d2484fbbe5bb5337',
            'redirect_uri'  => 'http://wzc.loc/app_dev.php/sociallogin?provider=facebook'
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
                        'username' => ( $auther->getEmail() ? $auther->getEmail() :  $auther->getSocialId() ),
                        #'' => $auther->getSex(),
                        #'date' => $auther->getBirthday()
                        #$auther->getAvatar()
                    );


                    $user = new User();
                    $user->setProvider($auther->getProvider());
                    $user->setSocialId($auther->getSocialId());
                    $user->setFirstName(explode(' ',$auther->getName())[0]);
                    $user->setLastName(explode(' ',$auther->getName())[1]);
                    $user->setUsername(( $auther->getEmail() ? $auther->getEmail() :  $auther->getSocialId() ));
                    $user->setSalt(md5($auther->getSocialId()));
                    $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
                    $password = $encoder->encodePassword($auther->getSocialId(), $user->getSalt());
                    $user->setPassword($password);

                    $user->setRoles('ROLE_USER');
                    $this->getDoctrine()->getManager()->persist($user);
                    $this->getDoctrine()->getManager()->flush($user);

                    #return new RedirectResponse($this->generateUrl('main'));
                    #$this->registerAction(null,$values);
//                    return $this->redirect($this->generateUrl('register',array('campaign' => NULL, 'user' => $values)));


                    $record = $user;
                }

                    //Пользователь с таким SocialId и provider есть и это $record, надо бы его авторизовать
                    $password = $record->getPassword();
                    $roles = $record->getRoles();

                    // Get the security firewall name, login
                    #$providerKey = $this->container->getParameter('fos_user.firewall_name');
                    $token = new UsernamePasswordToken($record, $password, 'security', $roles);
                    $this->get("security.context")->setToken($token);

                    // Fire the login event
                    $event = new InteractiveLoginEvent($this->getRequest(), $token);
                    $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);


//                    return new RedirectResponse($this->generateUrl('main'));
            }
        }
//        return $this->redirect($this->generateUrl('main'));
            return $this->forward('WzcMainBundle:Default:index');
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
