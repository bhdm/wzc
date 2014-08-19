<?php

namespace Wzc\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wzc\MainBundle\Entity\TestAnswer;

class TestQuestionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $testAnswer = new TestAnswer();
        $builder
            ->add('title',null, array('label' => 'Вопрос'))
            ->add('body',null, array('label' => 'Описание вопроса'))
            ->add('isText','choice',  array(
                'empty_value' => false,
                'choices' => array(
                    '0' => 'Выборный',
                    '1' => 'Текстовый',
                ),
                'label' => 'Тип ответов',
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
            'data_class' => 'Wzc\MainBundle\Entity\TestQuestion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wzc_mainbundle_testquestion';
    }
}
