<?php

namespace Wzc\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wzc\MainBundle\Entity\User;

class ProfileType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',null, array('label'=>'E-mail: ', 'attr'=>array('class'=> 'styler')))
            ->add('lastName',null, array('label'=>'Фамилия: ', 'attr'=>array('class'=> 'styler')))
            ->add('firstName',null, array('label'=>'Имя: ', 'attr'=>array('class'=> 'styler')))
            ->add('surName',null, array('label'=>'Отчество: ', 'attr'=>array('class'=> 'styler')))
            ->add('birthdate','date', array('label'=>'дата рождения: '))
            ->add('city',null, array('label'=>'Город: ', 'attr'=>array('class'=> 'styler')))
            ->add('submit', 'submit', array('label' => 'Сохранить', 'attr'=>array('class'=> 'styler')));
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
