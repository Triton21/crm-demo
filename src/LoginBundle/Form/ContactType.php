<?php

namespace LoginBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('message', 'textarea', array(
                    'required' => true, "attr" => array("placeholder" => "Message", 'font-family' => 'Drug')))
                ->add('name', 'text', array(
                    "attr" => array("placeholder" => "Name")
                ))
                ->add('phone', 'text', array(
                    'attr' => array("placeholder" => "Phone")
                ))
                ->add('email', 'email', array(
                    'attr' => array("placeholder" => "Email")
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LoginBundle\Entity\Contact'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'loginbundle_contact';
    }

}
