<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditleadType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('customerName', 'text', array(
                    'label' => 'Name', 'attr' => array('class' => 'input-sm', 'placeholder' => 'Edit lead',)
                ))
                ->add('customerEmail', 'email', array(
                    'label' => 'Email', 'attr' => array('class' => 'input-sm')
                ))
                ->add('customerTel', 'text', array(
                    'label' => 'Phone', 'attr' => array('class' => 'input-sm')
                ))
                ->add('save', 'submit', array(
                    'label' => 'save', 'attr' => array('class' => 'btn-success btn-md')))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Lead'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'appbundle_editlead';
    }

}

