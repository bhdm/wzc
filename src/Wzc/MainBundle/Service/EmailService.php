<?php
namespace Wzc\MainBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Bundle\TwigBundle\TwigEngine as Templating;

class EmailService
{
    private $container;
    private $templating;

    public function __construct(Container $container, Templating $templating)
    {
        $this->container  = $container;
        $this->templating = $templating;
    }

    public function send($emails, $template, $subject = 'Уведомление')
    {
        $mail = new \PHPMailer();

        $portal = 'ВЗК';

        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->CharSet  = 'UTF-8';
        $mail->FromName = 'Портал ' . $portal;
        $mail->Subject  = $subject;

        # prod - оптравка через Exim, dev/test - отправка через Gmail
//        if ($this->container->getParameter('kernel.environment') == 'prod') {
//            $mail->Host = '127.0.0.1';
//            $mail->From = 'maillist@vidal.ru';
//        }
//        else {
            $mail->Host       = 'smtp.mail.ru';
            $mail->From       = 'medrampost@yandex.ru';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;
            $mail->SMTPAuth   = true;
            $mail->Username   = 'medrampost@yandex.ru';
            $mail->Password   = 'medrampost1';
//        }

        # устанавливаем содержимое письма
        $templateParams = array('portal' => $portal);
        if (is_string($template)) {
            $templateName = $template;
        }
        else {
            $templateName   = $template[0];
            $templateParams = array_merge($templateParams, $template[1]);
        }

        $mail->Body = $this->templating->render($templateName, $templateParams);

        # устанавливаем получателя(ей) письма
        if (is_string($emails)) {
            $mail->addAddress($emails);
        }
        else {
            foreach ($emails as $email) {
                $mail->addAddress($email);
            }
        }

        $mail->send();
    }
}