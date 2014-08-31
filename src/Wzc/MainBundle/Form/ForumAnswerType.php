<?php

namespace Wzc\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ForumAnswerType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', null, array('label' => ' ', 'attr' => array('class' => 'minickeditor')))
            ->add('submit', 'submit', array('label' => 'Ответить'));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wzc\MainBundle\Entity\ForumAnswer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wzc_mainbundle_forumanswer';
    }
}
