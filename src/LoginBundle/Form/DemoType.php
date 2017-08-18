<?php

namespace LoginBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DemoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                    'attr' => array('class' => 'input-md form-control', 'placeholder' => 'Name')
                ))
                ->add('email', 'email', array(
                    'attr' => array('class' => 'input-md form-control', 'placeholder' => 'Email')
                ))
                ->add('phone', 'text', array(
                    'attr' => array('class' => 'input-md form-control', 'placeholder' => 'Phone')
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LoginBundle\Entity\Demo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loginbundle_demo';
    }
}
