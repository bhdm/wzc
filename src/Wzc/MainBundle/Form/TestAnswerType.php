<?php

namespace Wzc\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TestAnswerType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null, array('label' => 'Ответ'))
            ->add('istrue','choice',  array(
                'empty_value' => false,
                'choices' => array(
                    '0' => 'не правильный',
                    '1' => 'правильный',
                ),
                'label' => 'Правильный',
                'required'  => false,
            ))
            ->add('submit', 'submit', array('label' => 'Сохранить'))
            ->add('back', 'button', array('label' => 'Назад', 'attr' => array('class' => 'alignRight')));
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wzc\MainBundle\Entity\TestAnswer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wzc_mainbundle_testanswer';
    }
}
