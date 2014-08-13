<?php

namespace Wzc\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

class AuthController extends Controller
{
    /**
     * @Route("/admin/login")
     * @Template()
     */
    public function loginAction()
    {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = 'Неправильный логин или пароль';
        } else {
            $error = 'Неправильный логин или пароль';
        }
        return array(
            'error' => $error
        );
    }





}
