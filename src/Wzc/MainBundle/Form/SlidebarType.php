<?php

namespace Wzc\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SlidebarType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label'=> 'Заголовок'))
            ->add('body', null, array('label'=> 'Текст к картинке'))
            ->add('enabled','choice',  array(
                'empty_value' => false,
                'choices' => array(
                    '1' => 'Активна',
                    '0' => 'Не активна',
                ),
                'label' => 'Активность',
                'required'  => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wzc\MainBundle\Entity\Slidebar'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wzc_mainbundle_slidebar';
    }
}