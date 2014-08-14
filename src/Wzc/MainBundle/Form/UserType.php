<?php

namespace Wzc\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',null, array('label'=>'E-mail'))
//            ->add('password',null, array('label'=>''))
            ->add('birthdate',null, array('label'=>'дата рождения'))
            ->add('city',null, array('label'=>'Город'))
            ->add('lastName',null, array('label'=>'Фамилия'))
            ->add('firstName',null, array('label'=>'Имя'))
            ->add('surName', array('label'=>'Отчество'))
            ->add('roles','choice',  array(
                'empty_value' => false,
                'choices' => array(
                    'ROLE_USER' => 'Пользователь',
                    'ROLE_ADMIN' => 'Администратор',
                ),
                'label' => 'Активность',
                'required'  => false,
            ))
            ->add('enabled','choice',  array(
                'empty_value' => false,
                'choices' => array(
                    '1' => 'Активна',
                    '0' => 'Не активна',
                ),
                'label' => 'Активность',
                'required'  => false,
            ))
            ->add('submit', 'submit', array('label' => 'Сохранить'));
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wzc\MainBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wzc_mainbundle_user';
    }
}
