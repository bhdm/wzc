<?php
namespace Company\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Wzc\MainBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class FixtureLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // создание пользователя
        $user = new User();
        $user->setUsername('admin');
        $user->setSalt(md5(time()));

        // шифрует и устанавливает пароль для пользователя,
        // эти настройки совпадают с конфигурационными файлами
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword('wzcwzc', $user->getSalt());
        $user->setPassword($password);

        $user->setRoles('ROLE_ADMIN');

        $manager->persist($user);



    }
}